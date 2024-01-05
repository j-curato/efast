<?php

namespace frontend\controllers;

use Yii;
use app\models\Mgrfrs;
use yii\web\Controller;
use app\models\RapidMgSord;
use yii\filters\VerbFilter;
use app\models\RapidMgSordSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * RapidMgSordController implements the CRUD actions for RapidMgSord model.
 */
class RapidMgSordController extends Controller
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
                            'index',
                            'view',


                        ],
                        'allow' => true,
                        'roles' => ['view_rapid_mg_sord']
                    ],
                    [
                        'actions' => [

                            'update',

                        ],
                        'allow' => true,
                        'roles' => ['update_rapid_mg_sord']
                    ],
                    [
                        'actions' => [
                            'create',

                        ],
                        'allow' => true,
                        'roles' => ['create_rapid_mg_sord']
                    ],
                    [
                        'actions' => [
                            'generate',
                        ],
                        'allow' => true,
                        'roles' => ['create_rapid_mg_sord', 'update_rapid_mg_sord']
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
     * Lists all RapidMgSord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RapidMgSordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RapidMgSord model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $model,
            'sordData' => [
                'cashDepositBalance' => $model->mgrfr->getCashBalanceById($model->reporting_period),
                'liquidations' => $model->mgrfr->getLiquidations($model->reporting_period),
                'cashDeposits' => $model->mgrfr->getCashDepositsByPeriod($model->reporting_period),
                'mgrfrDetails' => $model->mgrfr->getMgrfrDetails()
            ]
        ]);
    }

    /**
     * Creates a new RapidMgSord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RapidMgSord();

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
     * Updates an existing RapidMgSord model.
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
        ]);
    }

    /**
     * Deletes an existing RapidMgSord model.
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
     * Finds the RapidMgSord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RapidMgSord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RapidMgSord::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGenerate()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $reportingPeriod = Yii::$app->request->post('reporting_period');
            $model = Mgrfrs::findOne($id);

            return json_encode(
                [
                    'cashDepositBalance' => $model->getCashBalanceById($reportingPeriod),
                    'liquidations' => $model->getLiquidations($reportingPeriod),
                    'cashDeposits' => $model->getCashDepositsByPeriod($reportingPeriod),
                    'mgrfrDetails' => $model->getMgrfrDetails()
                ]
            );
        }
    }
}
