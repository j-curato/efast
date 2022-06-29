<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\CashFlow;
use app\models\ChartOfAccounts;
use app\models\DvAucs;
use app\models\FundClusterCode;
use app\models\JevAccountingEntries;
use Yii;
use app\models\JevPreparation;
use app\models\JevPreparationSearch;
use app\models\MajorAccounts;
use app\models\NetAssetEquity;
use app\models\Payee;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use app\models\SubMajorAccounts;
use app\models\SubMajorAccounts2;
use aryelds\sweetalert\SweetAlert;
use DateTime;
use ErrorException;
use Exception;
use frontend\models\Model;
use InvalidArgumentException;
use phpDocumentor\Reflection\Types\Nullable;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * JevPreparationController implements the CRUD actions for JevPreparation model.
 */
class JevPreparationController extends Controller
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
                    'view',
                    'delete',
                    'update',
                    'create',
                    'general-ledger',
                    'import',
                    'general-journal',
                    'adadj-filter',
                    'adadj',
                    'ckdj',
                    'trial-alance',
                    'insert-jev',
                    'IsCurrent',
                    'detailed-financial-position',
                    'consolidated-financial-position',
                    'get-subsidiary-ledger',
                    'detailed-financial-performance',
                    'consolidated-financial-erformance',
                    // 'update-jev',
                    'detailed-cashflow',
                    'consolidated-cashflow',
                    'changes-netasset-equity',
                    'export-jev',
                    'cdr-jev',
                    'dv-to-jev'

                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'delete',
                            'update',
                            'create',
                            'general-ledger',
                            'import',
                            'general-journal',
                            'adadj-filter',
                            'adadj',
                            'ckdj',
                            'trial-alance',
                            'insert-jev',
                            'IsCurrent',
                            'detailed-financial-position',
                            'consolidated-financial-position',
                            'get-subsidiary-ledger',
                            'detailed-financial-performance',
                            'consolidated-financial-erformance',
                            // 'update-jev',
                            'detailed-cashflow',
                            'consolidated-cashflow',
                            'changes-netasset-equity',
                            'export-jev',
                            'cdr-jev',
                            'dv-to-jev'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // [
                    //     'actions' => ['create'],
                    //     'allow' => true,
                    //     'roles' => ['accounting'],
                    // ],


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
    public function beforeAction($action)
    {
        if ($action->id == 'ledger') {
            $this->enableCsrfValidation = false;
        } else if ($action->id == 'update-jev') {
            $this->enableCsrfValidation = false;
        } else if ($action->id == 'is-current') {
            $this->enableCsrfValidation = false;
        }


        return parent::beforeAction($action);
    }

    /**
     * Lists all JevPreparation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JevPreparationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JevPreparation model.
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

    public function actionGeneralLedger()
    {

        if (!empty($_POST)) {

            $gen = $_POST['gen'];
            $book_id = $_POST['book_id'];
            $reporting_period = $_POST['reporting_period'];
            $x = explode('-', $reporting_period);
            $qwe = (new \yii\db\Query())
                ->select('uacs')
                ->from('chart_of_accounts')
                ->where('id =:id', ['id' => $gen])
                ->one();
            // GET THE BEGINNING BALANCE OF THE LAST YEAR OF INPUTED REPORTING PERIOD
            if ($reporting_period > 0) {
                $q = $x[0] - 1;

                $begin_balance = JevPreparation::find()
                    ->select('jev_preparation.reporting_period')
                    ->where("jev_preparation.reporting_period LIKE :reporting_period", [
                        'reporting_period' => "$q%"
                    ])->orderBy('date DESC')->one()->reporting_period;
            }
            // echo '<pre>';
            // var_dump($begin_balance);
            // echo '</pre>';

            $begin_month = $x[0] . '-01';
            $general_ledger = (new \yii\db\Query());
            $general_ledger->select([
                'jev_preparation.reporting_period',
                'jev_preparation.explaination',
                'accounting_codes.coa_object_code as uacs',
                'accounting_codes.coa_account_title as general_ledger',
                'jev_preparation.ref_number',
                'jev_preparation.jev_number',
                'jev_accounting_entries.credit',
                'jev_accounting_entries.debit',
                'accounting_codes.normal_balance',
                'jev_preparation.date'
            ])
                ->from('jev_accounting_entries')
                ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id=jev_preparation.id')
                ->join('LEFT JOIN', 'accounting_codes', 'jev_accounting_entries.object_code=accounting_codes.object_code');
            if (!empty($reporting_period)) {


                // KUHAAON ANG MGA DATA BETWEEN 
                $general_ledger->andwhere(['between', 'jev_preparation.reporting_period', $begin_month, $reporting_period]);
            }
            if (!empty($gen)) {

                $general_ledger->andWhere("accounting_codes.coa_object_code = :object_code", [
                    'object_code' => $qwe['uacs']
                ]);
            }
            if (!empty($book_id)) {
                $general_ledger->andWhere("jev_preparation.book_id = :book_id", [
                    'book_id' => $book_id
                ]);
            }
            // $general_ledger->orderBy('jev_preparation.reporting_period');
            // $chart = $general_ledger->orderBy('jev_accounting_entries.chart_of_account_id')
            //     ->orderBy('jev_preparation.date')
            //     ->all();
            $general_ledger->orderBy('jev_accounting_entries.chart_of_account_id')
                ->orderBy('jev_preparation.date');

            // QUERY  FOR BALNCE LAST YEAR
            $prev_begin_month = '';
            $prev_end_month = $x[0] - 1 . '-12';
            if ($x[0] == 2021) {
                $prev_begin_month = '2019-12';
            } else {
                $prev_begin_month = $x[0] - 1 . '-01';
            }
            $query1 = (new \yii\db\Query());
            $query1->select([
                // 'jev_preparation.reporting_period',
                //  'jev_preparation.explaination',
                // 'chart_of_accounts.uacs',
                //  'chart_of_accounts.general_ledger',
                //   'jev_preparation.ref_number',
                // 'jev_preparation.jev_number',
                // ' SUM(jev_accounting_entries.credit) as credit',
                //  'SUM(jev_accounting_entries.debit) as debit',
                // 'chart_of_accounts.normal_balance',
                //  'jev_preparation.date'

                "IFNULL(NULL,'$prev_end_month') as reporting_period",
                "IFNULL(NULL,'Beginning Balance') as explaination",
                "accounting_codes.coa_object_code as uacs",
                "IFNULL(NULL,'') as general_ledger",
                "IFNULL(NULL,'') as ref_number",
                "IFNULL(NULL,'') as jev_number",
                ' SUM(jev_accounting_entries.credit) as credit',
                'SUM(jev_accounting_entries.debit) as debit',
                "IFNULL(NULL,'') as date",
            ])
                ->from('jev_accounting_entries')
                ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id=jev_preparation.id')
                ->join('LEFT JOIN', 'accounting_codes', 'jev_accounting_entries.object_code=accounting_codes.object_code');
            if (!empty($reporting_period)) {

                // KUHAAON ANG MGA DATA BETWEEN 
                $query1->andwhere(['between', 'jev_preparation.reporting_period', $prev_begin_month, $prev_end_month]);
            }
            if (!empty($gen)) {
                $query1->andWhere("accounting_codes.coa_object_code = :object_code", [
                    'object_code' => $qwe['uacs']
                ]);
            }
            if (!empty($fund)) {
                $query1->andWhere("jev_preparation.book_id = :book_id", [
                    'book_id' => $book_id
                ]);
            }
            // $query1->orderBy('jev_preparation.reporting_period');
            $query1
                ->groupBy('accounting_codes.coa_object_code')
                // ->orderBy('jev_preparation.reporting_period DESC')

                // ->orderBy('jev_accounting_entries.chart_of_account_id')
            ;
            $q = (new \yii\db\Query())
                ->select([
                    "q.reporting_period",
                    "q.explaination",
                    "q.uacs",
                    "q.general_ledger",
                    "q.ref_number",
                    "q.jev_number",
                    'q.credit',
                    'q.debit',
                    "chart_of_accounts.normal_balance",
                    "q.date",
                ])
                ->from('chart_of_accounts');
            $qwe = $q->join('INNER JOIN', "({$query1->createCommand()->getRawSql()}) as q", 'chart_of_accounts.uacs = q.uacs');
            // return json_encode($qwe->all());
            // E UNION AND DUHA KA RESULT SA QUERY SA  
            $chart = $qwe->union($general_ledger, true)->all();

            $balance_per_uacs = [];
            $final_ledger = [];

            // MANIPULATE  THE DATA THEN SAVE TO A TEMPORARY ARRAY WITH ITS TOTAL BALANCE
            ArrayHelper::multisort($chart, ['reporting_period'], [SORT_ASC]);
            foreach ($chart as $key => $val) {
                $x = array_key_exists($val['uacs'], $balance_per_uacs);

                if ($x === false) {

                    if ($val['normal_balance'] == 'Credit') {
                        $balance_per_uacs[$val['uacs']] = $val['credit'] - $val['debit'];
                    } else {
                        $balance_per_uacs[$val['uacs']] =  $val['debit'] - $val['credit'];
                    }
                } else {
                    if ($val['normal_balance'] == 'Credit') {
                        $balance_per_uacs[$val['uacs']] = $balance_per_uacs[$val['uacs']] + $val['credit'] - $val['debit'];
                    } else {
                        $balance_per_uacs[$val['uacs']] = $balance_per_uacs[$val['uacs']] + $val['debit'] - $val['credit'];
                    }
                }

                $credit = $val['credit'] ? number_format($val['credit'], 2) : '';
                $debit = $val['debit'] ? number_format($val['debit'], 2) : '';
                if ($key > 0 && $chart[$key - 1]['reporting_period'] == $val['reporting_period']) {
                    $reporting_period = '';
                } else {
                    $reporting_period = date('F Y', strtotime($val['reporting_period']));
                }
                $final_ledger[] = [
                    'reporting_period' => $reporting_period,
                    'explaination' => $val['explaination'],
                    'jev_number' => $val['jev_number'],
                    'uacs' => $val['uacs'],
                    'general_ledger' => $val['general_ledger'],
                    'ref_number' =>  $val['ref_number'],
                    'debit' => $val['debit'],
                    'credit' => $val['credit'],
                    'date' => $val['date'],
                    'balance' => $balance_per_uacs[$val['uacs']],
                ];
            }

            $result = ArrayHelper::index($final_ledger, null, 'uacs');

            // ob_clean();
            // echo "<pre>";
            // var_dump($r);
            // echo "</pre>";
            // return ob_get_clean();

            // $q = ArrayHelper::multisort(array_column($result,'date'), 'date', [SORT_ASC,]);
            // $result = ArrayHelper::index($final_ledger, 'reporting_period', [function ($element) {
            //     return $element['reporting_period'];
            // }, '']);

            // array_push($chart,['balance'=>$balance])
            // return json_encode(['results' => $chart,]);

            $object_code = '';
            $ledger = '';
            if (!empty($final_ledger)) {

                $object_code = $gen ? $final_ledger[0]['uacs'] : '';
                $ledger = $gen ? $final_ledger[0]['general_ledger'] : '';
            }

            $book_name = '';
            if ($book_id) {
                $fund_cluster_code = Books::find()->where("id = :id", [
                    'id' => $book_id
                ])->one()->name;
            }
            if ($_POST['print'] == 1) {
                return json_encode([
                    'results' => $result,
                    'fund_cluster_code' => $book_name,
                    'reporting_period' => date('F Y', strtotime($reporting_period))
                ]);
            }

            // ob_start();
            // echo "<pre>";
            // var_dump($chart);
            // echo "</pre>";
            // return ob_get_clean();

            return $this->render('general_ledger_view', [
                'data' => $final_ledger,
                'object_code' => $object_code,
                'account_title' => $ledger,
                'print' => json_encode($result),
                'fund_cluster_code' => $fund_cluster_code
            ]);
        } else {

            return $this->render('general_ledger_view', [
                'object_code' => '',
                'x' => '',
                'print' => ''
            ]);
        }
        return $this->render('general_ledger');
    }



    /**
     * Creates a new JevPreparation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function insertEntries($jev_id, $object_codes, $debit = [], $credit = [])
    {


        foreach ($object_codes as $i => $val) {

            $entry = new JevAccountingEntries();
            $entry->object_code = $val;
            $entry->debit = !empty($debit[$i]) ? $debit[$i] : 0;
            $entry->credit = !empty($credit[$i]) ? $credit[$i] : 0;
            $entry->jev_preparation_id = $jev_id;
            if ($entry->save(false)) {
            }
        }
    }
    public function checkReportingPeriod($reporting_period)
    {

        $xyz = (new \yii\db\Query())
            ->select('*')
            ->from('jev_reporting_period')
            ->where('jev_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
            ->one();
        if (!empty($xyz)) {
            return false;
        }
        return true;
    }
    public function checkDebitCredit($debits = [], $credits = [])
    {
        $total_debit = number_format(floatVal(array_sum($debits)), 2);
        $total_credit = number_format(floatVal(array_sum($credits)), 2);

        if ($total_debit !== $total_credit) {

            return false;
        }
        return true;
    }
    // CHECK FV IF NAA NAY JEV
    public function checkDv($id = '')
    {
        if (empty($id)) {
            return false;
        }
        $query = Yii::$app->db->createCommand("SELECT EXISTS(SELECT * FROM jev_preparation WHERE jev_preparation.cash_disbursement_id  =  :id)")
            ->bindValue(':id', $id)
            ->queryScalar();
        if ($query) {
            return true;
        } else {
            return false;
        }
    }
    public function actionCreate()
    {
        $model = new JevPreparation();

        // $modelJevItems = [new JevAccountingEntries()];
        if ($model->load(Yii::$app->request->post())) {
            $debits = $_POST['debit'];
            $credits = $_POST['credit'];
            $object_code = $_POST['object_code'];
            $check_ada = $model->check_ada;
            if (!$this->checkReportingPeriod($model->reporting_period)) {
                return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
            }

            if (!$this->checkDebitCredit($debits, $credits)) {
                return json_encode(['isSuccess' => false, 'error' => 'Debit & Credit are Not Equal']);
            }
            if ($this->checkDv($model->cash_disbursement_id)) {
                return json_encode(['isSuccess' => false, 'error' => 'DV is already have a JEV']);
            }
            if (strtolower($check_ada) === 'ada') {
                $reference = 'ADADJ';
            } else if (strtolower($check_ada) === 'check') {
                $reference = 'CKDJ';
            } else {
                $reference =  $model->ref_number;
            }
            if ($reference == 'ADADJ' || $reference === 'CKDJ') {

                if (empty($model->payee_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Payee Cannot be Blank']);
                }

                if (empty($model->responsibility_center_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Responsibility Center Cannot be Blank']);
                }
            }
            $model->ref_number = $reference;
            $model->jev_number = $reference;
            $model->jev_number .= '-' . $this->getJevNumber($model->book_id, $model->reporting_period, $reference, 1);
            if ($model->validate()) {
                if ($model->save(false)) {
                    $this->insertEntries($model->id, $object_code, $debits, $credits);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                return json_encode($model->errors);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'type' => 'create',
            'entries' => [],
            'error' => ''
        ]);
        // if (Yii::$app->user->can('create-jev')) {
        //     return $this->render('create', [
        //         'model' => '',
        //         'type' => 'create'
        //     ]);
        // } else {
        //     throw new ForbiddenHttpException();
        // }
    }

    /**
     * Updates an existing JevPreparation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $debits = $_POST['debit'];
            $credits = $_POST['credit'];
            $object_code = $_POST['object_code'];
            $check_ada = $model->check_ada;
            if (!$this->checkReportingPeriod($model->reporting_period)) {
                return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
            }

            if (!$this->checkDebitCredit($debits, $credits)) {
                return json_encode(['isSuccess' => false, 'error' => 'Debit & Credit are Not Equal']);
            }
            if (intVal($model->cash_disbursement_id) !== intVal($oldModel->cash_disbursement_id)) {

                if ($this->checkDv($model->cash_disbursement_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'DV is already have a JEV']);
                }
            }
            if (strtolower($check_ada) === 'ada') {
                $reference = 'ADADJ';
            } else if (strtolower($check_ada) === 'check') {
                $reference = 'CKDJ';
            } else {
                $reference =  $model->ref_number;
            }
            if ($reference == 'ADADJ' || $reference === 'CKDJ') {

                if (empty($model->payee_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Payee Cannot be Blank']);
                }

                if (empty($model->responsibility_center_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Responsibility Center Cannot be Blank']);
                }
            }
            $model->ref_number = $reference;
            if ($model->validate()) {
                if ($model->save(false)) {
                    if (!empty($model->jevAccountingEntries)) {
                        foreach ($model->jevAccountingEntries as $val) {
                            $val->delete();
                        }
                    }
                    $this->insertEntries($model->id, $object_code, $debits, $credits);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                return json_encode($model->errors);
            }
        }

        $entries = Yii::$app->db->createCommand("SELECT 
        jev_accounting_entries.id,
        jev_accounting_entries.debit,
        jev_accounting_entries.credit,
        jev_accounting_entries.object_code,
        accounting_codes.account_title
        FROM
         jev_accounting_entries 
        LEFT JOIN accounting_codes ON jev_accounting_entries.object_code = accounting_codes.object_code WHERE jev_preparation_id =:id")
            ->bindValue(':id', $model->id)
            ->queryAll();


        return $this->render('update', [
            'model' => $model,
            'entries' => $entries

        ]);
    }

    /**
     * Deletes an existing JevPreparation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        // if (Yii::$app->user->can('delete-jev')) {

        //     $q =  $this->findModel($id);
        //     foreach ($q->jevAccountingEntries as $val) {
        //         $val->delete();
        //     }

        //     $q->delete();

        //     return $this->redirect(['index']);
        // } else {
        //     throw new ForbiddenHttpException();
        // }
    }

    /**
     * Finds the JevPreparation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JevPreparation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JevPreparation::findOne($id)) !== null) {

            // $fund_cluster = FundClusterCode::findOne($model->fund_cluster_code_id)->name;
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function checkIfBalance($jevItems)
    {
        $debit = 0;
        $credit = 0;
        foreach ($jevItems as $item) {
            $debit += $item->debit;
            $credit += $item->credit;
        }
        if ($debit == $credit) {
            return true;
        } else {
            return false;
        }
    }


    //    IMPORT DATA FROM EXCEL
    public function actionImport()

    {
        if (Yii::$app->user->can('import-jev')) {

            if (!empty($_POST)) {
                $name = $_FILES["file"]["name"];


                $id = uniqid();
                $file = "jev/{$id}_{$name}";;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                } else {
                    return "ERROR 2: MOVING FILES FAILED.";
                    die();
                }

                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $excel = $reader->load($file);
                // $excel->setActiveSheetIndexByName('Conso-For upload');
                $worksheet = $excel->getActiveSheet();
                $reader->setReadDataOnly(FALSE);
                // print_r($excel->getSheetNames());
                $rows = [];
                $jev = [];
                $jev_entries = [];
                $temp_data = [];
                $no_jev_number = [];
                $entry2 = [];
                $i = 1;
                $id = (!empty($w = JevPreparation::find()->orderBy('id DESC')->one())) ? $w->id : 0;
                $number_container = [];
                $transaction = Yii::$app->db->beginTransaction();
                foreach ($worksheet->getRowIterator(3) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    $y = 0;
                    // if ($key > 2) {
                    foreach ($cellIterator as $x => $cell) {

                        // $cells[] =   $cell->getValue()->getCalculatedValue();
                        $qwe = 0;
                        if ($y === 5 || $y === 5) {
                            $cells[] =   $cell->getFormattedValue();
                            // echo '<pre>';y
                            // var_dump('qwe');
                            // echo '</pre>';

                            $rows[] =  $cell->getCalculatedValue();
                        } elseif ($y == 8) {
                            $qwe = $cell->getCalculatedValue();
                            $cells[] = $qwe;
                        } elseif ($y == 9) {
                            $qwe = $cell->getCalculatedValue();
                            $cells[] = $qwe;
                        } else {
                            $cells[] = $cell->getValue();
                        }
                        $y++;
                    }
                    if (!empty($cells)) {
                    }

                    // ob_start();
                    // echo '<pre>';
                    // var_dump($cells);
                    // echo '</pre>';
                    // return ob_get_clean();

                    // if ($key > 2483) {
                    //     echo '<pre>';
                    //     var_dump($cells, $key);
                    //     echo '</pre>';
                    // }

                    $uacs = '';
                    $lvl = 0;
                    $object_code = '';
                    $chart_of_account_id = 0;
                    $uacs_id = str_replace(' ', '', $cells[1]);
                    if (!empty($cells[1])) {
                        $uacs = ChartOfAccounts::find()
                            ->select(['uacs', 'id'])
                            ->where("uacs = :uacs", [
                                'uacs' => $uacs_id
                            ])->one();
                        if (empty($uacs)) {
                            $uacs = SubAccounts1::find()->where("object_code = :object_code", [
                                'object_code' => $uacs_id
                            ])->one();
                            if (empty($uacs)) {
                                $uacs = SubAccounts2::find()->where("object_code = :object_code", [
                                    'object_code' => $uacs_id
                                ])->one();
                                if (!empty($uacs)) {
                                    $lvl = 3;
                                    $object_code = $uacs->object_code;
                                    $chart_of_account_id = $uacs->subAccounts1->chartOfAccount->id;
                                }
                            } else {
                                $lvl = 2;
                                $object_code = $uacs->object_code;
                                $chart_of_account_id = $uacs->chartOfAccount->id;
                            }
                        } else {

                            $object_code = $cells[1];
                            // $chart_of_account_id = $uacs->id;
                        }
                        $lvl = 1;
                        $object_code = $cells[1];
                        if (!empty($uacs)) {
                        }
                        $book = Books::find()->where("name= :name", [
                            'name' => $cells[3]
                        ])->one();
                        $cash_flow = '';
                        if (!empty($cells[16])) {
                            $cash_flow = CashFlow::find()->where("specific_cashflow = :specific_cashflow", ['specific_cashflow' => $cells[16]])->one()->id;
                        }
                        $net_asset = '';
                        if (!empty($cells[17])) {
                            $net_asset = NetAssetEquity::find()->where("specific_change = :specific_change", ['specific_change' => $cells[17]])->one()->id;
                        }
                        $payee = '';
                        if (!empty($cells[14])) {
                            // $payee = Payee::find()->where("account_name =:account_name", [
                            //     'account_name' => $cells[14]
                            // ])->one()->id;
                        }



                        $reporting_period = $cells[4];
                        // return $reporting_period;
                        $date = $cells[4] ? date("Y-m-d", strtotime($cells[5])) : '';
                        // echo '<pre>';
                        // var_dump($cells[4], $key);
                        // echo '</pre>';

                        if ($cells[0] != null) {
                            $id++;
                            // BATCH INSERRRRRRRRRRRRRRT
                            //cell[7] jev number
                            $s = array_search($cells[0],  array_column($number_container, 'no'));
                            // echo '<pre>';
                            // var_dump($s, $cells[7]);
                            // echo '</pre>';
                            if ($s === false) {
                                $jv = $this->getJevNumber($book->id, $reporting_period, $cells[7], 1);
                                $jev_number = $cells[7] . '-' . $jv;
                                $i++;

                                $temp_data[] = [
                                    $id,
                                    (!empty($book)) ? $book->id : '',
                                    $reporting_period,
                                    $date,
                                    $cells[6], //PARTICULAR 
                                    $cells[7], //REFERENCE 
                                    $jev_number,
                                    $cells[11] ? $cells[11] : '', //DV NUMBER
                                    $cells[12] ? $cells[12] : '', //CHECK/ADA/Noncash
                                    $cells[13] ? $cells[13] : '', //CHECK ADA NUMBER3
                                    $payee ? $payee : '', //PAYEE

                                ];

                                $jev = new JevPreparation();
                                $jev->book_id = $book->id;
                                $jev->reporting_period = $reporting_period;
                                $jev->date = $date;
                                $jev->explaination = $cells[6];
                                $jev->ref_number = $cells[7];
                                $jev->jev_number = $jev_number;
                                $jev->dv_number = $cells[11];
                                $jev->check_ada = $cells[12];
                                $jev->check_ada_number = $cells[13];
                                $jev->payee_id = $payee;
                                if ($jev->save(false)) {
                                }
                                $jev_entries[] = [
                                    $jev->id, //JEV PREPARATION ID
                                    // $chart_of_account_id,
                                    $cells[8] ? $cells[8] : 0, //debit amount
                                    $cells[9] ? $cells[9] : 0, //credit amount
                                    $cells[15] ? $cells[15] : '', //Current/Noncurrent
                                    $cells[10] ? $cells[10] : '', //CLOSsING OR NONCLOSSING
                                    $cash_flow,
                                    $net_asset,
                                    $cells[1],
                                    $lvl, //CHART OF ACCOUNTS LVL
                                ];
                                $number_container[] =  ['id' =>  $jev->id, 'no' => $cells[0]];

                                // $jev_entries= new JevAccountingEntries();
                                // $jev_entries->jev_preparation_id;

                            } else {

                                $jev_entries[] = [
                                    $number_container[$s]['id'], //JEV PREPARATION ID
                                    // $chart_of_account_id,
                                    $cells[8] ? $cells[8] : 0, //debit amount
                                    $cells[9] ? $cells[9] : 0, //credit amount
                                    $cells[15] ? $cells[15] : '', //Current/Noncurrent
                                    $cells[10] ? $cells[10] : '', //CLOSsING OR NONCLOSSING
                                    $cash_flow,
                                    $net_asset,
                                    $object_code,
                                    $lvl, //CHART OF ACCOUNTS LVL

                                ];
                            }
                        }
                    }
                    // }
                }

                // JEV ACCOUNTING ENTRIES COLUMNS
                $column = [
                    'jev_preparation_id',
                    // 'chart_of_account_id',
                    'debit',
                    'credit',
                    'current_noncurrent',
                    'closing_nonclosing',
                    'cashflow_id',
                    'net_asset_equity_id',
                    'object_code',
                    'lvl',

                ];
                // JEV PREPARATION COLUMN
                $jev_column = [
                    'id',
                    'book_id',
                    'reporting_period',
                    'date',
                    'explaination',
                    'ref_number',
                    'jev_number',
                    'dv_number',
                    'check_ada',
                    'check_ada_number',
                    'payee_id'

                ];
                // Yii::$app->db->createCommand()->batchInsert('jev_preparation', $jev_column, $temp_data)->execute();

                try {

                    Yii::$app->db->createCommand()->batchInsert('jev_accounting_entries', $column, $jev_entries)->execute();
                    $transaction->commit();
                } catch (ErrorException $error) {
                    $transaction->rollback();
                }
                // ob_clean();
                echo '<pre>';
                var_dump("Success");
                echo '</pre>';
                // return ob_get_clean();
                // foreach ($jev_entries as $x => $val) {
                //     if ($x > 420) {
                //         echo '<pre>';
                //         var_dump($val);
                //         echo '</pre>';
                //     }
                // }
                // unlink($file . '.xlsx');

            }
        } else {
            throw new ForbiddenHttpException();
        }
    }


    public function actionGeneralJournal()
    {


        if (!empty($_POST)) {
            if (!empty($_POST['book_id']) || !empty($_POST['reporting_period'])) {

                $book_id = $_POST['book_id'] ? $_POST['book_id'] : '';
                $reporting_period = $_POST['reporting_period'] ? "{$_POST['reporting_period']}" : '';
                $journal = JevPreparation::find()
                    ->joinWith(['jevAccountingEntries', 'jevAccountingEntries.chartOfAccount'])
                    ->where("jev_preparation.ref_number  = :ref_number", [
                        'ref_number' => 'GJ'
                    ]);


                if (!empty($book_id)) {

                    $journal->andwhere("book_id  = :book_id", [
                        'book_id' => $book_id
                    ]);
                }
                if (!empty($reporting_period)) {

                    $journal->andwhere("jev_preparation.reporting_period  = :reporting_period", [
                        'reporting_period' => $reporting_period
                    ]);
                }
                // echo '<pre>';
                // var_dump($reporting_period);
                // echo '</pre>';


                $x = $journal->orderBy('jev_preparation.jev_number ASC')->all();
                $book_name = '';
                if (!empty($book_id)) {
                    // $fund_cluster_code = $this->getBook($fund);
                    $book_name = $this->getBookName($book_id);
                }
                // echo '<pre>';
                // var_dump($fund);
                // echo '</pre>';
                return $this->render(
                    'general_journal',
                    [
                        'journal' => $x,
                        'book_name' => $book_name,
                        'reporting_period' => $reporting_period
                    ]
                );
            } else {
                return $this->render(
                    'general_journal',
                    [
                        // 'journal' => ''
                    ]
                );
            }
        } else {
            return $this->render(
                'general_journal',
                [
                    'journal' => ''
                ]
            );
        }
    }
    public function actionAdadjFilter()
    {
        return $this->render('adadj_filter', []);
    }
    public function actionAdadj()
    {

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'] ? "{$_POST['reporting_period']}" : '';
            $book_id = $_POST['book_id'];

            $data = JevPreparation::find()
                ->joinWith(['jevAccountingEntries' => function ($query) {
                    $query->joinWith('chartOfAccount')
                        ->orderBy('chart_of_accounts.uacs');
                }])
                ->joinWith('fundClusterCode')
                ->where("jev_preparation.jev_number like :jev_number", [
                    'jev_number' => 'ADADJ%'
                ]);
            if (!empty($reporting_period)) {
                $data->andWhere("jev_preparation.reporting_period =:reporting_period", [
                    'reporting_period' => $reporting_period
                ]);
            }
            if (!empty($fund)) {
                $data->andWhere("jev_preparation.book_id = :book_id", [
                    'book_id' => $book_id
                ]);
            }
            // ->andWhere($sa)

            $x = $data->orderBy('id')
                ->all();

            $credit = $this->creditDebit('credit', $book_id, $reporting_period, 'ADADJ');
            $debit = $this->creditDebit('debit', $book_id, $reporting_period, 'ADADJ');

            //     $credit = Yii::$app->db->createCommand("
            // SELECT DISTINCT chart_of_accounts.uacs,chart_of_accounts.general_ledger
            // from jev_preparation,jev_accounting_entries,chart_of_accounts
            // where jev_preparation.id  = jev_accounting_entries.jev_preparation_id
            // and jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            // AND jev_preparation.reporting_period = '$reporting_period'
            // AND jev_accounting_entries.credit>0
            //  ORDER BY chart_of_accounts.uacs

            // ")->queryAll();

            //     $debit = Yii::$app->db->createCommand("
            // SELECT DISTINCT chart_of_accounts.uacs,chart_of_accounts.general_ledger,jev_preparation.reporting_period
            // from jev_preparation,jev_accounting_entries,chart_of_accounts
            // where jev_preparation.id  = jev_accounting_entries.jev_preparation_id
            // and jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            // AND jev_preparation.reporting_period = '$reporting_period'
            // AND jev_accounting_entries.debit>0
            //  ORDER BY chart_of_accounts.uacs
            // ")->queryAll();

            // echo '<pre>';   
            // var_dump($x[0]['reporting_period']);
            // echo '</pre>';
            $title = "ADVICE TO DEBIT ACCOUNT DISBURSEMENT JOURNAL";
            if ($_POST['export'] > 0) {
                $this->ExcelExport($x, $credit, $debit, $reporting_period, $book_id, $title, 'ADADJ');
            }
            return $this->render('adadj_view', [
                'data' => $x,
                'credit' => $credit,
                'debit' => $debit,
            ]);
        } else {
            return $this->render('adadj_view', []);
        }
    }
    public function actionCkdj()
    {

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'] ? "{$_POST['reporting_period']}" : '';
            $book_id = $_POST['book_id'];

            $data = JevPreparation::find()

                ->joinWith([
                    'jevAccountingEntries',

                ])
                ->joinWith(['jevAccountingEntries.chartOfAccount' => function ($query) {
                    $query->orderBy('uacs');
                }])
                ->joinWith('fundClusterCode')
                ->where("jev_preparation.jev_number like :jev_number", [
                    'jev_number' => 'CKDJ%'
                ]);

            if (!empty($reporting_period)) {
                $data->andWhere("jev_preparation.reporting_period =:reporting_period", [
                    'reporting_period' => $reporting_period
                ]);
            }
            if (!empty($fund)) {
                $data->andWhere("jev_preparation.book_id = :book_id", [
                    'book_id' => $book_id
                ]);
            }
            // $data->addSelect(['total'=>$query = (new \yii\db\Query())->from('billing')
            // $sum = $query->sum('amount')]);
            $x = $data->orderBy('id')->all();
            $credit = $this->creditDebit('credit', $book_id, $reporting_period, 'CKDJ');
            $debit = $this->creditDebit('debit', $book_id, $reporting_period, 'CKDJ');


            // echo '<pre>';
            // var_dump($data);
            // echo '</pre>';
            $title = "CHECK DISBURSEMENT JOURNAL";
            if ($_POST['export'] > 0) {
                $this->ExcelExport($x, $credit, $debit, $reporting_period, $book_id, $title, 'CKDJ');
            }
            return $this->render('ckdj_view', [
                'credit' => $credit,
                'debit' => $debit,
                'data' => $x
            ]);
        } else {
            return $this->render('ckdj_view');
        }
    }

    // PAG KUHA SA MGA  CREDIT/DEBIT HEADER SA ADADJ OG SA CKDJ
    public function creditDebit($type, $book_id, $reporting_period, $jev_type)
    {
        $x =  JevPreparation::find()
            ->joinWith(['jevAccountingEntries', 'jevAccountingEntries.chartOfAccount'])
            ->select([
                'chart_of_accounts.id',
                'chart_of_accounts.uacs',
                'chart_of_accounts.general_ledger',
            ])
            ->where("jev_preparation.jev_number like :jev_number", [
                'jev_number' => "$jev_type%"
            ]);

        if (!empty($reporting_period)) {
            $x->andwhere("reporting_period = :reporting_period", [
                'reporting_period' => $reporting_period
            ]);
        }
        if (!empty($book_id)) {
            $x->andWhere("jev_preparation.book_id = :book_id", [
                'book_id' => $book_id
            ]);
        }
        // ->andWhere("jev_accounting_entries.credit > :credit", [
        //     'credit' => 0
        // ]);


        if ($type == 'credit') {
            $x->andWhere("jev_accounting_entries.credit > :credit", [
                'credit' => 0
            ]);
        } else if ($type == 'debit') {
            $x->andWhere("jev_accounting_entries.debit > :debit", [
                'debit' => 0
            ]);
        }
        $y = $x->orderBy('chart_of_accounts.uacs')->asArray()->all();
        return $y;
    }
    // use PhpOffice\PhpSpreadsheet\Spreadsheet;
    // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    public function ExcelExport($data, $credit, $debit, $reporting_period, $book_id, $title, $type)
    {


        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', "$title");
        $sheet->setCellValue('A3', "For the Month of $reporting_period");
        $sheet->setCellValue('A4', "Entity Name:");
        $sheet->setCellValue('B4', "DEPARTMENT OF TRADE AND INDUSTRY CARAGA");
        // $sheet->setCellValue('A5', "Fund Cluster:");
        // $sheet->setCellValue('B5', "$fund");
        $sheet->setCellValue('A6', 'DATE');
        $sheet->setCellValue('B6', 'JEV No,');
        $sheet->setCellValue('C6', 'DV No.');
        $sheet->setCellValue('D6', 'LDDAP/Check Number');
        $sheet->setCellValue('E6', 'Disbursing Officer');
        $sheet->setCellValue('F6', 'Payee');
        $x = 7;
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        // $sheet->getStyle()->applyFromArray($styleArray);

        foreach ($credit as $val) {

            $sheet->setCellValueByColumnAndRow($x, 6,  $val['general_ledger'] . '---' . $val['uacs']);

            // echo "<pre>";
            // var_dump($val['general_ledger']);
            // echo "</pre>";
            $x++;
        }
        // $x++;
        $sheet->setCellValueByColumnAndRow($x, 6,  'TOTAL ');
        $x++;
        foreach ($debit as $val) {

            $sheet->setCellValueByColumnAndRow($x, 6,  $val['general_ledger'] . '---' . $val['uacs']);

            // echo "<pre>";
            // var_dump($val['general_ledger']);
            // echo "</pre>";
            $x++;
        }
        $sheet->setCellValueByColumnAndRow($x, 6,  'TOTAL ');
        $row = 7;
        $col = 1;
        foreach ($data as $d) {
            $payee_name = '';
            if (!empty($d->payee_id)) {
                $payee_name = Payee::findOne($d->payee_id)->account_name;
            }
            $sheet->setCellValueByColumnAndRow(1, $row,  $d->reporting_period);
            $sheet->setCellValueByColumnAndRow(2, $row,  $d->jev_number);
            $sheet->setCellValueByColumnAndRow(3, $row,  $d->dv_number);
            $sheet->setCellValueByColumnAndRow(4, $row,  $d->check_ada_number);
            $sheet->setCellValueByColumnAndRow(6, $row,  $payee_name);
            $total = 0;
            foreach ($d->jevAccountingEntries as $ae) {

                if (!empty($ae->credit)) {
                    $index  = array_search($ae->chartOfAccount->uacs, array_column($credit, 'uacs'));

                    $sheet->setCellValueByColumnAndRow($index + 7, $row,  $ae->credit);
                    $total += $ae->credit;
                }
                if (!empty($ae->debit)) {
                    $index  = array_search($ae->chartOfAccount->uacs, array_column($debit, 'uacs'));

                    $sheet->setCellValueByColumnAndRow($index + 7 + count($credit) + 1, $row,  $ae->debit);
                    $total += $ae->debit;


                    // echo "<pre>";
                    // var_dump($ae->chartOfAccount->uacs, $index, $index + 7 + count($credit) + 2);
                    // echo "</pre>";
                }
            }
            // PAG BUTANG OG VALUE SA CREDIT TOTAL
            $sheet->setCellValueByColumnAndRow(7 + count($credit), $row, number_format($total));
            // PAG BUTANG OG VALUE SA DEBIT TOTAL

            $sheet->setCellValueByColumnAndRow(8 + count($credit) + count($debit), $row,  number_format($total));

            $row++;
            $col++;
        }

        $id = uniqid();
        $file_name = "$type" . '_' . "$id.xlsx";
        header('Content-Type: application/vnd.ms-excel');
        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
        header('Content-Transfer-Encoding: binary');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Pragma: public'); // HTTP/1.0
        // echo readfile($file);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $file = "transaction\ckdj_excel_$id.xlsx";
        $file2 = "transaction/ckdj_excel_$id.xlsx";
        $writer->save($file);
        // echo "<script> window.location.href = '$file';</script>";
        echo "<script>window.open('$file2','_self')</script>";
        //    echo readfile("../../frontend/web/transaction/" . $file_name);

        // unlink($file2);
        exit();
        // return json_encode(['res' => "transaction\ckdj_excel_$id.xlsx"]);
        // return json_encode($file);
        // exit;
    }

    public function actionTrialBalance()
    {

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'] ? $_POST['book_id'] : '';
            $x = explode('-', $reporting_period);
            $q = $x[0] - 1;

            $begin_balance = JevPreparation::find()
                ->select('jev_preparation.reporting_period')
                ->orderBy('reporting_period ASC')->one()->reporting_period;
            $t_balance = (new \yii\db\Query())
                ->select([
                    'SUM(jev_accounting_entries.credit) as total_credit',
                    'SUM(jev_accounting_entries.debit) as total_debit',
                    'jev_accounting_entries.object_code',
                    'accounting_codes.coa_object_code as uacs',
                    'accounting_codes.coa_account_title as general_ledger',
                    'jev_preparation.reporting_period'
                ])
                ->from(['jev_accounting_entries',])
                ->join('LEFT JOIN', 'accounting_codes', 'jev_accounting_entries.object_code =accounting_codes.object_code ')
                ->join('LEFT JOIN', "jev_preparation", 'jev_accounting_entries.jev_preparation_id=jev_preparation.id  ')
                ->where(['between', 'jev_preparation.reporting_period', $begin_balance, $reporting_period])
                ->andwhere("jev_preparation.book_id = :book_id", [
                    'book_id' => $book_id
                ])
                ->groupBy('accounting_codes.coa_object_code')
                ->orderBy('accounting_codes.coa_object_code')
                // ->limit(10)
                ->all();

            // $total_debit = array_sum(array_column($t_balance, 'total_debit'));
            // $total_credit = array_sum(array_column($t_balance, 'total_credit'));

            $book_name = '';
            if (!empty($book_id)) {
                $fund_cluster_code = $this->getBook($book_id);
            }
            $total_debit_balance = 0;
            $total_credit_balance = 0;
            $trial_balance_final = [];
            $credit_bal_per_uacs = 0;
            $debit_bal_per_uacs = 0;
            //    ob_start();
            foreach ($t_balance as $val) {
                $credit_bal_per_uacs = 0;
                $debit_bal_per_uacs = 0;
                if ($val['total_debit'] > $val['total_credit']) {
                    $debit_bal_per_uacs = $val['total_debit'] - $val['total_credit'];
                    $total_debit_balance += $debit_bal_per_uacs;
                } else if ($val['total_credit'] > $val['total_debit']) {
                    $credit_bal_per_uacs =  $val['total_credit'] - $val['total_debit'];
                    $total_credit_balance += $credit_bal_per_uacs;
                }
                // echo "<pre>";
                // var_dump($val);
                // echo "</pre>";

                $trial_balance_final[] = [
                    'general_ledger' => $val['general_ledger'],
                    'uacs' => $val['uacs'],
                    'debit' => $debit_bal_per_uacs >= 0.01 ? number_format($debit_bal_per_uacs, 2) : '',
                    'credit' => $credit_bal_per_uacs >= 0.01 ? number_format($credit_bal_per_uacs, 2) : ''
                ];
            }
            // echo "<pre>";
            // var_dump($total_debit_balance);
            // echo "</pre>";
            // ob_end_clean();


            // ob_start();
            // echo "<pre>";
            // var_dump($qwe);
            // echo "</pre>";
            // return ob_get_clean();

            return $this->render('trial_balance_view', [
                't_balance' => $trial_balance_final,
                'reporting_period' => date('F Y', strtotime($reporting_period)),
                'debit_total' => $total_debit_balance,
                'credit_total' => $total_credit_balance,
                'fund_cluster_code' => $fund_cluster_code
            ]);
        } else {
            return $this->render('trial_balance_view');
        }
        // $t_balance = JevPreparation::find()
        //     ->joinWith(['jev' => function () {
        //         // $query->joinWith('chartOfAccount')
        //         Yii::$app->db->createCommand("
        //         select jev_accounting_entries.*,
        //         SUM(jev_accounting_entries.credit) as total_credit,
        //         SUM(jev_accounting_entries.debit) as total_debit
        //         from jev_accounting_entries
        //         group by jev_accounting_entries.chart_of_account_id
        //         ")->queryAll();
        //     }])
        //     ->select("jev.total_credit")
        //     ->where("reporting_period = :reporting_period", [
        //         'reporting_period' => '2020-02'
        //     ])->all();
        // $t_balance =Yii::$app->db->createCommand("select jev_preparation.*,y.*
        // from
        // jev_preparation
        // ,
        // (
        //     select jev_accounting_entries.*,
        //         SUM(jev_accounting_entries.credit) as total_credit,
        //         SUM(jev_accounting_entries.debit) as total_debit
        //     from jev_accounting_entries

        //     group by jev_accounting_entries.chart_of_account_id

        // ) as y,chart_of_accounts where jev_preparation.id = y.jev_preparation_id 
        // and y.chart_of_account_id = chart_of_accounts.id 
        // and jev_preparation.reporting_period = "2020-01"
        // ORDER BY chart_of_accounts.uacs");

    }

    public function getBook($book_id)
    {
        $book_name = Books::find()->where("id=:id", ['id' => $book_id])->one()->name;
        return $book_name;
    }



    // public function actionGetA()
    // {
    //     // echo "<pre>";
    //     // var_dump($_POST['name']);
    //     // echo "</pre>";
    //     return json_encode($_POST['x']);
    // }

    public function actionInsertJev()
    {

        if (!empty($_POST)) {



            $reporting_period = $_POST['reporting_period'];

            // if (date('Y', strtotime($reporting_period)) < date('Y')) {
            //     return json_encode(['isSuccess' => false, 'error' => "Invalid Reporting Period"]);
            // } else {
            $xyz = (new \yii\db\Query())
                ->select('*')
                ->from('jev_reporting_period')
                ->where('jev_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                ->one();
            if (!empty($xyz)) {
                return json_encode(['isSuccess' => false, 'error' => " Reporting Period is Disabled"]);
            }
            // else
            // {
            //     return json_encode(['isSuccess' => false, 'error' => $xyz['reporting_period']]);
            // }

            // }
            $check_ada_date = !empty($_POST['check_ada_date']) ? $_POST['check_ada_date'] : '';
            $date = !empty($_POST['date']) ? $_POST['date'] : '';
            // $fund_cluster_code = $_POST['fund_cluster_code'] ? $_POST['fund_cluster_code'] : '';
            $r_center_id = !empty($_POST['r_center_id']) ? $_POST['r_center_id'] : '';
            // $r_center_id = $_POST['r_center_id'];


            $check_ada = !empty($_POST['check_ada']) ? $_POST['check_ada'] : '';
            if (strtolower($check_ada) === 'ada') {
                $reference = 'ADADJ';
            } else if (strtolower($check_ada) === 'check') {
                $reference = 'CKDJ';
            } else {
                $reference =  $_POST['reference'];
            }
            // $reference =  'Check';
            $payee = !empty($_POST['payee']) ? $_POST['payee'] : '';
            $lddap = !empty($_POST['lddap']) ? $_POST['lddap'] : '';
            $cadadr_number = !empty($_POST['cadadr_number']) ? $_POST['cadadr_number'] : '';
            $dv_number = !empty($_POST['dv_number']) ? $_POST['dv_number'] : '';
            $explanation = !empty($_POST['particular']) ? $_POST['particular'] : '';
            $payee_id = !empty($_POST['payee_id']) ? $_POST['payee_id'] : '';
            $ref_number = !empty($_POST['reference']) ? $_POST['reference'] : '';
            $ada_number = !empty($_POST['ada_number']) ? $_POST['ada_number'] : '';
            $book_id = !empty($_POST['book']) ? $_POST['book'] : '';
            $entry_type = !empty($_POST['entry_type']) ? $_POST['entry_type'] : '';

            $total_debit = round(array_sum($_POST['debit']), 2);
            $total_credit = round(array_sum($_POST['credit']), 2);
            $tt = 0;
            $account_entries = count($_POST['chart_of_account_id']);

            if ($total_debit == $total_credit) {

                $year = date('Y', strtotime($reporting_period));
                // if ($year != 2020) {



                // if (!empty($reporting_period) && !empty($fund_cluster_code)) {

                // for($x=0;$x<count($_POST['debit']);$x++){
                //      $amount = floatval(preg_replace('/[^\d.]/', '', $_POST['debit'][$x]));
                //      $tt+=$amount;
                //      echo $amount;
                // }

                // }     
                $transaction = \Yii::$app->db->beginTransaction();

                $jev_preparation = new JevPreparation();

                // kung update and transaction
                if ($_POST['update_id'] > 0) {

                    $jv = JevPreparation::findOne($_POST['update_id']);
                    if (!empty($jv->jevAccountingEntries)) {
                        foreach ($jv->jevAccountingEntries as $val) {
                            $val->delete();
                        }
                    }
                    $q = explode('-', $jv->jev_number);

                    $jev_number_serial = $q[4];

                    $jev_preparation->id = $jv->id;

                    $jev_referenece = $q[0];

                    $jev_book = trim($q[1]);
                    $book = Books::find()->where("id =:id", ['id' => $book_id])->one();
                    // $x
                    $qwe = strcasecmp($jev_referenece, $reference);
                    // if ($jev_book===$book->name){}

                    if ($qwe === 0 &&  strcasecmp($jv->book_id, $book_id) === 0) {
                        // return json_encode(['jev'=>$jev_referenece,'ref'=>$reference,'q'=>$qwe]);
                        // die();
                        $x = $reference;
                        $x .= '-' . $this->getJevNumber($book_id, $reporting_period, $reference, 1);
                        $y = explode('-', $x);
                        $jev_number = $y[0] . '-' . $y[1] . '-' . $y[2] . '-' . $y[3] . '-' . $jev_number_serial;
                        // return json_encode(['jev' => $jev_number]);
                        // die();
                    } else {

                        $jev_number = $reference;
                        $jev_number .= '-' . $this->getJevNumber($book_id, $reporting_period, $reference, 1);
                    }
                    // if ($jev_book === $book->name) {
                    //     $x = $reference;
                    //     $x .= '-' . $this->getJevNumber($book_id, $reporting_period, $reference, 1);
                    //     $y = explode('-', $x);
                    //     $jev_number = $y[0] . '-' . $y[1] . '-' . $y[2] . '-' . $y[3] . '-' . $jev_number_serial;
                    //     return json_encode(['jev'=>$reference]);
                    //     die();
                    // } else {
                    //     $jev_number = $reference;
                    //     $jev_number .= '-' . $this->getJevNumber($book_id, $reporting_period, $reference, 1);
                    // }


                    $jv->delete();
                } else {
                    $jev_number = $reference;
                    $jev_number .= '-' . $this->getJevNumber($book_id, $reporting_period, $reference, 1);
                }
                // return json_encode(['jev' => $jev_number]);
                // die();
                // $jev_number = $reference;
                // $jev_number .= '-' . $this->getJevNumber($book_id, $reporting_period, $reference, 1);
                // $x = explode('-', $jev_number);
                // $y = $x[0] . '-' . $x[1] . '-' . $x[2] . '-' . $x[3] . '-' . $jev_number_serial;
                $jev_preparation->reporting_period = $reporting_period;
                $jev_preparation->responsibility_center_id = $r_center_id;
                // $jev_preparation->fund_cluster_code_id = $fund_cluster_code;
                $jev_preparation->date = $date;
                $jev_preparation->jev_number = $jev_number;
                $jev_preparation->ref_number = $reference;
                $jev_preparation->dv_number = $dv_number;
                $jev_preparation->lddap_number = $lddap;
                $jev_preparation->explaination = $explanation;
                $jev_preparation->payee_id = $payee_id;
                // $jev_preparation->cash_flow_id =$reporting_period;
                // $jev_preparation->mrd_classification_id =$reporting_period;
                $jev_preparation->cadadr_serial_number = $cadadr_number;
                $jev_preparation->check_ada = $check_ada;
                $jev_preparation->check_ada_number = $ada_number;
                $jev_preparation->check_ada_date = $check_ada_date;
                $jev_preparation->book_id = $book_id;
                $jev_preparation->entry_type = $entry_type;
                $jev_preparation->cash_disbursement_id = $_POST['dv'];


                if ($jev_preparation->validate()) {
                    try {
                        if ($flag = $jev_preparation->save(false)) {
                            // return json_encode($jev_preparation->id);
                            $jev_preparation_id = $jev_preparation->id;
                            $isClosing = 'Non-closing';
                            if (explode('-', $reporting_period)[1] == 12) {
                                $isClosing == 'Closing';
                            }
                            $account_entries = count($_POST['chart_of_account_id']);
                            //     $s = [];
                            for ($i = 0; $i < $account_entries; $i++) {

                                // $x = explode('-', $_POST['chart_of_account_id'][$i]);
                                $credit_decimal_places = 0;
                                $debit_decimal_places = 0;
                                // if (floor($_POST['credit']) != $_POST['credit'] ? true : false) {
                                //     $c = explode('.', $_POST['credit'][$i])[1];
                                //     $credit_decimal_places = strlen($c);
                                // }
                                // if (floor($_POST['debit']) != $_POST['debit'] ? true : false) {
                                //     $d = explode('.', $_POST['debit'][$i])[1];
                                //     $debit_decimal_places = strlen($d);
                                // }

                                // if ($credit_decimal_places <= 2 || $debit_decimal_places <= 2) {



                                // $chart_id = 0;
                                // if ($x[2] == 2) {
                                $chart_id = (new \yii\db\Query())
                                    ->select(['chart_of_accounts.id'])
                                    ->from('accounting_codes')
                                    ->join("LEFT JOIN", 'chart_of_accounts', 'accounting_codes.coa_object_code = chart_of_accounts.uacs')
                                    ->where('accounting_codes.object_code =:object_code', ['object_code' => $_POST['chart_of_account_id'][$i]])
                                    ->one()['id'];
                                // } else if ($x[2] == 3) {
                                //     // $chart_id = (new \yii\db\Query())->select(['chart_of_accounts.id'])->from('sub_accounts1')
                                //     //     ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                                //     //     ->where('sub_accounts1.id =:id', ['id' => intval($x[0])])->one()['id'];
                                //     $chart_id = SubAccounts2::findOne(intval($x[0]))->subAccounts1->chart_of_account_id;
                                // } else {
                                //     $chart_id = $x[0];
                                // }

                                $jv = new JevAccountingEntries();
                                $jv->jev_preparation_id = $jev_preparation_id;
                                // $jv->chart_of_account_id = intval($chart_id);
                                $jv->debit = !empty($_POST['debit'][$i]) ? $_POST['debit'][$i] : 0;
                                $jv->credit = !empty($_POST['credit'][$i]) ? $_POST['credit'][$i] : 0;
                                // $jv->current_noncurrent=$jev_preparation->id;
                                $jv->cashflow_id =  !empty($_POST['cash_flow_id'][$i]) ? $_POST['cash_flow_id'][$i] : '';
                                $jv->net_asset_equity_id =  !empty($_POST['isEquity'][$i]) ? $_POST['isEquity'][$i] : '';
                                $jv->closing_nonclosing = $isClosing;
                                // $jv->lvl = $x[2];
                                $jv->object_code = $_POST['chart_of_account_id'][$i];

                                if (!($flag = $jv->save(false))) {
                                    //  return json_encode();
                                    $s[] =  $jv->cash_flow_transaction;
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
                        } else {
                            // return json_encode('w');
                        }
                        if ($flag) {

                            $transaction->commit();
                            // return $this->redirect(['view', 'id' => $model->id]);
                            return json_encode(['isSuccess' => 'success', 'id' => $jev_preparation_id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                        // return json_encode("q");
                    }
                } else {
                    // validation failed: $errors is an array containing error messages
                    $errors = $jev_preparation->errors;
                    return json_encode(['error' => $errors]);
                }
                // } else {
                //     return json_encode(['isSuccess' => false, 'error' => 'Reporting Period Must be 2021']);
                // }
            } else {
                return json_encode(
                    [
                        'isSuccess' => false,
                        'error' => 'Total Debit and Credit are Not Equal',
                        'debit' => $tt,
                        'credit' => $total_credit = $_POST['credit'],
                    ]
                );
            }


            // echo "<pre>";
            // var_dump($jev_number);
            // echo "</pre>";
        }
    }

    public function actionIsCurrent()
    {
        $x =  $_POST['chart_id'];
        // $chart_id = $x[0];
        // $chart = (new \yii\db\Query())
        //     ->select(['chart_of_accounts.id', 'chart_of_accounts.current_noncurrent', 'chart_of_accounts.account_group', 'major_accounts.object_code'])
        //     ->from('chart_of_accounts')
        //     ->join('LEFT JOIN', 'major_accounts', 'chart_of_accounts.major_account_id=major_accounts.id')
        //     ->join('LEFT JOIN', 'sub_accounts1', 'chart_of_accounts.id=sub_accounts1.chart_of_account_id')
        //     ->join('LEFT JOIN', 'sub_accounts2', 'sub_accounts1.id=sub_accounts2.sub_accounts1_id');



        // if (intval($x[2]) === 1) {
        //     $chart->where("chart_of_accounts.id = :id", ['id' =>  intval($chart_id)]);
        // } else if ($x[2] === 2) {
        //     $chart->where("sub_accounts1.id = :id", ['id' => intval($chart_id)]);
        // } else if ($x[2] === 3) {
        //     $chart->where("sub_accounts2.id = :id", ['id' => intval($chart_id)]);
        // }

        // $q = $chart->one();
        // $res = Yii::$app->db->createCommand("SELECT  current_noncurrent,account_group FROM chart_of_accounts where id = {$_POST['chart_id']}")->queryOne();

        //   print_r($chart);
        // $chart = (new \yii\db\Query());
        // $chart->select(['current_noncurrent'])
        //     ->from('chart_of_accounts')
        //     ->where("chart_of_accounts.id = :id", [
        //         'id' => $_POST['chart_id']
        //     ])->one();
        $query = Yii::$app->db->createCommand("SELECT *  FROM accounting_codes WHERE object_code = :object_code")
            ->bindValue(':object_code', $x)
            ->queryOne();
        $isEquity = false;
        $isCashEquivalent = false;
        if ($query['major_object_code'] == 1010000000) {
            $isCashEquivalent = true;
        }
        if (strtolower($query['account_group']) === 'equity') {
            $isEquity = true;
        }

        return json_encode(['result' => $query, 'isEquity' => $isEquity, 'isCashEquivalent' => $isCashEquivalent, 'current_noncurrent' => $query['current_noncurrent']]);

        // ob_clean();
        // echo "<pre>";
        // var_dump($query  );
        // echo "</pre>";
        // return ob_get_clean();
    }



    public function getFinancialPosition($reporting_period, $book_id)
    {

        $x = explode('-', $reporting_period);
        $reporting_period_last_year = $x[0] - 1 . '-' . $x[1];
        $begining_reporting_period = JevPreparation::find()->orderBy('reporting_period ASC')->one()->reporting_period;
        $begining_month = $x[0] . '-01';
        $q = Yii::$app->db->createCommand("SELECT * from 
            (SELECT chart_of_accounts.account_group,
            chart_of_accounts.uacs,chart_of_accounts.general_ledger,
            chart_of_accounts.current_noncurrent,major_accounts.name,chart_of_accounts.normal_balance,
            
            jev_preparation.reporting_period,
            SUM(jev_accounting_entries.debit) as total_debit, SUM(jev_accounting_entries.credit) as total_credit
            FROM jev_accounting_entries,jev_preparation,chart_of_accounts,major_accounts

            WHERE jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            AND jev_accounting_entries.jev_preparation_id = jev_preparation.id
            AND chart_of_accounts.major_account_id = major_accounts.id
            AND chart_of_accounts.account_group IN ('Assets','Liabilities','Equity')
            AND jev_preparation.reporting_period BETWEEN :begining_month AND :reporting_period
            AND jev_preparation.book_id = :book_id    
            GROUP BY chart_of_accounts.account_group,chart_of_accounts.major_account_id,chart_of_accounts.uacs
            ORDER BY chart_of_accounts.account_group,chart_of_accounts.current_noncurrent,chart_of_accounts.major_account_id) as r1

            LEFT JOIN
            
            (SELECT chart_of_accounts.uacs,
            SUM(jev_accounting_entries.debit) as last_year_total_debit, SUM(jev_accounting_entries.credit) as last_year_total_credit
            FROM jev_accounting_entries,jev_preparation,chart_of_accounts,major_accounts
            WHERE jev_accounting_entries.chart_of_account_id = chart_of_accounts.id 
            AND jev_accounting_entries.jev_preparation_id = jev_preparation.id
            AND chart_of_accounts.major_account_id = major_accounts.id
            AND chart_of_accounts.account_group IN ('Assets','Liabilities','Equity')
            AND jev_preparation.reporting_period BETWEEN  :begining_reporting_period AND :reporting_period
            AND jev_preparation.book_id = :book_id
            GROUP BY chart_of_accounts.account_group,chart_of_accounts.major_account_id,chart_of_accounts.uacs
            ORDER BY chart_of_accounts.account_group,chart_of_accounts.current_noncurrent,chart_of_accounts.major_account_id) as r2

            ON (r1.uacs = r2.uacs)
            ")
            ->bindValue(':book_id', intval($book_id))
            ->bindValue(':reporting_period', $reporting_period)
            ->bindValue(':reporting_period_last_year', $reporting_period_last_year)
            ->bindValue(':begining_reporting_period', $begining_reporting_period)
            ->bindValue(':begining_month', $begining_month)

            ->queryAll();

        $with_bal = [];

        foreach ($q as $val) {
            $current_bal = 0;
            $last_year_bal = 0;

            if (strtolower($val['normal_balance']) == 'credit') {
                $current_bal = $val['total_credit'] - $val['total_debit'];
                $last_year_bal = $val['last_year_total_credit'] - $val['last_year_total_debit'];
            } else {
                $current_bal = $val['total_debit'] - $val['total_credit'];
                $last_year_bal = $val['last_year_total_debit'] - $val['last_year_total_credit'];
            }

            $val['current_bal'] = $current_bal;
            $val['last_year_bal'] = $last_year_bal;
            $with_bal[] = $val;
        }
        return $with_bal;
    }
    public function actionDetailedFinancialPosition()
    {

        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $with_bal = $this->getFinancialPosition($reporting_period, $book_id);
            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['account_group'];
            }, 'current_noncurrent', 'name']);

            // ob_start();
            // echo "<pre>";
            // var_dump($reporting_period_last_year,$y);
            // echo "</pre>";
            // return ob_get_clean();
            $year = $this->getCurYearAndPrevYear($reporting_period);
            $book_name = $this->getBookName($book_id);
            return $this->render('detailed_financial_position_view', [
                'data' => $result,
                'reporting_period' => $year['cur_year'],
                'prev_year' => $year['prev_year'],
                'book_name' => $book_name
            ]);
        } else {
            return $this->render('detailed_financial_position_view', []);
        }
    }

    // CONSOLIDATED FINANCIAL STATEMENTS POSITION
    public function actionConsolidatedFinancialPosition()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $with_bal = $this->getFinancialPosition($reporting_period, $book_id);
            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['account_group'];
            }, 'current_noncurrent',]);

            // ob_start();
            // echo "<pre>";
            // var_dump($reporting_period_last_year,$y);
            // echo "</pre>";
            // return ob_get_clean();
            $book_name = $this->getBookName($book_id);
            $x = explode('-', $reporting_period);
            $reporting_period_last_year =  $x[0] - 1 . '-' . $x[1];

            $year = $this->getCurYearAndPrevYear($reporting_period);
            return $this->render('consolidated_financial_position_view', [
                'data' => $result,
                'reporting_period' => $year['cur_year'],
                'prev_year' => $year['prev_year'],
                'book_name' => $book_name
            ]);
        } else {
            return $this->render('consolidated_financial_position_view', []);
        }
    }
    // GET SUSIDIARY LEDGER
    // public function actionGetSubsidiaryLedger()
    // {

    //     if ($_POST) {
    //         // echo "<pre>";
    //         // var_dump($_POST['sub_account']);
    //         // echo "</pre>";

    //         $sl = (new \yii\db\Query())
    //             ->select([
    //                 'jev_preparation.date',
    //                 'jev_preparation.explaination',
    //                 'jev_preparation.ref_number',
    //                 'jev_accounting_entries.debit',
    //                 'jev_accounting_entries.credit',
    //                 'accounting_codes.normal_balance',
    //                 'accounting_codes.coa_account_title as general_ledger',
    //                 'jev_preparation.jev_number'

    //             ])
    //             ->from('jev_accounting_entries')
    //             ->join("LEFT JOIN",  "jev_preparation", "jev_accounting_entries.jev_preparation_id = jev_preparation.id")
    //             ->join("LEFT JOIN",  "accounting_codes", "jev_accounting_entries.object_code = accounting_codes.object_code")
    //             // ->where("jev_accounting_entries.lvl = :lvl", [
    //             //     'lvl' => 2
    //             // ])
    //             ->andWhere("jev_accounting_entries.object_code = :object_code", [
    //                 'object_code' => $_POST['sub_account']
    //             ])
    //             ->andWhere("jev_preparation.book_id = :book_id", [
    //                 'book_id' => $_POST['book_id']
    //             ])
    //             // ->groupBy('object_code')
    //             // ->orderBy('jev_preparation.id ASC')
    //             ->orderBy('jev_preparation.date,jev_preparation.jev_number ASC')
    //             // ->orderBy('jev_preparation.created_at ASC')
    //             ->all();
    //         $book_name = Books::find()->where("id =:id", ['id' => $_POST['book_id']])->one()->name;
    //         $sl_name = (new \yii\db\Query())->select(['account_title as name'])->from('sub_accounts_view')
    //             ->where("object_code =:object_code", ['object_code' => $_POST['sub_account']])->one()['name'];
    //         $sl_final = [];
    //         $balance = 0;
    //         // ArrayHelper::multisort($sl, ['jev_number'], [SORT_ASC]);

    //         foreach ($sl as $val) {

    //             if (strtolower($val['normal_balance']) == 'credit') {

    //                 $balance += $val['credit'] - $val['debit'];
    //             } else {
    //                 $balance += $val['debit'] - $val['credit'];
    //             }
    //             $val['balance'] = $balance;
    //             $sl_final[] = $val;
    //             // echo "<pre>";
    //             // var_dump($val);
    //             // echo "</pre>";
    //         }
    //         // echo "<pre>";
    //         // var_dump($sl_name);
    //         // echo "</pre>";
    //         $general_ledger = '';
    //         if (!empty($sl_final[0]['general_ledger'])) {
    //             $general_ledger = $sl_final[0]['general_ledger'];
    //         }

    //         return $this->render('subsidiary_ledger_view', [
    //             'data' => $sl_final,
    //             'fund_cluster' => $book_name,
    //             'general_ledger' => $general_ledger,
    //             'sl_name' => $sl_name
    //         ]);
    //     } else {
    //         return $this->render('subsidiary_ledger_view');
    //     }


    //     // return json_encode($sl);
    // }
    public function generateSubLedger($from_reporting_period, $to_reporting_period, $book_id, $year, $object_code = '', $sql1 = '', $sql2 = '', $uacs = '')
    {
        $and = !empty($sql1) ? 'AND' : '';

        $query = Yii::$app->db->createCommand("SELECT  * FROM (SELECT
           ROW_NUMBER() OVER (
            PARTITION BY object_code 
            ORDER BY object_code ) row_num,
            accounting_entries.date,
            accounting_entries.particular,
            accounting_entries.jev_number,
            IFNULL(accounting_entries.debit,0) as debit,
            IFNULL(accounting_entries.credit,0) as credit,
            accounting_entries.object_code,
            accounting_codes.account_title,
            CONCAT(accounting_entries.object_code,'-',
            accounting_codes.account_title) as head,
            accounting_codes.normal_balance
            FROM(
            SELECT  
            books.id as book_id,
            jev_preparation.reporting_period,
            jev_preparation.date,
            jev_preparation.explaination as particular,
            jev_preparation.jev_number,
            jev_accounting_entries.debit,
            jev_accounting_entries.credit,
            jev_accounting_entries.object_code,
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as uacs
            FROM jev_accounting_entries
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            LEFT JOIN books ON jev_preparation.book_id =  books.id
            WHERE jev_preparation.reporting_period <= :to_reporting_period
            AND jev_preparation.reporting_period >=:from_reporting_period
            AND books.id = :book_id
            $and $sql1
            ) as accounting_entries
            INNER JOIN accounting_codes ON accounting_entries.object_code = accounting_codes.object_code
            WHERE accounting_entries.object_code !=accounting_entries.uacs
       
            UNION ALL 
            SELECT
            0 as row_num,
            '' as `date`,
            'beginning_balance' as particular,
            '' as jev_number,
            IFNULL(jev_beginning_balance_item.debit,0) as debit,
            IFNULL(jev_beginning_balance_item.credit,0) as credit,
            jev_beginning_balance_item.object_code,
            accounting_codes.account_title,
            CONCAT(jev_beginning_balance_item.object_code,'-',
            accounting_codes.account_title) as head,
            accounting_codes.normal_balance
            FROM jev_beginning_balance_item 
            LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
            LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
            WHERE jev_beginning_balance.`year` = :_year
            AND jev_beginning_balance.book_id  = :book_id
            AND accounting_codes.object_code !=accounting_codes.coa_object_code
            $and $sql2
            ) as q 
            ORDER BY q.row_num ASC
            ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':_year', $year)
            ->bindValue(':object_code', $object_code)
            ->bindValue(':uacs', $uacs . '%')
            ->queryAll();

        return $query;
    }

    public function actionGenerateSubsidiaryLedger()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $object_code = $_POST['object_code'];
            $year  = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('Y');
            $from_reporting_period = $year . '-01';
            $params = [];
            $sql1 = Yii::$app->db->getQueryBuilder()->buildCondition('jev_accounting_entries.object_code =:object_code', $params);
            $sql2 = Yii::$app->db->getQueryBuilder()->buildCondition('jev_beginning_balance_item.object_code =:object_code', $params);
            $query = $this->generateSubLedger($from_reporting_period, $to_reporting_period, $book_id, $year, $object_code, $sql1, $sql2);
            $result = ArrayHelper::index($query, 'row_num', 'head');


            $chart_of_account = Yii::$app->db->createCommand("SELECT 
            accounting_codes.object_code,
            accounting_codes.account_title,
            accounting_codes.coa_object_code,
            accounting_codes.coa_account_title
            FROM accounting_codes
            WHERE accounting_codes.object_code = :object_code
            ")
                ->bindValue(':object_code', $object_code)
                ->queryOne();
            $book_name = Yii::$app->db->createCommand("SELECT books.name FROM books WHERE books.id = :book_id")
                ->bindValue(':book_id', $book_id)
                ->queryScalar();
            return json_encode(['results' => $query, 'chart_of_account' => $chart_of_account, 'book_name' => $book_name]);
        }
        return $this->render('subsidiary_ledger_view');
    }

    public function actionGetSubsidiaryLedger()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['print_reporting_period'];
            $book_id = $_POST['print_book_id'];
            $uacs  = $_POST['print_uacs'];
            $year  = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('Y');
            $from_reporting_period = $year . '-01';
            $params = [];
            $sql1 = Yii::$app->db->getQueryBuilder()->buildCondition('jev_accounting_entries.object_code LIKE :uacs', $params);
            $sql2 = Yii::$app->db->getQueryBuilder()->buildCondition('jev_beginning_balance_item.object_code LIKE :uacs', $params);
            $query = $this->generateSubLedger($from_reporting_period, $to_reporting_period, $book_id, $year, '', $sql1, $sql2, $uacs);
            $result = ArrayHelper::index($query, 'row_num', 'head');
            $book  = Yii::$app->db->createCommand("SELECT books.name FROM books WHERE id =:id")
                ->bindValue(':id', $book_id)
                ->queryScalar();
            return json_encode(['query' => $query, 'for_print' => $result, 'year' => $year, 'book_name' => $book]);
        }
        return $this->render('subsidiary_ledger_view');
    }

    // DETAILED STATEMENT OF FINANCIAL PERFORMANCE
    public function getFinancialPerformance($reporting_period, $book_id)
    {

        $x = explode('-', $reporting_period);
        $reporting_period_begin_month = $x[0] . '-' . '01';
        $prev_year_begin_month =  $x[0] - 1 . '-' . '01';
        $reporting_period_last_year =  $x[0] - 1 . '-' . $x[1];
        $q = Yii::$app->db->createCommand("SELECT * from 
            (SELECT chart_of_accounts.account_group,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            chart_of_accounts.current_noncurrent,
            major_accounts.name,
            chart_of_accounts.normal_balance,
            jev_preparation.id as jev_id,
            jev_preparation.reporting_period,
            SUM(jev_accounting_entries.debit) as total_debit,
            SUM(jev_accounting_entries.credit) as total_credit
            FROM jev_accounting_entries,jev_preparation,chart_of_accounts,major_accounts
            WHERE jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            AND jev_accounting_entries.jev_preparation_id = jev_preparation.id

            AND chart_of_accounts.major_account_id = major_accounts.id

            AND chart_of_accounts.account_group IN ('Expenses','Income')
            AND jev_preparation.reporting_period BETWEEN :reporting_period_begin_month AND :reporting_period
            AND jev_preparation.book_id = :book_id
            AND jev_accounting_entries.closing_nonclosing='Non-closing'
            GROUP BY chart_of_accounts.account_group,chart_of_accounts.major_account_id,chart_of_accounts.uacs
            ORDER BY chart_of_accounts.account_group,chart_of_accounts.current_noncurrent,chart_of_accounts.major_account_id) as r1

            LEFT JOIN
            (SELECT chart_of_accounts.uacs,
            SUM(jev_accounting_entries.debit) as last_year_total_debit, SUM(jev_accounting_entries.credit) as last_year_total_credit
            FROM jev_accounting_entries,jev_preparation,chart_of_accounts,major_accounts

            WHERE jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            AND jev_accounting_entries.jev_preparation_id = jev_preparation.id

            AND chart_of_accounts.major_account_id = major_accounts.id

            AND chart_of_accounts.account_group IN ('Expenses','Income')
            AND jev_preparation.reporting_period BETWEEN :prev_year_begin_month AND :reporting_period_last_year
            AND jev_preparation.book_id = :book_id
            AND jev_accounting_entries.closing_nonclosing='Non-closing'
            GROUP BY chart_of_accounts.account_group,chart_of_accounts.major_account_id,chart_of_accounts.uacs
            ORDER BY chart_of_accounts.account_group,chart_of_accounts.current_noncurrent,chart_of_accounts.major_account_id) as r2

            ON (r1.uacs = r2.uacs)
            ")
            ->bindValue(':reporting_period', $reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':reporting_period_last_year', $reporting_period_last_year)
            ->bindValue(':reporting_period_begin_month', $reporting_period_begin_month)
            ->bindValue(':prev_year_begin_month', $prev_year_begin_month)
            ->queryAll();

        $with_bal = [];

        foreach ($q as $val) {
            $current_bal = 0;
            $last_year_bal = 0;

            if (strtolower($val['normal_balance']) == 'credit') {
                $current_bal = $val['total_credit'] - $val['total_debit'];
                $last_year_bal = $val['last_year_total_credit'] - $val['last_year_total_debit'];
            } else {
                $current_bal = $val['total_debit'] - $val['total_credit'];
                $last_year_bal = $val['last_year_total_debit'] - $val['last_year_total_credit'];
            }

            $val['current_bal'] = $current_bal;
            $val['last_year_bal'] = $last_year_bal;
            $with_bal[] = $val;
        }
        return $with_bal;
    }
    // DETAILED FINANCIAL STATEMENT PERFORMANCE
    public function actionDetailedFinancialPerformance()
    {

        if ($_POST) {
            $reporting_period =  $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $with_bal = $this->getFinancialPerformance($reporting_period, $book_id);
            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['account_group'];
            }, 'current_noncurrent', 'name']);
            // ob_start();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();
            $book_name = $this->getBookName($book_id);;
            $x = explode('-', $reporting_period);
            $reporting_period_last_year =  $x[0] - 1 . '-' . $x[1];
            $year = $this->getCurYearAndPrevYear($reporting_period);
            return $this->render('detailed_financial_performance_view', [
                'data' => $result,
                'reporting_period' =>  $year['cur_year'],
                'prev_year' =>  $year['prev_year'],
                'book_name' => $book_name

            ]);
        } else {
            return $this->render('detailed_financial_performance_view');
        }
    }
    // GET BOOK NAME
    public function getBookName($book_id)
    {
        $book_name = Books::findOne($book_id)->name;
        return $book_name;
    }
    // CONSOLIDATED STATEMENT OF FINANCIAL PERFORMANCE
    public function actionConsolidatedFinancialPerformance()
    {
        if ($_POST) {
            $reporting_period =  $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $with_bal = $this->getFinancialPerformance($reporting_period, $book_id);


            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['account_group'];
            }, 'current_noncurrent',]);
            $book_name = $this->getBookName($book_id);
            // ob_start();
            // echo "<pre>";
            // var_dump($isClosing);
            // echo "</pre>";
            // return ob_get_clean();

            $x = explode('-', $reporting_period);
            $reporting_period_last_year =  $x[0] - 1 . '-' . $x[1];
            $year = $this->getCurYearAndPrevYear($reporting_period);
            return $this->render('consolidated_financial_performance_view', [
                'data' => $result,
                'reporting_period' =>  $year['cur_year'],
                'prev_year' =>  $year['prev_year'],
                'book_name' => $book_name

            ]);
        } else {
            return $this->render('consolidated_financial_performance_view');
        }
    }
    // MAG ASSIGN OG JEV NUMBER 
    public function getJevNumber($book_id, $reporting_period, $reference, $i)
    {
        // $reporting_period = "2021-12";
        $q = date("Y%", strtotime($reporting_period));
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(jev_number,'-',-1) AS UNSIGNED)  as q
        from jev_preparation

        WHERE reporting_period LIKE :r_year
        AND book_id = :book_id
        AND ref_number = :ref_number
        ORDER BY q DESC LIMIT 1")
            ->bindValue(':ref_number', $reference)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':r_year', $q)
            ->queryScalar();


        // $query = JevPreparation::find()
        // ->where("reporting_period LIKE :reporting_period", [
        //     'reporting_period' => "$q%"
        // ])
        // ->andWhere("book_id = :book_id", [
        //     'book_id' => $book_id
        // ])
        // ->andWhere("ref_number = :ref_number", [
        //     'ref_number' => $reference
        // ])
        // ->orderBy([
        //     'id' => SORT_DESC
        // ])->one();
        $ff = Books::find()
            ->where("id = :id", [
                'id' => $book_id
            ])->one()->name;
        if (!empty($query)) {
            // echo "<pre>";
            // var_dump($query->toArray());
            // echo "</pre>";
            // $x=1;
            // echo "<br> $i";
            $x = $query + $i;
        } else {
            $x = $i;
        }
        $y = null;
        $len = strlen($x);

        // add zero bag.o mag last number
        for ($i = $len; $i < 4; $i++) {
            $y .= 0;
        }
        $year = date('Y', strtotime($reporting_period));
        $year .= '-' . date('m', strtotime($reporting_period));
        $year .= '-' . $y . $x;

        // VarDumper::dump($year);
        $ff .= '-' . $year;
        // var_dump($ff);
        // die();
        return $ff;
        // ob_start();
        // echo "<pre>";
        // var_dump( $query);
        // echo "</pre>";
        // return ob_get_clean();
    }

    // KUHAON ANG DATA SA E UPDATE NA JEV
    public function actionUpdateJev()
    {

        if ($_POST) {

            $model = JevPreparation::findOne($_POST['update_id']);

            $res = [];

            // foreach ($model as $val) {

            // }

            $jev = [
                'reporting_period' => $model->reporting_period,
                'responsibility_center_id' => $model->responsibility_center_id,
                'fund_cluster_code_id' => $model->fund_cluster_code_id,
                'date' => $model->date,
                'ref_number' => $model->ref_number,
                'dv_number' => $model->dv_number,
                'lddap_number' => $model->lddap_number,
                // 'entity_name' => $model->entity_name,
                'explaination' => $model->explaination,
                'payee_id' => $model->payee_id,
                'payee_name' => !empty($model->payee->account_name) ? $model->payee->account_name : '',
                'cash_flow_id' => $model->cash_flow_id,
                'mrd_classification_id' => $model->mrd_classification_id,
                'cadadr_serial_number' => $model->cadadr_serial_number,
                'check_ada' => $model->check_ada,
                'check_ada_number' => $model->check_ada_number,
                'check_ada_date' => $model->check_ada_date,
                'book_id' => $model->book_id,
                'cash_disbursement_id' => $model->cash_disbursement_id,
                'entry_type' => $model->entry_type,

            ];
            $jev_ae =  Yii::$app->db->createCommand("SELECT 
        jev_accounting_entries.jev_preparation_id,
                               jev_accounting_entries.debit,
                              jev_accounting_entries.credit,
                               jev_accounting_entries.net_asset_equity_id,
                               accounting_codes.object_code,
                              jev_accounting_entries.cashflow_id,
       accounting_codes.account_title
       FROM jev_accounting_entries 
       LEFT JOIN accounting_codes ON jev_accounting_entries.object_code = accounting_codes.object_code
       WHERE jev_accounting_entries.jev_preparation_id = :dv_id")->bindValue(':dv_id', $model->id)->queryAll();
            // foreach ($model->jevAccountingEntries as $val) {

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
            //     $jev_ae[] = [
            //         'jev_preparation_id' => $val->jev_preparation_id,
            //         'chart_of_account_id' => $val->chart_of_account_id,
            //         'id' => $chart_id,
            //         'debit' => $val->debit,
            //         'credit' => $val->credit,
            //         'current_noncurrent' => $val->current_noncurrent,
            //         // 'cash_flow_transaction' => intval($val->cash_flow_transaction),
            //         'net_asset_equity_id' => $val->net_asset_equity_id,
            //         'object_code' => $val->object_code,
            //         'lvl' => $val->lvl,
            //         'cashflow_id' => $val->cashflow_id,
            //     ];
            // }

            // echo "<pre>";
            // var_dump($jev_ae);
            // echo "</pre>";
            return json_encode(['jev_preparation' => $jev, 'jev_accounting_entries' => $jev_ae]);
        }
    }

    public function getCashflow($reporting_period, $book_id)
    {
        $x = explode('-', $reporting_period);
        $reporting_period_begin_month = $x[0] . '-01';
        $prev_year_reporting_period = $x[0] - 1 . '-' . $x[1];
        $prev_year_begin_month = $x[0] - 1 . '-' . $x[1];

        $q = Yii::$app->db->createCommand(
            "SELECT * from 
                ( SELECT cash_flow.major_cashflow,cash_flow.sub_cashflow1,cash_flow.specific_cashflow ,
                SUM(debit) as total_debit,SUM(credit)as total_credit,
                chart_of_accounts.normal_balance,cash_flow.sub_cashflow2,cash_flow.id
                
                FROM jev_accounting_entries,cash_flow,jev_preparation,chart_of_accounts
                WHERE  jev_accounting_entries.cashflow_id=cash_flow.id
                AND jev_accounting_entries.chart_of_account_id=chart_of_accounts.id
                AND jev_accounting_entries.jev_preparation_id = jev_preparation.id
                AND jev_accounting_entries.cashflow_id IS NOT NULL
                AND jev_preparation.reporting_period BETWEEN :reporting_period_begin_month AND :reporting_period
                AND jev_preparation.book_id = :book_id
                GROUP BY jev_accounting_entries.cashflow_id  ) as r1
            LEFT JOIN
            (SELECT SUM(debit) as prev_year_total_debit,SUM(credit)as prev_year_total_credit ,cash_flow.id
                FROM jev_accounting_entries,cash_flow,jev_preparation,chart_of_accounts
                WHERE  jev_accounting_entries.cashflow_id=cash_flow.id
                AND jev_accounting_entries.chart_of_account_id=chart_of_accounts.id
                AND jev_accounting_entries.jev_preparation_id = jev_preparation.id
                AND jev_accounting_entries.cashflow_id IS NOT NULL
                AND jev_preparation.reporting_period BETWEEN :prev_year_begin_month AND :prev_year_reporting_period
                AND jev_preparation.book_id = :book_id
                GROUP BY jev_accounting_entries.cashflow_id  )  as r2
            ON (r1.id = r2.id)
            "
        )
            ->bindValue(':reporting_period', $reporting_period)
            ->bindValue(':reporting_period_begin_month', $reporting_period_begin_month)
            ->bindValue(':prev_year_reporting_period', $prev_year_reporting_period)
            ->bindValue(':prev_year_begin_month', $prev_year_begin_month)
            ->bindValue(':book_id', $book_id)
            ->queryAll();
        $with_bal = [];

        foreach ($q as $val) {
            $current_bal = 0;
            $last_year_bal = 0;

            if (strtolower($val['normal_balance']) == 'credit') {
                $current_bal = $val['total_credit'] - $val['total_debit'];
                $last_year_bal = $val['pre_year_total_credit'] - $val['pre_year_total_debit'];
            } else {
                $current_bal = $val['total_debit'] - $val['total_credit'];
                $last_year_bal = $val['prev_year_total_debit'] - $val['prev_year_total_credit'];
            }

            $val['current_bal'] = $current_bal;
            $val['last_year_bal'] = $last_year_bal;
            $with_bal[] = $val;
        }
        return $with_bal;
    }
    public function getCurYearAndPrevYear($reporting_period)
    {
        $x = explode('-', $reporting_period);
        $reporting_period_last_year =  $x[0] - 1 . '-' . $x[1];

        $cur_year = date('F Y', strtotime($reporting_period));
        $prev_year = date('Y', strtotime($reporting_period_last_year));
        return ['cur_year' => $cur_year, 'prev_year' => $prev_year];
    }
    // GET DETAILED CASHFLOW
    public function actionDetailedCashflow()
    {

        if ($_POST) {

            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $with_bal = $this->getCashflow($reporting_period, $book_id);

            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['major_cashflow'];
            }, 'sub_cashflow1', 'sub_cashflow2']);
            $x = explode('-', $reporting_period);


            $year = $this->getCurYearAndPrevYear($reporting_period);
            $book_name = $this->getBookName($book_id);
            //   ob_start();
            // echo "<pre>";
            // var_dump($with_bal);
            // echo "</pre>";
            // return ob_get_clean();

            return $this->render('detailed_cashflow_view', [
                'data' => $result,
                'reporting_period' => $year['cur_year'],
                'prev_year' => $year['prev_year'],
                'book_name' => $book_name
            ]);

            // ob_start();
            // echo "<pre>";
            // var_dump($q['prev_year']);
            // echo "</pre>";
            // return ob_get_clean();
        } else {
            return $this->render('detailed_cashflow_view');
        }
    }
    public function actionConsolidatedCashflow()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $with_bal = $this->getCashflow($reporting_period, $book_id);
            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['major_cashflow'];
            }, 'sub_cashflow1']);
            $year = $this->getCurYearAndPrevYear($reporting_period);
            $book_name = $this->getBookName($book_id);
            return $this->render('consolidated_cashflow_view', [
                'data' => $result,
                'reporting_period' => $year['cur_year'],
                'prev_year' => $year['prev_year'],
                'book_name' => $book_name
            ]);
            // ob_start();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();
        } else {

            return $this->render("consolidated_cashflow_view");
        }
    }


    public function actionChangesNetassetEquity()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $x = explode('-', $reporting_period);
            $reporting_period_begin_month = $x[0] . '-01';
            $prev_year = $x[0] - 1 . '-' . $x[1];
            $prev_year_begin_month = $x[0] - 1 . '-01';
            $query = Yii::$app->db->createCommand("SELECT * FROM
            (SELECT  jev_preparation.reporting_period, SUM(jev_accounting_entries.debit) as total_debit,
            SUM(jev_accounting_entries.credit) as total_credit,net_asset_equity.specific_change,net_asset_equity.id,
            chart_of_accounts.normal_balance,net_asset_equity.group
            FROM jev_accounting_entries,jev_preparation,chart_of_accounts,net_asset_equity
            WHERE jev_accounting_entries.jev_preparation_id=jev_preparation.id
            AND jev_accounting_entries.net_asset_equity_id = net_asset_equity.id
            AND jev_accounting_entries.chart_of_account_id=chart_of_accounts.id
            AND jev_accounting_entries.net_asset_equity_id IS NOT NULL
            AND jev_preparation.reporting_period BETWEEN :reporting_period_begin_month AND :reporting_period
            AND jev_preparation.book_id = :book_id
            GROUP BY jev_accounting_entries.net_asset_equity_id) as q1
            LEFT JOIN
            (SELECT   SUM(jev_accounting_entries.debit) as prev_year_total_debit,
            SUM(jev_accounting_entries.credit) as prev_year_total_credit,net_asset_equity.id as prev_id
            FROM jev_accounting_entries,jev_preparation,chart_of_accounts,net_asset_equity
            WHERE jev_accounting_entries.jev_preparation_id=jev_preparation.id
            AND jev_accounting_entries.net_asset_equity_id = net_asset_equity.id
            AND jev_accounting_entries.chart_of_account_id=chart_of_accounts.id
            AND jev_accounting_entries.net_asset_equity_id IS NOT NULL
            AND jev_preparation.reporting_period BETWEEN :prev_year_begin_month AND :prev_year
            AND jev_preparation.book_id = :book_id
            GROUP BY jev_accounting_entries.net_asset_equity_id) as q2
            ON (q1.id=q2.prev_id)")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':reporting_period_begin_month', $reporting_period_begin_month)
                ->bindValue(':book_id', $book_id)
                ->bindValue(':prev_year', $prev_year)
                ->bindValue(':prev_year_begin_month', $prev_year_begin_month)
                ->queryAll();
            $with_bal = [];

            foreach ($query as $val) {
                $current_bal = 0;
                $last_year_bal = 0;

                if (strtolower($val['normal_balance']) == 'credit') {
                    $current_bal = $val['total_credit'] - $val['total_debit'];
                    $last_year_bal = $val['prev_year_total_credit'] - $val['prev_year_total_debit'];
                } else {
                    $current_bal = $val['total_debit'] - $val['total_credit'];
                    $last_year_bal = $val['prev_year_total_debit'] - $val['prev_year_total_credit'];
                }

                $val['current_bal'] = $current_bal;
                $val['last_year_bal'] = $last_year_bal;
                $with_bal[] = $val;
            }
            $result = ArrayHelper::index($with_bal, null, [function ($element) {
                return $element['group'];
            }, 'specific_change']);
            // ob_start();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();
            $book_name = $this->getBookName($book_id);
            $year = $this->getCurYearAndPrevYear($reporting_period);
            return $this->render('changes_in_netasset_equity_view', [
                'data' => $result,
                'reporting_period' => $year['cur_year'],
                'prev_year' => $year['prev_year'],
                'book_name' => $book_name
            ]);
        } else {
            return $this->render('changes_in_netasset_equity_view');
        }
    }

    public function actionExportJev()
    {

        if ($_POST) {
            $reporting_period = $_POST['year'];
            $year =  $_POST['year'];
            $query = JevAccountingEntries::find()
                ->joinWith('jevPreparation')
                ->where('jev_preparation.reporting_period >=:reporting_period', ['reporting_period' => $reporting_period])
                ->all();
            $q1 = (new \yii\db\Query())
                ->select([
                    'SUM(jev_accounting_entries.debit) as total_debit',
                    'SUM(jev_accounting_entries.credit) as total_credit',
                    'jev_accounting_entries.object_code',
                    'jev_accounting_entries.lvl',
                    'books.name as book_name'
                ])
                ->from('jev_accounting_entries')
                ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id = jev_preparation.id')
                ->join('LEFT JOIN', 'books', 'jev_preparation.book_id = books.id')
                ->where("jev_preparation.reporting_period <:reporting_period", ['reporting_period' => $reporting_period])
                ->groupBy("jev_accounting_entries.object_code")
                ->all();

            // $sub1 = (new \yii\db\Query())
            //     ->select('*')
            //     ->from('sub_accounts1')
            //     ->all();

            // $sub2 = (new \yii\db\Query())
            //     ->select('*')
            //     ->from('sub_accounts2')
            //     ->all();
            // $chart = (new \yii\db\Query())
            //     ->select('*')
            //     ->from('chart_of_accounts')
            //     ->all();
            $accounting_codes = (new \yii\db\Query())
                ->select('object_code,account_title,coa_object_code,coa_account_title')
                ->from('accounting_codes')
                ->all();
            // ob_clean();
            // echo "<pre>";
            // var_dump($accounting_codes);
            // echo "</pre>";
            // return ob_get_clean();







            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->setAutoFilter('A1:N1');
            $sheet->setCellValue('A1', "JEV Number");
            $sheet->setCellValue('B1', "DV Number");
            $sheet->setCellValue('C1', "Check/ADA Number");
            $sheet->setCellValue('D1', "Payee");
            $sheet->setCellValue('E1', "UACS");
            $sheet->setCellValue('F1', "General Ledger");
            $sheet->setCellValue('G1', 'Entry Object Code');
            $sheet->setCellValue('H1', 'Entry Account Title');
            $sheet->setCellValue('I1', 'Reporting Period');
            $sheet->setCellValue('J1', 'Date');
            $sheet->setCellValue('K1', 'Particular');
            $sheet->setCellValue('L1', 'Debit');
            $sheet->setCellValue('M1', 'Credit');
            $sheet->setCellValue('N1', 'Reference');
            $sheet->setCellValue('O1', 'BOOK');
            $sheet->setCellValue('P1', 'Closing/ Non-Closing');

            // BEGINNING BALANCE
            // $sheet->setCellValue('K2', 'Beginning Balance');
            // $sheet->setCellValue('L2', $q1['total_debit']);
            // $sheet->setCellValue('M2', $q1['total_credit']);
            $x = 7;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );
            $beginning_balances = Yii::$app->db->createCommand("SELECT 
            books.`name` as book_name,
            accounting_codes.object_code,
            accounting_codes.account_title,
            jev_beginning_balance_item.debit,
            jev_beginning_balance_item.credit
             FROM jev_beginning_balance_item 
            LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
            LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
            LEFT JOIN books ON jev_beginning_balance.book_id = books.id
            WHERE jev_beginning_balance.`year` = :_year
            ")
                ->bindValue(':_year', $year)
                ->queryAll();
            // return json_encode($beginning_balances);

            $row = 2;
            foreach ($beginning_balances as $x) {
                $account_title = $x['account_title'];
                $object_code = $x['object_code'];

                $sheet->setCellValueByColumnAndRow(
                    7,
                    $row,
                    $object_code
                );
                //ENTRY ACCOUNT TITLE
                $sheet->setCellValueByColumnAndRow(
                    8,
                    $row,
                    $account_title
                );
                $sheet->setCellValueByColumnAndRow(
                    11,
                    $row,
                    'Beginning Balance'
                );

                $sheet->setCellValueByColumnAndRow(
                    12,
                    $row,
                    !empty($x['debit']) ? $x['debit'] : ''
                );
                //CREDIT
                $sheet->setCellValueByColumnAndRow(
                    13,
                    $row,
                    !empty($x['credit']) ? $x['credit'] : ''
                );
                $sheet->setCellValueByColumnAndRow(
                    15,
                    $row,
                    !empty($x['book_name']) ? $x['book_name'] : ''
                );
                $row++;
            }
            foreach ($query  as  $val) {

                // jev_number
                $sheet->setCellValueByColumnAndRow(1, $row,  !empty($val->jevPreparation->jev_number) ? $val->jevPreparation->jev_number : '');
                // dv number
                $sheet->setCellValueByColumnAndRow(2, $row,  !empty($val->jevPreparation->dv_number) ? $val->jevPreparation->dv_number : '');
                // check ada number
                $sheet->setCellValueByColumnAndRow(3, $row,  !empty($val->jevPreparation->check_ada_number) ? $val->jevPreparation->check_ada_number : '');
                //payee
                $sheet->setCellValueByColumnAndRow(
                    4,
                    $row,
                    !empty($val->jevPreparation->payee_id) ? $val->jevPreparation->payee->account_name : ''
                );

                $general_ledger = '';
                $object_code = '';
                $coa_object_code = '';
                $coa_account_title = '';

                $eee = array_search($val->object_code, array_column($accounting_codes, 'object_code'));

                $general_ledger = $accounting_codes[$eee]['account_title'];
                $object_code = $accounting_codes[$eee]['object_code'];
                $coa_object_code = $accounting_codes[$eee]['coa_object_code'];
                $coa_account_title = $accounting_codes[$eee]['coa_account_title'];
                //UACS
                $sheet->setCellValueByColumnAndRow(
                    5,
                    $row,
                    $coa_object_code
                );
                //GENERAL LEDGER

                $sheet->setCellValueByColumnAndRow(
                    6,
                    $row,
                    $coa_account_title
                );


                $sheet->setCellValueByColumnAndRow(
                    7,
                    $row,
                    $object_code
                );
                //ENTRY ACCOUNT TITLE
                $sheet->setCellValueByColumnAndRow(
                    8,
                    $row,
                    $general_ledger
                );
                //REPORTING PERIOD
                $sheet->setCellValueByColumnAndRow(
                    9,
                    $row,
                    !empty($val->jevPreparation->reporting_period) ? $val->jevPreparation->reporting_period : ''
                );
                //DATE
                $sheet->setCellValueByColumnAndRow(
                    10,
                    $row,
                    !empty($val->jevPreparation->date) ? $val->jevPreparation->date : ''
                );
                //PARTICULAR
                $sheet->setCellValueByColumnAndRow(
                    11,
                    $row,
                    !empty($val->jevPreparation->explaination) ? $val->jevPreparation->explaination : ''
                );
                //DEBIT
                $sheet->setCellValueByColumnAndRow(
                    12,
                    $row,
                    !empty($val->debit) ? $val->debit : ''
                );
                //CREDIT
                $sheet->setCellValueByColumnAndRow(
                    13,
                    $row,
                    !empty($val->credit) ? $val->credit : ''
                );
                //REFERENCE
                $sheet->setCellValueByColumnAndRow(
                    14,
                    $row,
                    !empty($val->jevPreparation->ref_number) ? $val->jevPreparation->ref_number : ''
                );
                $sheet->setCellValueByColumnAndRow(
                    15,
                    $row,
                    !empty($val->jevPreparation->books->name) ? $val->jevPreparation->books->name : ''
                );
                $sheet->setCellValueByColumnAndRow(
                    16,
                    $row,
                    !empty($val->jevPreparation->entry_type) ? $val->jevPreparation->entry_type : ''
                );

                $row++;
            }

            date_default_timezone_set('Asia/Manila');
            // return date('l jS \of F Y h:i:s A');
            $id = date('Y-m-d h A');
            $file_name = "jev_$id.xlsx";
            // header('Content-Type: application/vnd.ms-excel');
            // header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            // header('Content-Transfer-Encoding: binary');
            // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            // header('Pragma: public'); // HTTP/1.0
            // echo readfile($file);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            $file =  "transaction\jev_$id.xlsx";
            $file2 = Url::base() . '/' . "transaction/jev_$id.xlsx";

            $writer->save($file);
            // return ob_get_clean();
            header('Content-Type: application/vnd.ms-excel');
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Pragma: public'); // HTTP/1.0
            // readfile($file2);
            echo "<script>window.open('$file2','_self')</script>";
            // return json_encode($file2);
            // unlink($file2);
            // flush();
            // ob_clean();
            // flush();

            // // echo "<script> window.location.href = '$file';</script>";
            // echo "<script>window.open('$file2','_self')</script>";

            //    echo readfile("../../frontend/web/transaction/" . $file_name);
            exit();
            // return json_encode(['res' => "transaction\ckdj_excel_$id.xlsx"]);
            // return json_encode($file);
            // exit;
        }
    }

    public function actionCdrJev($id)
    {
        return $this->render('create', [
            'model' => $id,
            'type' => 'cdr',

        ]);
    }
    public function actionDvToJev($id)
    {

        $model = new JevPreparation();

        if ($model->load(Yii::$app->request->post())) {
            $debits = $_POST['debit'];
            $credits = $_POST['credit'];
            $object_code = $_POST['object_code'];
            $check_ada = $model->check_ada;




            if (!$this->checkReportingPeriod($model->reporting_period)) {
                return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
            }
            if (!$this->checkDebitCredit($debits, $credits)) {
                return json_encode(['isSuccess' => false, 'error' => 'Debit & Credit are Not Equal']);
            }
            if ($this->checkDv($model->cash_disbursement_id)) {
                return json_encode(['isSuccess' => false, 'error' => 'DV is already have a JEV']);
            }
            if (strtolower($check_ada) === 'ada') {
                $reference = 'ADADJ';
            } else if (strtolower($check_ada) === 'check') {
                $reference = 'CKDJ';
            } else {
                $reference =  $model->ref_number;
            }
            $model->ref_number = $reference;
            $model->jev_number = $reference;
            $model->jev_number .= '-' . $this->getJevNumber($model->book_id, $model->reporting_period, $reference, 1);
            if ($model->validate()) {
                if ($model->save(false)) {
                    $this->insertEntries($model->id, $object_code, $debits, $credits);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                return json_encode($model->errors);
            }
        }
        $dv_entries = Yii::$app->db->createCommand("SELECT 
        accounting_codes.account_title,
        dv_accounting_entries.object_code,
        dv_accounting_entries.debit,
        dv_accounting_entries.credit 
        FROM dv_accounting_entries
        LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
        
         WHERE dv_aucs_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
        return $this->render('create', [
            'model' => $model,
            'type' => 'create',
            'entries' => $dv_entries,

        ]);
    }
    public function actionGenerateGeneralLedger()
    {
        if ($_POST) {

            $to_reporting_period = $_POST['reporting_period'];
            $reporting_period = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $from_reporting_period = $reporting_period->format('Y') . '-01';
            $book_id = $_POST['book_id'];
            $object_code = $_POST['object_code'];
            $year = $reporting_period->format('Y');

            $beginning_balance = Yii::$app->db->createCommand("SELECT
                jev_beginning_balance_item.debit,
                jev_beginning_balance_item.credit,
                (CASE
                    WHEN chart_of_accounts.normal_balance ='Debit' THEN jev_beginning_balance_item.debit - jev_beginning_balance_item.credit
                ELSE jev_beginning_balance_item.credit -  jev_beginning_balance_item.debit
                END) as beginning_balance_total
                
                FROM jev_beginning_balance_item 
                
                LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
                LEFT JOIN chart_of_accounts ON jev_beginning_balance_item.object_code = chart_of_accounts.uacs
                    WHERE jev_beginning_balance.`year` = :_year
                    AND jev_beginning_balance_item.object_code = :object_code
                    AND jev_beginning_balance.book_id  = :book_id
                
                ")
                ->bindValue(':_year', $year)
                ->bindValue(':book_id', $book_id)
                ->bindValue(':object_code', $object_code)
                ->queryOne();
            $query = Yii::$app->db->createCommand("SELECT
                        accounting_entries.*,
                        chart_of_accounts.normal_balance,
                        (CASE 
                        WHEN chart_of_accounts.normal_balance = 'Debit' THEN accounting_entries.debit - accounting_entries.credit
                        ELSE accounting_entries.credit - accounting_entries.debit
                        END) as total
                        FROM(
                        SELECT  
                        jev_preparation.reporting_period,
                        jev_preparation.date,
                        jev_preparation.explaination as particular,
                        jev_preparation.jev_number,
                        jev_accounting_entries.debit,
                        jev_accounting_entries.credit,
                        SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as uacs
                        
                        
                        FROM jev_accounting_entries
                        LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
                        LEFT JOIN books ON jev_preparation.book_id =  books.id
                        WHERE jev_accounting_entries.object_code LIKE :object_code
                        AND jev_preparation.reporting_period <= :to_reporting_period
                        AND jev_preparation.reporting_period >=:from_reporting_period
                        AND books.id = :book_id
                        ) as accounting_entries
                        INNER  JOIN chart_of_accounts ON accounting_entries.uacs = chart_of_accounts.uacs
                        ORDER BY accounting_entries.`date`
                ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->bindValue(':object_code', $object_code . '%')
                ->queryAll();
            return json_encode([
                'beginning_balance' => $beginning_balance,
                'query' => $query,
            ]);
        }
    }
    public function actionSubTrialBalance()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = $r_period_date->format('F Y');
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_id  = $_POST['book_id'];
            $query = Yii::$app->db->createCommand("SELECT 
            accounting_codes.object_code,
            accounting_codes.account_title as account_title,
            accounting_codes.normal_balance,
            (CASE
            WHEN accounting_codes.normal_balance = 'Debit' THEN accounting_entries.debit - accounting_entries.credit
            ELSE accounting_entries.credit - accounting_entries.debit
            END) as total_debit_credit,
            beginning_balance.total_beginning_balance as begin_balance
            FROM (
            SELECT
            jev_accounting_entries.object_code
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.book_id = :book_id
            AND jev_preparation.reporting_period <= :to_reporting_period
            GROUP BY jev_accounting_entries.object_code
            UNION
            SELECT 
               jev_beginning_balance_item.object_code
                FROM jev_beginning_balance
                LEFT JOIN jev_beginning_balance_item ON jev_beginning_balance.id = jev_beginning_balance_item.jev_beginning_balance_id
            WHERE  jev_beginning_balance.book_id=:book_id
            GROUP BY object_code
            ) as jev_object_codes
            LEFT JOIN (SELECT
            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit,
            jev_accounting_entries.object_code 
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.book_id = :book_id
            AND jev_preparation.reporting_period >= :from_reporting_period
            AND jev_preparation.reporting_period <= :to_reporting_period
            GROUP BY jev_accounting_entries.object_code) as accounting_entries ON jev_object_codes.object_code = accounting_entries.object_code
            LEFT JOIN (SELECT 
                accounting_codes.object_code,
                (CASE
                    WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(jev_beginning_balance_item.debit,0)  - IFNULL(jev_beginning_balance_item.credit,0)
                    ELSE IFNULL(jev_beginning_balance_item.credit,0) - IFNULL(jev_beginning_balance_item.debit,0)
                END) as total_beginning_balance
                FROM jev_beginning_balance_item 
              LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
              LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
              LEFT JOIN books ON jev_beginning_balance.book_id = books.id
              WHERE 
                    jev_beginning_balance.`year` = :_year
                AND jev_beginning_balance.book_id = :book_id) as beginning_balance ON jev_object_codes.object_code = beginning_balance.object_code
            LEFT JOIN accounting_codes ON jev_object_codes.object_code = accounting_codes.object_code
            
            WHERE   (CASE
            WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) !=0
            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->bindValue(':_year', $year)
                ->queryAll();
            return json_encode($query);
        }

        return $this->render('subsidiary_trial_balance');
    }
    public function actionLiquidationReportToJev($id)
    {

        $entries = Yii::$app->db->createCommand("SELECT 
                accounting_codes.account_title,
                ro_liquidation_report_items.object_code,
                ro_liquidation_report_items.amount as debit,
                0 as credit 
                FROM ro_liquidation_report_items
                LEFT JOIN accounting_codes ON ro_liquidation_report_items.object_code = accounting_codes.object_code
                WHERE ro_liquidation_report_items.fk_ro_liquidation_report_id = :id
                UNION ALL 
                SELECT 
                    '' as account_title,
                    '' as object_code,
                    0 as debit,
                    ro_liquidation_report_refunds.amount as credit 
                FROM ro_liquidation_report_refunds 
                WHERE ro_liquidation_report_refunds.fk_ro_liquidation_report_id = :id
                UNION ALL
                SELECT 
                    accounting_codes.account_title,
                    dv_aucs.object_code,
                    0 as debit,
                    IFNULL(items_total.total,0) - IFNULL(refund_total.total,0) as credit

                    FROM ro_liquidation_report
                    LEFT JOIN 
                    (SELECT SUM(ro_liquidation_report_items.amount) as total, ro_liquidation_report_items.fk_ro_liquidation_report_id
                    FROM ro_liquidation_report_items 
                    WHERE ro_liquidation_report_items.is_deleted !=1
                    GROUP BY ro_liquidation_report_items.fk_ro_liquidation_report_id) as items_total
                    ON ro_liquidation_report.id = items_total.fk_ro_liquidation_report_id
                    LEFT JOIN 
                    (SELECT SUM(ro_liquidation_report_refunds.amount) as total,ro_liquidation_report_refunds.fk_ro_liquidation_report_id 
                    FROM ro_liquidation_report_refunds 
                    WHERE 
                    ro_liquidation_report_refunds.is_deleted !=1
                    GROUP BY ro_liquidation_report_refunds.fk_ro_liquidation_report_id) as refund_total
                    ON ro_liquidation_report.id = refund_total.fk_ro_liquidation_report_id
                    LEFT JOIN dv_aucs ON ro_liquidation_report.fk_dv_aucs_id = dv_aucs.id
                    LEFT JOIN accounting_codes ON dv_aucs.object_code = accounting_codes.object_code
                    WHERE ro_liquidation_report.id = :id
                    ")->bindValue(':id', $id)
            ->queryAll();
        $model = new JevPreparation();

        if ($model->load(Yii::$app->request->post())) {
            $debits = $_POST['debit'];
            $credits = $_POST['credit'];
            $object_code = $_POST['object_code'];
            $check_ada = $model->check_ada;




            if (!$this->checkReportingPeriod($model->reporting_period)) {
                return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
            }
            if (!$this->checkDebitCredit($debits, $credits)) {
                return json_encode(['isSuccess' => false, 'error' => 'Debit & Credit are Not Equal']);
            }
            if ($this->checkDv($model->cash_disbursement_id)) {
                return json_encode(['isSuccess' => false, 'error' => 'DV is already have a JEV']);
            }
            if (strtolower($check_ada) === 'ada') {
                $reference = 'ADADJ';
            } else if (strtolower($check_ada) === 'check') {
                $reference = 'CKDJ';
            } else {
                $reference =  $model->ref_number;
            }
            $model->ref_number = $reference;
            $model->jev_number = $reference;
            $model->jev_number .= '-' . $this->getJevNumber($model->book_id, $model->reporting_period, $reference, 1);
            if ($model->validate()) {
                if ($model->save(false)) {
                    $this->insertEntries($model->id, $object_code, $debits, $credits);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                return json_encode($model->errors);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'type' => 'create',
            'entries' => $entries,

        ]);
    }
}
