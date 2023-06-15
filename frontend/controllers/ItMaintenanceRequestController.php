<?php

namespace frontend\controllers;

use Yii;
use app\models\ItMaintenanceRequest;
use app\models\ItMaintenanceRequestSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItMaintenanceRequestController implements the CRUD actions for ItMaintenanceRequest model.
 */
class ItMaintenanceRequestController extends Controller
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
                    'delete',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'delete',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
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
    private function getSerialNum($type, $date)
    {
        $dte = DateTime::createFromFormat('Y-m-d', $date);
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(it_maintenance_request.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM it_maintenance_request  
            WHERE 
            it_maintenance_request.serial_number LIKE :yr
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr', '%' . $dte->format('Y') . '%')
            ->queryScalar();

        if (empty($qry)) {
            $qry = 1;
        }
        $num = '';
        if (strlen($qry) < 5) {
            $num .= str_repeat(0, 5 - strlen($qry));
        }
        $num .= $qry;
        return strtoupper($type) . '-' . $dte->format('Y-m') . '-' . $num;
    }
    /**
     * Lists all ItMaintenanceRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItMaintenanceRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItMaintenanceRequest model.
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
     * Creates a new ItMaintenanceRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItMaintenanceRequest();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $model->serial_number = $this->getSerialNum($model->type, $model->date_requested);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {

                return json_encode($e->getMessage());
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ItMaintenanceRequest model.
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
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {

                return json_encode($e->getMessage());
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ItMaintenanceRequest model.
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
     * Finds the ItMaintenanceRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ItMaintenanceRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ItMaintenanceRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
