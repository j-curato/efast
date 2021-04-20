<?php

namespace frontend\controllers;

use Yii;
use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashDisbursemenController implements the CRUD actions for CashDisbursement model.
 */
class CashDisbursemenController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CashDisbursement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashDisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
            $excel->setActiveSheetIndexByName('Import DV AUCS (2)');
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
                    // if (
                    //     $y === 7
                    //     || $y === 8
                    //     // || $y === 9
                    //     || $y === 10
                    //     // || $y === 11

                    // ) {
                    //     $cells[] = $cell->getCalculatedValue();
                    // }
                    // else if ($y===4 || $y===5){
                    //     $cells[]=$cell->getFormattedValue();
                    // }
          
                    // else {
                        $cells[] =   $cell->getValue();
                    // }
                    $y++;
                }
                $book_name = trim($cells[1]);
                $reporting_period = date("Y-m", strtotime($cells[2]));
                $mode_of_payment = $cells[3];
                $check_ada_number = strtoupper(trim($cells[4]));
                $good_canceled = $cells[5];
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
}
