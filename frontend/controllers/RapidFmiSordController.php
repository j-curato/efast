<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\RapidFmiSord;
use app\models\FmiSubprojects;
use yii\filters\AccessControl;
use app\models\RapidFmiSordSearch;
use yii\web\NotFoundHttpException;

/**
 * RapidFmiSordController implements the CRUD actions for RapidFmiSord model.
 */
class RapidFmiSordController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['view_rapid_fmi_sord']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_rapid_fmi_sord']
                    ],
                    [
                        'actions' => [
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_rapid_fmi_sord']
                    ],
                    [
                        'actions' => [
                            'generate-sord',
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
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
     * Lists all RapidFmiSord models.
     * rapid_fmi_sordreturn mixed
     */
    public function actionIndex()
    {
        $searchModel = new RapidFmiSordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RapidFmiSord model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model  =  $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'sordData' => $this->getSord($model->fk_fmi_subproject_id, $model->reporting_period)
        ]);
    }

    /**
     * Creates a new RapidFmiSord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RapidFmiSord();

        if ($model->load(Yii::$app->request->post())) {

            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save()) {
                    throw new ErrorException("Model Save Failed");
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RapidFmiSord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save()) {
                    throw new ErrorException("Model Save Failed");
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'sordData' => $this->getSord($model->fk_fmi_subproject_id, $model->reporting_period)
        ]);
    }

    /**
     * Deletes an existing RapidFmiSord model.
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
     * Finds the RapidFmiSord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RapidFmiSord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RapidFmiSord::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGenerateSord()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $reportingPeriod = Yii::$app->request->post('reporting_period');
            $model = FmiSubprojects::findOne($id);
            return $this->getSord($id, $reportingPeriod);
        }
    }
    private function getSord($id, $reportingPeriod)
    {
        $model = FmiSubprojects::findOne($id);
        return json_encode(
            [
                'beginningBalance' => $model->getBeginningBalance($reportingPeriod),
                'liquidations' => $model->getLiquidationsA($reportingPeriod),
                'details' => $model->getDetails(),
                'grantDepositsForTheMonth' => $model->getGrantDepositsByPeriod($reportingPeriod),
                'equityDepositsForTheMonth' => $model->getEquityDepositsByPeriod($reportingPeriod),
                'otherDepositsForTheMonth' => $model->getOtherDepositsByPeriod($reportingPeriod),
            ]
        );
    }
}
