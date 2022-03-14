<?php

namespace frontend\controllers;

use app\models\PrAoqEntries;
use Yii;
use app\models\PrPurchaseOrder;
use app\models\PrPurchaseOrderSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrPurchaseOrderController implements the CRUD actions for PrPurchaseOrder model.
 */
class PrPurchaseOrderController extends Controller
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
                    'update',
                    'view',
                    'delete',
                    'create',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'view',
                            'delete',
                            'create',
                            'update',
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
     * Lists all PrPurchaseOrder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrPurchaseOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrPurchaseOrder model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function  findLowest($fk_pr_aoq_id, $query, $params = [])
    {

        $aoq_lowest = Yii::$app->db->createCommand("SELECT 
        pr_aoq_entries.id,
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
        FROM pr_aoq_entries
        LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id
        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
        LEFT JOIN unit_of_measure on pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
        WHERE pr_aoq_entries.pr_aoq_id = :id
        AND $query", $params)
            ->bindValue(':id', $fk_pr_aoq_id)
            ->queryAll();

        return $aoq_lowest;
    }
    public function actionView($id)
    {
        $model =  $this->findModel($id);

        $aoq_lowest = '';
        $params = [];
        $sql = Yii::$app->db->getQueryBuilder()->buildCondition("pr_aoq_entries.is_lowest=1", $params);
        $aoq_lowest = $this->findLowest($model->fk_pr_aoq_id, $sql);

        // if (empty($aoq_lowest)) {

        //     $sql = Yii::$app->db->getQueryBuilder()->buildCondition("pr_aoq_entries.amount = (SELECT MIN(pr_aoq_entries.amount) FROM pr_aoq_entries WHERE pr_aoq_entries.pr_aoq_id = :id )", $params);
        //     $aoq_lowest = $this->findLowest($model->fk_pr_aoq_id, $sql);
        // }
        return $this->render('view', [
            'model' => $model,
            'aoq_lowest' => $aoq_lowest
        ]);
    }

    /**
     * Creates a new PrPurchaseOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PrPurchaseOrder();

        if ($model->load(Yii::$app->request->post())) {

            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->po_number = $this->generatePoNumber($model->fk_contract_type_id);

            if ($model->save()) {
                if (!empty(array_unique($_POST['aoq_id']))) {
                    $this->newLowest(array_unique($_POST['aoq_id']), $model->fk_pr_aoq_id);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrPurchaseOrder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            // return json_encode($_POST['aoq_id']);

            if (!empty(array_unique($_POST['aoq_id']))) {
                $this->newLowest(array_unique($_POST['aoq_id']), $model->fk_pr_aoq_id);
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrPurchaseOrder model.
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
     * Finds the PrPurchaseOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrPurchaseOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrPurchaseOrder::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionAoqInfo()
    {
        if ($_POST) {
            $id = $_POST['id'];

            // $aoq_lowest = Yii::$app->db->createCommand("SELECT 
            // payee.account_name as payee,
            // IFNULL(payee.registered_address,'') as `address`,
            // IFNULL(payee.tin_number,'') as tin_number,
            // pr_aoq_entries.amount as unit_cost
            // FROM pr_aoq_entries
            // LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id

            // WHERE pr_aoq_entries.pr_aoq_id = :id 
            // AND pr_aoq_entries.amount = (SELECT MIN(pr_aoq_entries.amount) FROM pr_aoq_entries WHERE pr_aoq_entries.pr_aoq_id = :id )")
            //     ->bindValue(':id', $id)
            //     ->queryAll();

            $aoq_lowest = Yii::$app->db->createCommand("SELECT 
                pr_aoq_entries.id as aoq_entry_id,
                pr_aoq_entries.pr_rfq_item_id as rfq_item_id,
                payee.account_name as payee,
                pr_aoq_entries.amount as unit_cost,
                pr_aoq_entries.remark,
                pr_purchase_request_item.quantity,
                IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
                pr_stock.stock_title as `description`,
                IFNULL(payee.registered_address,'') as `address`,
                IFNULL(payee.tin_number,'') as tin_number,
                pr_stock.bac_code
                FROM pr_aoq_entries
                LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id
                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                WHERE pr_aoq_entries.pr_aoq_id =:id
                AND pr_aoq_entries.is_lowest = 1")
                ->bindValue(':id', $id)
                ->queryAll();
            if (empty($aoq_lowest)) {
                $aoq_lowest = Yii::$app->db->createCommand("SELECT 
               pr_aoq_entries.id as aoq_entry_id,
               pr_aoq_entries.pr_rfq_item_id as rfq_item_id,
                payee.account_name as payee,
                pr_aoq_entries.amount as unit_cost,
                pr_aoq_entries.remark,
                pr_purchase_request_item.quantity,
                IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
                pr_stock.stock_title as `description`,
                pr_stock.bac_code,
                pr_aoq_entries.is_lowest
                FROM pr_aoq_entries
                LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id
                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                            INNER JOIN (SELECT MIN(pr_aoq_entries.amount) as amount, pr_aoq_entries.pr_rfq_item_id FROM pr_aoq_entries WHERE pr_aoq_entries.pr_aoq_id = :id  
                            GROUP BY pr_aoq_entries.pr_rfq_item_id) as lowest_value ON pr_aoq_entries.pr_rfq_item_id = lowest_value.pr_rfq_item_id   AND   pr_aoq_entries.amount =lowest_value.amount 
                WHERE pr_aoq_entries.pr_aoq_id =:id
    
                 ")
                    ->bindValue(':id', $id)
                    ->queryAll();
            }

            $aoq_items = Yii::$app->db->createCommand("SELECT 
             pr_aoq_entries.id,
             pr_aoq_entries.pr_rfq_item_id as rfq_item_id,
             
            payee.account_name as payee,
            pr_aoq_entries.amount as unit_cost,
            pr_aoq_entries.remark,
            pr_purchase_request_item.quantity,
            IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
            pr_stock.stock_title as `description`,
            pr_stock.bac_code,
            pr_aoq_entries.is_lowest

            FROM pr_aoq_entries
            LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id
            LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
            LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
            LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            WHERE pr_aoq_entries.pr_aoq_id = :id ")
                ->bindValue(':id', $id)
                ->queryAll();

            return json_encode([
                'lowest' => $aoq_lowest,
                'aoq_items' => $aoq_items
            ]);
        }
    }
    public function newLowest($aoq_entry_id = [], $aoq_id = '')
    {
        $params = [];
        $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'pr_aoq_entries.id', $aoq_entry_id], $params);
        $q =  Yii::$app->db->createCommand("UPDATE  pr_aoq_entries SET is_lowest=0 WHERE  $sql AND pr_aoq_id = :aoq_id", $params)
            ->bindValue(':aoq_id', $aoq_id)
            ->query();
        // echo $q->getRawSql();
        // die();
        foreach ($aoq_entry_id as $val) {
            $model = PrAoqEntries::findOne($val);
            $model->is_lowest = 1;
            if ($model->save(false)) {
            }
        }
    }
    public function generatePoNumber($contract_id)
    {
        $reporting_period = '2022-01';
        $contract_type = Yii::$app->db->createCommand("SELECT pr_contract_type.contract_name FROM pr_contract_type WHERE id =:id")
            ->bindValue(':id', $contract_id)
            ->queryScalar();
        $last_number  = Yii::$app->db->createCommand("SELECT SUBSTRING_INDEX(po_number,'-',-1) as q  FROM pr_purchase_order ORDER BY q DESC LIMIT 1")->queryScalar();
        if (!empty($last_number)) {
            $last_number += 1;
        } else {
            $last_number = 1;
        }
        $zero = '';
        for ($i = strlen($last_number); $i < 4; $i++) {
            $zero .= 0;
        }

        return 'RO-' . $contract_type . '-' . $reporting_period . '-' . $zero . $last_number;
    }
    public function actionSearchPurchaseOrder($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" id, `po_number` as text"])
                ->from('pr_purchase_order')
                ->where(['like', 'po_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
}
