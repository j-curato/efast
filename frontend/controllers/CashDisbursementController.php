<?php

namespace frontend\controllers;

use app\models\CancelledDisbursements;
use app\models\CancelledDisbursementsSearch;
use Yii;
use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use app\models\DvAccountingEntries;
use app\models\DvAucs;
use app\models\DvAucsEntries;
use DateTime;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashDisbursementController implements the CRUD actions for CashDisbursement model.
 */
class CashDisbursementController extends Controller
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
                    'create',
                    'update',
                    'delete',
                    'view',
                    'insert-cash-disbursement',
                    'import',
                    'get-all-dv',
                    'get-cash-disbursement',
                    'cancel',
                    'cancel-disbursement',
                    'cancel-disbursement-index',
                    'search-dv',

                ],

                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                            'insert-cash-disbursement',
                            'import',
                            'get-all-dv',
                            'get-cash-disbursement',
                            'cancel',
                            'cancel-disbursement',
                            'cancel-disbursement-index',
                            'search-dv',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    // [
                    //     'actions' => [
                    //         'index',

                    //     ],
                    //     'allow' => true,
                    //     'roles' => ['department-offices']
                    // ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    private function getDvDetails($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 

        dv_aucs.dv_number,
        dv_aucs.particular,
        payee.account_name as payee,
        disburse.ttlDisburse,
        cash.cash_id
        FROM dv_aucs
        LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,
        SUM(dv_aucs_entries.amount_disbursed) as ttlDisburse 
        FROM dv_aucs_entries
         WHERE dv_aucs_entries.is_deleted = 0
        GROUP BY dv_aucs_entries.dv_aucs_id
        ) as 
        disburse ON dv_aucs.id = disburse.dv_aucs_id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        LEFT JOIN (SELECT cash_disbursement.dv_aucs_id,cash_disbursement.id as cash_id FROM cash_disbursement WHERE cash_disbursement.is_cancelled = 0) cash
        ON dv_aucs.id = cash.dv_aucs_id
        
        WHERE 
        dv_aucs.id = :id")
            ->bindValue(':id', $id)
            ->queryOne();
        return $query;
    }

    /**
     * Lists all CashDisbursement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashDisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CashDisbursement model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CashDisbursement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CashDisbursement();

        if ($model->load(Yii::$app->request->post())) {

            try {
                $checkExist = YIi::$app->db->createCommand("SELECT EXISTS(SELECT *
                 FROM cash_disbursement WHERE cash_disbursement.is_cancelled = 0 AND cash_disbursement.dv_aucs_id = :dv_id)")
                    ->bindValue(':dv_id', $model->dv_aucs_id)
                    ->queryScalar();
                if ($checkExist) {
                    throw new ErrorException("DV Already Disbursed");
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }

                Yii::$app->db->createCommand("UPDATE advances_entries 
                    LEFT JOIN advances ON advances_entries.advances_id  = advances.id
                    SET advances_entries.is_deleted = 0,
                    advances_entries.cash_disbursement_id = :cash_id
                    WHERE 
                    advances.dv_aucs_id = :dv_id
                    AND advances_entries.is_deleted = 9
                    ")
                    ->bindValue(':dv_id', $model->dv_aucs_id)
                    ->bindValue(':cash_id', $model->id)
                    ->query();
            } catch (ErrorException $e) {
                return json_encode(['error' => true, 'error_message' => $e->getMessage()]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'type' => 'create'
        ]);
    }

    /**
     * Updates an existing CashDisbursement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $checkExist = YIi::$app->db->createCommand("SELECT EXISTS(SELECT *
                 FROM cash_disbursement WHERE cash_disbursement.is_cancelled = 0 AND cash_disbursement.dv_aucs_id = :dv_id AND cash_disbursement.id != :cash_id)")
                    ->bindValue(':dv_id', $model->dv_aucs_id)
                    ->bindValue(':cash_id', $model->id)
                    ->queryScalar();
                if ($checkExist) {
                    throw new ErrorException("DV Already Disbursed");
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }

                Yii::$app->db->createCommand("UPDATE advances_entries 
                    LEFT JOIN advances ON advances_entries.advances_id  = advances.id
                    SET advances_entries.is_deleted = 0,
                    advances_entries.cash_disbursement_id = :cash_id
                    WHERE 
                    advances.dv_aucs_id = :dv_id
                    AND advances_entries.is_deleted = 9
                    ")
                    ->bindValue(':dv_id', $model->dv_aucs_id)
                    ->bindValue(':cash_id', $model->id)
                    ->query();
            } catch (ErrorException $e) {
                return json_encode(['error' => true, 'error_message' => $e->getMessage()]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
            'type' => 'update',
            'dv_details' => $this->getDvDetails($model->dv_aucs_id)
        ]);
    }

    /**
     * Deletes an existing CashDisbursement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        if ($model->is_cancelled === 1) {
            $model->delete();
        } else {
            return $this->redirect(['index']);
        }
        // $this->findModel($id)->delete();

        return $this->redirect(['cancel-disbursement-index']);
    }

    /**
     * Finds the CashDisbursement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CashDisbursement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CashDisbursement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionInsertCashDisbursement()
    {

        if ($_POST) {

            $reporting_period = $_POST["reporting_period"];
            $book_id = $_POST["book"];
            $check_ada_no = $_POST["check_ada_no"];
            $good_cancelled = empty($_POST["good_cancelled"]) ? $_POST['good_cancelled'] : 0;
            $issuance_date = $_POST["issuance_date"];
            $mode_of_payment = $_POST["mode_of_payment"];
            $ada_number = $_POST["ada_number"];
            $selected_items = !empty($_POST['selection']) ? $_POST['selection'] : '';
            $out_time = date('H:i:s', strtotime($_POST['out_time']));
            $begin_time = date('H:i:s', strtotime($_POST['begin_time']));

            // return json_encode(["isSuccess" => false,'error'=>$begin_time]);
            // if (!empty(count($_POST['selection'])) > 1) {
            //     return json_encode(["error" => "Selected Dv is More Than 1"]);
            // } else {

            if (!empty($_POST['update_id'])) {
                $cd = CashDisbursement::findOne($_POST['update_id']);
            } else {
                $cd = new CashDisbursement();
            }
            if ($good_cancelled == 0) {
                if (empty($selected_items)) {
                    return json_encode(['isSuccess' => false, "error" => "Select DV"]);
                    die();
                }
                if (count($selected_items) > 1) {
                    return json_encode(["error" => "Selected Dv is More Than 1"]);
                    die();
                } else {


                    $check_accounting_in_out = Yii::$app->db->createCommand("SELECT dv_aucs.in_timestamp, dv_aucs.out_timestamp FROM dv_aucs WHERE dv_aucs.id = :id")->bindValue(':id', $_POST['selection'][0])->queryOne();

                    if (empty($check_accounting_in_out['in_timestamp']) || empty($check_accounting_in_out['out_timestamp'])) {
                        return json_encode(['isSuccess' => false, "error" => "There is no time in and time out for DV selected."]);
                        die();
                    }
                }

                $cd->dv_aucs_id = $_POST['selection'][0];
            } else if ($good_cancelled == 1) {
                if (!empty($selected_items)) {
                    return json_encode(["error" => "Select Type is Cancelled "]);
                    die();
                }
            } else if (empty($good_cancelled)) {
                return json_encode(["error" => "Good/Cancelled is Required "]);
                die();
            }
            // SELECT * FROM `cash_disbursement` WHERE cash_disbursement.dv_aucs_id=6697;
            if (empty($_POST['update_id'])) {
                $query = (new \yii\db\Query)
                    ->select("cash_disbursement.id")
                    ->from("cash_disbursement")
                    ->where("cash_disbursement.dv_aucs_id = :dv_aucs_id", ['dv_aucs_id' => $selected_items[0]])
                    ->andWhere("cash_disbursement.is_cancelled=0")
                    ->one();
                if (!empty($query)) {
                    return json_encode(['isSuccess' => 'exist', 'id' => $query['id']]);
                }
            }

            if (empty($good_cancelled)) {
                $good_cancelled = 0;
            }

            $cd->book_id = $book_id;
            $cd->reporting_period = $reporting_period;
            $cd->mode_of_payment = $mode_of_payment;
            $cd->check_or_ada_no = $check_ada_no;
            $cd->is_cancelled = $good_cancelled;
            $cd->issuance_date = $issuance_date;
            $cd->ada_number = $ada_number;
            $cd->begin_time = $begin_time;
            $cd->out_time = $out_time;

            if ($cd->validate()) {
                if ($cd->save()) {

                    $q = Yii::$app->db->createCommand("UPDATE advances_entries 
            LEFT JOIN advances ON advances_entries.advances_id  = advances.id
            SET advances_entries.is_deleted = 0,
            advances_entries.cash_disbursement_id = :cash_id
            WHERE 
            advances.dv_aucs_id = :dv_id
            AND advances_entries.is_deleted = 9
            ")
                        ->bindValue(':dv_id', $cd->dv_aucs_id)
                        ->bindValue(':cash_id', $cd->id)
                        ->execute();
                    return json_encode(["isSuccess" => true, 'id' => $cd->id]);
                }
            } else {

                // echo"<pre>";
                // var_dump($q);
                // echo"</pre>";
                // die();
                return json_encode(["isSuccess" => false, "error" => $cd->errors]);
            }
            // }
        }
    }
    public function actionImport()
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
            $excel->setActiveSheetIndexByName('Cash Disbursement');
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
            foreach ($worksheet->getRowIterator(4) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 7) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $book_name = trim($cells[1]);
                    $reporting_period =  date("Y-m", strtotime($cells[2]));
                    $mode_of_payment = trim($cells[3]);
                    $check_ada_number = trim($cells[4]);
                    $ada_number = trim($cells[5]);
                    $good_cancelled = strtolower(trim($cells[6]));
                    $issuance_date = date('Y-m-d', strtotime($cells[7]));
                    $dv_number = trim($cells[8]);
                    $dv_id = null;
                    return   $cells[7];
                    die();
                    if ($good_cancelled === 'good') {

                        $dv = (new \yii\db\Query)
                            ->select("id")
                            ->from("dv_aucs")
                            ->where("dv_number =:dv_number", ['dv_number' => $dv_number])
                            ->one();

                        if (empty($dv)) {
                            return json_encode(['isSuccess' => false, 'error' => "DV Number Does not exist in line $key"]);
                        }

                        $dv_id = $dv['id'];
                    }
                    $book = (new \yii\db\Query())
                        ->select("books.id")
                        ->from('books')
                        ->where("books.name = :name", ['name' => $book_name])
                        ->one();
                    if (empty($book)) {
                        return json_encode(['isSuccess' => false, 'error' => "Book Does not exist in line $key"]);
                    }
                    strtolower(trim($good_cancelled)) === 'good' ? false : true;
                    $data[] = [
                        'book_id' => $book['id'],
                        'dv_id' => $dv_id,
                        'reporting_period' => $reporting_period,
                        'issuance_date' => $issuance_date,
                        'mode_of_payment' => $mode_of_payment,
                        'check_ada_number' => $check_ada_number,
                        'good_cancelled' => $good_cancelled,
                        'ada_number' => $ada_number

                    ];
                }
            }

            $column = [
                'book_id',
                'dv_aucs_id',
                'reporting_period',
                'issuance_date',
                'mode_of_payment',
                'check_or_ada_no',
                'is_cancelled',
                'ada_number',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('cash_disbursement', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            return ob_get_clean();
        }
    }

    public function actionGetAllDv()
    {
        $query = (new \yii\db\Query())
            ->select(['cash_disbursement.id as cash_id', 'dv_aucs.dv_number'])
            ->from('cash_disbursement')
            ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id  = dv_aucs.id')
            ->where('cash_disbursement.is_cancelled = :is_cancelled', ['is_cancelled' => false])
            ->all();

        // ob_clean();
        // echo "<pre>";
        // var_dump($query);
        // echo "</pre>";
        return json_encode($query);
        // ob_clean();
        // echo "<pre>";
        // var_dump($query);
        // echo "</pre>";
        // return ob_get_clean();
    }
    public function actionGetDv()
    {
        if (!empty($_POST)) {
            $cash_id = $_POST['cash_id'];

            // $query = (new \yii\db\Query())
            //     ->select([
            //         'cash_disbursement.book_id',
            //         'dv_aucs.dv_number',
            //         'dv_aucs.id as dv_aucs_id',
            //         'dv_aucs.payee_id',
            //         'dv_aucs.particular',
            //         'dv_aucs.reporting_period',
            //         'cash_disbursement.check_or_ada_no',
            //         'cash_disbursement.mode_of_payment',
            //         'cash_disbursement.issuance_date',
            //         'cash_disbursement.ada_number',
            //         'responsibility_center.id as rc_id',
            //         'transaction.id as transaction_id',
            //         'SUM(dv_aucs_entries.amount_disbursed) as total_disbursed',
            //         'jev_preparation.id as jev_id'

            //     ])
            //     ->from("cash_disbursement")
            //     ->join('LEFT JOIN', 'jev_preparation', 'cash_disbursement.id = jev_preparation.cash_disbursement_id')
            //     ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
            //     ->join('LEFT JOIN', 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
            //     ->join('LEFT JOIN', 'process_ors', 'dv_aucs_entries.process_ors_id = process_ors.id')
            //     ->join('LEFT JOIN', 'transaction', 'process_ors.transaction_id = transaction.id')
            //     ->join('LEFT JOIN', 'responsibility_center', 'transaction.responsibility_center_id = responsibility_center.id')
            //     ->where("cash_disbursement.id = :id", [
            //         'id' => $cash_id
            //     ])
            //     ->groupBy([
            //         'cash_disbursement.id',
            //         'cash_disbursement.book_id',
            //         'cash_disbursement.check_or_ada_no',
            //         'cash_disbursement.mode_of_payment',
            //         'cash_disbursement.issuance_date',
            //         'cash_disbursement.ada_number',
            //     ])
            //     ->one();
            $query = (new \yii\db\Query())
                ->select('*')
                ->from('detailed_cash_view')
                ->where('cash_id = :cash_id', ['cash_id' => $cash_id])
                ->one();
            $date = new DateTime($query['issuance_date']);
            // echo $date->format('Y-m-d H:i:s');
            // $q = new DateTime($query['issuance_date']);
            $query['issuance_date'] = $date->format('Y-m-d');

            $model = DvAucs::findOne($query['dv_aucs_id']);

            $dv_accounting_entries = [];
            $dv_accounting_entries = Yii::$app->db->createCommand("SELECT 
            dv_accounting_entries.dv_aucs_id,
                                   dv_accounting_entries.debit,
                                  dv_accounting_entries.credit,
                                   dv_accounting_entries.net_asset_equity_id,
                                   dv_accounting_entries.object_code,
                                  dv_accounting_entries.cashflow_id,
           accounting_codes.account_title
           FROM dv_accounting_entries 
           LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
           WHERE dv_accounting_entries.dv_aucs_id = :dv_id")->bindValue(':dv_id', $model->id)
                ->queryAll();
            // if (!empty($model->dvAccountingEntries)) {

            //     foreach ($model->dvAccountingEntries as $val) {

            //         if ($val->lvl === 2) {
            //             $chart_id = (new \yii\db\Query())->select(['sub_accounts1.id'])->from('sub_accounts1')
            //                 ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
            //                 ->where('sub_accounts1.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
            //         } else if ($val->lvl === 3) {
            //             $chart_id = (new \yii\db\Query())->select(['sub_accounts2.id'])->from('sub_accounts2')
            //                 // ->join("LEFT JOIN", 'sub_accounst1', 'sub_accounts2.sub_accounts1_id = sub_accounts1.id')
            //                 // ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
            //                 ->where('sub_accounts2.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
            //         } else {
            //             $chart_id =  $val->chart_of_account_id;
            //         }
            //         $dv_accounting_entries[] = [
            //             'dv_aucs_id' => $val->dv_aucs_id,
            //             'chart_of_account_id' => $val->chart_of_account_id,
            //             'id' => $chart_id,
            //             'debit' => $val->debit,
            //             'credit' => $val->credit,
            //             'net_asset_equity_id' => $val->net_asset_equity_id,
            //             'object_code' => $val->object_code,
            //             'lvl' => $val->lvl,
            //             'cashflow_id' => $val->cashflow_id,
            //         ];
            //     }
            // }

            return json_encode(['results' => $query, 'dv_accounting_entries' => $dv_accounting_entries]);
        }
    }
    public function actionGetCashDisbursement()
    {
        if ($_POST) {
            $selected = $_POST['selection'];
            $q = "(";
            $x = count($selected);
            foreach ($selected as $key => $val) {

                if ($key + 1 === $x) {
                    $q .= $val;
                } else {
                    $q .= $val . ',';
                }
            }
            $q .= ')';
            $query  = Yii::$app->db->createCommand("SELECT 
            cash_disbursement.check_or_ada_no,
            cash_disbursement.id,
            dv_aucs.dv_number,
            dv_aucs.particular,
            dv.total_disbursed,
            payee.account_name as payee 

             FROM cash_disbursement,dv_aucs,payee,(SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,dv_aucs_entries.dv_aucs_id FROM dv_aucs_entries GROUP BY dv_aucs_id) as dv
              WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
              AND dv_aucs.payee_id = payee.id
              AND dv_aucs.id = dv.dv_aucs_id
              AND cash_disbursement.id IN $q")
                // ->bindValue(:id,$q)
                ->queryAll();
            // $query = (new \yii\db\Query())
            //     ->select("*")
            //     ->from("cash_disbursement")
            //     ->where('cash_disbursement.id IN :id', ['id' => $q])
            //     ->all();
            return json_encode(['results' => $query]);
        }
        return json_encode('qwe');
    }

    public function actionCancel()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $model = CashDisbursement::findOne($id);
            $model->is_cancelled ? $model->is_cancelled = false : $model->is_cancelled = true;
            if ($model->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => $model->is_cancelled]);
            }
        }
    }
    public function actionCancelDisbursement()
    {
        $searchModel = new CashDisbursementSearch();
        $searchModel->is_cancelled = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $selected = $_POST['selection'];
            $model = CashDisbursement::findOne($selected[0]);

            $query = Yii::$app->db->createCommand("SELECT EXISTS(
                SELECT *
                FROM cash_disbursement
                WHERE `parent_disbursement` =  :parent_id  AND is_cancelled = 1)")
                ->bindValue(':parent_id', $model->id)
                ->queryScalar();
            if (intval($query) === 1) {
                return json_encode(['isSuccess' => false, 'cancelled' => 'cancel', 'error' => 'na cancel na']);
            }
            try {

                $new_model  = new CashDisbursement();
                $new_model->book_id = $model->book_id;
                $new_model->dv_aucs_id = $model->dv_aucs_id;
                $new_model->reporting_period = $reporting_period;
                $new_model->mode_of_payment = $model->mode_of_payment;
                $new_model->check_or_ada_no = $model->check_or_ada_no;
                $new_model->is_cancelled = 1;
                $new_model->issuance_date = $model->issuance_date;
                $new_model->ada_number = $model->ada_number;
                $new_model->parent_disbursement = $model->id;

                if ($new_model->validate()) {

                    if ($new_model->save(false)) {
                        return json_encode(['isSuccess' => true, 'cancelled' => $new_model->is_cancelled]);
                    }
                } else {
                    return json_encode(['isSuccess' => false, 'cancelled' => $new_model->errors, 'error' => $new_model->errors['reporting_period']]);
                }
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'cancelled' => 'cancel', 'error' => $e->getMessage()]);
            }
        }
        return $this->render('cancel_disbursement', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancelDisbursementIndex()
    {
        $searchModel = new CancelledDisbursementsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
        return $this->render('cancel_disbursement_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearchDv($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(['cash_disbursement.id as id', 'dv_aucs.dv_number as text'])
                ->from('cash_disbursement')
                ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id  = dv_aucs.id')
                ->andWhere('cash_disbursement.is_cancelled = :is_cancelled', ['is_cancelled' => false])
                ->andWhere(['like', 'dv_aucs.dv_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    public function actionDvDetails()
    {
        if (YIi::$app->request->isPost) {
            return json_encode($this->getDvDetails(YIi::$app->request->post('id')));
        }
    }
}
