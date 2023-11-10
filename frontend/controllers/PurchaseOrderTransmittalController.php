<?php

namespace frontend\controllers;

use app\models\PurchaseOrderForTransmittalSearch;
use Yii;
use app\models\PurchaseOrderTransmittal;
use app\models\PurchaseOrderTransmittalItems;
use app\models\PurchaseOrderTransmittalSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PurchaseOrderTransmittalController implements the CRUD actions for PurchaseOrderTransmittal model.
 */
class PurchaseOrderTransmittalController extends Controller
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
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['view_purchase_order_transmittal']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_purchase_order_transmittal']
                    ],
                    [
                        'actions' => [
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_purchase_order_transmittal']
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
     * Lists all PurchaseOrderTransmittal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseOrderTransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseOrderTransmittal model.
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
    public function insertItems($id, $po_item_ids = [], $item_id = [])
    {
        foreach ($po_item_ids as $index => $val) {

            if (!empty($item_id[$index])) {
                $item = PurchaseOrderTransmittalItems::findOne($item_id[$index]);
            } else {
                $item = new PurchaseOrderTransmittalItems();
            }
            $item->fk_purchase_order_transmittal_id = $id;
            $item->fk_purchase_order_item_id = $val;
            if ($item->save(false)) {
            } else {
                return ['isSuccess' => true, 'error_message' => $item->errors];
            }
        }
        return ['isSuccess' => true];
    }
    /**
     * Creates a new PurchaseOrderTransmittal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new PurchaseOrderTransmittal();
        $searchModel = new PurchaseOrderForTransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 10];
        $model->fk_approved_by = 99684622555676858;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = YIi::$app->db->beginTransaction();
            $items  = !empty(Yii::$app->request->post('items')) ? Yii::$app->request->post('items') : [];

            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insertEntries = $model->insertItems($items);
                if ($insertEntries !== true) {
                    throw new ErrorException($insertEntries);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();

                return  $e->getMessage();
            }
        }
        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Updates an existing PurchaseOrderTransmittal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new PurchaseOrderForTransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 10];
        if ($model->load(Yii::$app->request->post())) {
            $transaction = YIi::$app->db->beginTransaction();
            $items  = !empty(Yii::$app->request->post('items')) ? Yii::$app->request->post('items') : [];

            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insertEntries = $model->insertItems($items);
                if ($insertEntries !== true) {
                    throw new ErrorException($insertEntries);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return  $e->getMessage();
            }
        }



        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing PurchaseOrderTransmittal model.
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
     * Finds the PurchaseOrderTransmittal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseOrderTransmittal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseOrderTransmittal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
