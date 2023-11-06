<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use yii\db\Query;
use ErrorException;
use app\models\PrAoq;
use app\models\Office;
use common\models\User;
use yii\web\Controller;
use app\models\PrAoqSearch;
use yii\filters\VerbFilter;
use app\models\PrAoqEntries;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\components\helpers\MyHelper;
use app\models\PrRfq;
use yii\helpers\VarDumper;

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
                    'search-aoq'
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
                            'cancel',
                            'search-aoq'
                        ],
                        'allow' => true,
                        'roles' => ['aoq']
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
        $model =  $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'aoq_items_query' => $model->getItems($id),
            'bac_compositions' => MyHelper::getRbac($model->rfq->bac_composition_id),
        ]);
    }

    /**
     * Creates a new PrAoq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function insertEntries($model_id, $items)
    // {
    //     try {
    //         foreach ($items as $i => $itm) {
    //             foreach ($itm as $val) {
    //                 if (!empty($val['item_id'])) {
    //                     $aoq =  PrAoqEntries::findOne($val['item_id']);
    //                 } else {
    //                     $aoq = new PrAoqEntries();
    //                 }
    //                 $aoq->payee_id = $val['payee_id'];
    //                 $aoq->pr_aoq_id = $model_id;
    //                 $aoq->amount = $val['unit_cost'];
    //                 $aoq->remark = $val['remarks'];
    //                 $aoq->pr_rfq_item_id = $i;
    //                 $aoq->is_lowest = !empty($val['lowest']) && $val['lowest'] == 'on' ? 1 : 0;
    //                 if (!$aoq->validate()) {
    //                     throw new ErrorException(json_encode($aoq->errors));
    //                 }
    //                 if (!$aoq->save(false)) {
    //                     throw new ErrorException('AOQ Item Model Save Failed');
    //                 }
    //             }
    //         }

    //         return true;
    //     } catch (ErrorException $e) {
    //         return $e->getMessage();
    //     }
    // }
    public function actionCreate()
    {
        $model = new PrAoq();
        $user_data = User::getUserDetails();
        $model->fk_office_id = $user_data->employee->office->id;
        if ($model->load(Yii::$app->request->post())) {
            try {
                $transaction  = Yii::$app->db->beginTransaction();
                $items = call_user_func_array('array_merge',  Yii::$app->request->post('items') ?? []);
                $nopToDate = $model->rfq->getNopToDate();
                $aoqDeadline =  !empty($nopToDate) ? DateTime::createFromFormat('Y-m-d H:i:s', $nopToDate)->format('Y-m-d')
                    : DateTime::createFromFormat('Y-m-d H:i:s', $model->rfq->deadline)->format('Y-m-d');
                $model->pr_date = $aoqDeadline;
                if (empty($items)) {
                    throw new ErrorException('Please Insert an Item');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }

                if (!$model->save(false)) {
                    throw new ErrorException('Model save failed');
                }

                $insItems = $model->insertItems($items);
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
        if ($model->load(Yii::$app->request->post()) || Yii::$app->request->post()) {
            try {
                $transaction  = Yii::$app->db->beginTransaction();
                $items = call_user_func_array('array_merge',  Yii::$app->request->post('items') ?? []);
                if ($model->checkHasPo()) {
                    throw new ErrorException('Cannot Update AOQ has a PO');
                }
                if ($model->fk_office_id != $oldModel->fk_office_id) {
                    $model->aoq_number = $this->aoqNumberGenerator($model->pr_date, $model->fk_office_id);
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model save failed');
                }
                $insItems = $model->insertItems($items);
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
            'items' => $this->formatUpdateItems(ArrayHelper::index($model->getItems(), null, 'rfq_item_id'))
        ]);
    }
    private function formatUpdateItems($modelItems)
    {
        $items = [];
        foreach ($modelItems as $itm) {
            $bidders = [];
            foreach ($itm as $i) {
                $bidders[] = [
                    'id' => $i['item_id'],
                    'unitCost' => $i['amount'],
                    'maskedAmount' => number_format($i['amount'], 2),
                    'payeeId' => $i['payee_id'],
                    'remark' => $i['remark'],
                    'isLowest' => intval($i['is_lowest']) === 1 ? true : false,
                    'payeeName' => $i['payee'],

                ];
            }
            $items[] = [
                'bac_code' => $itm[0]['bac_code'],
                'quantity' => $itm[0]['quantity'],
                'specification' => $itm[0]['specification'],
                'rfq_item_id' => $itm[0]['rfq_item_id'],
                'stock_title' => $itm[0]['stock_title'],
                'unit_of_measure' => $itm[0]['unit_of_measure'],
                'bidders' => $bidders,
            ];
        }
        return  $items;
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
    // public function removeEntry($id, $aoq_items)
    // {

    //     if (empty($aoq_items)) return;

    //     $params = [];
    //     $and = '';
    //     if (count($aoq_items) > 1) {
    //         $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'pr_aoq_entries.id', $aoq_items], $params);
    //         $and = 'AND';
    //     } else if (count($aoq_items) === 1) {
    //         $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'pr_aoq_entries.id', $aoq_items[min(array_keys($aoq_items))]], $params);
    //         $and = 'AND';
    //     } else {
    //         $sql = '';
    //     }
    //     $q = Yii::$app->db->createCommand("DELETE  FROM pr_aoq_entries 
    //     WHERE pr_aoq_entries.pr_aoq_id = :id  $and $sql", $params)
    //         ->bindValue(':id', $id)
    //         ->execute();
    // }

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
    // public function aoqEntriesData($pr_aoq_id = null)
    // {
    //     if ($pr_aoq_id == null) return;
    //     return $query = Yii::$app->db->createCommand("SELECT
    //             pr_aoq_entries.id as item_id,
    //             pr_rfq_item.id as rfq_item_id,
    //             pr_stock.bac_code,
    //             unit_of_measure.unit_of_measure,
    //             pr_stock.stock_title,
    //             IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
    //             pr_purchase_request_item.quantity,
    //             payee.account_name as payee,
    //             payee.id as payee_id,
    //             pr_aoq_entries.amount,
    //             pr_aoq_entries.remark,
    //             pr_aoq_entries.is_lowest
    //             FROM pr_aoq_entries

    //             LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
    //             LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
    //             LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
    //             LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
    //             LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
    //             WHERE pr_aoq_entries.pr_aoq_id = :pr_aoq_id")
    //         ->bindValue(':pr_aoq_id', $pr_aoq_id)
    //         ->queryAll();
    // }
    // public function rfqItemData($id)
    // {
    //     $query = Yii::$app->db->createCommand("SELECT

    //     CAST(pr_rfq_item.id as CHAR(50)) as rfq_item_id,
    //     pr_stock.bac_code,
    //     unit_of_measure.unit_of_measure,
    //     pr_stock.stock_title,
    //     IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
    //     pr_purchase_request_item.quantity
    //      FROM pr_rfq_item
    //     LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
    //     LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
    //     LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id

    //     WHERE pr_rfq_id = :id")
    //         ->bindValue(':id', $id)
    //         ->queryAll();
    //     return json_encode($query);
    // }
    public function actionGetRfqInfo()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $rfqItems = PrRfq::findOne($id)->getItems();
            foreach ($rfqItems as $index => $item) {
                $rfqItems[$index]['rfq_item_id'] = $item['item_id'];
            }
            return json_encode($rfqItems);
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
            if (!Yii::$app->user->can('ro_procurement_admin')) {
                $user_data = User::getUserDetails();
                $query->andWhere('fk_office_id = :fk_office_id', ['fk_office_id' =>  $user_data->employee->office->id]);
            }
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
