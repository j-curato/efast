<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use common\models\User;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\MgLiquidations;
use yii\filters\AccessControl;
use app\models\NotificationToPay;
use yii\web\NotFoundHttpException;
use app\models\MgLiquidationsSearch;

/**
 * MgLiquidationsController implements the CRUD actions for MgLiquidations model.
 */
class MgLiquidationsController extends Controller
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
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['view_rapid_mg_liquidation'],
                    ],
                    [
                        'actions' => [
                            'update'
                        ],
                        'allow' => true,
                        'roles' => ['update_rapid_mg_liquidation'],
                    ],
                    [
                        'actions' => [
                            'create'
                        ],
                        'allow' => true,
                        'roles' => ['create_rapid_mg_liquidation'],
                    ],
                    [
                        'actions' => [
                            'get-notifications-to-pay'
                        ],
                        'allow' => true,
                        'roles' => ['create_rapid_mg_liquidation', 'update_rapid_mg_liquidation'],
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
     * Lists all MgLiquidations models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MgLiquidationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MgLiquidations model.
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
     * Creates a new MgLiquidations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MgLiquidations();
        $user_data = User::getUserDetails();
        $model->fk_office_id = $user_data->employee->office->id;
        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                // $filteredItems = array_filter($items, function ($item) {
                //     return isset($item['is_checked']) && strtolower($item['is_checked']) === 'on';
                // });

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insertItems = $model->insertItems($items);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MgLiquidations model.
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
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insertItems = $model->insertItems($items);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MgLiquidations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the MgLiquidations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MgLiquidations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MgLiquidations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    // get all notifications to by  MGRFR id  where not in tbl_mg_liquidation_items table
    public function actionGetNotificationsToPay()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            return json_encode(NotificationToPay::getMGRFRNotificationsToPayById($id));
        }
    }
}
