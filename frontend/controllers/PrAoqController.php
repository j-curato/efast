<?php

namespace frontend\controllers;

use Yii;
use app\models\PrAoq;
use app\models\PrAoqEntries;
use app\models\PrAoqSearch;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                ],
                'rules' => [
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PrAoq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertEntries(
        $model_id,
        $pr_rfq_items,
        $unit_costs,
        $payee_ids,
        $remarks,
        $pr_aoq_item,
        $is_lowest
    ) {
        // var_dump($pr_rfq_items);
        // die();
        foreach ($pr_rfq_items as $i => $val) {


            if (empty($pr_aoq_item[$i])) {
                $aoq = new PrAoqEntries();
            } else {
                $aoq =  PrAoqEntries::findOne($pr_aoq_item[$i]);
            }
            $aoq->payee_id = !empty($payee_ids[$i]) ? $payee_ids[$i] : '';
            $aoq->pr_aoq_id = $model_id;
            $aoq->amount = !empty($unit_costs[$i]) ? $unit_costs[$i] : '';
            $aoq->remark = !empty($remarks[$i]) ? $remarks[$i] : '';
            $aoq->pr_rfq_item_id = !empty($pr_rfq_items[$i]) ? $pr_rfq_items[$i] : '';
            // $aoq->is_lowest = $is_lowest[$i];
            // var_dump($remarks[$i]);
            // die();
            // var_dump([
            //     'pr_rfq_items' => $pr_rfq_items[$i],
            //     // 'pr_aoq_item' => $pr_aoq_item[],
            //     'payee_ids' => $payee_ids[$i],
            //     // 'unit_costs' => $unit_costs,
            //     // 'remarks' => $remarks
            // ]);

            // die();
            if ($aoq->save(false)) {
            } else {
                return false;
            }
        }

        return true;
    }
    public function actionCreate()
    {
        $model = new PrAoq();

        if ($model->load(Yii::$app->request->post())) {
            $model->aoq_number = $this->aoqNumberGenerator($model->pr_date);
            $payee_ids = $_POST['payee_id'];
            $unit_costs  = $_POST['unit_cost'];
            $pr_rfq_items = $_POST['pr_rfq_item'];
            $remarks = $_POST['remarks'];
            $is_lowest = [];

            $transaction  = Yii::$app->db->beginTransaction();

            try {

                if ($flag = true) {


                    if ($model->save(false)) {
                        $flag = $this->insertEntries(
                            $model->id,
                            $pr_rfq_items,
                            $unit_costs,
                            $payee_ids,
                            $remarks,
                            [],
                            $is_lowest
                        );
                        // return json_encode($this->insertEntries(
                        //     $model->id,
                        //     $pr_rfq_items,
                        //     $unit_costs,
                        //     $payee_ids,
                        //     $remarks,
                        //     $is_lowest
                        // ));
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('error');
                }
            } catch (ErrorException $e) {

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

        if ($model->load(Yii::$app->request->post())) {


            // return var_dump($_POST['remarks']);
            $payee_ids = $_POST['payee_id'];
            $unit_costs  = $_POST['unit_cost'];
            $pr_rfq_items = $_POST['pr_rfq_item'];
            $remarks = $_POST['remarks'];
            $pr_aoq_item = !empty($_POST['pr_aoq_item']) ? $_POST['pr_aoq_item'] : [];
            $is_lowest = [];
            // return json_encode([
            //     'pr_rfq_items'=>$pr_rfq_items,
            //     'unit_costs'=>$unit_costs,
            //     'payee_ids'=>$payee_ids,
            //     'remarks'=>$remarks,
            //     'pr_aoq_item'=>$pr_aoq_item,
            // ]);
            $transaction  = Yii::$app->db->beginTransaction();
            try {

                $this->removeEntry($model->id, $pr_aoq_item);
                if ($flag = true) {


                    if ($model->save(false)) {
                        $flag = $this->insertEntries(
                            $model->id,
                            $pr_rfq_items,
                            $unit_costs,
                            $payee_ids,
                            $remarks,
                            $pr_aoq_item,
                            $is_lowest
                        );
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('error');
                }
            } catch (ErrorException $e) {

                return json_encode($e->getMessage());
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'aoq_entries' => $this->aoqEntriesData($id)
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

    public function aoqNumberGenerator($reporting_period)
    {

        $last_num  = Yii::$app->db->createCommand("SELECT CAST(substring_index(aoq_number,'-',-1) AS UNSIGNED)as last_id
        FROM pr_aoq ORDER BY last_id DESC LIMIT 1")
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

        return 'RO-' . $reporting_period . '-' . $zero . $last_num;
    }
    public function aoqEntriesData($pr_aoq_id = null)
    {
        if ($pr_aoq_id == null) return;
        return $query = Yii::$app->db->createCommand("SELECT
                pr_aoq_entries.id as aoq_item_id,
                pr_rfq_item.id as rfq_item_id,
                pr_stock.bac_code,
                unit_of_measure.unit_of_measure,
                pr_stock.stock_title,
                IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
                pr_purchase_request_item.quantity,
                payee.account_name as payee,
                payee.id as payee_id,
                pr_aoq_entries.amount,
                pr_aoq_entries.remark
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

        pr_rfq_item.id as rfq_item_id,
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

            $query->select([" id, `aoq_number` as text"])
                ->from('pr_aoq')
                ->where(['like', 'aoq_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
}
