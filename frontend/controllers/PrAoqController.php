<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\Office;
use Yii;
use app\models\PrAoq;
use app\models\PrAoqEntries;
use app\models\PrAoqSearch;
use DateTime;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PrAoqController implements the CRUD actions for PrAoq model.
 */
class PrAoqController extends Controller
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
                    'get-rqf-info',
                    'cancel',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'cancel',
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'get-rqf-info',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'get-rqf-info',
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
     * Lists all PrAoq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrAoqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrAoq model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // return json_encode(MyHelper::getRbac());
        return $this->render('view', [
            'model' => $this->findModel($id),
            'aoq_items_query' => $this->getAoqItems($id),
            'bac_compositions' => MyHelper::getRbac(),
        ]);
    }
    private function getAoqItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
    pr_rfq_item.id as rfq_item_id,
    pr_purchase_request_item.quantity,
    pr_stock.stock_title as `description`,
    IFNULL(REPLACE(pr_purchase_request_item.specification,'[n]','<br>'),'') as specification,
    payee.account_name as payee,
    IF(IFNULL(pr_aoq_entries.amount,0)!=0,pr_aoq_entries.amount,'-') as amount,
    pr_purchase_request.purpose,
    pr_aoq_entries.remark,
    pr_aoq_entries.is_lowest,
    unit_of_measure.unit_of_measure,
    pr_rfq.bac_composition_id
    FROM `pr_aoq_entries`
    LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
    LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
    LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id= pr_purchase_request_item.id
    LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
    LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id  = pr_stock.id
    LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
    LEFT JOIN pr_rfq ON pr_rfq_item.pr_rfq_id = pr_rfq.id
    WHERE pr_aoq_entries.pr_aoq_id = :id")

            ->bindValue(':id', $id)
            ->queryAll();
    }

    /**
     * Creates a new PrAoq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertEntries($model_id, $items)
    {


        try {
            foreach ($items as $i => $itm) {
                foreach ($itm as $val) {

                    if (!empty($val['item_id'])) {
                        $aoq =  PrAoqEntries::findOne($val['item_id']);
                    } else {
                        $aoq = new PrAoqEntries();
                        $aoq->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()  % 9223372036854775807")->queryScalar();
                    }
                    $aoq->payee_id = $val['payee_id'];
                    $aoq->pr_aoq_id = $model_id;
                    $aoq->amount = $val['unit_cost'];
                    $aoq->remark = $val['remarks'];
                    $aoq->pr_rfq_item_id = $i;
                    $aoq->is_lowest = !empty($val['lowest']) && $val['lowest'] == 'on' ? 1 : 0;
                    if (!$aoq->validate()) {
                        throw new ErrorException(json_encode($aoq->errors));
                    }
                    if (!$aoq->save(false)) {
                        throw new ErrorException('AOQ Item Model Save Failed');
                    }
                }
            }

            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function actionCreate()
    {
        $model = new PrAoq();
        $model->fk_office_id = Yii::$app->user->identity->fk_office_id ?? null;

        if ($model->load(Yii::$app->request->post())) {
            try {
                $transaction  = Yii::$app->db->beginTransaction();
                $items  = Yii::$app->request->post('items') ?? [];
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()  % 9223372036854775807")->queryScalar();
                $model->aoq_number = $this->aoqNumberGenerator($model->rfq->deadline, $model->fk_office_id);
                $model->pr_date = $model->rfq->deadline;
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model save failed');
                }
                $insItems = $this->insertEntries(
                    $model->id,
                    $items
                );
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrAoq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $transaction  = Yii::$app->db->beginTransaction();
                if ($model->fk_office_id != $oldModel->fk_office_id) {
                    $model->aoq_number = $this->aoqNumberGenerator($model->rfq->deadline, $model->fk_office_id);
                }
                $items  = Yii::$app->request->post('items') ?? [];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model save failed');
                }
                $insItems = $this->insertEntries(
                    $model->id,
                    $items
                );
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $model,
            'aoq_entries' => ArrayHelper::index($this->aoqEntriesData($id), null, 'rfq_item_id')
        ]);
    }

    /**
     * Deletes an existing PrAoq model.
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
     * Finds the PrAoq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrAoq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrAoq::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function removeEntry($id, $aoq_items)
    {

        if (empty($aoq_items)) return;

        $params = [];
        $and = '';
        if (count($aoq_items) > 1) {
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'pr_aoq_entries.id', $aoq_items], $params);
            $and = 'AND';
        } else if (count($aoq_items) === 1) {
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'pr_aoq_entries.id', $aoq_items[min(array_keys($aoq_items))]], $params);
            $and = 'AND';
        } else {
            $sql = '';
        }
        $q = Yii::$app->db->createCommand("DELETE  FROM pr_aoq_entries 
        WHERE pr_aoq_entries.pr_aoq_id = :id  $and $sql", $params)
            ->bindValue(':id', $id)
            ->execute();
    }

    public function aoqNumberGenerator($aoq_date, $office_id)
    {
        $office = Office::findOne($office_id);
        $date = DateTime::createFromFormat('Y-m-d', $aoq_date);
        $last_num  = Yii::$app->db->createCommand("SELECT CAST(substring_index(aoq_number,'-',-1) AS UNSIGNED)as last_id
        FROM pr_aoq
        WHERE fk_office_id = :office_id
        AND pr_aoq.aoq_number LIKE :yr
         ORDER BY last_id DESC LIMIT 1")
            ->bindValue(':office_id', $office_id)
            ->bindValue(':yr', "%" . $date->format('Y') . '%')
            ->queryScalar();
        if (!empty($last_num)) {
            $last_num  = intval($last_num) + 1;
        } else {
            $last_num = 1;
        }
        $number_length = strlen($last_num);
        $zero = '';
        while ($number_length  < 4) {
            $zero .= 0;
            $number_length++;
        }

        return $office->office_name . '-' . $date->format('Y-m-d') . '-' . $zero . $last_num;
    }
    public function aoqEntriesData($pr_aoq_id = null)
    {
        if ($pr_aoq_id == null) return;
        return $query = Yii::$app->db->createCommand("SELECT
                pr_aoq_entries.id as item_id,
                pr_rfq_item.id as rfq_item_id,
                pr_stock.bac_code,
                unit_of_measure.unit_of_measure,
                pr_stock.stock_title,
                IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
                pr_purchase_request_item.quantity,
                payee.account_name as payee,
                payee.id as payee_id,
                pr_aoq_entries.amount,
                pr_aoq_entries.remark,
                pr_aoq_entries.is_lowest
                FROM pr_aoq_entries

                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                WHERE pr_aoq_entries.pr_aoq_id = :pr_aoq_id")
            ->bindValue(':pr_aoq_id', $pr_aoq_id)
            ->queryAll();
    }
    public function rfqItemData($id)
    {
        $query = Yii::$app->db->createCommand("SELECT

        CAST(pr_rfq_item.id as CHAR(50)) as rfq_item_id,
        pr_stock.bac_code,
        unit_of_measure.unit_of_measure,
        pr_stock.stock_title,
        IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
        pr_purchase_request_item.quantity
         FROM pr_rfq_item
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
        LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
        
        WHERE pr_rfq_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
        return json_encode($query);
    }
    public function actionGetRfqInfo()
    {
        if ($_POST) {
            $id = $_POST['id'];

            return $this->rfqItemData($id);
        }
    }

    public function actionSearchAoq($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" CAST(id as CHAR(50)) as id, `aoq_number` as text"])
                ->from('pr_aoq')
                ->where(['like', 'aoq_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    public function actionCancel($id)
    {
        if (Yii::$app->request->post()) {
            try {
                $model = $this->findModel($id);
                $model->is_cancelled =  $model->is_cancelled ? 0 : 1;
                $model->cancelled_at = date('Y-m-d H:i:s');
                if ($model->is_cancelled === 1) {
                    $qry = Yii::$app->db->createCommand("SELECT 
                            GROUP_CONCAT(pr_purchase_order.po_number) as pr_nums
                            FROM pr_purchase_order
                            WHERE pr_purchase_order.fk_pr_aoq_id = :id
                            AND pr_purchase_order.is_cancelled = 0
                            GROUP BY 
                            pr_purchase_order.fk_pr_aoq_id")
                        ->bindValue(':id', $model->id)
                        ->queryScalar();

                    if (!empty($qry)) {
                        throw new ErrorException("Unable to cancel AOQ,PR No./s $qry is/are not Cancelled.");
                    }
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                return json_encode(['error' => false, 'message' => 'Successfuly Save']);
            } catch (ErrorException $e) {
                return json_encode(['error' => true, 'message' => $e->getMessage()]);
            }
        }
    }
}
