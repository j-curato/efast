<?php

namespace frontend\controllers;

use Yii;
use app\models\DepreciationSchedule;
use app\models\DepreciationScheduleSearch;
use PHPUnit\Util\Log\JSON;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DepreciationScheduleController implements the CRUD actions for DepreciationSchedule model.
 */
class DepreciationScheduleController extends Controller
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
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                    'generate',



                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'generate',
                        ],
                        'allow' => true,
                        'roles' => ['depreciation_schedule']
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
     * Lists all DepreciationSchedule models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DepreciationScheduleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DepreciationSchedule model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'data' => $this->getDepreciations($model->reporting_period, $model->fk_book_id)
        ]);
    }

    /**
     * Creates a new DepreciationSchedule model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DepreciationSchedule();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DepreciationSchedule model.
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
     * Deletes an existing DepreciationSchedule model.
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
     * Finds the DepreciationSchedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DepreciationSchedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DepreciationSchedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function getDepreciations($reporting_period, $book = null)
    {
        $query =   Yii::$app->db->createCommand("CALL depreciations(:reporting_period,:book_id)")
            ->bindValue(':reporting_period', $reporting_period)
            ->bindValue(':book_id', !empty($book)?$book:null)
            ->queryAll();
        return $query;
    }
    public function actionGenerate()
    {
        if (Yii::$app->request->isPost) {
            $reporting_period = Yii::$app->request->post('reporting_period');
            $book = !empty(Yii::$app->request->post('book_id')) ? Yii::$app->request->post('book_id') : null;
            return json_encode($this->getDepreciations($reporting_period, $book));
        }
    }
}
