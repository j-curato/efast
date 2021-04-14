<?php

namespace frontend\controllers;

use Yii;
use app\models\DvAucs;
use app\models\DvAucsEntries;
use app\models\DvAucsSearch;
use app\models\ProcessOrs;
use app\models\Raouds;
use app\models\Raouds2Search;
use app\models\RaoudsSearchForProcessOrsSearch;
use ErrorException;
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
        $searchModel = new RaoudsSearchForProcessOrsSearch();
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
        $searchModel = new RaoudsSearchForProcessOrsSearch();
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

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
        $transaction_type = $_POST['transaction_type'];
        $selected_dv = $_POST['selection'];
        $dv_length = count($selected_dv);
        if (strtolower($transaction_type) === 'single') {

            if (intval($_POST['dv_count']) > 1) {
                return json_encode(["error" => "Transaction type is Single"]);
                die();
            }
            // return json_encode(["error" => "qwe"]);
            // die();
        } else if (empty($transaction_type)) {
            return json_encode(["error" => "Select Transaction Type"]);
            die();
        }
        foreach ($selected_dv as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    'mfo_pap_code.code AS mfo_pap_code_code', 'mfo_pap_code.name AS mfo_pap_name', 'fund_source.name AS fund_source_name',
                    'chart_of_accounts.uacs as object_code', 'chart_of_accounts.general_ledger', 'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id', 'raouds.id AS raoud_id',
                    'raouds.obligated_amount', 'record_allotments.particulars',
                    'transaction.payee_id as payee_id','raouds.process_ors_id',
                    'entry.total', 'record_allotment_entries.amount', '(record_allotment_entries.amount - entry.total) AS remain'
                ])
                ->from('raouds')
                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id=record_allotments.id")
                ->join("LEFT JOIN", "chart_of_accounts", "record_allotment_entries.chart_of_account_id=chart_of_accounts.id")
                ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
                ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                ->join("LEFT JOIN", "process_ors", "raouds.process_ors_id=process_ors.id")
                ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                raouds.id , raouds.process_ors_id,
                raouds.record_allotment_entries_id
                FROM raouds,raoud_entries,process_ors
                WHERE raouds.process_ors_id= process_ors.id
                AND raouds.id = raoud_entries.raoud_id
                AND raouds.process_ors_id IS NOT NULL 
                GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")
                ->where("raouds.id = :id", ['id' => $val])->one();
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
        return json_encode(['results' => $x]);
    }
    public function actionInsertDv()
    {


        if ($_POST) {
            $raoud_id = $_POST['raoud_id'];
            $nature_of_transaction_id = $_POST['nature_of_transaction'];
            $mrd_classification_id = $_POST['mrd_classification'];
            $reporting_period = $_POST['reporting_period'];
            $particular = $_POST['particular'];
            $payee_id = $_POST['payee'];


            $transaction = Yii::$app->db->beginTransaction();


            try {
                if (!empty($_POST['update_id'])) {
                    $dv = DvAucs::findOne($_POST['update_id']);
                    foreach ($dv->dvAucsEntries as $val) {
                        $val->delete();
                    }
                } else {

                    $dv = new DvAucs();
                    $dv->dv_number = $this->getDvNumber($reporting_period);
                }
                // $dv->raoud_id = intval($raoud_id);
                $dv->nature_of_transaction_id = $nature_of_transaction_id;
                $dv->mrd_classification_id = $mrd_classification_id;
                $dv->reporting_period = $reporting_period;
                $dv->particular = $particular;
                $dv->payee_id = $payee_id;
                // $dv->one_percent_ewt = $one_percent_ewt;
                // $dv->two_percent_ewt = $two_percent_ewt;
                // $dv->five_percent_ewt = $five_percent_ewt;
                // $dv->three_percent_ft = $three_percent_ft;
                // $dv->five_percent_ft = $five_percent_ft;
                // $dv->dv_number = $this->getDvNumber($reporting_period);

                if ($dv->validate()) {
                    if ($flag = $dv->save(false)) {
                        foreach ($_POST['raoud_id'] as $key => $val) {
                            $dv_entries = new DvAucsEntries();
                            $dv_entries->raoud_id = $val;
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
                    }
                } else {
                    return json_encode(['error' => $dv->errors]);
                }
                if ($flag) {

                    $transaction->commit();
                    // return $this->redirect(['view', 'id' => $model->id]);
                    return json_encode(['isSuccess' => 'success', 'id' => $dv->id]);
                }
            } catch (ErrorException $error) {

                $transaction->rollBack();

                return json_encode($error);
            }
        }
    }

    public function getDvNumber($reporting_period)
    {
        $latest_dv = (new \yii\db\Query())
            ->select('dv_number')
            ->from('dv_aucs')
            ->orderBy('id DESC')
            ->one();
        $dv_number = $reporting_period;

        if (!empty($latest_dv)) {
            $last_number = explode('-', $latest_dv['dv_number'])[2] + 1;
        } else {
            $last_number = 1;
        }
        $x = '';
        for ($i = strlen($last_number); $i < 4; $i++) {
            $x .= 0;
        }
        $dv_number .= '-' . $x . $last_number;

        // echo "<pre>";
        // var_dump($dv_number)
        // echo "</pre>";
        return $dv_number;
    }
    public function actionUpdateDv()
    {
        if (!empty($_POST)) {
            $dv_id = $_POST['dv_id'];


            $query = (new \yii\db\Query())
                ->select([
                    "dv_aucs_entries.*", 'chart_of_accounts.uacs as object_code', "chart_of_Accounts.general_ledger",
                    "raoud_entries.amount as obligated_amount", "dv_aucs.reporting_period", "dv_aucs.particular",
                    "dv_aucs.payee_id", "dv_aucs.mrd_classification_id", "dv_aucs.nature_of_transaction_id",
                    "dv_aucs.reporting_period","process_ors.serial_number","total_amount.total_ors"

                ])
                ->from("dv_aucs_entries")
                ->join("LEFT JOIN", "dv_aucs", "dv_aucs_entries.dv_aucs_id = dv_aucs.id")
                ->join("LEFT JOIN", "raouds", "dv_aucs_entries.raoud_id = raouds.id")
                ->join("LEFT JOIN", "process_ors", "dv_aucs_entries.process_ors_id = process_ors.id")
                ->join("LEFT JOIN","(SELECT SUM(raoud_entries.amount)as total_ors,
                process_ors.id as ors_id FROM process_ors,raouds,raoud_entries

                where process_ors.id = raouds.process_ors_id
                AND raouds.id=raoud_entries.raoud_id
           
                GROUP BY process_ors.id) as total_amount","total_amount.ors_id=dv_aucs_entries.process_ors_id")

                ->join("LEFT JOIN", "raoud_entries", "raouds.id = raoud_entries.raoud_id")
                ->join("LEFT JOIN", "chart_of_accounts", "raoud_entries.chart_of_account_id = chart_of_accounts.id")
                ->where("dv_aucs_entries.dv_aucs_id =:dv_aucs_id", ["dv_aucs_id" => $dv_id])
                ->all();
            return json_encode(["result" => $query]);
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
                $flag="";
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
                        || $y === 9
                        || $y === 10
                        || $y === 11

                    ) {
                        $cells[] = $cell->getCalculatedValue();
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
                $mrd_classification_name = $cells[13];



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
                        return json_encode(["error" => "$key $payee_name"]);
                    }
                    if (empty($nature_of_transaction)) {
                        return json_encode(["error" => "$key $nature_of_transaction_name"]);
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
                        $dv->dv_number = $this->getDvNumber($reporting_period);
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
                return json_encode(['isSuccess' => 'success', ]);
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
}
