<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\BudgetEntries;
use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\RecordAllotmentEntries;
use Yii;
use app\models\RecordAllotments;
use app\models\RecordAllotmentsSearch;
use app\models\SubAccounts2;
use app\models\Transaction;
use Exception;
use Mpdf\Tag\Em;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecordAllotmentsController implements the CRUD actions for RecordAllotments model.
 */
class RecordAllotmentsController extends Controller
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
     * Lists all RecordAllotments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecordAllotmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RecordAllotments model.
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
     * Creates a new RecordAllotments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RecordAllotments();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => '',
        ]);
    }

    /**
     * Updates an existing RecordAllotments model.
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

        return $this->render('_form_new', [
            'model' => $id,
        ]);
    }

    /**
     * Deletes an existing RecordAllotments model.
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
     * Finds the RecordAllotments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecordAllotments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecordAllotments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateRecordAllotments()
    {
        $date_issued = $_POST['date_issued'];
        $valid_until = $_POST['valid_until'];
        $reporting_period = $_POST['reporting_period'];
        $particulars = $_POST['particular'];
        $fund_cluster_code_id = $_POST['fund_cluster_code_id'];
        $document_recieve_id = $_POST['document_recieve'];
        $financing_source_code_id = $_POST['financing_source_code'];
        $authorization_code_id = $_POST['authorization_code'];
        $mfo_pap_code_id = $_POST['mfo_pap_code'];
        $fund_source_id = $_POST['fund_source'];
        $book_id = $_POST['book'];
        $transaction = \Yii::$app->db->beginTransaction();
        // COUNTER NI SIYA KUN ASA DAPIT NA CHART_OF_ACCOUNT_ID IYA I UPDATE OG E INSERT KUNG MAG DUNGAG KAG ENTRIES
        $x = 0;
        if (!empty($_POST['update_id'])) {
            $recordAllotment = RecordAllotments::findOne(intval($_POST['update_id']));

            foreach ($recordAllotment->recordAllotmentEntries as $val) {

                $chart_id = $_POST['chart_of_account_id'][$x];

                $ra_entries = RecordAllotmentEntries::findOne($val->id);
                $ra_entries->chart_of_account_id = $chart_id;
                $ra_entries->record_allotment_id = $recordAllotment->id;
                $ra_entries->amount = $_POST['amount'][$x];
                if ($ra_entries->save(false)) {
                }
                $x++;
            }
            // foreach ($ra->recordAllotmentEntries as $val) {
            //     $val->delete();
            // }
            // $recordAllotment->id = $ra->id;
            // $ra->delete();
            // return json_encode($_POST['update_id']);

        } else {
            $recordAllotment = new RecordAllotments();
        }
        $serial_number =Yii::$app->memem->getRaoudSerialNumber($reporting_period, $book_id, $_POST['update_id']);
        $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT * FROM `fund_category_and_classification_code` WHERE  {$_POST['fund_classification_code']}>=`fund_category_and_classification_code`.from  and {$_POST['fund_classification_code']} <= `fund_category_and_classification_code`.to LIMIT 1 ")->queryOne();
        $recordAllotment->date_issued = $date_issued;
        $recordAllotment->fund_classification = $_POST['fund_classification_code'];
        $recordAllotment->serial_number = $serial_number;
        $recordAllotment->valid_until = $valid_until;
        $recordAllotment->reporting_period = $reporting_period;
        $recordAllotment->particulars = $particulars;
        $recordAllotment->fund_cluster_code_id = $fund_cluster_code_id;
        $recordAllotment->document_recieve_id = $document_recieve_id;
        $recordAllotment->authorization_code_id = $authorization_code_id;
        $recordAllotment->financing_source_code_id = $financing_source_code_id;
        $recordAllotment->mfo_pap_code_id = $mfo_pap_code_id;
        $recordAllotment->book_id = $book_id;
        $recordAllotment->fund_source_id = $fund_source_id;
        $recordAllotment->fund_category_and_classification_code_id = $fund_category_and_classification_code_id['id'];
        if ($recordAllotment->validate()) {
            try {
                if ($flag = $recordAllotment->save(false)) {
                    for ($x; $x < count($_POST['chart_of_account_id']); $x++) {
                        $chart_id = $_POST['chart_of_account_id'][$x];

                        $ra_entries = new RecordAllotmentEntries();
                        $ra_entries->chart_of_account_id = $chart_id;
                        $ra_entries->record_allotment_id = $recordAllotment->id;
                        $ra_entries->amount = $_POST['amount'][$x];
                        if ($ra_entries->validate()) {
                            if ($ra_entries->save()) {
                                $raoud = new Raouds();
                                // $raoud->record_allotment_id = $recordAllotment->id;
                                $raoud->serial_number = $recordAllotment->serial_number;
                                $raoud->reporting_period = $reporting_period;
                                $raoud->record_allotment_entries_id = $ra_entries->id;

                                if ($raoud->validate()) {   

                                    if ($raoud->save(false)) {
                                        $raoudEntry = new RaoudEntries();
                                        $raoudEntry->chart_of_account_id = $chart_id;
                                        $raoudEntry->raoud_id = $raoud->id;
                                        $raoudEntry->amount = $_POST['amount'][$x];
                                        if ($raoudEntry->validate()) {
                                            if ($raoudEntry->save(false)) {
                                            } else echo 'qwe';
                                        }
                                        // else{
                                        //   $raoudEntry->errors;
                                        // }
                                    }
                                } else {
                                    return  json_encode(['isSuccess' => false, 'error' => $raoud->errors]);
                                }
                            }
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return json_encode(['isSuccess' => true, 'view_id' => $recordAllotment->id]);
                    return $this->render('view', [
                        'model' => $this->findModel($recordAllotment->id),
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();


                return json_encode(['isSuccess' => false, 'error' => $e]);
            }
        } else {
            return  json_encode(['isSuccess' => false, 'error' => $recordAllotment->errors]);
        }
    }

    public function actionUpdateRecordAllotment()
    {

        if ($_POST) {
            $record_allotment_id = $_POST['update_id'];
            // $query = (new \yii\db\Query())->select('*')
            //     ->from('record_allotments')
            //     ->join('LEFT JOIN', 'record_allotment_entries', 'record_allotments.id=record_allotment_entries.record_allotment_id')
            //     ->where("record_allotments.id = :id", ['id' => $record_allotment_id])
            //     ->one();
            // $query = RecordAllotments::find()->where("id=:id", ['id' => 120]);

            $model = RecordAllotments::findOne($record_allotment_id);
            $record_allotment = [
                'date_issued' => $model->date_issued,
                'document_recieve_id' => $model->document_recieve_id,
                'fund_cluster_code_id' => $model->fund_cluster_code_id,
                'financing_source_code_id' => $model->financing_source_code_id,
                'fund_category_and_classification_code_id' => $model->fund_category_and_classification_code_id,
                'authorization_code_id' => $model->authorization_code_id,
                'mfo_pap_code_id' => $model->mfo_pap_code_id,
                'fund_source_id' => $model->fund_source_id,
                'reporting_period' => $model->reporting_period,
                'serial_number' => $model->serial_number,
                'allotment_number' => $model->allotment_number,
                'valid_until' => $model->valid_until,
                'particulars' => $model->particulars,
                'fund_classification' => $model->fund_classification,
                'book_id'=>$model->book_id,
            ];
            $record_allotment_entries = [];
            foreach ($model->recordAllotmentEntries as $val) {

                $record_allotment_entries[] = [
                    'chart_of_account_id' => $val->chart_of_account_id,
                    'amount' => $val->amount,
                    'object_code' => $val->object_code,
                    'lvl' => $val->lvl,
                ];
            }
            // echo "<pre>";
            // var_dump($record_allotment_entries);
            // echo "</pre>";   
            return  json_encode(['record_allotments' => $record_allotment, 'record_allotment_entries' => $record_allotment_entries]);
        }
    }

    public function actionQwe()
    {

        return Yii::$app->memem->hello('heelo');
         
    }
    // public function getSerialNumber($reporting_period, $book_id, $update_id)
    // {


    //     $book_name = Books::findOne($book_id);

    //     // $q = RecordAllotments::find()
    //     // ->orderBy(['id' => SORT_DESC])
    //     // ->one();

    //     // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
    //     $f = (new \yii\db\Query())
    //         ->select('serial_number')
    //         ->from('record_allotments');
    //         !empty($update_id) ? $f->where("id =:id", ['id' => $update_id]) : $f->orderBy("id DESC");
    //     $q = $f->one();


    //     if (!empty($q)) {
    //         $x = explode('-', $q['serial_number']);
    //         $y = 1;
    //         if (!empty($update_id)) {
    //             $y = 0;
    //         }
    //         $last_number = $x[3] + $y;
    //     } else {
    //         $last_number = 1;
    //     }

    //     $serial_number = $book_name->name . '-' . $reporting_period . '-' . $last_number;
    //     return  $serial_number;
    // }
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
            // $excel->setActiveSheetIndexByName('Chart of Accounts - Final');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;
            // 
            $number_container =[];

            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    $cells[] =   $cell->getValue();
                }
                $group_number=$cells[0];
                $date_issued = $cells[0];
                $valid_until = $cells[1];
                $reporting_period = $cells[2];
                $fund_cluster_code_name = $cells[3];
                $document_recieve_name = $cells[4];
                $financing_source_code_name = $cells[5];
                $authorization_code_name = $cells[6];
                $mfo_pap_code_name = $cells[7];
                $fund_source_name = $cells[7];
                $fund_classification_code = $cells[7];
                $book_name = $cells[7];
                $amount = $cells[7];
                $chart_name = $cells[7];
                // $name = $cells[1];
                $chart = (new \yii\db\Query())
                ->select('id')
                ->from('chart_of_accounts')
                ->where('general_ledger :general_ledger',['general_ledger'=>$chart_name])
                ->one();

                if (
                    empty($trackidate_issuedng_number)
                ) {
                    return json_encode(['error' => "Error Somthing is Missing in Line $key"]);
                    break;
                } else {

                    $exist= array_search($group_number,array_column($number_container,'id'));
                    if (!$exist){
                        $recordAllotment = new RecordAllotments();
                        // $serial_number = $this->getSerialNumber($reporting_period, $book_id, $_POST['update_id']);
                        $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT * FROM `fund_category_and_classification_code` WHERE  {$_POST['fund_classification_code']}>=`fund_category_and_classification_code`.from  and {$_POST['fund_classification_code']} <= `fund_category_and_classification_code`.to LIMIT 1 ")->queryOne();
                        $recordAllotment->date_issued = $date_issued;
                        $recordAllotment->fund_classification = $_POST['fund_classification_code'];
                        // $recordAllotment->serial_number = $serial_number;
                        $recordAllotment->valid_until = $valid_until;
                        $recordAllotment->reporting_period = $reporting_period;
                        // $recordAllotment->particulars = $particular;
                        // $recordAllotment->fund_cluster_code_id = $fund_cluster_code_id;
                        // $recordAllotment->document_recieve_id = $document_recieve_id;
                        // $recordAllotment->authorization_code_id = $authorization_code_id;
                        // $recordAllotment->financing_source_code_id = $financing_source_code_id;
                        // $recordAllotment->mfo_pap_code_id = $mfo_pap_code_id;
                        // $recordAllotment->book_id = $book_id;
                        // $recordAllotment->fund_source_id = $fund_source_id;
                        // $recordAllotment->fund_category_and_classification_code_id = $fund_category_and_classification_code_id['id'];
                        if ($recordAllotment->save(false)){

                            $record_allotment_id= $recordAllotment->id;
                            $number_container[] =  ['id' =>  $recordAllotment->id, 'no' => $group_number];
                        }
                        

                    }
                    else{
                        $record_allotment_id = $number_container[$exist]['id'];
                    }
                    $data[]=[
                        
                        'chart_of_account_id' => $chart['id'],
                        'record_allotment_id' => $record_allotment_id,
                        'amount' => $amount,
                    ];
                    // $payee  = (new \yii\db\Query())
                    // ->select(['account_name','id'])
                    // ->from('payee')
                    // ->where('account_name LIKE :account_name',['account_name'=>$payee_name])
                    // ->one();
                    // $responsibility_center  = (new \yii\db\Query())
                    // ->select(['name','id'])
                    // ->from('responsibility_center')
                    // ->where('name LIKE :name',['name'=>$responsibility_center_name])
                    // ->one();
                    // $responsibility_center = ResponsibilityCenter::find()->where('like', 'name', $responsibility_center_name)->one();
                    // if (!empty($payee)) {
                    //     $payee_id = $payee['id'];
                    // } else {
                    //     return json_encode(['error' => "Payee Does Not Exist in line $key"]);
                    //     break;
                    // }
                    // if (!empty($responsibility_center)) {
                    //     $responsibility_center_id = $responsibility_center['id'];
                    // } else {
                    //     return json_encode(['error' => "Responsibility Center Does Not Exist in Line $key"]);
                    //     break;
                    // }
                    
                    // $data[] = [
                    //     'responsibility_center_id' => $responsibility_center_id,
                    //     'payee_id' => $payee_id,
                    //     'particular' => $particular,
                    //     'gross_amount' => $amount,
                    //     'tracking_number' => $tracking_number,
                    //     'earmark_no' => $earmark_number,
                    //     'payroll_number' => $payroll_number,
                    //     'transaction_date' => $date
                    // ];
                }
            }

            $column = [
                'responsibility_center_id',
                'payee_id',
                'particular',
                'gross_amount',
                'tracking_number',
                'earmark_no',
                'payroll_number',
                'transaction_date',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('transaction', $column, $data)->execute();

            // return $this->redirect(['index']);
            return json_encode(['error'=>$data]);
            // ob_clean();
            // echo "<pre>";
            // var_dump($payee);
            // echo "<pre>";
            // return ob_get_clean();
        }
    }

}
