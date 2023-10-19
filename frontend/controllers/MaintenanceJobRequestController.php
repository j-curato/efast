<?php

namespace frontend\controllers;

use Yii;
use app\models\MaintenanceJobRequest;
use app\models\MaintenanceJobRequestSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaintenanceJobRequestController implements the CRUD actions for MaintenanceJobRequest model.
 */
class MaintenanceJobRequestController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['view_maintenance_job_request']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_maintenance_job_request']
                    ],
                    [
                        'actions' => [
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_maintenance_job_request']
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
     * Lists all MaintenanceJobRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MaintenanceJobRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MaintenanceJobRequest model.
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
     * Creates a new MaintenanceJobRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MaintenanceJobRequest();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->mjr_number = $this->mjrNumber();
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MaintenanceJobRequest model.
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MaintenanceJobRequest model.
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
     * Finds the MaintenanceJobRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MaintenanceJobRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MaintenanceJobRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function mjrNumber()
    {

        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(mjr_number,'-',-1) AS UNSIGNED) as last_num FROM maintenance_job_request ORDER BY last_num DESC LIMIT 1")->queryScalar();

        if (!empty($last_num)) {
            $last_num = intval($last_num) + 1;
        } else {
            $last_num = 1;
        }
        $zero = '';

        for ($i = strlen($last_num); $i <= 4; $i++) {
            $zero .= 0;
        }
        return date('Y') . '-' . $zero . $last_num;
    }
}
