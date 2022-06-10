<?php

namespace frontend\controllers;

use app\models\PrProjectProcurement;
use Yii;
use app\models\PrPurchaseRequest;
use app\models\PrPurchaseRequestItem;
use app\models\PrPurchaseRequestSearch;
use DateTime;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrPurchaseRequestController implements the CRUD actions for PrPurchaseRequest model.
 */
class PrPurchaseRequestController extends Controller
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
                    'search-pr',
                    'get-items',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'search-pr',
                            'get-items',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => [
                            'purchase_request'
                        ]
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'search-pr',
                            'get-items',
                        ],
                        'allow' => true,
                        'roles' => [
                            'super-user',
                        ]
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
     * Lists all PrPurchaseRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrPurchaseRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrPurchaseRequest model.
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
     * Creates a new PrPurchaseRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertPrItems($model_id, $pr_stocks_id, $unit_cost, $quantity, $specification, $pr_item_id, $unit_of_measure_id)
    {

        foreach ($pr_stocks_id as $i => $val) {

            if (empty($pr_item_id[$i])) {

                $item = new PrPurchaseRequestItem();
                $item->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            } else {
                $item =  PrPurchaseRequestItem::findOne($pr_item_id[$i]);
            }

            $item->pr_purchase_request_id = $model_id;
            $item->pr_stock_id = $val;
            $item->quantity = $quantity[$i];
            $item->unit_cost = $unit_cost[$i];
            $item->unit_of_measure_id = $unit_of_measure_id[$i];
            $item->specification = empty($specification[$i]) ? null : $specification[$i];
            if ($item->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new PrPurchaseRequest();

        if ($model->load(Yii::$app->request->post())) {

            // return var_dump($_POST['segment']);
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->pr_number = $this->getPrNumber($model->date, $model->pr_project_procurement_id);

            $pr_stocks_id = [];
            $specification = [];
            $unit_of_measure_id = [];
            $pr_item_id = [];

            $unit_cost = $_POST['unit_cost'];
            $quantity = $_POST['quantity'];
            if (empty($_POST['pr_stocks_id'])) {
                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
            } else {
                $pr_stocks_id = $_POST['pr_stocks_id'];
                $specification = $_POST['specification'];
                $unit_of_measure_id = $_POST['unit_of_measure_id'];
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag =  $this->insertPrItems(
                            $model->id,
                            $pr_stocks_id,
                            $unit_cost,
                            $quantity,
                            $specification,
                            $pr_item_id,
                            $unit_of_measure_id
                        );
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('Error');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrPurchaseRequest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->is_final) {
            return $this->goHome();
        }
        $check_rfqs = YIi::$app->db->createCommand("SELECT id FROM pr_rfq WHERE pr_purchase_request_id = :id")
            ->bindValue(':id', $model->id)
            ->queryAll();
        if (!empty($check_rfqs)) {
            return $this->goBack();
        }

        if ($model->load(Yii::$app->request->post())) {
            $old = $this->findModel($id);

            $old_date =  $old->date;
            $new_date = $model->date;


            $pr_stocks_id = [];
            $specification = [];
            $unit_of_measure_id = [];
            $pr_item_id = [];

            if (empty($_POST['pr_stocks_id'])) {
                return $this->render('update', [
                    'model' => $model,
                    'error' => 'Please Insert Items'
                ]);
            } else {
                $pr_stocks_id = $_POST['pr_stocks_id'];
                $specification = $_POST['specification'];
                $unit_of_measure_id = $_POST['unit_of_measure_id'];
            }
            if ($old_date !== $new_date) {
                $arr =  explode('-', $model->pr_number);
                $number = $arr[5];
                $province = $arr[0];
                $division = $arr[2];

                $model->pr_number  = $province . '-' . $division . '-' . $model->date . '-' . $number;
            }
            if (!empty($_POST['pr_item_id'])) {
                $pr_item_id = $_POST['pr_item_id'];
            }
            $unit_cost = $_POST['unit_cost'];
            // return var_dump($unit_cost);
            $quantity = $_POST['quantity'];
            // return json_encode(

            //     [`
            //         $pr_stocks_id,
            //         $pr_item_id
            //     ]
            // );


            // var_dump($specification);
            // echo "<br>";
            //     var_dump($pr_item_id);
            //     $x = in_array(12, $pr_item_id);
            //   echo   $x;
            //     die();



            $transaction = Yii::$app->db->beginTransaction();


            $query = Yii::$app->db->createCommand("SELECT id FROM pr_purchase_request_item WHERE pr_purchase_request_id = :pr_id")
                ->bindValue(':pr_id', $model->id)->queryAll();

            foreach ($query as $val) {


                if (in_array($val['id'], $pr_item_id) != 1) {

                    $check_query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT 1 FROM pr_rfq_item WHERE pr_rfq_item.pr_purchase_request_item_id =  :id) ")
                        ->bindValue(':id', $val['id'])
                        ->queryScalar();
                    if (intval($check_query) === 0) {
                        $q = PrPurchaseRequestItem::findOne($val['id']);
                        $q->delete();
                    } else {


                        return $this->render('update', [
                            'model' => $model,
                            'error' => "PR Item Cannot Remove Having RFQ's"
                        ]);
                    }
                }
            }

            // $query = (new \yii\db\Query())
            //     ->delete()
            //     ->from('pr_purchase_request_item')
            //     ->where("$sql", $params);

            // Yii::$app->db->createCommand("DELETE FROM pr_purchase_request_item WHERE pr_purchase_request_id = :id")
            //     ->bindValue(':id', $model->id)
            //     ->query();
            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag = $this->insertPrItems(
                            $model->id,
                            $pr_stocks_id,
                            $unit_cost,
                            $quantity,
                            $specification,
                            $pr_item_id,
                            $unit_of_measure_id
                        );
                    } else {
                        return 'error';
                    }
                }
                if ($flag) {
                    $transaction->commit();

                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();

                    return json_encode('Error');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();

                return json_encode($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrPurchaseRequest model.
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
     * Finds the PrPurchaseRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrPurchaseRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrPurchaseRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getPrNumber($d, $pr_project_procurement_id)
    {
        $division = Yii::$app->db->createCommand("SELECT
                    pr_office.division,
                    pr_project_procurement.*
                    FROM pr_project_procurement
                    INNER JOIN pr_office ON pr_project_procurement.pr_office_id = pr_office.id
                    WHERE
                    pr_project_procurement.id = :pr_project_procurement_id")
            ->bindValue(':pr_project_procurement_id', $pr_project_procurement_id)
            ->queryScalar();

        $province = 'RO';
        $date = DateTime::createFromFormat('Y-m-d', $d)->format('Y-m-d');
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_number,'-',-1) AS UNSIGNED) as last_number 
        FROM pr_purchase_request
        WHERE pr_purchase_request.date = :_date
        AND 
        pr_purchase_request.pr_number LIKE :division
         ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':_date', $date)
            ->bindValue(':division', '%' . $division . '%')
            ->queryScalar();

        $num  = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $final = '';
        for ($i =  strlen($num); $i < 4; $i++) {
            $final .= 0;
        }



        return $province . '-' . strtoupper($division) . '-' . $date . '-' . $final . $num;
    }
    public function actionSearchPr($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" id, `pr_number` as text"])
                ->from('pr_purchase_request')
                ->where(['like', 'pr_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    public function actionGetItems()
    {

        if ($_POST) {

            $pr_items_data = Yii::$app->db->createCommand("SELECT 
                pr_purchase_request_item.id as pr_item_id,
                pr_stock.bac_code,
            pr_stock.stock_title,
            unit_of_measure.unit_of_measure,
            IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
            pr_purchase_request_item.unit_cost,
            pr_purchase_request_item.quantity,
            pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity as total_cost
            FROM pr_purchase_request_item 
            LEFT JOIN pr_stock  ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            WHERE pr_purchase_request_item.pr_purchase_request_id =:id")
                ->bindValue(':id', $_POST['id'])
                ->queryAll();
            $pr_data = Yii::$app->db->createCommand("SELECT 
                    pr_purchase_request.pr_number,
                    pr_purchase_request.date as date_propose,
                    books.`name` as book_name,
                    pr_purchase_request.purpose,
                    requested_by.employee_name as requested_by,
                    approved_by.employee_name as approved_by,
                    pr_project_procurement.title as project_title,
                    pr_project_procurement.amount as project_amount,
                    pr_office.office,
                    pr_office.division,
                    pr_office.unit,
                    prepared_by.employee_name as prepared_by
                    FROM `pr_purchase_request`
                    LEFT JOIN employee_search_view  as requested_by ON  pr_purchase_request.requested_by_id = requested_by.employee_id
                    LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
                    LEFT JOIN books ON pr_purchase_request.book_id = books.id
                    LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
                    LEFT JOIN pr_office ON pr_project_procurement.pr_office_id = pr_office.id
                    LEFT JOIN employee_search_view as prepared_by ON pr_project_procurement.employee_id = prepared_by.employee_id
                    WHERE 
                    pr_purchase_request.id = :id
            
            ")
                ->bindValue(':id', $_POST['id'])
                ->queryOne();
            return json_encode([
                'pr_data' => $pr_data,
                'pr_items_data' => $pr_items_data
            ]);
        }
    }
    public function actionFinal($id)
    {
        $model = $this->findModel($id);

        if ($model->is_final) {
            $model->is_final = 0;
        } else {
            $model->is_final = 1;
        }
        if ($model->save(false))
            return $this->render('view', [
                'model' => $model,
            ]);
    }
}
