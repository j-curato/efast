<?php

namespace frontend\controllers;

use Yii;
use app\models\TripTicket;
use app\models\TripTicketItems;
use app\models\TripTicketSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TripTicketController implements the CRUD actions for TripTicket model.
 */
class TripTicketController extends Controller
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
                    'update',
                    'delete',
                    'create',

                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'update',
                            'delete',
                            'create',
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
    private function serialNumber($car_type)
    {

        $year = date('Y');
        $query = Yii::$app->db->createCommand(
            "CALL trip_ticket_serial_number(:_year,:car_type)"
        )
            ->bindValue(':_year', $year . '%')
            ->bindValue(':car_type', $car_type)
            ->queryScalar();
        $zero = '';
        $num = 1;
        if (!empty($query)) {
            $num = intval($query);
        }
        $num_len =  5 - strlen($num);
        if ($num_len > 0) {
            $zero = str_repeat(0, $num_len);
        }
        return strtoupper($car_type) . '-' . $year . '-' . $zero . $num;
    }
    private function items($id)
    {
        return  Yii::$app->db->createCommand("SELECT
        id,
       departure_time,
       departure_place,
       arrival_time,
       arrival_place,
       employee_search_view.employee_id,
       employee_search_view.employee_name
       
       
        FROM `trip_ticket_items`
       LEFT JOIN employee_search_view ON trip_ticket_items.passenger_id = employee_search_view.employee_id
       WHERE 
       fk_trip_ticket_id = :id
       AND is_deleted = 0
       ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    /**
     * Lists all TripTicket models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TripTicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TripTicket model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $items = $this->items($id);
        return $this->render('view', [
            'model' => $this->findModel($id),

            'items' => $items,
        ]);
    }

    /**
     * Creates a new TripTicket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function insertItems($trip_ticket_id, $items = [])
    {


        foreach ($items as $item) {
            if (!empty($item['item_id'])) {
                $i = TripTicketItems::findOne($item['item_id']);
            } else {

                $i = new TripTicketItems();
            }
            $i->fk_trip_ticket_id = $trip_ticket_id;
            $i->departure_place = $item['departure_place'];
            $i->arrival_place = $item['arrival_place'];
            $i->departure_time = $item['departure_time'];
            $i->arrival_time = $item['arrival_time'];
            $i->arrival_time = $item['arrival_time'];
            $i->passenger_id = $item['employee_id'];
            if ($i->save(false)) {
            }
        }
    }
    public function actionCreate()
    {
        $model = new TripTicket();

        if ($model->load(Yii::$app->request->post())) {
            $items = !empty($_POST['items']) ? $_POST['items'] : [];
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->serial_no = $this->serialNumber($model->carType->car_name);
            if ($model->save(false)) {
                $this->insertItems($model->id, $items);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TripTicket model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $items = $this->items($model->id);
        if ($model->load(Yii::$app->request->post())) {
            $items = !empty($_POST['items']) ? $_POST['items'] : [];
            if ($oldModel->car_id != $model->car_id) {
                $model->serial_no = $this->serialNumber($model->carType->car_name);
            }
            if ($model->save(false)) {
                $params = [];
                $sql = '';
                if (!empty(array_column($items, 'item_id'))) {
                    $sql = "AND";
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', array_column($items, 'item_id')], $params);
                }
                Yii::$app->db->createCommand("UPDATE trip_ticket_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                  trip_ticket_items.fk_trip_ticket_id = :id    $sql  ", $params)
                    ->bindValue(':id', $model->id)->query();
                $this->insertItems($model->id, $items);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    /**
     * Deletes an existing TripTicket model.
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
     * Finds the TripTicket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TripTicket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TripTicket::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
