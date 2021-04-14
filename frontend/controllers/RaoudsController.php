<?php

namespace frontend\controllers;

use Yii;
use app\models\Raouds;
use app\models\RaoudsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RaoudsController implements the CRUD actions for Raouds model.
 */
class RaoudsController extends Controller
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
     * Lists all Raouds models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RaoudsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Raouds model.
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
     * Creates a new Raouds model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new Raouds();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }
        $searchModel = new RaoudsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
    }

    /**
     * Updates an existing Raouds model.
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
     * Deletes an existing Raouds model.
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
     * Finds the Raouds model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Raouds the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Raouds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetRaoud()
    {
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

        ->where("raouds.id = :id", ['id' => $_POST['update_id']])->one();
        return json_encode(["result"=>$query]);
    }
}
