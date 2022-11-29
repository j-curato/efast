<?php

namespace frontend\controllers;

use Yii;
use app\models\TravelOrder;
use app\models\TravelOrderItems;
use app\models\TravelOrderSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\validators\DateValidator;
use yii\validators\EmailValidator;

/**
 * TravelOrderController implements the CRUD actions for TravelOrder model.
 */
class TravelOrderController extends Controller
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
                    'update',
                    'delete',
                    'create',
                    'index',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'update',
                            'delete',
                            'create',
                            'index',
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
     * Lists all TravelOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TravelOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TravelOrder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),

            'items' => $this->items($id)
        ]);
    }

    /**
     * Creates a new TravelOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertItems($tavel_order_id = '', $items = [])
    {
        if (empty($tavel_order_id)) {
            return ['isSuccess' => false, 'error_message' => 'Travel Order ID is Missing'];
        }

        foreach ($items as $item) {
            if (!empty($item['item_id'])) {
                $to_item = TravelOrderItems::findOne($item['item_id']);
            } else {
                $to_item = new TravelOrderItems();
            }
            $to_item->fk_travel_order_id = $tavel_order_id;
            $to_item->is_deleted = 0;
            $to_item->fk_employee_id = $item['employee_id'];
            $to_item->from_date = !empty($item['from_date']) ? $item['from_date'] : null;
            $to_item->to_date = !empty($item['to_date']) ? $item['to_date'] : null;
            if ($to_item->save(false)) {
            }
        }
        return ['isSuccess' => true];
    }
    public function items($id)
    {
        return Yii::$app->db->createCommand("SELECT travel_order_items.id,
        travel_order_items.from_date,
        travel_order_items.to_date,
        travel_order_items.fk_employee_id as employee_id,
        employee_search_view.employee_name,
        employee_search_view.position
        FROM 
        travel_order_items
        LEFT JOIN employee_search_view ON travel_order_items.fk_employee_id = employee_search_view.employee_id
        WHERE travel_order_items.fk_travel_order_id = :id
        AND travel_order_items.is_deleted != 1")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function actionCreate()
    {
        $model = new TravelOrder();



        if ($model->load(Yii::$app->request->post())) {
            $items = !empty($_POST['items']) ? $_POST['items'] : [];
            $transaction = Yii::$app->db->beginTransaction();
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            if ($model->save()) {
                $insert_items = $this->insertItems($model->id, $items);
                if ($insert_items['isSuccess']) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode($insert_items['error_message']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,

        ]);
    }

    /**
     * Updates an existing TravelOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            $items = !empty($_POST['items']) ? $_POST['items'] : [];
            $transaction = Yii::$app->db->beginTransaction();
            $model->purpose = $_POST['purpose'];
            $model->expected_outputs = !empty($_POST['expected_output']) ? $_POST['expected_output'] : null;
            if ($model->save()) {
                $params = [];
                $sql = '';
                if (!empty(array_column($items, 'item_id'))) {
                    $sql = "AND";
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', array_column($items, 'item_id')], $params);
                }
                Yii::$app->db->createCommand("UPDATE travel_order_items SET is_deleted = 1 WHERE 
                      travel_order_items.fk_travel_order_id = :id    $sql  ", $params)
                    ->bindValue(':id', $model->id)->query();
                $insert_items = $this->insertItems($model->id, $items);
                if ($insert_items['isSuccess']) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode($insert_items['error_message']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->items($id)
        ]);
    }

    /**
     * Deletes an existing TravelOrder model.
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
     * Finds the TravelOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TravelOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TravelOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
