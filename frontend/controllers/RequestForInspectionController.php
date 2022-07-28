<?php

namespace frontend\controllers;

use Yii;
use app\models\RequestForInspection;
use app\models\RequestForInspectionItems;
use app\models\RequestForInspectionSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequestForInspectionController implements the CRUD actions for RequestForInspection model.
 */
class RequestForInspectionController extends Controller
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
                    'update',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'create',
                            'update',
                            'delete',
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

    /**
     * Lists all RequestForInspection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestForInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RequestForInspection model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'purchase_orders' => $this->poDetails($id)
        ]);
    }
    public function poDetails($id)
    {

        $purchase_orders = Yii::$app->db->createCommand("SELECT 
        request_for_inspection_items.id ,
        pr_purchase_order_item.id as po_id,
        pr_purchase_order.po_number,
        pr_purchase_order.po_date,
        pr_purchase_order.place_of_delivery,
        pr_purchase_request.purpose, 
        pr_project_procurement.title as project_title
         FROM `request_for_inspection_items`
        LEFT JOIN pr_purchase_order_item ON request_for_inspection_items.fk_purchase_order_item_id = pr_purchase_order_item.id
        LEFT JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id  = pr_purchase_order.id
        LEFT JOIN pr_aoq ON pr_purchase_order.fk_pr_aoq_id = pr_aoq.id
        LEFT JOIN pr_rfq ON pr_aoq.pr_rfq_id = pr_rfq.id
        LEFT JOIN pr_purchase_request ON pr_rfq.pr_purchase_request_id = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        WHERE request_for_inspection_items.fk_request_for_inspection_id = :id
        AND request_for_inspection_items.is_deleted !=1
        ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $purchase_orders;
    }
    /**
     * Creates a new RequestForInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertItems($rfi_id, $po_ids = [], $item_ids = [])
    {

        if (!empty($po_ids)) {

            try {
                foreach ($po_ids as $index => $val) {

                    if (!empty($item_ids[$index])) {

                        $item = RequestForInspectionItems::findOne($item_ids[$index]);
                    } else {

                        $item = new RequestForInspectionItems();
                    }

                    $item->fk_request_for_inspection_id = $rfi_id;
                    $item->fk_purchase_order_item_id = $val;
                    if ($item->validate()) {

                        if ($item->save(false)) {
                            // echo $item->fk_purchase_order_item_id;
                            // echo '<br>';
                        }
                    } else {
                        return $item->errors;
                    }
                }
                // die();
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }


        return true;
    }
    public function actionCreate()
    {
        $model = new RequestForInspection();

        if ($model->load(Yii::$app->request->post())) {
            $po_ids = $_POST['purchase_order_id'];
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->rfi_number = $this->rfiNumber();
            if ($model->save(false)) {
                $this->insertItems($model->id, $po_ids);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RequestForInspection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $po_ids = $_POST['purchase_order_id'];

            $item_ids = !empty($_POST['item_id']) ? $_POST['item_id'] : [];

            if ($model->save(false)) {
                if (!empty($item_ids)) {

                    $params = [];
                    $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                    Yii::$app->db->createCommand("UPDATE request_for_inspection_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                        $sql AND request_for_inspection_items.fk_request_for_inspection_id = :id", $params)
                        ->bindValue(':id', $model->id)->query();
                }
                $this->insertItems($model->id, $po_ids, $item_ids);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->render('update', [
            'model' => $model,
            'items' => $this->poDetails($id)
        ]);
    }

    /**
     * Deletes an existing RequestForInspection model.
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
     * Finds the RequestForInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RequestForInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RequestForInspection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function rfiNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(rfi_number,'-',-1)  AS UNSIGNED) as last_number
         FROM request_for_inspection
         ORDER BY last_number DESC LIMIT 1")->queryScalar();

        $num = 1;
        if (!empty($query)) {
            $num  = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {

            $zero .= 0;
        }
        return date('Y') . '-' . $zero . $num;
    }
}
