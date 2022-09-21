<?php

namespace frontend\controllers;

use app\models\PurchaseOrderForTransmittalSearch;
use Yii;
use app\models\PurchaseOrderTransmittal;
use app\models\PurchaseOrderTransmittalItems;
use app\models\PurchaseOrderTransmittalSearch;
use DateTime;
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
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
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
            'items' => $this->transmittalItems($id)
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
        if (Yii::$app->request->isPost) {
            $items  = !empty($_POST['pr_purchase_order_item_ids']) ? array_unique($_POST['pr_purchase_order_item_ids']) : [];
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->date = $_POST['date'];
            $model->serial_number = $this->serialNumber($model->date);
            $transaction = YIi::$app->db->beginTransaction();
            try {
                if ($model->validate()) {
                    if ($model->save(false)) {

                        $insert_entry = $this->insertItems($model->id, $items);
                        if ($insert_entry['isSuccess']) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {

                            $transaction->rollBack();
                            return json_encode(['isSuccess' => false, 'error_message' => $insert_entry['error_message']]);
                        }
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();

                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'action' => 'purchase-order-transmittal/create',

        ]);
    }

    /**
     * Updates an existing PurchaseOrderTransmittal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function transmittalItems($id)
    {
        return YIi::$app->db->createCommand("SELECT
        purchase_order_transmittal_items.id,
        pr_purchase_order_item.id as po_id,
        pr_purchase_order_item.serial_number,
        payee.account_name as payee,
        pr_purchase_request.purpose ,
        SUM(pr_aoq_entries.amount * pr_purchase_request_item.quantity) as total_amount
        
        
        FROM purchase_order_transmittal_items
        INNER JOIN pr_purchase_order_item ON  purchase_order_transmittal_items.fk_purchase_order_item_id = pr_purchase_order_item.id
        LEFT JOIN pr_purchase_order_items_aoq_items ON pr_purchase_order_item.id = pr_purchase_order_items_aoq_items.fk_purchase_order_item_id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
        WHERE purchase_order_transmittal_items.fk_purchase_order_transmittal_id = :id
        GROUP BY 
        purchase_order_transmittal_items.id,
        pr_purchase_order_item.id,
        pr_purchase_order_item.serial_number,
        payee.account_name,
        pr_purchase_request.purpose ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new PurchaseOrderForTransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->isPost) {
            $items  = !empty($_POST['pr_purchase_order_item_ids']) ? array_unique($_POST['pr_purchase_order_item_ids']) : [];
            $item_id  = !empty($_POST['item_id']) ? $_POST['item_id'] : [];

            $model->date = $_POST['date'];
            $transaction = YIi::$app->db->beginTransaction();
            try {
                if ($model->validate()) {
                    if ($model->save(false)) {
                        $params = [];
                        $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'purchase_order_transmittal_items.id', $item_id], $params);
                        YIi::$app->db->createCommand("DELETE FROM purchase_order_transmittal_items
                        WHERE 
                        purchase_order_transmittal_items.fk_purchase_order_transmittal_id = :id
                        AND $sql
                        ", $params)
                            ->bindValue(':id', $model->id)->query();

                        $insert_entry = $this->insertItems($model->id, $items, $item_id);
                        if ($insert_entry['isSuccess']) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {

                            $transaction->rollBack();
                            return json_encode(['isSuccess' => false, 'error_message' => $insert_entry['error_message']]);
                        }
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();

                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'items' => $this->transmittalItems($id),
            'action' => 'purchase-order-transmittal/update',
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
    public function serialNumber($date)
    {
        $year = DateTime::createFromFormat('Y-m-d', $date)->format('Y');

        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num 
        FROM purchase_order_transmittal
        ORDER BY last_num DESC LIMIT 1
        
        ")
            ->queryScalar();
        if (empty($last_num)) {
            $last_num = 1;
        } else {
            $last_num = intval($last_num) + 1;
        }
        $zero = '';
        for ($i = strlen($last_num); $i < 4; $i++) {
            $zero .= 0;
        }
        return $year . '-' . $zero . $last_num;
    }
}
