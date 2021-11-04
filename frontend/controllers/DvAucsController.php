<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\DvAccountingEntries;
use Yii;
use app\models\DvAucs;
use app\models\DvAucsEntries;
use app\models\DvAucsSearch;
use app\models\ProcessOrsSearch;

use app\models\SubAccounts2;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

    /**
     * Lists all DvAucs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DvAucsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];

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
        // $model = new DvAucs();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
        $searchModel = new ProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
        ]);
    }

    /**
     * Updates an existing DvAucs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('update', [
        //     'model' => $model,
        // ]);
        $searchModel = new ProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // echo $id;
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
        ]);
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
        // return json_encode(["isSuccess" => false, "error" => $_POST['amount_disbursed']]);
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
                    "process_ors.id as ors_id", "process_ors.serial_number", "transaction.particular as transaction_particular",
                    "payee.account_name as transaction_payee",
                    "process_ors.book_id",
                    "total_obligated.total", "payee.id as transaction_payee_id"
                ])
                ->from('process_ors')
                ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")
                ->join("LEFT JOIN", "payee", "transaction.payee_id = payee.id")

                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount)as total,process_ors.id as ors_id
                FROM process_ors,raouds,raoud_entries

                where process_ors.id = raouds.process_ors_id
                AND raouds.id=raoud_entries.raoud_id
                GROUP BY process_ors.id) as total_obligated", "process_ors.id=total_obligated.ors_id")
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
            $transaction_type = strtolower($_POST['transaction_type']);
            $transaction_timestamp = date('Y-m-d H:i:s', strtotime($_POST['transaction_timestamp']));
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

            if ($transaction_type === 'single') {
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
            $x = [];
            foreach ($ors as $o) {
                $x[] = $o['book_id'];
            }
            // $y = array_unique($ors);
            $y = array_unique($x);
            if (count($y) > 1) {
                return json_encode(['isSuccess' => false, 'error' => "bawal lain2 og book number"]);
            }

            if ($transaction_type === 'no ors') {
                $book_id = $_POST['book'];
            } else {
                // $book_id = $y[0]['book_id'];
                $book_id = $y[0];
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
                    $dv->dv_number = $this->getDvNumber($reporting_period, $book_id);
                    $dv->transaction_begin_time = $transaction_timestamp;
                }
                // $dv->raoud_id = intval($raoud_id);
                $dv->nature_of_transaction_id = $nature_of_transaction_id;
                $dv->mrd_classification_id = $mrd_classification_id;
                $dv->reporting_period = $reporting_period;
                $dv->particular = $particular;
                $dv->payee_id = $payee_id;
                $dv->book_id = $book_id;
                $dv->tracking_sheet_id = $tracking_sheet;
                $dv->transaction_type = ucwords($transaction_type);
                if ($dv->validate()) {
                    if ($flag = $dv->save(false)) {
                        if ($transaction_type != 'no ors') {

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
        $latest_dv = Yii::$app->db->createCommand("SELECT substring_index(dv_number, '-', -1) as q 
        from dv_aucs
        WHERE reporting_period LIKE :year
        ORDER BY q DESC  LIMIT 1")
            ->bindValue(':year', $year . '%')
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
                    "raoud_entries.amount as obligated_amount",
                    "dv_aucs.reporting_period",
                    "dv_aucs.particular",
                    "dv_aucs.payee_id",
                    "dv_aucs.mrd_classification_id",
                    "dv_aucs.nature_of_transaction_id",
                    "dv_aucs.reporting_period",
                    "dv_aucs.transaction_type",
                    "dv_aucs.book_id",
                    "dv_aucs.tracking_sheet_id",
                    "process_ors.serial_number",
                    "process_ors.id as ors_id",
                    "FORMAT(total_obligated.total,'N','en-us') as total",
                    "transaction.particular as transaction_particular",
                    "payee.account_name as transaction_payee"
                ])
                ->from("dv_aucs_entries")
                ->join("LEFT JOIN", "dv_aucs", "dv_aucs_entries.dv_aucs_id = dv_aucs.id")
                ->join("LEFT JOIN", "raouds", "dv_aucs_entries.raoud_id = raouds.id")
                ->join("LEFT JOIN", "process_ors", "dv_aucs_entries.process_ors_id = process_ors.id")
                ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")
                ->join("LEFT JOIN", "payee", "transaction.payee_id = payee.id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount)as total,process_ors.id as ors_id
                FROM process_ors,raouds,raoud_entries

                where process_ors.id = raouds.process_ors_id
                AND raouds.id=raoud_entries.raoud_id
                GROUP BY process_ors.id) as total_obligated", "process_ors.id=total_obligated.ors_id")

                ->join("LEFT JOIN", "raoud_entries", "raouds.id = raoud_entries.raoud_id")
                ->join("LEFT JOIN", "chart_of_accounts", "raoud_entries.chart_of_account_id = chart_of_accounts.id")
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

                foreach ($model->dvAccountingEntries as $val) {

                    if ($val->lvl === 2) {
                        $chart_id = (new \yii\db\Query())->select(['sub_accounts1.id'])->from('sub_accounts1')
                            ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                            ->where('sub_accounts1.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
                    } else if ($val->lvl === 3) {
                        $chart_id = (new \yii\db\Query())->select(['sub_accounts2.id'])->from('sub_accounts2')
                            // ->join("LEFT JOIN", 'sub_accounst1', 'sub_accounts2.sub_accounts1_id = sub_accounts1.id')
                            // ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                            ->where('sub_accounts2.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
                    } else {
                        $chart_id =  $val->chart_of_account_id;
                    }
                    $dv_accounting_entries[] = [
                        'dv_aucs_id' => $val->dv_aucs_id,
                        'chart_of_account_id' => $val->chart_of_account_id,
                        'id' => $chart_id,
                        'debit' => $val->debit,
                        'credit' => $val->credit,
                        'net_asset_equity_id' => $val->net_asset_equity_id,
                        'object_code' => $val->object_code,
                        'lvl' => $val->lvl,
                        'cashflow_id' => $val->cashflow_id,
                    ];
                }
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
            $q = Yii::$app->db->createCommand("SELECT EXISTS(SELECT 
            *
            FROM
            cash_disbursement 
            WHERE dv_aucs_id = :dv_id
            AND is_cancelled =1) ")->bindValue(':dv_id', $model->id)
                ->queryScalar();
            if (intval($q) === 1) {
                return json_encode(['isSuccess' => false, 'cancelled' => 'Disbursement is Not Cancelled']);
                die();
            }


            if (!empty($model->dvAucsEntries)) {
                foreach ($model->dvAucsEntries as $val) {
                    if ($val->processOrs->is_cancelled === 1) {
                        return json_encode(['isSuccess' => false, 'cancelled' =>  "{$val->processOrs->serial_number} is not Activated"]);
                    }
                }
            }
            $model->is_cancelled ? $model->is_cancelled = false : $model->is_cancelled = true;
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
        $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];

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
}
