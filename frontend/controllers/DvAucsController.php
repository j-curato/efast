<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use yii\db\Query;
use ErrorException;
use app\models\Books;
use app\models\DvAucs;

use yii\web\Controller;
use app\models\Advances;
use yii\filters\VerbFilter;
use app\models\DvAucsSearch;

use app\models\SubAccounts2;
use app\models\DvAucsEntries;
use yii\filters\AccessControl;
use app\models\AdvancesEntries;
use app\models\ProcessOrsSearch;
use app\models\ProcessOrsEntries;
use yii\web\NotFoundHttpException;
use app\models\DvAccountingEntries;
use app\models\DvAucsFile;
use app\models\TrackingSheetIndexSearch;
use common\models\UploadForm;
use phpDocumentor\Reflection\PseudoTypes\False_;
use yii\helpers\FileHelper;
use yii\helpers\Url;

/**
 * DvAucsController implements the CRUD actions for DvAucs model.
 */
class DvAucsController extends Controller
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
                    'get-dv',
                    'insert-dv',
                    'update-dv',
                    'import',
                    'cancel',
                    'dv-form',
                    'is-payable',
                    'create-tracking',
                    'tracking-view',
                    'tracking-update',
                    'tracking-index',
                    'add-link',
                    'turnarround-time',
                    'return',
                    'in',
                    'out',
                    'turnarround-view',
                    'get-object-code'

                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                            'get-dv',
                            'insert-dv',
                            'update-dv',
                            'import',
                            'cancel',
                            'dv-form',
                            'is-payable',
                            'create-tracking',
                            'tracking-view',
                            'tracking-update',
                            'tracking-index',
                            'add-link',
                            'turnarround-time',
                            'return',
                            'in',
                            'out',
                            'turnarround-view',
                            'get-object-code'
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'index',

                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ]
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
    public function beforeAction($action)
    {
        if ($action->id == 'update-dv') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all DvAucs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DvAucsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DvAucs model.
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
     * Creates a new DvAucs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DvAucs();

        if ($_POST) {

            if ($model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        $searchModel = new ProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
            'model' => $model
        ]);
    }

    /**
     * Updates an existing DvAucs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function insertAccountingEntries($dv_id = '', $object_codes = [], $debits = [], $credits = [], $accounting_entries = [])
    {

        if (!empty($accounting_entries)) {
            $params = [];
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'dv_accounting_entries.id', $accounting_entries], $params);
            Yii::$app->db->createCommand("DELETE FROM dv_accounting_entries WHERE  dv_accounting_entries.dv_aucs_id =:dv_id 
            AND dv_accounting_entries.payroll_id IS NULL
            AND dv_accounting_entries.remittance_payee_id IS NULL
            AND $sql", $params)
                ->bindValue(':dv_id', $dv_id)
                ->query();
        }
        // else {
        //     Yii::$app->db->createCommand("DELETE FROM dv_accounting_entries WHERE dv_aucs_id = :id",)
        //         ->bindValue(':id', $dv_id)
        //         ->query();
        // }
        // $query = YIi::$app->db->createCommand("SELECT id FROM dv_accounting_entries WHERE dv_aucs_id = :id")->bindValue(':id', $dv_id)->queryAll();
        // $source_fund_source_type_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', array_column($query,'id')), array_map('serialize', $accounting_entries))

        // );
        // var_dump($source_fund_source_type_difference);
        // die();
        foreach ($object_codes as $key => $val) {
            if (empty($accounting_entries[$key])) {
                $entry = new DvAccountingEntries();
            } else {
                $entry =  DvAccountingEntries::findOne($accounting_entries[$key]);
            }
            $entry->dv_aucs_id = $dv_id;
            $entry->object_code = $val;
            $entry->debit = !empty($debits[$key]) ? $debits[$key] : 0;
            $entry->credit = !empty($credits[$key]) ? $credits[$key] : 0;
            if ($entry->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function insertDvItems(
        $dv_aucs_id = '',
        $process_ors_id = [],
        $amount_disbursed = [],
        $vat_nonvat = [],
        $ewt = [],
        $compensation = [],
        $trust_liab = []
    ) {
        if (empty($dv_aucs_id)) {
            return  'No DV ID';
        }


        foreach ($process_ors_id as $key => $val) {
            $amount_disbursed_amount = !empty($amount_disbursed[$key]) ? $amount_disbursed[$key] : 0;
            $vat_nonvat_amount = !empty($vat_nonvat[$key]) ? $vat_nonvat[$key] : 0;
            $ewt_amount = !empty($ewt[$key]) ? $ewt[$key] : 0;
            $compensation_amount = !empty($compensation[$key]) ? $compensation[$key] : 0;
            $trust_liab_amount = !empty($trust_liab[$key]) ? $trust_liab[$key] : 0;
            $dv_item = new DvAucsEntries();
            $dv_item->process_ors_id = $val;
            $dv_item->dv_aucs_id = $dv_aucs_id;
            $dv_item->amount_disbursed = $amount_disbursed_amount;
            $dv_item->vat_nonvat = $vat_nonvat_amount;
            $dv_item->ewt_goods_services = $ewt_amount;
            $dv_item->compensation = $compensation_amount;
            $dv_item->other_trust_liabilities = $trust_liab_amount;
            if ($dv_item->save(false)) {
            } else {
                return 'DV Item Insert Fail';
            }
        }
        return true;
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $model;

        if ($_POST) {
            $transaction = YIi::$app->db->beginTransaction();
            $reporting_period = !empty($_POST['reporting_period']) ? $_POST['reporting_period'] : null;
            $nature_of_transaction = !empty($_POST['nature_of_transaction']) ? $_POST['nature_of_transaction'] : null;
            $mrd_classification = !empty($_POST['mrd_classification']) ? $_POST['mrd_classification'] : null;
            $payee = !empty($_POST['payee']) ? $_POST['payee'] : null;
            $transaction_type = !empty($_POST['transaction_type']) ? $_POST['transaction_type'] : null;
            $book = !empty($_POST['book']) ? $_POST['book'] : null;
            $particular = !empty($_POST['particular']) ? $_POST['particular'] : null;
            if ($oldModel->book_id != $book) {
                $book_name = Yii::$app->db->createCommand("SELECT `name` FROM books WHERE id = :id")->bindValue(':id', $book)->queryScalar();
                $dv_number = explode('-', $model->dv_number);
                $dv_number[0] = $book_name;
                $new_dv = implode('-', $dv_number);
                $model->dv_number = $new_dv;
            }

            $object_codes = !empty($_POST['object_code']) ? $_POST['object_code'] : [];
            $debits = !empty($_POST['debit']) ? $_POST['debit'] : [];
            $credits = !empty($_POST['credit']) ? $_POST['credit'] : [];

            $process_ors_id = !empty($_POST['process_ors_id']) ? $_POST['process_ors_id'] : [];
            $amount_disbursed = !empty($_POST['amount_disbursed']) ? $_POST['amount_disbursed'] : [];
            $vat_nonvat = !empty($_POST['vat_nonvat']) ? $_POST['vat_nonvat'] : [];
            $ewt_goods_services = !empty($_POST['ewt_goods_services']) ? $_POST['ewt_goods_services'] : [];
            $compensation = !empty($_POST['compensation']) ? $_POST['compensation'] : [];
            $other_trust_liabilities = !empty($_POST['other_trust_liabilities']) ? $_POST['other_trust_liabilities'] : [];
            $advances_province = !empty($_POST['advances_province']) ? $_POST['advances_province'] : '';
            $advances_period = !empty($_POST['advances_parent_reporting_period']) ? $_POST['advances_parent_reporting_period'] : '';
            $advances_bank_account_id = !empty($_POST['advances_bank_account_id']) ? $_POST['advances_bank_account_id'] : '';
            $advances_entries_id = !empty($_POST['advances_entries_id']) ? $_POST['advances_entries_id'] : [];
            $advances_reporting_period = !empty($_POST['advances_reporting_period']) ? $_POST['advances_reporting_period'] : [];
            $advances_report_type = !empty($_POST['advances_report_type']) ? $_POST['advances_report_type'] : [];
            $advances_fund_source = !empty($_POST['advances_fund_source']) ? $_POST['advances_fund_source'] : [];
            $advances_fund_source_type = !empty($_POST['advances_fund_source_type']) ? $_POST['advances_fund_source_type'] : '';
            $advances_object_code = !empty($_POST['advances_object_code']) ? $_POST['advances_object_code'] : [];
            $advances_amount = !empty($_POST['advances_amount']) ? $_POST['advances_amount'] : [];
            $advances_update_id = !empty($_POST['advances_id']) ? $_POST['advances_id'] : '';
            $dv_object_code = !empty($_POST['dv_object_code']) ? $_POST['dv_object_code'] : '';


            if (empty($mrd_classification)) {
                return json_encode(['form_error' => [
                    'mrd_classification' => 'MRD Classification cannot be blank'
                ]]);
            }
            if (empty($nature_of_transaction)) {
                return json_encode(['form_error' => [
                    'nature_of_transaction' => 'Nature of Transaction cannot be blank'
                ]]);
            }
            $model->reporting_period = $reporting_period;
            $model->payee_id = $payee;
            $model->transaction_type = $transaction_type;
            $model->nature_of_transaction_id = $nature_of_transaction;
            $model->mrd_classification_id =  $mrd_classification;
            $model->book_id = $book;
            $model->particular = $particular;
            $model->object_code = $dv_object_code;
            // return $model->book_id;
            // Yii::$app->db->createCommand("DELETE FROM dv_accounting_entries WHERE dv_aucs_id = :id ")
            //     ->bindValue(':id', $model->id)
            //     ->query();
            $accounting_entries = !empty($_POST['accounting_entries']) ? $_POST['accounting_entries'] : [];
            try {
                $flag = true;
                if ($model->validate()) {
                    if (!empty($debits) || !empty($credits)) {
                        $sum_debits = number_format(floatVal(array_sum($debits)), 2);
                        $sum_credits = number_format(floatVal(array_sum($credits)), 2);
                        if ($sum_debits !==  $sum_credits) {
                            $transaction->rollBack();
                            return json_encode(['check_error' => "Not Balance $sum_debits :  $sum_credits"]);
                        }
                    }
                    $accounting_entry =   $this->insertAccountingEntries($model->id, $object_codes, $debits, $credits, $accounting_entries);
                    if ($accounting_entry === false) {
                        $transaction->rollBack();
                        return json_encode(['check_error' => 'Error in inserting Accounting entries']);
                    }
                    if ($model->save(false)) {
                        Yii::$app->db->createCommand("DELETE FROM dv_aucs_entries WHERE dv_aucs_id = :id")
                            ->bindValue(':id', $model->id)
                            ->query();
                        $flag = $this->insertDvItems(
                            $dv_aucs_id = $model->id,
                            $process_ors_id,
                            $amount_disbursed,
                            $vat_nonvat,
                            $ewt_goods_services,
                            $compensation,
                            $other_trust_liabilities
                        );

                        if (!empty($advances_province) && !empty($advances_period) && !empty($advances_bank_account_id)) {
                            $advances_id = $this->insertAdvances($advances_province, $advances_period, $model->id, $advances_update_id, $advances_bank_account_id);
                            $this->insertAdvancesEntries(
                                $advances_id,
                                $advances_object_code,
                                $advances_amount,
                                $advances_fund_source,
                                $advances_fund_source_type,
                                $advances_report_type,
                                $advances_reporting_period,
                                $advances_entries_id,
                                $model->book_id
                            );
                        }
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['form_error' => $model->errors]);
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode(['check_error' => $flag]);
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        // return $this->render('update', [
        //     'model' => $model,
        // ]);
        $searchModel = new ProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // echo $id;

        $accounting_entries = Yii::$app->db->createCommand("SELECT CONCAT(dv_accounting_entries.object_code,'-',accounting_codes.account_title) as account_title ,
        dv_accounting_entries.object_code,
        dv_accounting_entries.debit,
        dv_accounting_entries.credit,
        dv_accounting_entries.id 
        FROM dv_accounting_entries
        LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
         WHERE dv_accounting_entries.dv_aucs_id = :id")
            ->bindValue(':id', $model->id)
            ->queryAll();
        $dv_items = Yii::$app->db->createCommand("SELECT 
            process_ors.serial_number,
            `transaction`.particular,
            payee.account_name as payee,
            dv_aucs_entries.process_ors_id,
            dv_aucs_entries.amount_disbursed,
            dv_aucs_entries.vat_nonvat,
            dv_aucs_entries.ewt_goods_services,
            dv_aucs_entries.compensation,
            dv_aucs_entries.other_trust_liabilities
            FROM dv_aucs_entries
            LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            LEFT JOIN payee ON `transaction`.payee_id = payee.id
            WHERE dv_aucs_entries.dv_aucs_id = :id")
            ->bindValue(':id', $model->id)
            ->queryAll();

        return $this->render('update', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'accounting_entries' => $accounting_entries,
            'dv_items' => $dv_items
        ]);
    }
    public function insertAdvancesEntries(
        $advances_id,
        $object_codes = [],
        $amounts = [],
        $fund_source = [],
        $fund_source_type = [],
        $report_type = [],
        $reporting_periods = [],
        $advances_entries_id = [],
        $book_id

    ) {
        // var_dump($book_id);
        // die();
        foreach ($fund_source as $i => $val) {
            if (empty($advances_entries_id[$i])) {

                $ad_entry = new AdvancesEntries();
                $ad_entry->is_deleted = 9;
                $ad_entry->advances_id = $advances_id;
            } else {
                $ad_entry = AdvancesEntries::findOne($advances_entries_id[$i]);
                if (!empty($ad_entry->advances->cashDisbursement->id)) {
                    if ($ad_entry->advances->cashDisbursement->is_cancelled != 1) {
                        $ad_entry->is_deleted = 0;
                    }
                }
            }

            $ad_entry->fund_source_type = $fund_source_type[$i];
            $ad_entry->object_code = $object_codes[$i];
            $ad_entry->fund_source = trim($val, " \r\n\t");
            $ad_entry->reporting_period = $reporting_periods[$i];
            $ad_entry->amount = $amounts[$i];
            $ad_entry->report_type = $report_type[$i];

            $ad_entry->book_id = intval($book_id);
            if ($ad_entry->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function insertAdvances($province, $reporting_period, $dv_aucs_id, $advances_update_id = '', $advances_bank_account_id)
    {

        if (empty($advances_update_id)) {
            $advances = new Advances();
            $advances->nft_number = Yii::$app->memem->generateAdvancesNumber();
        } else {
            $advances = Advances::findOne($advances_update_id);
        }
        $advances->reporting_period = $reporting_period;
        $advances->province = $province;
        $advances->dv_aucs_id = $dv_aucs_id;
        $advances->bank_account_id = $advances_bank_account_id;
        if ($advances->save(false)) {
            return $advances->id;
        } else {
            echo "FAILED IN ADVANCES";
            die();
        }
    }

    /**
     * Deletes an existing DvAucs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the DvAucs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DvAucs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DvAucs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetDv()
    {
        $x = [];
        $transaction_type = strtolower($_POST['transaction_type']);
        $selected_dv = $_POST['selection'];
        $dv_length = count($selected_dv);
        // return json_encode(["isSuccess" => false, "error" => $_POST['transaction_type']]);
        if ($transaction_type === 'single') {

            if (intval(count($selected_dv)) > 1) {
                return json_encode(["isSuccess" => false, "error" => "Transaction type is Single"]);
                die();
            }
            // return json_encode(["error" => "qwe"]);
            // die();
        } else if (empty($transaction_type)) {
            return json_encode(["isSuccess" => false, "error" => "Select Transaction Type"]);
            die();
        }
        if ($transaction_type === 'no ors') {
            return json_encode(["isSuccess" => false, "error" => "Cannot Add Transaction type is No Ors"]);
            die();
        }
        foreach ($selected_dv as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    "process_ors.id as ors_id",
                    "process_ors.serial_number",
                    "transaction.particular as transaction_particular",
                    "payee.account_name as transaction_payee",
                    "process_ors.book_id",
                    "total_obligated.total", "payee.id as transaction_payee_id"
                ])
                ->from('process_ors')
                ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")
                ->join("LEFT JOIN", "payee", "transaction.payee_id = payee.id")

                ->join("LEFT JOIN", "(SELECT 
                dv_aucs_entries.process_ors_id as ors_id,
                SUM(dv_aucs_entries.amount_disbursed) as total
                FROM dv_aucs_entries
                INNER JOIN dv_aucs ON dv_aucs_entries.dv_aucs_id = dv_aucs.id
                INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
                WHERE
                dv_aucs.is_cancelled = 0
                AND
                cash_disbursement.is_cancelled=0
                GROUP BY dv_aucs_entries.process_ors_id
                ) as total_obligated", "process_ors.id=total_obligated.ors_id")
                // ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                // ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id=record_allotments.id")
                // ->join("LEFT JOIN", "chart_of_accounts", "record_allotment_entries.chart_of_account_id=chart_of_accounts.id")
                // ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                // ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                // ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
                // ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                // ->join("LEFT JOIN", "process_ors", "raouds.process_ors_id=process_ors.id")
                // ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")
                // ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                // raouds.id , raouds.process_ors_id,
                // raouds.record_allotment_entries_id
                // FROM raouds,raoud_entries,process_ors
                // WHERE raouds.process_ors_id= process_ors.id
                // AND raouds.id = raoud_entries.raoud_id
                // AND raouds.process_ors_id IS NOT NULL 
                // GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")

                ->where("process_ors.id = :id", ['id' => $val])->one();
            // $query['obligation_amount'] =  $_POST['amount'][$val];
            $query['amount_disbursed'] =  $_POST['amount_disbursed'][$val];
            $query['vat_nonvat'] =  $_POST['vat_nonvat'][$val];
            $query['ewt_goods_services'] =  $_POST['ewt_goods_services'][$val];
            $query['compensation'] =  $_POST['compensation'][$val];
            $query['other_trust_liabilities'] =  $_POST['other_trust_liabilities'][$val];

            $x[] = $query;
        }

        // return json_encode($_POST['selection']);
        // $query=Yii::$app->db->createCommand("SELECT * FROM raouds where id IN ('1','2')")->queryAll();
        // ob_start();
        // echo "<pre>";
        // var_dump($_POST['1_percent_ewt']);
        // echo "</pre>";
        return json_encode(['isSuccess' => true, 'results' => $x]);
    }
    public function actionInsertDv()
    {


        if ($_POST) {
            $process_id = !empty($_POST['process_ors_id']) ? $_POST['process_ors_id'] : '';
            $nature_of_transaction_id = $_POST['nature_of_transaction'];
            $mrd_classification_id = $_POST['mrd_classification'];
            $reporting_period = $_POST['reporting_period'];
            $particular = $_POST['particular'];
            $payee_id = $_POST['payee'];
            $book_id = !empty($_POST['book']) ? $_POST['book'] : 5;
            $transaction_type = $_POST['transaction_type'];
            $advances_reporting_period = !empty($_POST['advances_reporting_period']) ? $_POST['advances_reporting_period'] : [];
            $advances_report_type = !empty($_POST['advances_report_type']) ? $_POST['advances_report_type'] : [];
            $advances_fund_source_type = !empty($_POST['advances_fund_source_type']) ? $_POST['advances_fund_source_type'] : [];
            $advances_fund_source = !empty($_POST['advances_fund_source']) ? $_POST['advances_fund_source'] : [];
            $advances_object_code = !empty($_POST['advances_object_code']) ? $_POST['advances_object_code'] : [];
            $advances_amount = !empty($_POST['advances_amount']) ? $_POST['advances_amount'] : [];
            $advances_province = !empty($_POST['advances_province']) ? $_POST['advances_province'] : [];
            // $advances_province = 'adn';
            $advances_period = !empty($_POST['advances_parent_reporting_period']) ? $_POST['advances_parent_reporting_period'] : [];
            // $advances_period = '2022-02';
            $advances_entries_id = !empty($_POST['advances_entries_id']) ? $_POST['advances_entries_id'] : [];
            $advances_update_id = !empty($_POST['advances_id']) ? $_POST['advances_id'] : '';
            $advances_bank_account_id = !empty($_POST['advances_bank_account_id']) ? $_POST['advances_bank_account_id'] : '';
            // return json_encode(
            //     [

            //         $advances_reporting_period,
            //         $advances_report_type,
            //         $advances_fund_source_type,
            //         $advances_fund_source,
            //         $advances_object_code,
            //     ]

            // );



            if (empty($_POST['transaction_timestamp'])) {
                $now = new DateTime();
                $bgn_time = $now->format('Y-m-d H:i:s');
            } else {
                $bgn_time = $_POST['transaction_timestamp'];
            }
            $transaction_timestamp = date('Y-m-d H:i:s', strtotime($bgn_time));
            $tracking_sheet = $_POST['tracking_sheet'];
            // if (array_sum($_POST['debit']) != array_sum($_POST['credit'])) {
            //     return json_encode(['isSuccess' => false, 'error' => 'Not Equal Debit and Credit']);
            // }
            // die();

            $account_entries =  !empty($_POST['chart_of_account_id']) ? count($_POST['chart_of_account_id']) : 0;
            if ($account_entries === 1 && $_POST['chart_of_account_id'][0] === '') {
                $account_entries = 0;
            }
            // return json_encode( $_POST['chart_of_account_id'] );

            if (strtolower($transaction_type) === 'single') {
                if (count($process_id) > 1) {
                    return json_encode(["isSuccess" => false, "error" => "Cannot Insert Transaction Type is Single But has More Than 1 Entries"]);
                    die();
                }
                $query = DvAucsEntries::find()
                    ->where("dv_aucs_entries.process_ors_id =:process_ors_id", ['process_ors_id' => $process_id[0]])
                    ->all();
                if (empty($_POST['update_id'])) {
                    foreach ($query as $val) {

                        if ($val->dvAucs->transaction_type === 'Single' && $val->dvAucs->is_cancelled === 0) {

                            return json_encode(['isSuccess' => 'exist', 'error' => 'Naa nay DV', 'id' => $val->dvAucs->id]);
                        }
                    }
                }
            }

            $params = [];
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'process_ors.id', $_POST['process_ors_id']], $params);
            $ors = (new \yii\db\Query())
                ->select("book_id")
                ->from('process_ors')
                ->where("$sql", $params)
                ->distinct('book_id')
                ->all();

            // return json_encode($ors->createCommand()->getRawSql());
            $x = [];
            foreach ($ors as $o) {
                $x[] = $o['book_id'];
            }
            // $y = array_unique($ors);
            $y = array_unique($x);
            if (count($y) > 1) {
                return json_encode(['isSuccess' => false, 'error' => "bawal lain2 og book number"]);
            }

            if (strtolower($transaction_type) === 'single') {
                if (empty($y))   return json_encode(['isSuccess' => false, 'error' => 'no ors',]);
                $book_id = $y[0];
            } else if (strtolower($transaction_type) === 'multiple') {
                if (empty($y))   return json_encode(['isSuccess' => false, 'error' => 'no ors',]);
                $book_id = $y[0];
            } else {
                // $book_id = $y[0]['book_id'];
                $book_id = $_POST['book'];
                // return json_encode(['isSuccess' => false, 'error' => $y[0]['book_id']]);
            }



            $transaction = Yii::$app->db->beginTransaction();


            try {

                if (!empty($_POST['update_id'])) {
                    $dv = DvAucs::findOne($_POST['update_id']);
                    foreach ($dv->dvAucsEntries as $val) {
                        $val->delete();
                    }
                    foreach ($dv->dvAccountingEntries as $val) {
                        $val->delete();
                    }
                    $x = explode('-', $dv->dv_number);
                    $bok = Books::findone($book_id);

                    $x[0] = $bok->name;
                    $x[1] = date('Y', strtotime($reporting_period));
                    $x[2] = explode('-', $reporting_period)[1];
                    $dv->dv_number = implode('-', $x);
                } else {
                    $dv = new DvAucs();
                    $dv->transaction_begin_time = $transaction_timestamp;
                    $dv->dv_number = $this->getDvNumber($reporting_period, $book_id);
                }
                // $dv->raoud_id = intval($raoud_id);
                $dv->nature_of_transaction_id = $nature_of_transaction_id;
                $dv->mrd_classification_id = $mrd_classification_id;
                $dv->reporting_period = $reporting_period;
                $dv->particular = $particular;
                $dv->payee_id = $payee_id;
                $dv->book_id = $book_id;
                $dv->tracking_sheet_id = $tracking_sheet;
                $dv->transaction_type = $transaction_type;
                if ($dv->validate()) {
                    if ($flag = $dv->save(false)) {
                        if (strtolower($transaction_type) != 'no ors') {

                            foreach ($process_id as $key => $val) {
                                $dv_entries = new DvAucsEntries();
                                // $dv_entries->raoud_id = $val;
                                $dv_entries->process_ors_id = $_POST['process_ors_id'][$key];
                                $dv_entries->dv_aucs_id = $dv->id;
                                $dv_entries->amount_disbursed = $_POST['amount_disbursed'][$key];
                                $dv_entries->vat_nonvat = $_POST['vat_nonvat'][$key];
                                $dv_entries->ewt_goods_services = $_POST['ewt_goods_services'][$key];
                                $dv_entries->compensation = $_POST['compensation'][$key];
                                $dv_entries->other_trust_liabilities = $_POST['other_trust_liabilities'][$key];
                                if ($dv_entries->save(false)) {
                                }
                                // $dv_entries->total_withheld =$_POST['_percent_'];
                                // $dv_entries->tax_withheld =$_POST['_percent_'];

                            }
                        } else {
                            // return  json_encode(["isSuccess"=>false,'error'=>$_POST['amount_disbursed'][0]]);
                            // die();
                            $amount = $_POST['amount_disbursed'][0];
                            $dv_entries = new DvAucsEntries();
                            // $dv_entries->raoud_id = $val;
                            $dv_entries->dv_aucs_id = $dv->id;
                            $dv_entries->amount_disbursed = $amount;
                            $dv_entries->vat_nonvat = $_POST['vat_nonvat'][0];
                            $dv_entries->ewt_goods_services = $_POST['ewt_goods_services'][0];
                            $dv_entries->compensation = $_POST['compensation'][0];
                            $dv_entries->other_trust_liabilities = $_POST['other_trust_liabilities'][0];
                            // return  json_encode(["isSuccess" => false, 'error' => $dv_entries->amount_disbursed]);
                            // die();
                            if ($dv_entries->save(false)) {
                            }
                        }
                        if (!empty($account_entries)) {
                            for ($i = 0; $i < $account_entries; $i++) {

                                // $x = explode('-', $_POST['chart_of_account_id'][$i]);

                                // $chart_id = 0;
                                // if ($x[2] == 2) {
                                //     $chart_id = (new \yii\db\Query())->select(['chart_of_accounts.id'])->from('sub_accounts1')
                                //         ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                                //         ->where('sub_accounts1.id =:id', ['id' => intval($x[0])])->one()['id'];
                                // } else if ($x[2] == 3) {
                                //     // $chart_id = (new \yii\db\Query())->select(['chart_of_accounts.id'])->from('sub_accounts1')
                                //     //     ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                                //     //     ->where('sub_accounts1.id =:id', ['id' => intval($x[0])])->one()['id'];
                                //     $chart_id = SubAccounts2::findOne(intval($x[0]))->subAccounts1->chart_of_account_id;
                                // } else {
                                //     $chart_id = $x[0];
                                // }

                                $dv_accounting_entries = new DvAccountingEntries();
                                $dv_accounting_entries->dv_aucs_id = $dv->id;
                                // $dv_accounting_entries->chart_of_account_id = intval($chart_id);
                                $dv_accounting_entries->debit = !empty($_POST['debit'][$i]) ? $_POST['debit'][$i] : 0;
                                $dv_accounting_entries->credit = !empty($_POST['credit'][$i]) ? $_POST['credit'][$i] : 0;
                                // $dv_accounting_entries->current_noncurrent=$jev_preparation->id;
                                $dv_accounting_entries->cashflow_id =  !empty($_POST['cash_flow_id'][$i]) ? $_POST['cash_flow_id'][$i] : '';
                                $dv_accounting_entries->net_asset_equity_id =  !empty($_POST['isEquity'][$i]) ? $_POST['isEquity'][$i] : '';
                                // $dv_accounting_entries->lvl = $x[2];
                                $dv_accounting_entries->object_code = $_POST['chart_of_account_id'][$i];

                                if (!($flag = $dv_accounting_entries->save(false))) {
                                    //  return json_encode();
                                    $s[] =  $dv_accounting_entries->cash_flow_transaction;
                                    // echo "<pre>";
                                    // var_dump($jv->id);
                                    // echo "</pre>";
                                    $transaction->rollBack();
                                    break;
                                }
                                // } else {
                                //     return json_encode("more than 1 decimals");
                                //     $transaction->rollBack();
                                //     break;
                                // }
                            }
                        }
                    }
                } else {
                    return json_encode(['isSuccess' => false, 'error' => $dv->errors]);
                }

                // if (!empty($advances_fund_source)) {
                $check_advances = Yii::$app->db->createCommand("SELECT id FROM advances WHERE advances.dv_aucs_id = :id")
                    ->bindValue(':id', $dv->id)
                    ->queryOne();
                if (empty($check_advances['id']) && !empty($advances_province)) {

                    $advances_id = $this->insertAdvances($advances_province, $advances_period, $dv->id, $advances_update_id, $advances_bank_account_id);
                } else if (!empty($check_advances)) {
                    $advances_id = $check_advances['id'];
                    $advances_id = $this->insertAdvances($advances_province, $advances_period, $dv->id, $advances_update_id, $advances_bank_account_id);

                    $q = Yii::$app->db->createCommand("SELECT id FROM advances_entries WHERE advances_entries.advances_id = :advances_id")
                        ->bindValue(':advances_id', $advances_id)
                        ->queryAll();
                    $source_fund_source_type_difference = array_map(
                        'unserialize',
                        array_diff(array_map('serialize', array_column($q, 'id')), array_map('serialize', $advances_entries_id))

                    );
                    $params = [];
                    $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'advances_entries.id', $source_fund_source_type_difference], $params);

                    if (!empty($source_fund_source_type_difference)) {
                        $delete_advances_entries = Yii::$app->db->createCommand("UPDATE  advances_entries
                    LEFT JOIN liquidation_entries ON advances_entries.id = liquidation_entries.advances_entries_id
                    SET advances_entries.is_deleted = 1
                   WHERE $sql AND liquidation_entries.id IS NULL", $params)

                            ->execute();
                    }
                }
                if (!empty($advances_id)) {

                    $this->insertAdvancesEntries(
                        $advances_id,
                        $advances_object_code,
                        $advances_amount,
                        $advances_fund_source,
                        $advances_fund_source_type,
                        $advances_report_type,
                        $advances_reporting_period,
                        $advances_entries_id,
                        $dv->book_id
                    );
                }

                // }
                if ($flag) {

                    $transaction->commit();
                    // return $this->redirect(['view', 'id' => $model->id]);
                    return json_encode(['isSuccess' => true, 'id' => $dv->id]);
                }
            } catch (ErrorException $error) {

                $transaction->rollBack();

                return json_encode($error->getMessage());
            }
        }
    }

    public function getDvNumber($reporting_period, $book_id)
    {
        $year = date('Y', strtotime($reporting_period));
        // $book_id=5;
        $latest_dv = Yii::$app->db->createCommand("SELECT CAST(substring_index(dv_number, '-', -1)AS UNSIGNED) as q 
        from dv_aucs
        WHERE reporting_period LIKE :_year
        ORDER BY q DESC  LIMIT 1")
            ->bindValue(':_year', $year . '%')
            ->queryScalar();
        !empty($book_id) ? $book_id : $book_id = 5;
        // $latest_dv = (new \yii\db\Query())
        //     ->select('dv_number')
        //     ->from('dv_aucs')
        //     ->orderBy('id DESC')
        //     ->one();
        $book = Books::findOne($book_id);
        $dv_number = $book->name . '-' . $reporting_period;

        if (!empty($latest_dv)) {
            // $last_number = explode('-', $latest_dv['dv_number'])[3] + 1;
            $last_number = (int) $latest_dv + 1;
        } else {
            $last_number = 1;
        }
        $x = '';
        for ($i = strlen($last_number); $i < 4; $i++) {
            $x .= 0;
        }
        $dv_number .= '-' . $x . $last_number;

        // echo "<pre>";
        // var_dump(explode('-',$latest_dv['dv_number']));
        // echo "</pre>";
        return $dv_number;
    }




    public function actionUpdateDv()
    {
        if (!empty($_POST)) {
            $dv_id = $_POST['dv_id'];


            $query = (new \yii\db\Query())
                ->select([
                    "dv_aucs_entries.*",
                    "dv_aucs.reporting_period",
                    "dv_aucs.particular",
                    "dv_aucs.payee_id",
                    "dv_aucs.mrd_classification_id",
                    "dv_aucs.nature_of_transaction_id",
                    "dv_aucs.reporting_period",
                    "dv_aucs.transaction_type",
                    "dv_aucs.book_id",
                    "dv_aucs.tracking_sheet_id",
                    'tracking_sheet.tracking_number',
                    "process_ors.serial_number",
                    "process_ors.id as ors_id",
                    "total_obligated.total",
                    "transaction.particular as transaction_particular",
                    "payee.account_name as transaction_payee"
                ])
                ->from("dv_aucs_entries")
                ->join("LEFT JOIN", "dv_aucs", "dv_aucs_entries.dv_aucs_id = dv_aucs.id")
                ->join("LEFT JOIN", "raouds", "dv_aucs_entries.raoud_id = raouds.id")
                ->join("LEFT JOIN", "process_ors", "dv_aucs_entries.process_ors_id = process_ors.id")
                ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")
                ->join("LEFT JOIN", "payee", "dv_aucs.payee_id = payee.id")
                ->join("LEFT JOIN", "tracking_sheet", "dv_aucs.tracking_sheet_id = tracking_sheet.id")
                ->join("LEFT JOIN", "(SELECT SUM(process_ors_entries.amount)as total,process_ors.id as ors_id
                FROM process_ors
                LEFT JOIN process_ors_entries ON process_ors.id = process_ors_entries.process_ors_id
                GROUP BY process_ors.id) as total_obligated", "process_ors.id=total_obligated.ors_id")

                ->where("dv_aucs_entries.dv_aucs_id =:dv_aucs_id", ["dv_aucs_id" => $dv_id])
                ->all();
            if (empty($query)) {
                $query = (new \yii\db\Query())
                    ->select([
                        "dv_aucs.reporting_period",
                        "dv_aucs.particular",
                        "dv_aucs.payee_id",
                        "dv_aucs.mrd_classification_id",
                        "dv_aucs.nature_of_transaction_id",
                        "dv_aucs.reporting_period",
                        "dv_aucs.transaction_type",
                        "dv_aucs.book_id",
                        "dv_aucs_entries.*",
                    ])
                    ->from('dv_aucs')
                    ->join("LEFT JOIN", 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
                    ->where("dv_aucs.id =:id", ['id' => $dv_id])
                    ->all();
            }

            $model = DvAucs::findOne($dv_id);
            $dv_accounting_entries = [];
            if (!empty($model->dvAccountingEntries)) {
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
                // foreach ($model->dvAccountingEntries as $val) {

                //     if ($val->lvl === 2) {
                //         $chart_id = (new \yii\db\Query())->select(['sub_accounts1.id'])->from('sub_accounts1')
                //             ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                //             ->where('sub_accounts1.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
                //     } else if ($val->lvl === 3) {
                //         $chart_id = (new \yii\db\Query())->select(['sub_accounts2.id'])->from('sub_accounts2')
                //             // ->join("LEFT JOIN", 'sub_accounst1', 'sub_accounts2.sub_accounts1_id = sub_accounts1.id')
                //             // ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                //             ->where('sub_accounts2.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
                //     } else {
                //         $chart_id =  $val->chart_of_account_id;
                //     }
                //     $dv_accounting_entries[] = [
                //         'dv_aucs_id' => $val->dv_aucs_id,
                //         'chart_of_account_id' => $val->chart_of_account_id,
                //         'id' => $chart_id,
                //         'debit' => $val->debit,
                //         'credit' => $val->credit,
                //         'net_asset_equity_id' => $val->net_asset_equity_id,
                //         'object_code' => $val->object_code,
                //         'lvl' => $val->lvl,
                //         'cashflow_id' => $val->cashflow_id,
                //     ];
                // }
            }

            return json_encode(["result" => $query, 'dv_accounting_entries' => $dv_accounting_entries]);
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
            $excel->setActiveSheetIndexByName('Import DV AUCS');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

            // $latest_tracking_no = (new \yii\db\Query())
            //     ->select('tracking_number')
            //     ->from('transaction')
            //     ->orderBy('id DESC')->one();
            // if ($latest_tracking_no) {
            //     $x = explode('-', $latest_tracking_no['tracking_number']);
            //     $last_number = $x[4] + 1;
            // } else {
            //     $last_number = 1;
            // }
            // 
            $transaction = Yii::$app->db->beginTransaction();
            $flag = "";
            foreach ($worksheet->getRowIterator(3) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if (
                        $y === 7
                        || $y === 8
                        // || $y === 9
                        || $y === 10
                        // || $y === 11

                    ) {
                        $cells[] = $cell->getCalculatedValue();
                    } else if ($y === 4 || $y === 5) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                $dv_number = trim($cells[0]);
                $reporting_period = date("Y-m", strtotime($cells[1]));
                $ors_number = $cells[2];
                $payee_name = strtoupper(trim($cells[4]));
                $particular = $cells[5];
                $dv_amount = $cells[6];
                $vat_nonvat_amount = $cells[7];
                $ewt_goods_services_amount = $cells[8];
                $compensation_amount = $cells[9];
                $trust_liab_amount = $cells[10];
                $nature_of_transaction_name = trim($cells[12]);
                $mrd_classification_name = trim($cells[13]);



                if (
                    // !empty($payee_name)
                    // || !empty($dv_number)
                    // || !empty($reporting_period)
                    // || !empty($ors_number)
                    // || !empty($particular)
                    // || !empty($dv_amount)
                    // || !empty($vat_nonvat_amount)
                    // || !empty($ewt_goods_services_amount)
                    // || !empty($compensation_amount)
                    // || !empty($trust_liab_amount)
                    // || !empty($nature_of_transaction_name)
                    // || !empty($mrd_classification_name)
                    $key < 607

                ) {
                    //     return json_encode(['isSuccess' => false, 'error' => "Error Somthing is Missing in Line $key"]);
                    //     die();
                    // } else {
                    // $ors= (new \yii\db\Query())
                    // ->select("")
                    // ->from()

                    $ors = (new \yii\db\Query())
                        ->select("*")
                        ->from("process_ors")
                        ->where("serial_number =:serial_number", ["serial_number" => $ors_number])
                        ->one();
                    $nature_of_transaction = (new \yii\db\Query())
                        ->select(["name", 'id'])
                        ->from("nature_of_transaction")
                        ->where("name =:name", ["name" => $nature_of_transaction_name])
                        ->one();
                    $mrd_classification = (new \yii\db\Query)
                        ->select(["name", 'id'])
                        ->from("mrd_classification")
                        ->where("name =:name", ["name" => $mrd_classification_name])
                        ->one();

                    $payee  = (new \yii\db\Query())
                        ->select(['account_name', 'id'])
                        ->from('payee')
                        ->where('account_name LIKE :account_name', ['account_name' => "%$payee_name%"])
                        ->one();
                    if (empty($payee)) {
                        return json_encode(["error" => "$key $payee_name payee"]);
                    }
                    if (empty($nature_of_transaction)) {
                        return json_encode(["error" => "$key $nature_of_transaction_name nature of transaction"]);
                    }
                    // $data[] = [
                    //     "nature" => $nature_of_transaction['id'],
                    //     "mrd" => $mrd_classification['id'],
                    //     "reporting" => $reporting_period,
                    //     "particular" => $particular,
                    //     "payee" => $payee['id'],
                    //     "dv_amount" => $dv_amount,
                    //     "vat"  => $vat_nonvat_amount,
                    //     "ewt" => $ewt_goods_services_amount,
                    //     'conpensation'  => $compensation_amount,
                    //     'trust' => $trust_liab_amount,
                    //     'ors'=>!empty($ors)?$ors['id']:''


                    // ];
                    try {


                        $dv = new DvAucs();
                        $dv->dv_number = $dv_number;
                        // $dv->raoud_id = intval($raoud_id);
                        $dv->nature_of_transaction_id = $nature_of_transaction['id'];
                        $dv->mrd_classification_id = $mrd_classification['id'];
                        $dv->reporting_period = $reporting_period;
                        $dv->particular = $particular;
                        $dv->payee_id = $payee['id'];

                        if ($dv->validate()) {
                            if ($flag = $dv->save(false)) {
                                // foreach ($_POST['raoud_id'] as $key => $val) {
                                $dv_entries = new DvAucsEntries();
                                $dv_entries->process_ors_id = !empty($ors) ? $ors['id'] : '';
                                $dv_entries->dv_aucs_id = $dv->id;
                                $dv_entries->amount_disbursed = $dv_amount;
                                $dv_entries->vat_nonvat = $vat_nonvat_amount;
                                $dv_entries->ewt_goods_services = $ewt_goods_services_amount;
                                $dv_entries->compensation = $compensation_amount;
                                $dv_entries->other_trust_liabilities = $trust_liab_amount;
                                if ($dv_entries->validate()) {

                                    if ($dv_entries->save(false)) {
                                    }
                                } else {
                                    return json_encode(['error' => $dv_entries->errors]);
                                }
                                // $dv_entries->total_withheld =$_POST['_percent_'];
                                // $dv_entries->tax_withheld =$_POST['_percent_'];

                                // }
                            }
                        } else {

                            return json_encode(['error' => "error sa flag"]);
                        }
                    } catch (ErrorException $error) {

                        $transaction->rollBack();

                        return json_encode(["error" => "yawa"]);
                    }
                    // $last_number++;
                }
            }
            if ($flag) {

                $transaction->commit();
                // return $this->redirect(['view', 'id' => $model->id]);
                return json_encode(['isSuccess' => 'success',]);
            }

            // $column = [
            //     'responsibility_center_id',
            //     'payee_id',
            //     'particular',
            //     'gross_amount',
            //     'tracking_number',
            //     // 'earmark_no',
            //     'payroll_number',
            //     // 'transaction_date',
            // ];
            // $ja = Yii::$app->db->createCommand()->batchInsert('transaction', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($data);
            echo "<pre>";
            return ob_get_clean();
        }
    }
    public function actionCancel()
    {

        if ($_POST) {
            $id = $_POST['id'];

            $model = DvAucs::findOne($id);
            // if (!empty($model->cashDisbursement->id)) {

            //     if ($model->cashDisbursement->is_cancelled === 0) {
            //         return json_encode(['isSuccess' => false, 'cancelled' => 'Disbursement is Not Cancelled']);
            //         die();
            //     }
            // }
            $check_if_has_cash = Yii::$app->db->createCommand("SELECT EXISTS(SELECT 
            *
            FROM
            cash_disbursement 
            WHERE dv_aucs_id = :dv_id) ")->bindValue(':dv_id', $model->id)
                ->queryScalar();



            if (!empty($model->dvAucsEntries)) {
                foreach ($model->dvAucsEntries as $val) {
                    if ($val->processOrs->is_cancelled === 1) {
                        return json_encode(['isSuccess' => false, 'cancelled' =>  "{$val->processOrs->serial_number} is not Activated"]);
                    }
                }
            }
            $model->is_cancelled ? $model->is_cancelled = false : $model->is_cancelled = true;
            if ($model->is_cancelled === true) {

                if (intval($check_if_has_cash) === 1) {
                    $q = Yii::$app->db->createCommand("SELECT EXISTS(SELECT 
                        *
                        FROM
                        cash_disbursement 
                        WHERE dv_aucs_id = :dv_id
                        AND is_cancelled =1) ")
                        ->bindValue(':dv_id', $model->id)
                        ->queryScalar();
                    if (intval($q) === 0) {
                        return json_encode(['isSuccess' => false, 'cancelled' => 'Cash Disbursment is not Cancelled. Please Cancel The Check  In Cancel Disbursment Module']);
                        die();
                    }
                }
            }
            if ($model->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => $model->is_cancelled]);
            } else {
                return json_encode(['isSuccess' => false, 'cancelled' => 'save failed']);
            }

            // ob_clean();  
            // echo "<pre>";
            // var_dump($model->cashDisbursement);
            // echo "</pre>";
            // return ob_get_clean();
            // return json_encode($model);
        }
        // return json_encode('qwer');
    }
    public function actionDvForm($id)
    {
        return $this->render('dv_form', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionAddLink()
    {
        if ($_POST) {
            $link = $_POST['link'];
            $id = $_POST['id'];
            $dv  = DvAucs::findOne($id);

            $dv->dv_link = $link;
            if ($dv->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => 'save success']);
            }
            return json_encode(['isSuccess' => true, 'cancelled' => $link]);
        }
    }
    public function actionTurnarroundTime()
    {
        $searchModel = new DvAucsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        return $this->render('turnarround_time', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionReturn($id)
    {
        if ($_POST) {

            date_default_timezone_set('Asia/Manila');
            $time =  $_POST['time'];

            $timestamp  = date('Y-m-d H:i:s', strtotime($_POST['time'] . ' ' . $_POST['date']));

            $model = $this->findModel($id);
            // if (!empty($model->out_timestamp)) {
            //     return $model->out_timestamp;
            // }
            $model->return_timestamp = $timestamp;
            if ($model->save(false)) {
                return json_encode(['success' => true]);
            }
        }
    }
    public function actionIn($id)
    {
        if ($_POST) {
            date_default_timezone_set('Asia/Manila');
            $time =  $_POST['time'];
            $timestamp  = date('Y-m-d H:i:s', strtotime($_POST['time'] . ' ' . $_POST['date']));
            $model = $this->findModel($id);

            // if (empty($model->return_timestamp)) {
            //     return json_encode(['success' => false, 'error' => 'DV is not returned']);
            // }
            $model->in_timestamp = $timestamp;
            if ($model->save(false)) {
                return json_encode(['success' => true]);
            }
        }
    }
    public function actionOut($id)
    {
        if ($_POST) {

            date_default_timezone_set('Asia/Manila');
            $time =  $_POST['time'];
            $timestamp  = date('Y-m-d H:i:s', strtotime($_POST['time'] . ' ' . $_POST['date']));
            $model = $this->findModel($id);
            if (!empty($model->return_timestamp)) {
                if (empty($model->accept_timestamp)) {
                    return json_encode(['success' => false, 'error' => 'Cannot Out Dv is not Accepted Yet']);
                }
            }
            $model->out_timestamp = $timestamp;
            if ($model->save(false)) {
                return json_encode(['success' => true]);
            }
        }
    }
    public function actionTurnarroundView($id)
    {
        return $this->render('turnarround_time_view', [
            'model' => $this->findModel($id)
        ]);
    }
    public function actionIsPayable()
    {

        if ($_POST) {
            $id = $_POST['id'];
            $dv = DvAucs::findOne($id);

            if ($dv->is_payable === 1) {
                $dv->is_payable = 0;
            } else {
                $dv->is_payable = 1;
            }

            if ($dv->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => $dv->is_cancelled]);
            } else {
                return json_encode(['isSuccess' => false, 'cancelled' => 'save failed']);
            }
        }
    }
    public function insertDvAccountingEntriesFromPayroll($dv_id, $payroll_id)
    {
        $query = Yii::$app->db->createCommand("UPDATE dv_accounting_entries
        SET dv_aucs_id =:dv_id
        WHERE dv_accounting_entries.payroll_id = :payroll_id
        ")
            ->bindValue(':dv_id', $dv_id)
            ->bindValue(':payroll_id', $payroll_id)
            ->query();
        // foreach ($query as $val) {

        //     $dv = new DvAccountingEntries();
        //     $dv->dv_aucs_id = $dv_id;
        //     $dv->object_code = $val['object_code'];
        //     $dv->debit = !empty($val['debit']) ? $val['debit'] : 0;
        //     $dv->credit = !empty($val['credit']) ? $val['credit'] : 0;
        //     if ($dv->save(false)) {
        //     } else {
        //         return false;
        //     }
        // }
    }
    public function actionCreateTracking()
    {

        $searchModel = new ProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($_POST) {
            if (empty($_POST['book_id'])) {
                return json_encode(['isSuccess' => false, 'error' => 'Book is Required']);
            }
            if (empty($_POST['reporting_period'])) {
                return json_encode(['isSuccess' => false, 'error' => 'Reporting Period is Required']);
            }
            $recieved_at = $_POST['date_receive'];
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $particular = $_POST['particular'];
            $payee_id = $_POST['payee'];
            $transaction_type = $_POST['transaction_type'];
            $amount_disbursed = $_POST['amount_disbursed'];
            $vat = $_POST['vat_nonvat'];
            $ewt_goods_services = $_POST['ewt_goods_services'];
            $compensation = $_POST['compensation'];
            $liabilities = $_POST['other_trust_liabilities'];
            $ors = $_POST['process_ors_id'];
            $payroll_id = !empty($_POST['payroll_id']) ? $_POST['payroll_id'] : null;
            $remittance_id = !empty($_POST['remittance_id']) ? $_POST['remittance_id'] : null;

            $transaction = Yii::$app->db->beginTransaction();
            $q = DateTime::createFromFormat('Y-m-d', $recieved_at);
            // return $recieved_at;
            // return date('Y-m-d H:i:s',strtotime($recieved_at));
            // if ($transaction_type === 'Single' && !empty($recieved_at)) {
            //     $min_key = min(array_keys($ors));

            //     $ors_created_at = Yii::$app->db->createCommand("SELECT 
            //     process_ors.created_at
            //  FROM  process_ors
            //     WHERE process_ors.id= :id
            //     LIMIT 1")
            //         ->bindValue(':id', $ors[$min_key])
            //         ->queryScalar();
            //     if (strtotime($recieved_at) > strtotime($ors_created_at)) {
            //         return json_encode(['isSuccess' => false, 'error' => 'Receive Date must be Greater than  ORS date']);
            //     }
            // }
            if ($transaction_type === 'Single') {

                $book_id = Yii::$app->db->createCommand("SELECT book_id FROM process_ors WHERE id = :id")->bindValue(':id', $ors[min(array_keys($ors))])->queryScalar();
                // return json_encode($book_id);
            }

            try {
                if ($flag = true) {


                    $model = new DvAucs();
                    $model->dv_number = $this->getDvNumber($reporting_period, $book_id);
                    $model->reporting_period = $reporting_period;
                    $model->book_id  = $book_id;
                    $model->recieved_at  = date('Y-m-d H:i:s', strtotime($recieved_at));
                    $model->payee_id = $payee_id;
                    $model->particular =  $particular;
                    $model->transaction_type = $transaction_type;
                    $model->payroll_id = $payroll_id;
                    $model->fk_remittance_id = $remittance_id;
                    $check  = Yii::$app->db->createCommand("SELECT EXISTS(SELECT * FROM dv_aucs WHERE payroll_id = :payroll_id)")
                        ->bindValue(':payroll_id', $model->payroll_id)
                        ->queryScalar();
                    if (intval($check) == 1) {
                        return json_encode(['isSuccess' => false, 'error' => 'Naa nay Tracking Sheet ']);
                    }
                    if ($model->save(false)) {
                        if (strtolower($model->transaction_type) == 'payroll') {
                            if (empty($model->payroll_id)) {
                                return json_encode(['isSuccess' => false, 'error' => 'Payroll Number is Required ']);
                            } else if (!empty($model->payroll_id)) {

                                $this->insertDvAccountingEntriesFromPayroll($model->id, $model->payroll_id);
                            }
                        }

                        if (strtolower($model->transaction_type) == 'remittance') {

                            if (empty($model->fk_remittance_id)) {
                                return json_encode(['isSuccess' => false, 'error' => 'Remittance Number is Required ']);
                            }
                        }


                        $flag =  $this->insertDvEntries(
                            $model->id,
                            $ors,
                            $amount_disbursed,
                            $vat,
                            $ewt_goods_services,
                            $compensation,
                            $liabilities
                        );
                    } else {
                        $flag = false;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    if (strtolower($model->transaction_type) == 'remittance') {

                        Yii::$app->db->createCommand("INSERT INTO dv_accounting_entries (dv_aucs_id,object_code,debit,credit)
                             
                        SELECT 
                        :dv_id as dv_id,
                        dv_accounting_entries.object_code,
                        remittance_items.amount as debit,
                        0 as credit
                        FROM remittance_items
                        LEFT JOIN dv_accounting_entries ON remittance_items.fk_dv_acounting_entries_id = dv_accounting_entries.id
                        LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
                        WHERE remittance_items.is_removed = 0 AND remittance_items.fk_remittance_id = :remittance_id")
                            ->bindValue(':dv_id', $model->id)
                            ->bindValue(':remittance_id', $model->fk_remittance_id)
                            ->query();
                    }


                    return $this->redirect(['tracking-view', 'id' => $model->id]);
                } else {
                    return json_encode("Error");
                }
            } catch (ErrorException $e) {
                $transaction->rollback();
                return json_encode($e->getMessage());
            }
        }
        return $this->render('_tracking_form', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
        ]);
    }
    public function actionTrackingView($id)
    {
        return $this->render('tracking_view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function insertDvEntries(
        $model_id,
        $ors,
        $amount_disbursed,
        $vat,
        $ewt_goods_services,
        $compensation,
        $liabilities
    ) {
        foreach ($ors as $i => $val) {
            $entry = new DvAucsEntries();
            $entry->dv_aucs_id = $model_id;
            $entry->process_ors_id  = $val;
            $entry->amount_disbursed = $amount_disbursed[$i];
            $entry->vat_nonvat = $vat[$i];
            $entry->ewt_goods_services = $ewt_goods_services[$i];
            $entry->compensation = $compensation[$i];
            $entry->other_trust_liabilities = $liabilities[$i];
            if ($entry->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function actionTrackingUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new ProcessOrsSearch();
        $oldModel = $model;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // echo $id;
        if ($_POST) {
            if (empty($_POST['book_id'])) {
                return json_encode(['isSuccess' => false, 'error' => 'Book is Required']);
            }
            if (empty($_POST['reporting_period'])) {
                return json_encode(['isSuccess' => false, 'error' => 'Reporting Period is Required']);
            }


            $recieved_at = !empty($_POST['date_receive']) ? $_POST['date_receive'] : null;
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $particular = $_POST['particular'];
            $payee_id = $_POST['payee'];
            $transaction_type = $_POST['transaction_type'];
            $amount_disbursed = $_POST['amount_disbursed'];
            $vat = $_POST['vat_nonvat'];
            $ewt_goods_services = $_POST['ewt_goods_services'];
            $compensation = $_POST['compensation'];
            $liabilities = $_POST['other_trust_liabilities'];
            $ors = $_POST['process_ors_id'];
            $payroll_id = !empty($_POST['payroll_id']) ? $_POST['payroll_id'] : null;
            $transaction = Yii::$app->db->beginTransaction();
            if ($oldModel->book_id != $book_id) {
                $book_name = Yii::$app->db->createCommand("SELECT `name` FROM books WHERE id = :id")->bindValue(':id', $book_id)->queryScalar();
                $dv_number = explode('-', $model->dv_number);
                $dv_number[0] = $book_name;
                $new_dv = implode('-', $dv_number);
                $model->dv_number = $new_dv;
            }
            if ($transaction_type === 'Single') {

                $book_id = Yii::$app->db->createCommand("SELECT book_id FROM process_ors WHERE id = :id")->bindValue(':id', $ors[min(array_keys($ors))])->queryScalar();
                // return json_encode($book_id);
            }
            if ($transaction_type === 'Single' && !empty($recieved_at)) {
                $min_key = min(array_keys($ors));

                $ors_created_at = Yii::$app->db->createCommand("SELECT 
                process_ors.created_at
                 FROM  process_ors
                WHERE process_ors.id= :id
                LIMIT 1")
                    ->bindValue(':id', $ors[$min_key])
                    ->queryScalar();
                // if (strtotime($recieved_at) < strtotime($ors_created_at)) {
                //     return json_encode(['isSuccess' => false, 'error' => 'Receive Date must be Greater than  ORS date']);
                // }
            }

            Yii::$app->db->createCommand("DELETE FROM dv_aucs_entries WHERE dv_aucs_entries.dv_aucs_id =:id")
                ->bindValue(':id', $model->id)
                ->query();
            // return json_encode($ors);
            try {
                if ($flag = true) {

                    $model->reporting_period = $reporting_period;
                    $model->book_id  = $book_id;
                    $model->payee_id = $payee_id;
                    $model->particular =  $particular;
                    $model->transaction_type = $transaction_type;
                    $model->recieved_at  = date('Y-m-d H:i:s', strtotime($recieved_at));
                    $model->payroll_id = $payroll_id;

                    if ($model->save(false)) {
                        if (strtolower($model->transaction_type) == 'payroll') {
                            if (empty($model->payroll_id)) {
                                return json_encode('Payroll number is required');
                            } else if (!empty($model->payroll_id)) {
                                // Yii::$app->db->createCommand("DELETE FROM dv_accounting_entries WHERE dv_aucs_id = :dv_id")
                                //     ->bindValue(':dv_id', $model->id)
                                //     ->query();
                                $this->insertDvAccountingEntriesFromPayroll($model->id, $model->payroll_id);
                            }
                        }
                        $flag =  $this->insertDvEntries(
                            $model->id,
                            $ors,
                            $amount_disbursed,
                            $vat,
                            $ewt_goods_services,
                            $compensation,
                            $liabilities
                        );
                    } else {
                        $flag = false;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['tracking-view', 'id' => $model->id]);
                } else {
                    return json_encode("Error");
                }
            } catch (ErrorException $e) {
                $transaction->rollback();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('_tracking_form', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'model' => $model
        ]);
    }
    public function actionTrackingIndex()
    {
        $searchModel = new TrackingSheetIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('tracking_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionGetObjectCode()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $query = Yii::$app->db->createCommand("SELECT 
            object_code,
            `name` as account_title FROM 
            sub_accounts1 WHERE reporting_period = :reporting_period
            AND object_code LIKE '2020101000%'
            ")
                ->bindValue(':reporting_period', $reporting_period)
                ->queryOne();
            return json_encode($query);
        }
    }

    public function actionCreateNew()
    {
        $model = new DvAucs();

        if ($_POST) {

            if ($model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        $searchModel = new ProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_form2', [

            'model' => $model
        ]);
    }
    public function actionFile()
    {

        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            // $q = $_FILES['file'];
            if (isset($_FILES['file']) && isset($_POST['id'])) {
                $dv = DvAucs::findOne($_POST['id']);
                $dv_number =  "\scanned-dv\\" . $dv->dv_number;
                $file = $_FILES;
                $file = \yii\web\UploadedFile::getInstanceByName('file');
                $model->file = $file;
                $path =  Yii::$app->basePath . $dv_number;
                FileHelper::createDirectory($path);
                if ($model->validate()) {
                    if ($file_name = $model->upload($path, $dv->dv_number)) {
                        $dv_file = new DvAucsFile();
                        $dv_file->fk_dv_aucs_id = $dv->id;
                        $dv_file->file_name  = $file_name;
                        if ($dv_file->save(false)) {
                            return json_encode(['isSuccess' => true]);
                        }
                    } else {
                        return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                    }
                } else {
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
                // if ($model->upload()) {
                //     // file is uploaded successfully
                //     return 'succes';
                // }
            }
        }
        // return $this->render('file_upload', ['model' => $model]);
    }
}
