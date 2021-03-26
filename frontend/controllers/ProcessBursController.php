<?php

namespace frontend\controllers;

use Yii;
use app\models\ProcessBurs;
use app\models\ProcessBursSearch;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\Raouds2Search;
use ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessBursController implements the CRUD actions for ProcessBurs model.
 */
class ProcessBursController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class ,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ProcessBurs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessBursSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProcessBurs model.
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
     * Creates a new ProcessBurs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new ProcessBurs();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        $searchModel = new Raouds2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
        ]);
    }

    /**
     * Updates an existing ProcessBurs model.
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
        $searchModel = new Raouds2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id
        ]);
    }

    /**
     * Deletes an existing ProcessBurs model.
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
     * Finds the ProcessBurs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessBurs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProcessBurs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetRaoud()
    {
        $x = [];
        foreach ($_POST['selection'] as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    'mfo_pap_code.code AS mfo_pap_code_code', 'mfo_pap_code.name AS mfo_pap_name', 'fund_source.name AS fund_source_name',
                    'chart_of_accounts.uacs as object_code', 'chart_of_accounts.general_ledger', 'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id', 'raouds.id AS raoud_id',
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
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                raouds.id, raouds.process_ors_id,
                raouds.record_allotment_entries_id
                FROM raouds,raoud_entries,process_ors
                WHERE raouds.process_ors_id= process_ors.id
                AND raouds.id = raoud_entries.raoud_id
                AND raouds.process_ors_id IS NOT NULL 
                GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")

                ->where("raouds.id = :id", ['id' => $val])->one();
            $query['obligation_amount'] =  $_POST['amount'][$val];
            $x[] = $query;
        }

        // return json_encode($_POST['selection']);
        // $query=Yii::$app->db->createCommand("SELECT * FROM raouds where id IN ('1','2')")->queryAll();

        return json_encode(['results' => $x]);
    }

    public function actionInsertProcessBurs()
    {
        // return json_encode($_POST['reporting_period']);
        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];
            // return json_encode($_POST['chart_of_account_id']);
            $transaction = \Yii::$app->db->beginTransaction();
            // KUNG NAAY SULOD ANG UPDATE ID  MAG ADD OG RAOUD OG ENTRY NIYA  PARA E ADJUST
            if ($_POST['update_id'] > 0) {
                try {
                    // KUHAON ANG PARENT NA RAOUD NA E ADJUST
                    $raoud_to_adjust = Raouds::find()->where("id =:id", ['id' => $_POST['update_id']])->one();
                    // $ors_id = ::find($_POST['update_id'])->recordAllotment->id;
                   
                   
                    $total_amount= array_sum($_POST['obligation_amount']);
                    return json_encode(['error'=>$raoud_to_adjust->id]);
                    if ($raoud_to_adjust->raoudEntries->amount >$total_amount){
                        
                    $raoud_to_adjust->isActive = false;
                    $raoud_to_adjust->save();

                    // echo $reporting_period;
                    foreach ($_POST['chart_of_account_id'] as $index => $value) {
                        // KANI MAO NI ANG RAOUDS KUNG ASA E CHARGE ANG GE ADJUST
                        $raoud_to_charge_adjustment = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                        $raoud_to_charge_adjustment->isActive = 0;
                        $raoud_to_charge_adjustment->save();

                        for ($i = 0; $i < 2; $i++) {
                            $raoud = new Raouds();
                            // $raoud->record_allotment_id = $raoud_to_charge_adjustment->record_allotment_id;
                            $amount = 0;
                            if ($i === 0) {
                                $amount = -$_POST['burs_amount'][$index];
                                $chart_of_account_id= $raoud_to_adjust->raoudEntries->chart_of_account_id;
                                $record_allotment_entries_id=$raoud_to_adjust->record_allotment_entries_id;
                            } else {
                                $amount = $_POST['burs_amount'][$index];
                                $chart_of_account_id=$value;
                                $record_allotment_entries_id=$raoud_to_charge_adjustment->record_allotment_entries_id;
                            }
                            $raoud->record_allotment_entries_id = $record_allotment_entries_id;
                            $raoud->is_parent = false;
                            $raoud->isActive = $i;
                            $raoud->process_burs_id = $raoud_to_adjust->process_burs_id;
                            $raoud->reporting_period = $_POST['reporting_period'];
                            $raoud->burs_amount = $amount;
                            if ($flag = $raoud->validate()) {
                                if ($raoud->save()) {
                                    
                                    $raoud_entry = new RaoudEntries();
                                    $raoud_entry->raoud_id = $raoud->id;
                                    $raoud_entry->chart_of_account_id = $chart_of_account_id;
                                    $raoud_entry->amount = $amount;
                                    $raoud_entry->parent_id_from_raoud = $raoud_to_adjust->id;

                                    if ($raoud_entry->validate()) {
                                        if ($raoud_entry->save()) {
                                        }
                                    } else {
                                        $transaction->rollBack();
                                        return json_encode(['error' => 'yawa sa raoud entry']);
                                    }
                                }
                                else{
                                    return json_encode(['error'=>'wla na save']);
                                }
                            } else {

                                $transaction->rollBack();
                                return json_encode(["error" => 'yawa sa raoud']);
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return json_encode(['error' => 'walay error di success']);
                    }
                    

                }
                else{
                    return json_encode(['error'=>' amount is greater than  burs amount']);
                }



                    
                } catch (ErrorException $e) {
                    // return json_encode(["error" => $e]);
                    throw new ErrorException($e);
                }
            } 
            // KUNG WLAY SULOD ANG UPDATE_ID DRI MO SULOD MAG BUHAT OG BAG.O NA DATA
            else {
                try {
                    $burs = new ProcessBurs();
                    $burs->reporting_period = $reporting_period;
                    if ($burs->validate()) {

                        if ($flag = $burs->save()) {
                            // echo $reporting_period;
                            foreach ($_POST['chart_of_account_id'] as $index => $value) {

                                $q = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                                $q->isActive = 0;
                                $q->save(); 
                                $raoud = new Raouds();
                                // $raoud->record_allotment_id = $q->record_allotment_id;
                                $raoud->record_allotment_entries_id = $q->record_allotment_entries_id;
                                $raoud->is_parent = false;
                                $raoud->process_burs_id = $burs->id;
                                $raoud->reporting_period = $burs->reporting_period;
                                $raoud->burs_amount = $_POST['burs_amount'][$index];
                                if ($raoud->validate()) {
                                    if ($raoud->save()) {
                                        $raoud_entry = new RaoudEntries();
                                        $raoud_entry->raoud_id = $raoud->id;
                                        $raoud_entry->chart_of_account_id = $value;
                                        $raoud_entry->amount = $_POST['burs_amount'][$index];
                                        if ($raoud_entry->validate()) {
                                            if ($raoud_entry->save()) {
                                            }
                                        } else {
                                            $transaction->rollBack();
                                            return json_encode(['error' => 'yawa sa raoud entry']);
                                        }
                                    }
                                } else {

                                    $transaction->rollBack();
                                    return json_encode(["error" => $raoud->obligated_amount]);
                                }


                                // $ors_entry = new ProcessOrsEntries();
                                // $ors_entry->chart_of_account_id = $value;
                                // $ors_entry->process_ors_id = $ors->id;
                                // $ors_entry->amount = $_POST['burs_amount'][$index];
                                // if ($ors_entry->validate()) {

                                //     if ($ors_entry->save()) {
                                //     }
                                // } else {
                                //     $transaction->rollBack();
                                //     return json_encode(["error" => 'yawa sa ors entry']);
                                // }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return json_encode(['error' => 'walay error di success']);
                        }
                    } else {
                        return json_encode(['error' => $burs->error]);
                    }
                } catch (ErrorException $e) {
                    return json_encode(["error" => $e]);
                }
            }

            // $ors->reporting_period = $reporting_period

        }
    }
}
