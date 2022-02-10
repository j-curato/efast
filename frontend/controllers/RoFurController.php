<?php

namespace frontend\controllers;

use Yii;
use app\models\RoFur;
use app\models\RoFurSearch;
use DateTime;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * RoFurController implements the CRUD actions for RoFur model.
 */
class RoFurController extends Controller
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
                    'view',
                    'index',
                    'create',
                    'delete',
                    'update',
                    'division-fur'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'delete',
                            'update',
                            'division-fur'
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
     * Lists all RoFur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoFurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RoFur model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $model,
            'dataProvider' =>  json_encode($this->generateFur(
                $model->from_reporting_period,
                $model->to_reporting_period,
                $model->division,
                $model->document_recieve_id
            ))
        ]);
    }

    /**
     * Creates a new RoFur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RoFur();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $year = DateTime::createFromFormat('Y-m', $model->to_reporting_period)->format('Y');
            $model->from_reporting_period = $year . '-01';
            if ($model->save(false)) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RoFur model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $year = DateTime::createFromFormat('Y-m', $model->to_reporting_period)->format('Y');
            $model->from_reporting_period = $year . '-01';
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RoFur model.
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
     * Finds the RoFur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoFur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RoFur::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function generateFur(
        $from_reporting_period,
        $to_reporting_period,
        $division,
        $document_recieve
    ) {
        $current_ors = new Query();
        $current_ors->select([

            "saob_rao.division",
            "saob_rao.mfo_pap_code_id",
            "saob_rao.document_recieve_id",
            "saob_rao.major_id",
            "SUM(saob_rao.allotment_amount) as total_allotment",
            "SUM(saob_rao.ors_amount) as total_ors"
        ])
            ->from('saob_rao')

            ->where(" saob_rao.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
            ->andWhere("saob_rao.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
        if (strtolower($division) !== 'all') {

            $current_ors->andWhere("saob_rao.division = :division", ['division' => $division]);
        }
        if (strtolower($document_recieve) !== 'all') {

            $current_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
        }
        $current_ors->groupBy(
            "saob_rao.division,
            saob_rao.mfo_pap_code_id,
            saob_rao.document_recieve_id,
            saob_rao.major_id"
        );


        $prev_ors = new Query();
        $prev_ors->select([
            "saob_rao.division",
            "saob_rao.mfo_pap_code_id",
            "saob_rao.document_recieve_id",
            "saob_rao.major_id",
            "SUM(saob_rao.allotment_amount) as total_allotment",
            "SUM(saob_rao.ors_amount) as total_ors"
        ])
            ->from('saob_rao')
            ->where(" saob_rao.reporting_period < :from_reporting_period", ['from_reporting_period' => $from_reporting_period]);
        if (strtolower($division) !== 'all') {

            $prev_ors->andWhere("saob_rao.division = :division", ['division' => $division]);
        }
        if (strtolower($document_recieve) !== 'all') {

            $prev_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
        }
        $prev_ors->groupBy(
            "saob_rao.division,
            saob_rao.mfo_pap_code_id,
            saob_rao.document_recieve_id,
            saob_rao.major_id"
        );





        $sql_current_ors = $current_ors->createCommand()->getRawSql();
        $sql_prev_ors = $prev_ors->createCommand()->getRawSql();
        $query = Yii::$app->db->createCommand("SELECT
        current.division,
        mfo_pap_code.`code` as mfo_code,
        mfo_pap_code.`name` as mfo_name,
        mfo_pap_code.`description` as mfo_description,
        document_recieve.`name` as document_name,
        major_accounts.`name` as major_name,
        major_accounts.`object_code` as major_object_code,
        IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
          IFNULL(prev.total_ors ,0)as prev_total_ors,
        IFNULL(current.total_ors,0) as current_total_ors,
        IFNULL(prev.total_ors ,0) + 
        IFNULL(current.total_ors,0) as ors_to_date,
       ( IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) )-
       ( IFNULL(prev.total_ors ,0) + 
        IFNULL(current.total_ors,0)) as balance,
       ( IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) )-
        IFNULL(prev.total_ors ,0) 
         as begin_balance,
       (  IFNULL(prev.total_ors ,0) + IFNULL(current.total_ors,0))
            /
        ( IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) ) as utilization
        FROM ($sql_current_ors) as current
        LEFT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
        AND current.document_recieve_id = prev.document_recieve_id
        AND current.major_id = prev.major_id)
 
        LEFT JOIN major_accounts ON current. major_id = major_accounts.id
        LEFT JOIN mfo_pap_code ON current.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN document_recieve ON current.document_recieve_id = document_recieve.id
        WHERE

        IFNULL(prev.total_ors ,0) + 
        IFNULL(current.total_ors,0) >0
        OR 
        IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0

        ")->queryAll();

        $result = ArrayHelper::index($query, null, [function ($element) {
            return $element['division'];
        }, 'mfo_name', 'document_name']);
        $mfo = Yii::$app->db->createCommand("SELECT * FROM mfo_pap_code")->queryAll();
        $mfo_final = ArrayHelper::index($mfo, null, 'name');
        return json_encode(['result' => $result, 'mfo_pap' => $mfo_final]);
    }
    public function actionDivisionFur()
    {
        if ($_POST) {
            $fur_filter = $_POST['RoFur'];

            // return json_encode($fur_filter);

            $to_reporting_period = $fur_filter['to_reporting_period'];
            $division = !empty($fur_filter['division']) ? $fur_filter['division'] : '';
            $document_recieve = $fur_filter['document_recieve_id'];
            $year  = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('Y');
            $from_reporting_period = $year . '-01';
            if (!empty(Yii::$app->user->identity->division)) {
                $division = Yii::$app->user->identity->division;
            }

            return $this->generateFur(
                $from_reporting_period,
                $to_reporting_period,
                $division,
                $document_recieve
            );
        }
        return $this->render('division_fur');
    }
}
