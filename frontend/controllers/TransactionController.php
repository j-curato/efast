<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\SubAccounts1;
use Yii;
use app\models\Transaction;
use app\models\TransactionIars;
use app\models\TransactionItems;
use app\models\TransactionPrItems;
use app\models\TransactionSearch;
use common\models\User;
use DateTime;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;




/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'update',
                    'delete',
                    'view',
                    'create',
                    'ors-form',
                    'voucher',
                    'get-all-transaction',
                    'import-transaction',
                    'sample',
                    'get-transaction',
                    'search-transaction',
                    'iar-details',
                    'get-pr-allotments',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'view',
                            'create',
                            'ors-form',
                            'voucher',
                            'get-all-transaction',
                            'import-transaction',
                            'sample',
                            'get-transaction',
                            'search-transaction',
                            'iar-details',
                            'get-pr-allotments',
                        ],
                        'allow' => true,
                        'roles' => ['department-offices', 'super-user', 'ro_transaction'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'update',
                            'view',
                            'create',
                            'ors-form',
                            'voucher',
                            'search-transaction',
                            'iar-details',
                            'get-pr-allotments',
                        ],
                        'allow' => true,
                        'roles' => ['ro-common-user'],
                    ],

                ],


            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    private function InsertTransactionIar($transaction_id = '', $iars = [], $isUpdate = false)
    {
        try {

            foreach ($iars as $val) {
                $item = new TransactionIars();
                $item->fk_transaction_id = $transaction_id;
                $item->fk_iar_id = $val;
                if (!$item->validate()) {
                    return $item->errors;
                }
                if (!$item->save(false)) {
                    return 'Transaction IARS Save Error';
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    private function validatePrAllotment($prAllotmentId, $amount, $itemId = null)
    {

        $params = [];
        $sql = '';
        if (!empty($itemId)) {
            $sql .= ' AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', "transaction_pr_items.id", $itemId], $params);
        }

        $bal = Yii::$app->db->createCommand("SELECT 

        pr_purchase_request_allotments.amount - IFNULL(ttlInTsn.ttl,0) as bal
        FROM 
        pr_purchase_request_allotments
        LEFT JOIN (
        SELECT transaction_pr_items.fk_pr_allotment_id,
        SUM(transaction_pr_items.amount) as ttl
        FROM transaction_pr_items
        WHERE 
        transaction_pr_items.is_deleted = 0
        $sql
        GROUP BY transaction_pr_items.fk_pr_allotment_id
        ) as ttlInTsn ON pr_purchase_request_allotments.id = ttlInTsn.fk_pr_allotment_id
        WHERE 
        pr_purchase_request_allotments.id  = :id
       
         ", $params)
            ->bindValue(':id', $prAllotmentId)
            ->queryScalar();

        $finalBal = floatval($bal) - floatval($amount);

        if ($finalBal < 0) {
            return "PR Allotment Cannot be less than " . number_format($bal, 2);
        }
        return true;
    }
    // insert purchase requests
    private function InsertPrs($id, $items = [], $isUpdate = false)
    {

        try {

            if ($isUpdate) {
                $params = [];
                $item_ids = array_column($items, 'item_id');
                $sql = '';
                if (!empty($item_ids)) {
                    $sql = 'AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'transaction_pr_items.id', $item_ids], $params);
                }
                Yii::$app->db->createCommand("UPDATE   transaction_pr_items 
                LEFT JOIN (SELECT * FROM process_ors_txn_items  WHERE  process_ors_txn_items.is_deleted = 0) as ors_txn ON transaction_pr_items.id = ors_txn.fk_transaction_item_id SET transaction_pr_items.is_deleted = 1 
                    WHERE 
                     transaction_pr_items.fk_transaction_id = :id 
                     AND transaction_pr_items.is_deleted  = 0
                     AND ors_txn.id IS NULL

                      $sql", $params)
                    ->bindValue(':id', $id)
                    ->execute();
            }
            $itemId = '';
            foreach ($items as $key => $item) {
                $amt = floatval($item['amount']) < 0 ? $item['amount'] * -1 : $item['amount'];
                if (!empty($item['item_id'])) {
                    $itemId = $item['item_id'];
                    $pr =  TransactionPrItems::findOne($item['item_id']);
                } else {

                    $pr = new TransactionPrItems();
                }
                $validate = $this->validatePrAllotment($item['prAllotmentId'], $amt, $itemId);
                if ($validate !== true) {
                    return $validate;
                }
                $x = $key + 1;
                if (floatVal($amt) == 0) {
                    throw new ErrorException('PR allotment amount must be more than 0 in PR table row no.' . $x);
                }
                $pr->fk_transaction_id  = $id;
                $pr->amount  = $amt;
                $pr->fk_pr_allotment_id  = $item['prAllotmentId'];

                if (!$pr->validate()) {
                    return $pr->errors;
                }
                if (!$pr->save(false)) {
                    return 'Transaction Pr Items Not Save';
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    private function GetTxnPrItems($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 
        pr_purchase_request.pr_number,
        record_allotments.serial_number as allotment_number,
        pr_purchase_request_allotments.id as prAllotmentId,
        UPPER(office.office_name) as office_name,
        UPPER(divisions.division) as division ,
        CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
        fund_source.`name` as fund_source_name,
        chart_of_accounts.general_ledger as account_title,
        pr_purchase_request_allotments.amount as prAllotmentAmt,
        IFNULL(pr_purchase_request_allotments.amount,0) - IFNULL(ttlTransaction.ttlTransactAmt,0) as balance,
        books.`name` as book_name,
        ttlTransaction.ttlTransactAmt,
        pr_purchase_request.purpose,
        transaction_pr_items.amount as txnPrAmt
        FROM transaction_pr_items 
  
        INNER JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
        INNER JOIN record_allotment_entries ON pr_purchase_request_allotments.fk_record_allotment_entries_id = record_allotment_entries.id
        INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
        LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        LEFT JOIN office ON record_allotments.office_id = office.id
        lEFT JOIN divisions ON record_allotments.division_id = divisions.id
        LEFT JOIN books ON record_allotments.book_id = books.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id
        LEFT JOIN (SELECT
          transaction_pr_items.fk_pr_allotment_id,
          SUM(transaction_pr_items.amount) as ttlTransactAmt
          FROM transaction_pr_items
          WHERE transaction_pr_items.is_deleted = 0
          GROUP BY transaction_pr_items.fk_pr_allotment_id
        ) as ttlTransaction ON pr_purchase_request_allotments.id = ttlTransaction.fk_pr_allotment_id
        WHERE 
        transaction_pr_items.fk_transaction_id = 100154277548261534
        AND transaction_pr_items.is_deleted = 0")
            ->bindValue(':id', $id)
            ->queryAll();
        return $query;
    }

    private function insertItems($transaction_id, $items = [], $isUpdate = false)
    {
        try {
            if ($isUpdate) {
                $params = [];
                $item_ids = array_column($items, 'item_id');
                $sql = '';
                if (!empty($item_ids)) {
                    $sql = 'AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                }
                Yii::$app->db->createCommand("UPDATE transaction_items SET is_deleted = 1 WHERE 
                     transaction_items.fk_transaction_id = :id  $sql", $params)
                    ->bindValue(':id', $transaction_id)
                    ->execute();
            }
            foreach ($items as $item) {
                $item_id = '';
                if (!empty($item['item_id'])) {
                    $item_id = $item['item_id'];
                    $transaction_item  = TransactionItems::findOne($item_id);
                } else {
                    $transaction_item = new TransactionItems();
                }
                $vlt = MyHelper::checkAllotmentBalance(
                    $item['allotment_id'],
                    $item['gross_amount'],
                    null,
                    $item_id
                );
                if ($vlt !== true) {
                    throw new ErrorException($vlt);
                }
                $transaction_item->fk_transaction_id = $transaction_id;
                $transaction_item->fk_record_allotment_entries_id = $item['allotment_id'];
                $transaction_item->amount = $item['gross_amount'];


                if (!$transaction_item->validate()) {
                    throw new ErrorException(json_encode($transaction_item->errors));
                }
                if (!$transaction_item->save(false)) {
                    throw new ErrorException('Transaction Items Save Error');
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }

        return true;
    }
    private function getPrItems($id)
    {
        return YIi::$app->db->createCommand("CALL GetTransactionPrItems(:id)")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function getItems($id)
    {
        return YIi::$app->db->createCommand("CALL GetTransactionAllotmentItems(:id)")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        return $this->render('ors_form', [
            'model' => $model,
            'items' => $model->getItems()
        ]);
    }



    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transaction();
        if (Yii::$app->request->post()) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $model->type = Yii::$app->request->post('Transaction')['type'];
                $model->fk_book_id = Yii::$app->request->post('Transaction')['fk_book_id'];
                $model->payee_id = Yii::$app->request->post('Transaction')['payee_id'];
                $model->particular = Yii::$app->request->post('Transaction')['particular'];
                $model->transaction_date = Yii::$app->request->post('Transaction')['transaction_date'];
                $model->responsibility_center_id = Yii::$app->request->post('Transaction')['responsibility_center_id'] ?? '';
                $prItems = Yii::$app->request->post('prItems') ?? [];
                $allotmentItems = Yii::$app->request->post('items') ?? [];
                if (empty($allotmentItems)) {
                    throw new ErrorException('Allotment and Gross Amount is Required');
                }

                $user_data = User::getUserDetails();
                $division = strtolower($user_data->employee->empDivision->division);
                $iarItems = [];
                if ($model->type === 'multiple') {
                    $multipleIar = Yii::$app->request->post('multiple_iar') ?? [];
                    if (empty($multipleIar)) {
                        throw new ErrorException('IAR is Required');
                    }
                    $iarItems = $multipleIar;
                } else if ($model->type === 'single') {
                    $singleIar = Yii::$app->request->post('single_iar') ?? [];
                    if (empty($singleIar)) {
                        throw new ErrorException('IAR is Required');
                    }
                    $iarItems[] = $singleIar;
                }
                if (!Yii::$app->user->can('ro_accounting_admin')) {
                    $r_center = Yii::$app->db->createCommand("SELECT `id` FROM responsibility_center WHERE `name`=:division")
                        ->bindValue(':division', $division)
                        ->queryScalar();
                    $model->responsibility_center_id = $r_center;
                }
                $model->tracking_number = $this->getTrackingNumber($model->responsibility_center_id,  $model->transaction_date);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if ($model->type != 'no-iar'  && empty($prItems)) {
                    throw new ErrorException(json_encode(array('isSuccess' => false, 'error_message' => 'Please Select a Purchase Request Below')));
                }

                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insPrs = $this->InsertPrs($model->id, $prItems);
                if ($insPrs !== true) {
                    throw new ErrorException($insPrs);
                }
                $isItms = $this->insertItems($model->id, $allotmentItems);
                if ($isItms !== true) {
                    throw new ErrorException($isItms);
                }
                $insIar = $model->insertIarItems($iarItems);
                if ($insIar !== true) {
                    throw new ErrorException($insIar);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'action' => 'create'

        ]);
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $items = $model->getItems();
        if ((Yii::$app->request->post())) {
            try {
                $transaction = Yii::$app->db->beginTransaction();
                $model->type = Yii::$app->request->post('Transaction')['type'];
                $model->fk_book_id = Yii::$app->request->post('Transaction')['fk_book_id'];
                $model->payee_id = Yii::$app->request->post('Transaction')['payee_id'];
                $model->particular = Yii::$app->request->post('Transaction')['particular'];
                $model->transaction_date = Yii::$app->request->post('Transaction')['transaction_date'];
                if (Yii::$app->user->can('ro_accounting_admin')) {
                    $model->responsibility_center_id = Yii::$app->request->post('Transaction')['responsibility_center_id'];
                }
                $prItems = Yii::$app->request->post('prItems') ?? [];
                $allotmentItems = Yii::$app->request->post('items') ?? [];
                if (empty($allotmentItems)) {
                    throw new ErrorException('Allotment and Gross Amount is Required');
                }
                $old_year = DateTime::createFromFormat('m-d-Y', $oldModel->transaction_date)->format('Y');
                $new_year = DateTime::createFromFormat('m-d-Y', $model->transaction_date)->format('Y');
                if (intval($old_year) !== intval($new_year) || intval($oldModel->responsibility_center_id) !==  intval($model->responsibility_center_id)) {
                    $model->tracking_number = $this->getTrackingNumber($model->responsibility_center_id, $model->transaction_date);
                }
                if (empty($allotmentItems)) {
                    throw new ErrorException('Allotment and Gross Amount is Required');
                }
                $iarItems = [];

                if ($model->type === 'multiple') {
                    $multipleIar = Yii::$app->request->post('multiple_iar') ?? [];
                    if (empty($multipleIar)) {
                        throw new ErrorException('IAR is Required');
                    }
                    $iarItems = $multipleIar;
                } else if ($model->type === 'single') {
                    $singleIar = Yii::$app->request->post('single_iar') ?? [];
                    if (empty($singleIar)) {
                        throw new ErrorException('IAR is Required');
                    }
                    $iarItems[] = $singleIar;
                }

                if ($model->type != 'no-iar'  && empty($prItems)) {
                    throw new ErrorException('Please Select a Purchase Request Below');
                }

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $InsertPrs = $this->InsertPrs($model->id, $prItems, true);
                if ($InsertPrs !== true) {
                    throw new ErrorException($InsertPrs);
                }
                $insertItems = $this->insertItems($model->id, $allotmentItems, true);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                // $insIar = $this->InsertTransactionIar($model->id, $iarItems);
                $insIar = $model->insertIarItems($iarItems);
                if ($insIar !== true) {
                    throw new ErrorException($insIar);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items,
            'action' => 'update',
            'transactionPrItems' => $model->getPrItems()
        ]);
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     // if (Yii::$app->user->can('delete-transaction')) {

    //     // $this->findModel($id)->delete();

    //     // return $this->redirect(['index']);
    //     // } else {

    //     //     throw new ForbiddenHttpException();
    //     // }
    // }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionOrsForm()
    {
        return $this->render('ors_form');
    }
    public function actionVoucher()
    {
        return $this->render('disbursement_voucher');
    }
    public function actionGetAllTransaction()
    {
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('transaction')
            ->all();
        return json_encode($query);
    }
    // IMPORT FILE MUST BE XLSX EXTENSION
    public function actionImportTransaction()
    {
        if (!empty($_POST)) {
            // $chart_id = $_POST['chart_id'];
            $name = $_FILES["file"]["name"];
            // var_dump($_FILES['file']);
            // die();
            $id = uniqid();
            $file = "transaction/{$id}_{$name}";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file);
            $excel->setActiveSheetIndexByName('Import Transactions');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

            $latest_tracking_no = (new \yii\db\Query())
                ->select('tracking_number')
                ->from('transaction')
                ->orderBy('id DESC')->one();
            if ($latest_tracking_no) {
                $x = explode('-', $latest_tracking_no['tracking_number']);
                $last_number = $x[2] + 1;
            } else {
                $last_number = 1;
            }
            // 
            $qwe = 1;
            foreach ($worksheet->getRowIterator(3) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 3) {
                        $cells[] = $cell->getCalculatedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $tracking_number = $cells[0];
                    $payee_name =  trim($cells[1]);
                    $particular = $cells[2];
                    $amount = $cells[3];
                    $responsibility_center_name = $cells[4];
                    $payroll_number = $cells[5];
                    // $earmark_number = $cells[5];
                    // $date = $cells[7];

                    // $name = $cells[1];
                    if (
                        !empty($tracking_number)
                        || !empty($payee_name)
                        || !empty($particular)
                        || !empty($amount)
                        || !empty($responsibility_center_name)
                        || !empty($payroll_number)
                    ) {

                        if (
                            // empty( $tracking_number)
                            empty($payee_name)
                            || empty($particular)
                            // || empty($amount)
                            // || empty($responsibility_center_name)
                            // || empty($earmark_number)
                            // || empty($payroll_number)
                            // || empty($date)
                        ) {
                            return json_encode(['isSuccess' => false, 'error' => "Error Something is Missing in Line $key"]);
                            die();
                        } else {
                            $payee  = (new \yii\db\Query())
                                ->select(['account_name', 'id'])
                                ->from('payee')
                                ->where('account_name LIKE :account_name', ['account_name' => "%$payee_name%"])
                                ->one();
                            // $payee= Yii::$app->db->createCommand("SELECT * FROM payee WHERE payee.account_name LIKE '%$payee_name%'")->queryOne();
                            $responsibility_center  = (new \yii\db\Query())
                                ->select(['name', 'id'])
                                ->from('responsibility_center')
                                ->where('name LIKE :name', ['name' => $responsibility_center_name])
                                ->one();
                            // $responsibility_center = ResponsibilityCenter::find()->where('like', 'name', $responsibility_center_name)->one();
                            if (!empty($payee)) {
                                $payee_id = $payee['id'];
                            } else {
                                return json_encode(['isSuccess' => false, 'error' => "Payee Does Not Exist in line $key $payee_name"]);
                                die();
                            }
                            if (!empty($responsibility_center)) {
                                $responsibility_center_id = $responsibility_center['id'];
                            } else {
                                return json_encode(['isSuccess' => false, 'error' => "Responsibility Center Does Not Exist in Line $key"]);
                                die();
                            }

                            $data[] = [
                                'responsibility_center_id' => !empty($responsibility_center) ? $responsibility_center['id'] : NULL,
                                'payee_id' => $payee_id,
                                'particular' => $particular,
                                'gross_amount' => $amount,
                                'tracking_number' => $this->getTrackingNumber($responsibility_center['id'], $qwe, date('m-d-y')),
                                // 'earmark_no' => $earmark_number,
                                'payroll_number' => $payroll_number,
                                // 'transaction_date' => $date
                            ];
                            //     return "yawa";
                            //     die();
                            // }
                        }
                    }
                    // echo "<pre>";
                    // var_dump($last_number);
                    // echo "<pre>";
                    $last_number++;
                    $qwe++;
                }
            }

            $column = [
                'responsibility_center_id',
                'payee_id',
                'particular',
                'gross_amount',
                'tracking_number',
                // 'earmark_no',
                'payroll_number',
                // 'transaction_date',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('transaction', $column, $data)->execute();

            // return $this->redirect(['index']);
            return json_encode(['isSuccess' => true]);
            // ob_clean();
            // echo "<pre>";
            // var_dump($data);
            // echo "<pre>";
            // return ob_get_clean();
        }
    }
    public function actionSample($id)
    {
        return $this->render('view_sample', [
            'model' => $this->findModel2($id),
        ]);
    }
    protected function findModel2($id)
    {
        if (($model = SubAccounts1::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function getTrackingNumber($responsibility_center_id, $date)
    {

        $q  = DateTime::createFromFormat('m-d-Y', $date);
        $year = $q->format('Y');
        return  Yii::$app->db->createCommand("CALL transaction_number(:_year,:responsibility_center_id,@transction_number)")
            ->bindValue(':_year', $year)
            ->bindValue(':responsibility_center_id', $responsibility_center_id)->queryScalar();
    }
    public function actionGetTransaction()
    {
        if (!empty($_POST)) {
            $transaction_id = $_POST['transaction_id'];
            $query = Yii::$app->db->createCommand("SELECT 
            payee.account_name,`transaction`.particular, SUM(transaction_items.amount) as gross_amount
            FROM `transaction`
            LEFT JOIN payee ON `transaction`.payee_id = payee.id
            LEFT JOIN transaction_items ON `transaction`.id = transaction_items.fk_transaction_id
            where `transaction`.id =:transaction_id
            GROUP BY 
            payee.account_name,`transaction`.particular")
                ->bindValue(':transaction_id', $transaction_id)
                ->queryOne();
            $transaction_items  = Yii::$app->db->createCommand("SELECT 
            record_allotments_view.entry_id as raoud_id,
                                   record_allotments_view.serial_number,
                                   record_allotments_view.mfo_code as mfo_pap_code_code,
                                   record_allotments_view.mfo_name as mfo_pap_name,
                                   record_allotments_view.fund_source as fund_source_name,
                                   record_allotments_view.uacs as object_code,
                                   record_allotments_view.general_ledger,
                                   record_allotments_view.balance as remain,
                                  chart_of_accounts.id as chart_of_account_id,
           transaction_items.amount as obligation_amount
           FROM transaction_items 
           LEFT JOIN record_allotments_view ON transaction_items.fk_record_allotment_entries_id = record_allotments_view.entry_id 
           LEFT JOIN chart_of_accounts ON record_allotments_view.uacs = chart_of_accounts.uacs
            WHERE transaction_items.fk_transaction_id = :transaction_id")
                ->bindValue(':transaction_id', $transaction_id)
                ->queryAll();
            return json_encode(["result" => $query, 'transaction_items' => $transaction_items]);
        }
    }
    public function actionSearchTransaction($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select(["id", "tracking_number as text", "SUBSTRING_INDEX(`transaction`.tracking_number,'-',-2) as q"])
                ->from('transaction')
                ->where(['like', 'tracking_number', $q])
                ->orderBy('q DESC');

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        //  elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => AdvancesEntries::find($id)->fund_source];
        // }
        return $out;
    }
    public function actionIarDetails()
    {
        if (Yii::$app->request->isPost) {
            $id = $_POST['id'];
            $query = YIi::$app->db->createCommand("SELECT 
            iar_index.payee_id as id,
            iar_index.payee_name,
            iar_index.purpose,
            iar_index.total_amount as amount
            FROM iar_index
            WHERE iar_index.id = :id")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionGetPrAllotments()
    {
        if (Yii::$app->request->isPost) {
            $id  = Yii::$app->request->post('id');
            $query = Yii::$app->db->createCommand("SELECT 
            pr_purchase_request.pr_number,
            record_allotments.serial_number as allotment_number,
            pr_purchase_request_allotments.id as prAllotmentId,
            UPPER(office.office_name) as office_name,
            UPPER(divisions.division) as division ,
            CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
            fund_source.`name` as fund_source_name,
            chart_of_accounts.general_ledger as account_title,
            pr_purchase_request_allotments.amount as prAllotmentAmt,
            IFNULL(pr_purchase_request_allotments.amount,0) - IFNULL(ttlTransaction.ttlTransactAmt,0) as balance,
            books.`name` as book_name,
            ttlTransaction.ttlTransactAmt,
            pr_purchase_request.purpose
            FROM
            pr_purchase_request
            INNER JOIN pr_purchase_request_allotments ON pr_purchase_request.id = pr_purchase_request_allotments.fk_purchase_request_id
            INNER JOIN record_allotment_entries ON pr_purchase_request_allotments.fk_record_allotment_entries_id = record_allotment_entries.id
            INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
            LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
            LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
            LEFT JOIN office ON record_allotments.office_id = office.id
            lEFT JOIN divisions ON record_allotments.division_id = divisions.id
            LEFT JOIN books ON record_allotments.book_id = books.id
            LEFT JOIN (SELECT
              transaction_pr_items.fk_pr_allotment_id,
              SUM(transaction_pr_items.amount) as ttlTransactAmt
              FROM transaction_pr_items
              WHERE transaction_pr_items.is_deleted = 0
              GROUP BY transaction_pr_items.fk_pr_allotment_id
            ) as ttlTransaction ON pr_purchase_request_allotments.id = ttlTransaction.fk_pr_allotment_id
            WHERE pr_purchase_request.id = :id
                AND pr_purchase_request_allotments.is_deleted =0
                AND  IFNULL(pr_purchase_request_allotments.amount,0) - IFNULL(ttlTransaction.ttlTransactAmt,0) >0
             ")
                ->bindValue(':id', $id)
                ->queryAll();
            return json_encode($query);
        }
    }
    public function actionSearchTransactionPaginated($page = 1, $q = null, $id = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            // $out['results'] = ['id' => $id, 'text' => Payee::findOne($id)->account_name];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('transaction.id, transaction.tracking_number AS text')
                ->from('transaction')
                ->where(['like', 'transaction.tracking_number', $q]);

            $query->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
}
