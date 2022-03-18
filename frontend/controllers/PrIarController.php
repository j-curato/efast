<?php

namespace frontend\controllers;

use Yii;
use app\models\PrIar;
use app\models\PrIarItem;
use app\models\PrIarSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrIarController implements the CRUD actions for PrIar model.
 */
class PrIarController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create', 'delete', 'view', 'update'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'view', 'update'],
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
     * Lists all PrIar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrIarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrIar model.
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
     * Creates a new PrIar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertItems($quantity, $iar_id)
    {
        foreach ($quantity as $key => $val) {
            $iar_item = new PrIarItem();
            $iar_item->quantity = $val;
            $iar_item->fk_pr_iar_id = $iar_id;
            $iar_item->fk_pr_aoq_entry_id = $key;
            if ($iar_item->save(false)) {
            }
        }
        return;
    }
    public function actionCreate()
    {
        $model = new PrIar();

        if ($model->load(Yii::$app->request->post())) {

            $quantity = $_POST['quantity'];

            if ($model->save(false)) {
                $this->insertItems($quantity, $model->id);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrIar model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrIar model.
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
     * Finds the PrIar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrIar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrIar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetPoItems()
    {

        if ($_POST) {

            $po_id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT 
            pr_aoq_entries.id as aoq_entry_id,
            payee.account_name as payee,
            IFNULL(payee.tin_number,'')as tin_number, 
            IFNULL(payee.registered_address,'')as `address`, 
            pr_aoq_entries.amount as unit_cost,
            pr_aoq_entries.remark,
            pr_purchase_request_item.quantity,
            IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
            pr_stock.stock_title as `description`,
            pr_stock.bac_code,
            unit_of_measure.unit_of_measure
            FROM pr_purchase_order
            LEFT JOIN pr_aoq ON pr_purchase_order.fk_pr_aoq_id = pr_aoq.id
            LEFT JOIN pr_aoq_entries ON pr_aoq.id = pr_aoq_entries.pr_aoq_id
            LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id
            LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
            LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
            LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN unit_of_measure on pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            WHERE pr_purchase_order.id = :po_id
            AND pr_aoq_entries.is_lowest=1")
                ->bindValue(':po_id', $po_id)
                ->queryAll();
            return json_encode($query);
        }
    }
}
