<?php

namespace frontend\controllers;

use app\models\BudgetEntries;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\RecordAllotmentEntries;
use Yii;
use app\models\RecordAllotments;
use app\models\RecordAllotmentsSearch;
use app\models\SubAccounts2;
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
        $transaction = \Yii::$app->db->beginTransaction();
        $recordAllotment = new RecordAllotments();
        if (!empty($_POST['update_id'])){
            $ra = RecordAllotments::findOne(intval($_POST['update_id']));
            foreach($ra->recordAllotmentEntries as $val){
                $val->delete();
            }
            $recordAllotment->id=$ra->id;
            $ra->delete();
            // return json_encode($_POST['update_id']);
            
        }

        $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT * FROM `fund_category_and_classification_code` WHERE  {$_POST['fund_classification_code']}>=`fund_category_and_classification_code`.from  and {$_POST['fund_classification_code']} <= `fund_category_and_classification_code`.to LIMIT 1 ")->queryOne();
        //return  json_encode($fund_category_and_classification_code_id['id']);
        $recordAllotment->date_issued = $date_issued;
        $recordAllotment->fund_classification = $_POST['fund_classification_code'];
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
                        $chart_id = 0;
                        if ($y[2] == 2) {
                            $chart_id = (new \yii\db\Query())->select(['chart_of_accounts.id'])->from('sub_accounts1')
                                ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                                ->where('sub_accounts1.id =:id', ['id' => intval($y[0])])->one()['id'];
                        } else if ($y[2] == 3) {
                            // $chart_id = (new \yii\db\Query())->select(['chart_of_accounts.id'])->from('sub_accounts1')
                            //     ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                            //     ->where('sub_accounts1.id =:id', ['id' => intval($y[0])])->one()['id'];
                                $chart_id = SubAccounts2::findOne(intval($y[0]))->subAccounts1->chart_of_account_id;
                        } else {
                            $chart_id = $y[0];
                        }
                        $ra_entries = new RecordAllotmentEntries();
                        $ra_entries->chart_of_account_id = $chart_id;
                        $ra_entries->lvl = $y[2];
                        $ra_entries->object_code = $y[1];
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
                                        $raoudEntry->chart_of_account_id = $chart_id;
                                        $raoudEntry->lvl = $y[2];
                                        $raoudEntry->object_code = $y[1];
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
                return json_encode(['yawas', $e]);
            }
        } else {
            return  json_encode($recordAllotment->errors);
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
            ];
            $record_allotment_entries = [];
            foreach ($model->recordAllotmentEntries as $val) {
                if ($val->lvl === 2) {
                    $chart_id = (new \yii\db\Query())->select(['sub_accounts1.id'])->from('sub_accounts1')
                        ->join("LEFT JOIN", 'chart_of_accounts', 'sub_accounts1.chart_of_account_id = chart_of_accounts.id')
                        ->where('sub_accounts1.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
                } else if ($val->lvl === 3) {
                    $chart_id = (new \yii\db\Query())->select(['sub_accounts2.id'])->from('sub_accounts2')
                        ->where('sub_accounts2.object_code =:object_code', ['object_code' => $val->object_code])->one()['id'];
                } else {
                    $chart_id =  $val->chart_of_account_id;
                }

                $record_allotment_entries[] = [
                    'chart_of_account_id' => $val->chart_of_account_id,
                    'amount' => $val->amount,
                    'object_code' => $val->object_code,
                    'lvl' => $val->lvl,
                    'id' => $chart_id
                ];
            }
            // echo "<pre>";
            // var_dump($record_allotment_entries);
            // echo "</pre>";   
            return  json_encode(['record_allotments' => $record_allotment, 'record_allotment_entries' => $record_allotment_entries]);
        }
    }
}
