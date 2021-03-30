<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\BudgetEntries;
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

        $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT * FROM `fund_category_and_classification_code` WHERE  {$_POST['fund_classification_code']}>=`fund_category_and_classification_code`.from  and {$_POST['fund_classification_code']} <= `fund_category_and_classification_code`.to LIMIT 1 ")->queryOne();
        $recordAllotment->date_issued = $date_issued;
        $recordAllotment->fund_classification = $_POST['fund_classification_code'];
        $recordAllotment->serial_number = $this->getSerialNumber($reporting_period, $book_id, $_POST['update_id']);
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
                                $raoud->serial_number = "$x";
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

        $book = Books::find()->where("id =:id", ['id' => 5])->one();
        $jev_number = "GJ-Fund 01-2021-01-0018";
        $q = explode('-', $jev_number);
        $jev_number_serial = $q[4];
        $jev_referenece = $q[0];
        $jev_book = $q[1];
        $q = '';
        if ($book->name === $jev_book) {
            $q = 'qqq';
        }

        echo "<pre>";
        var_dump($q);
        echo "</pre>";
    }
    public function getSerialNumber($reporting_period, $book_id, $update_id)
    {


        $book_name = Books::findOne($book_id);

        // $q = RecordAllotments::find()
        // ->orderBy(['id' => SORT_DESC])
        // ->one();

        // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
        $f = (new \yii\db\Query())
            ->select('serial_number')
            ->from('record_allotments');
            !empty($update_id) ? $f->where("id =:id", ['id' => $update_id]) : $f->orderBy("id DESC");
        $q = $f->one();


        if (!empty($q)) {
            $x = explode('-', $q['serial_number']);
            $y = 1;
            if (!empty($update_id)) {
                $y = 0;
            }
            $last_number = $x[3] + $y;
        } else {
            $last_number = 1;
        }

        $serial_number = $book_name->name . '-' . $reporting_period . '-' . $last_number;
        return  $serial_number;
    }
}
