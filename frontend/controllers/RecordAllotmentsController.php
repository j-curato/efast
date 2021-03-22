<?php

namespace frontend\controllers;

use app\models\BudgetEntries;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\RecordAllotmentEntries;
use Yii;
use app\models\RecordAllotments;
use app\models\RecordAllotmentsSearch;
use app\models\Transaction;
use Exception;
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

        return $this->render('index2', [
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
            'model' => $model,
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

        return $this->render('update', [
            'model' => $model,
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

        $transaction = \Yii::$app->db->beginTransaction();
        $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT * FROM `fund_category_and_classification_code` WHERE  {$_POST['fund_classification_code']}>=`fund_category_and_classification_code`.from  and {$_POST['fund_classification_code']} <= `fund_category_and_classification_code`.to LIMIT 1 ")->queryOne();
        //    return  json_encode($fund_category_and_classification_code_id['id']);
        $recordAllotment = new RecordAllotments();
        $recordAllotment->date_issued = $date_issued;
        $recordAllotment->serial_number = '123';
        $recordAllotment->valid_until = $valid_until;
        $recordAllotment->reporting_period = $reporting_period;
        $recordAllotment->particulars = $particulars;
        $recordAllotment->fund_cluster_code_id = $fund_cluster_code_id;
        $recordAllotment->document_recieve_id = $document_recieve_id;
        $recordAllotment->authorization_code_id = $authorization_code_id;
        $recordAllotment->financing_source_code_id = $financing_source_code_id;
        $recordAllotment->mfo_pap_code_id = $mfo_pap_code_id;
        $recordAllotment->fund_source_id = $fund_source_id;
        $recordAllotment->fund_category_and_classification_code_id = $fund_category_and_classification_code_id['id'];
        // echo $fund_category_and_classification_code_id['id'];3
        // echo json_encode($_POST['chart_of_account_id'] );
        if ($recordAllotment->validate()) {
            try {
                if ($flag = $recordAllotment->save(false)) {
                    for ($x = 0; $x < count($_POST['chart_of_account_id']); $x++) {
                        $y = explode('-', $_POST['chart_of_account_id'][$x]);
                        $ra_entries = new RecordAllotmentEntries();
                        $ra_entries->chart_of_account_id = $y[0];
                        $ra_entries->record_allotment_id = $recordAllotment->id;
                        $ra_entries->amount = $_POST['amount'][$x];
                        if ($ra_entries->validate()) {
                            if ($ra_entries->save()) {
                                $y = explode('-', $_POST['chart_of_account_id'][$x]);
                                $raoud = new Raouds();
                                $raoud->record_allotment_id = $recordAllotment->id;
                                $raoud->serial_number = "$x";
                                $raoud->reporting_period = $reporting_period;
                                $raoud->record_allotment_entries_id = $ra_entries->id;

                                if ($raoud->validate()) {

                                    if ($raoud->save(false)) {
                                        $raoudEntry = new RaoudEntries();
                                        $raoudEntry->chart_of_account_id = $y[0];
                                        $raoudEntry->raoud_id = $raoud->id;
                                        $raoudEntry->amount = $_POST['amount'][$x];
                                        if ($raoudEntry->validate()) {
                                            if ($raoudEntry->save(false)) {
                                                echo $raoudEntry->id;
                                            } else echo 'qwe';
                                        }
                                        // else{
                                        //   $raoudEntry->errors;
                                        // }
                                    }
                                } else {
                                    return  json_encode($raoud->errors);
                                }
                            }
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return json_encode(["success"]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return json_encode(['yawas']);
            }
        } else {
            return  json_encode($recordAllotment->errors);
        }
    }
}
