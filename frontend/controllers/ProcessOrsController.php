<?php

namespace frontend\controllers;

use Yii;
use app\models\ProcessOrs;
use app\models\ProccessOrsSearch;
use app\models\ProcessOrsEntries;
use app\models\ProcessOrsEntriesSearch;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\RaoudsSearch;
use yii\db\ForeignKeyConstraint;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessOrsController implements the CRUD actions for ProcessOrs model.
 */
class ProcessOrsController extends Controller
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
     * Lists all ProcessOrs models.
     * @return mixed
     */
    public function actionIndex()
    {
        // $searchModel = new ProccessOrsSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel = new ProcessOrsEntriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProcessOrs model.
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
     * Creates a new ProcessOrs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new ProcessOrs();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
        $searchModel = new RaoudsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing ProcessOrs model.
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
     * Deletes an existing ProcessOrs model.
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
     * Finds the ProcessOrs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessOrs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProcessOrs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSample()
    {
        $x = [];
        foreach ($_POST['selection'] as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    'mfo_pap_code.code', 'mfo_pap_code.name', 'fund_source.name',
                    'chart_of_accounts.uacs', 'chart_of_accounts.general_ledger', 'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id', 'raouds.id AS raoud_id',
                    'entry.total', 'record_allotment_entries.amount', '(record_allotment_entries.amount - entry.total) AS remain'
                ])
                ->from('raouds')
                ->join("LEFT JOIN", "record_allotments", "raouds.record_allotment_id=record_allotments.id")
                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                ->join("LEFT JOIN", "chart_of_accounts", "record_allotment_entries.chart_of_account_id=chart_of_accounts.id")
                ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=record_allotments.id")
                ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                raouds.id, raouds.record_allotment_id,raouds.process_ors_id,
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

    public function actionInsertProcessOrs()
    {
        // return json_encode($_POST['reporting_period']);

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];

            $ors = new ProcessOrs();
            $ors->reporting_period = $reporting_period;
            if ($ors->save()) {


                // return json_encode($q);
                // $raoud = new Raouds();
                
                foreach ($_POST['chart_of_account_id'] as $index => $value) {

                    $q = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                    $raoud = new Raouds();
                    $raoud->record_allotment_id = $q->record_allotment_id;
                    $raoud->process_ors_id = $ors->id;
                    $raoud->reporting_period = $ors->reporting_period;
                    $raoud->obligated_amount = $_POST['final_amount'][$index];

                    if ($raoud->save()) {
                        $raoud_entry = new RaoudEntries();
                        $raoud_entry->raoud_id = $raoud->id;
                        $raoud_entry->chart_of_account_id = $value;
                        $raoud_entry->amount = $_POST['final_amount'][$index];
                        if ($raoud_entry->save()) {
                            echo $raoud->id;
                        }
                    }

                    $ors_entry = new ProcessOrsEntries();
                    $ors_entry->chart_of_account_id = $value;
                    $ors_entry->process_ors_id = $ors->id;
                    $ors_entry->amount = $_POST['final_amount'][$index];
                    if ($ors_entry->save()) {
                    }
                }
            }

            // $ors->reporting_period = $reporting_period

        }
    }
    public function actionQwe()
    {
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('raouds')
            ->where("id= :id", ['id' => 44])
            ->one();
        return $query['id'];
    }
}
