<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\PrProjectProcurement;
use Yii;
use app\models\PrPurchaseRequest;
use app\models\PrPurchaseRequestAllotments;
use app\models\PrPurchaseRequestItem;
use app\models\PrPurchaseRequestSearch;
use app\models\PurchaseRequestIndex;
use app\models\PurchaseRequestIndexSearch;
use DateTime;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
    private function getPrItems($id)
    {
        return Yii::$app->db->createCommand("CALL GetPrItems(:id)")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function getPrAllotments($id)
    {
        return Yii::$app->db->createCommand("CALL GetPrAllotments(:id)")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function validatePpmp(
        $cse_type,
        $amount = 0,
        $qty = 0,
        $ppmp_item_id = '',
        $item_id = ''
    ) {

        $params = [];
        $sql  = '';

        if (!empty($item_id)) {
            $sql = 'AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'pr_purchase_request_item.id', $item_id], $params);
        }
        if ($cse_type === 'cse') {
            $query = Yii::$app->db->createCommand("SELECT 
            (IFNULL(supplemental_ppmp_cse.jan_qty,0)+
            IFNULL(supplemental_ppmp_cse.feb_qty,0)+
            IFNULL(supplemental_ppmp_cse.mar_qty,0)+
            IFNULL(supplemental_ppmp_cse.apr_qty,0)+
            IFNULL(supplemental_ppmp_cse.may_qty,0)+
            IFNULL(supplemental_ppmp_cse.jun_qty,0)+
            IFNULL(supplemental_ppmp_cse.jul_qty,0)+
            IFNULL(supplemental_ppmp_cse.aug_qty,0)+
            IFNULL(supplemental_ppmp_cse.sep_qty,0)+
            IFNULL(supplemental_ppmp_cse.oct_qty,0)+
            IFNULL(supplemental_ppmp_cse.nov_qty,0)+
            IFNULL(supplemental_ppmp_cse.dec_qty,0)) - IFNULL(ttlInPr.ttlPrQty,0) as bal_qty,
            
            (IFNULL(supplemental_ppmp_cse.jan_qty,0)+
                IFNULL(supplemental_ppmp_cse.feb_qty,0)+
              IFNULL(supplemental_ppmp_cse.mar_qty,0)+
              IFNULL(supplemental_ppmp_cse.apr_qty,0)+
              IFNULL(supplemental_ppmp_cse.may_qty,0)+
              IFNULL(supplemental_ppmp_cse.jun_qty,0)+
              IFNULL(supplemental_ppmp_cse.jul_qty,0)+
              IFNULL(supplemental_ppmp_cse.aug_qty,0)+
              IFNULL(supplemental_ppmp_cse.sep_qty,0)+
              IFNULL(supplemental_ppmp_cse.oct_qty,0)+
              IFNULL(supplemental_ppmp_cse.nov_qty,0)+
              IFNULL(supplemental_ppmp_cse.dec_qty,0)
            )* IFNULL(supplemental_ppmp_cse.amount,0) - IFNULL(ttlInPr.ttlPr,0) as bal_amt  
            
            
            
            FROM 
            supplemental_ppmp_cse
            LEFT JOIN 
             (SELECT 
            pr_purchase_request_item.fk_ppmp_cse_item_id,
            SUM(pr_purchase_request_item.quantity *pr_purchase_request_item.unit_cost) as ttlPr,
            SUM(pr_purchase_request_item.quantity) as ttlPrQty
            FROM pr_purchase_request_item
            WHERE pr_purchase_request_item.is_deleted = 0
            $sql
            GROUP BY pr_purchase_request_item.fk_ppmp_cse_item_id
            ) as ttlInPr ON supplemental_ppmp_cse.id = ttlInPr.fk_ppmp_cse_item_id
            WHERE
            supplemental_ppmp_cse.id  = :cse_or_non_cse_id", $params)
                ->bindValue(':cse_or_non_cse_id', $ppmp_item_id)
                ->queryOne();
            $bal_amt = floatval($query['bal_amt']);
            $bal_qty = intval($query['bal_qty']);
            $bal = $bal_amt - ($amount * $qty);
            $qty = $bal_qty - $qty;
            if ($bal < 0) {
                return  "Amount Cannot be more than " . number_format($bal_amt, 2);
            }
            if ($qty < 0) {
                return  "Quantity Cannot be more than $bal_qty";
            }
        } else if ($cse_type === 'non_cse' || $cse_type === 'fixed_expenses') {
            try {
                $query  = YIi::$app->db->createCommand("SELECT 
                IFNULL(supplemental_ppmp_non_cse_items.amount,0) - IFNULL(item_in_pr_total.total_pr_amt,0) as bal_amt
                FROM
                supplemental_ppmp_non_cse_items 
                LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
                LEFT JOIN unit_of_measure ON pr_stock.unit_of_measure_id  = unit_of_measure.id
                LEFT JOIN (SELECT 
                pr_purchase_request_item.fk_ppmp_non_cse_item_id,
                SUM(pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity) as total_pr_amt
                FROM pr_purchase_request_item
                WHERE 
                pr_purchase_request_item.is_deleted =0
                $sql
                GROUP BY
                pr_purchase_request_item.fk_ppmp_non_cse_item_id
                ) as item_in_pr_total ON supplemental_ppmp_non_cse_items.id = item_in_pr_total.fk_ppmp_non_cse_item_id
                 WHERE supplemental_ppmp_non_cse_items.id = :non_cse_item_id
                    ", $params)
                    ->bindValue(':non_cse_item_id', $ppmp_item_id)
                    ->queryOne();
                $bal_amt = floatval($query['bal_amt']);

                $bal = $bal_amt - ($amount * $qty);

                if ($bal < 0) {
                    return  "Amount Cannot be more than " . number_format($bal_amt, 2);
                }
            } catch (ErrorException $e) {
                return $e->getMessage();
                die();
            }
        }
        return true;
    }
    // public function checkAllotmentBalance($allotment_id, $amount = 0, $entry_id = null)
    // {

    //     $params = [];
    //     $sql = '';
    //     if (!empty($entry_id)) {
    //         $sql = 'AND ';
    //         $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'pr_purchase_request_allotments.id', $entry_id], $params);
    //     }
    //     $balance = Yii::$app->db->createCommand("CALL GetAllotmentBalance(:allotment_id,:entry_id,NULL,NULL,NULL,NULL)")
    //         ->bindValue(':allotment_id', $allotment_id)
    //         ->bindValue(':entry_id', $entry_id)

    //         ->queryScalar();
    //     $cur_balance = floatval($balance) - floatval($amount);
    //     if ($cur_balance < 0) {
    //         return  "Allotment Amount Cannot be more than " . number_format($balance, 2);
    //     }
    //     return true;
    // }
    // CALCULATE PR ITEMS VS PR ALLOTMENTS TOTAL
    public function calculateItemsTotal($pr_items, $pr_allotments_amt)
    {
        $pr_grnd_ttl = 0;
        $pr_allotment_grnd_ttl = floatval(array_sum($pr_allotments_amt));
        foreach ($pr_items as $item) {
            $ttl = intval($item['quantity']) * floatval($item['unit_cost']);
            $pr_grnd_ttl = $pr_grnd_ttl + floatval($ttl);
        }

        if (floatval($pr_allotment_grnd_ttl) !== floatval($pr_grnd_ttl)) {
            return false;
        }

        return true;
    }

    /**
     * Lists all PrPurchaseRequest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseRequestIndexSearch();
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

        $office_division_unit_purpose = '';
        $model = $this->findModel($id);
        if (!empty($model->fk_supplemental_ppmp_noncse_id)) {
            $office_division_unit_purpose = Yii::$app->db->createCommand("SELECT 
    office.office_name,
    divisions.division,
    division_program_unit.`name` as division_program_unit,
    supplemental_ppmp_non_cse.activity_name as purpose,
    supplemental_ppmp.is_supplemental
     FROM pr_purchase_request
    INNER JOIN supplemental_ppmp_non_cse ON pr_purchase_request.fk_supplemental_ppmp_noncse_id = supplemental_ppmp_non_cse.id
    LEFT JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
    LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
    LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
    LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
    WHERE pr_purchase_request.id = :id")
                ->bindValue(':id', $model->id)
                ->queryOne();
        } else if (!empty($model->fk_supplemental_ppmp_cse_id)) {
            $office_division_unit_purpose = Yii::$app->db->createCommand("SELECT 
    office.office_name,
    divisions.division,
    division_program_unit.`name` as division_program_unit,
    pr_stock.stock_title as purpose,
    supplemental_ppmp.is_supplemental
     FROM pr_purchase_request
    INNER JOIN supplemental_ppmp_cse ON pr_purchase_request.fk_supplemental_ppmp_cse_id = supplemental_ppmp_cse.id
    LEFT JOIN supplemental_ppmp ON supplemental_ppmp_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
    LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
    LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
    LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
    LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
    WHERE pr_purchase_request.id = :id")
                ->bindValue(':id', $model->id)
                ->queryOne();
        } else {
            $office_division_unit_purpose = Yii::$app->db->createCommand("SELECT 
    office.office_name,
      divisions.division
  FROM `pr_purchase_request`
  LEFT JOIN office ON pr_purchase_request.fk_office_id = office.id
  LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
  WHERE 
  pr_purchase_request.id = :id")
                ->bindValue(':id', $model->id)
                ->queryOne();
        }

        return $this->render('view', [
            'model' => $model,
            'items' => $this->getPrItems($id),
            'allotment_items' => $this->getPrAllotments($id),
            'office_division_unit_purpose' => $office_division_unit_purpose

        ]);
    }

    /**
     * Creates a new PrPurchaseRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertPrItems($model_id, $items = [], $isUpdate = false)
    {
        if (empty($items)) {
            return 'PR Cannot be Empty';
        }

        $c = 1;
        if ($isUpdate) {
            $params = [];
            $item_ids = array_column($items, 'item_id');
            $sql = '';
            if (!empty($item_ids)) {
                $sql = 'AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
            }
            Yii::$app->db->createCommand("UPDATE pr_purchase_request_item SET is_deleted = 1 WHERE 
                     pr_purchase_request_item.pr_purchase_request_id = :id  $sql", $params)
                ->bindValue(':id', $model_id)
                ->execute();
        }
        $cseType = '';
        $cse_or_non_cse_id = '';
        foreach ($items as $i => $item) {

            if (empty($item['quantity'])) {
                return 'Quantity Cannot be blank in item ' . $c;
            }
            if (empty($item['unit_cost'])) {
                return 'Unit Cost Cannot be blank in item ' . $c;
            }
            if (!empty($item['cse_item_id'])) {
                $cseType = 'cse';
                $cse_or_non_cse_id = $item['cse_item_id'];
            }
            if (!empty($item['non_cse_item_id'])) {
                $cseType = 'non_cse';
                $cse_or_non_cse_id = $item['non_cse_item_id'];
            }

            try {
                $validate =  $this->validatePpmp(

                    $cseType,
                    $item['unit_cost'],
                    $item['quantity'],
                    $cse_or_non_cse_id,
                    !empty($item['item_id']) ? $item['item_id'] : ''
                );
                if ($validate !== true) {
                    throw new ErrorException($validate . ' in Specification table item ' . $c);
                }
                if (!empty($item['item_id'])) {
                    $q =  PrPurchaseRequestItem::findOne($item['item_id']);
                } else {
                    $q = new PrPurchaseRequestItem();
                    $q->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                }
                $q->pr_purchase_request_id = $model_id;
                $q->pr_stock_id =   $item['pr_stocks_id'];
                $q->quantity =   $item['quantity'];
                $q->unit_cost =   $item['unit_cost'];
                $q->unit_of_measure_id =   $item['unit_of_measure_id'];
                $q->specification = empty($item['specification']) ? null :    $item['specification'];

                if (!empty($item['cse_item_id'])) {
                    $q->fk_ppmp_cse_item_id = $item['cse_item_id'];
                }
                if (!empty($item['non_cse_item_id'])) {
                    $q->fk_ppmp_non_cse_item_id = $item['non_cse_item_id'];
                }
                if (!$q->validate()) {
                    throw new ErrorException(json_encode($q->errors));
                }
                if (!$q->save(false)) {
                    throw new ErrorException('PR items Save error');
                }
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
            $c++;
        }
        return true;
    }
    public function insertPrAllotments($model_id, $allotment_items = [])
    {
        $i = 1;

        $params = [];
        $item_ids = array_column($allotment_items, 'pr_allotment_item_id');
        $sql = '';
        if (!empty($item_ids)) {
            $sql = 'AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
        }
        Yii::$app->db->createCommand("UPDATE pr_purchase_request_allotments SET is_deleted = 1 WHERE 
             pr_purchase_request_allotments.fk_purchase_request_id = :id  $sql", $params)
            ->bindValue(':id', $model_id)
            ->execute();
        foreach ($allotment_items as  $item) {
            if (empty($item['gross_amount'])) {
                throw new ErrorException('Gross Amount Cannot be Blank');
            }
            if (empty($item['allotment_id'])) {
                throw new ErrorException('Allotment  Cannot be Blank');
            }
            try {

                if (!empty($item['pr_allotment_item_id'])) {
                    $ai = PrPurchaseRequestAllotments::findOne($item['pr_allotment_item_id']);
                } else {
                    $ai = new PrPurchaseRequestAllotments();
                }
                $validate_allotment = MyHelper::checkAllotmentBalance(
                    $item['allotment_id'],
                    $item['gross_amount'],
                    !empty($item['pr_allotment_item_id']) ? $item['pr_allotment_item_id'] : null
                );
                if ($validate_allotment !== true) {
                    throw new ErrorException($validate_allotment . ' in Allotment Table Item ' . $i);
                }

                $ai->fk_purchase_request_id = $model_id;
                $ai->fk_record_allotment_entries_id = $item['allotment_id'];
                $ai->amount = $item['gross_amount'];
                if (!$ai->validate()) {

                    throw new ErrorException(json_encode($ai->errors));
                }
                if (!$ai->save(false)) {
                    throw new ErrorException('Allotment Save Failed');
                }
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
            $i++;
        }
        return true;
    }
    public function employeeData($employee_id = '')
    {
        if (empty($employee_id)) {
            return [];
        }
        return Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name,position FROM employee_search_view WHERE employee_id = :id")
            ->bindValue(':id', $employee_id)
            ->queryAll();
    }


    public function actionCreate()
    {
        $model = new PrPurchaseRequest();

        if (Yii::$app->request->isPost) {


            $office_id = !empty($_POST['office_id']) ? $_POST['office_id'] : '';
            $division_id = !empty($_POST['division_id']) ? $_POST['division_id'] : '';
            $division_program_unit_id = !empty($_POST['division_program_unit_id']) ? $_POST['division_program_unit_id'] : '';
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $office_id = $user_data->office->id;
                $division_id = $user_data->divisionName->id;
            }
            // $ppmp_id = !empty($_POST['ppmp_id']) ? $_POST['ppmp_id'] : '';

            $book_id = !empty($_POST['book_id']) ? $_POST['book_id'] : '';
            $purpose = !empty($_POST['purpose']) ? $_POST['purpose'] : '';
            $requested_by_id = !empty($_POST['requested_by_id']) ? $_POST['requested_by_id'] : '';
            $approved_by_id = !empty($_POST['approved_by_id']) ? $_POST['approved_by_id'] : '';
            $pr_items = !empty($_POST['pr_items']) ? $_POST['pr_items'] : [];
            $allotment_items = !empty($_POST['allotment_items']) ? $_POST['allotment_items'] : [];
            $budget_year = !empty($_POST['budget_year']) ? $_POST['budget_year'] : [];
            $date_now = new DateTime();
            $model->date = $date_now->format('Y-m-d');
            // return var_dump($_POST['segment']);
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();


            $model->book_id = $book_id;
            $model->pr_project_procurement_id = '';
            $model->purpose = $purpose;
            $model->requested_by_id = $requested_by_id;
            $model->approved_by_id = $approved_by_id;
            $model->budget_year = $budget_year;
            $model->fk_office_id = $office_id;
            $model->fk_division_id = $division_id;
            $model->fk_division_program_unit_id = $division_program_unit_id;
            // $id_arr = explode('-', $ppmp_id);
            // $cse_or_non_cse_id = $id_arr[0];
            // $cse_type = $id_arr[1];
            // if ($cse_type === 'cse') {

            //     $model->fk_supplemental_ppmp_noncse_id = null;
            //     $model->fk_supplemental_ppmp_cse_id  = $cse_or_non_cse_id;
            // } else if ($cse_type === 'non_cse') {

            //     $model->fk_supplemental_ppmp_noncse_id = $cse_or_non_cse_id;
            //     $model->fk_supplemental_ppmp_cse_id  = null;
            // } else {
            //     $model->fk_supplemental_ppmp_noncse_id = null;
            //     $model->fk_supplemental_ppmp_cse_id  = null;
            //     $model->is_fixed_expense  = 1;
            // }
            $model->pr_number = $this->getPrNumber($model->date, $model->fk_office_id, $model->fk_division_id);



            $transaction = Yii::$app->db->beginTransaction();

            $allotment_items_ttl = array_column($allotment_items, 'gross_amount');
            $validate_ttl = $this->calculateItemsTotal($pr_items, $allotment_items_ttl);

            if ($validate_ttl !== true) {
                return json_encode(['isSuccess' => false, 'error_message' => 'The sum of the allotments does not match the sum of the specifications.']);
            }

            try {
                if (!$model->validate()) {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
                if ($model->save(false)) {
                    $insert_items = $this->insertPrItems(
                        $model->id,
                        $pr_items
                        // $cse_or_non_cse_id,
                        // $cse_type
                    );
                    if ($insert_items !== true) {
                        $transaction->rollBack();
                        return json_encode(['isSuccess' => false, 'error_message' => $insert_items]);
                    }
                    $insert_allotments = $this->insertPrAllotments($model->id, $allotment_items);
                    if ($insert_allotments !== true) {
                        $transaction->rollBack();
                        return json_encode(['isSuccess' => false, 'error_message' => $insert_allotments]);
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'action' => 'pr-purchase-request/create',
            'requested_by' => [],
            'approved_by' => [],
            'divisions_list' => Yii::$app->memem->getDivisions(),
            'mfo_list' => Yii::$app->memem->getMfoPapCode(),
            'fund_source_list' => Yii::$app->memem->getFundSources(),
            'offices' => Yii::$app->memem->getOffices(),
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

        if ($model->is_final && !Yii::$app->user->can('super-user')) {
            return $this->goHome();
        }
        $check_rfqs = YIi::$app->db->createCommand("SELECT id FROM pr_rfq WHERE pr_purchase_request_id = :id")
            ->bindValue(':id', $model->id)
            ->queryAll();
        if (!empty($check_rfqs)  && !Yii::$app->user->can('super-user')) {
            return $this->goBack();
        }

        if (Yii::$app->request->isPost) {
            $old = $this->findModel($id);

            // $old_date =  $old->date;
            // $new_date = $model->date;
            $ppmp_id = !empty($_POST['ppmp_id']) ? $_POST['ppmp_id'] : '';
            $book_id = !empty($_POST['book_id']) ? $_POST['book_id'] : '';
            $purpose = !empty($_POST['purpose']) ? $_POST['purpose'] : '';
            $requested_by_id = !empty($_POST['requested_by_id']) ? $_POST['requested_by_id'] : '';
            $approved_by_id = !empty($_POST['approved_by_id']) ? $_POST['approved_by_id'] : '';
            $pr_items = !empty($_POST['pr_items']) ? $_POST['pr_items'] : [];
            $allotment_items = !empty($_POST['allotment_items']) ? $_POST['allotment_items'] : [];

            $office_id = !empty($_POST['office_id']) ? $_POST['office_id'] : '';
            $division_id = !empty($_POST['division_id']) ? $_POST['division_id'] : '';
            $division_program_unit_id = !empty($_POST['division_program_unit_id']) ? $_POST['division_program_unit_id'] : '';
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $office_id = $user_data->office->id;
                $division_id = $user_data->divisionName->id;
            }
            $model->fk_office_id = $office_id;
            $model->fk_division_id = $division_id;
            $model->fk_division_program_unit_id = $division_program_unit_id;
            $date_now = new DateTime();
            $model->date = $date_now->format('Y-m-d');
            $model->book_id = $book_id;
            $model->pr_project_procurement_id = '';
            $model->purpose = $purpose;
            $model->requested_by_id = $requested_by_id;
            $model->approved_by_id = $approved_by_id;
            // $id_arr = explode('-', $ppmp_id);
            // $cse_or_non_cse_id = $id_arr[0];
            // $cse_type = $id_arr[1];
            // if ($cse_type === 'cse') {
            //     $model->fk_supplemental_ppmp_noncse_id = null;
            //     $model->fk_supplemental_ppmp_cse_id  = $cse_or_non_cse_id;
            // } else if ($cse_type === 'non_cse') {

            //     $model->fk_supplemental_ppmp_noncse_id = $cse_or_non_cse_id;
            //     $model->fk_supplemental_ppmp_cse_id  = null;
            // }


            $transaction = Yii::$app->db->beginTransaction();
            $allotment_items_ttl = array_column($allotment_items, 'gross_amount');
            $validate_ttl = $this->calculateItemsTotal($pr_items, $allotment_items_ttl);

            if ($validate_ttl !== true) {
                return json_encode(['isSuccess' => false, 'error_message' => 'The sum of the allotments does not match the sum of the specifications.']);
            }

            try {
                if (!$model->validate()) {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
                if ($model->save(false)) {

                    $insert_items = $this->insertPrItems(
                        $model->id,
                        $pr_items,
                        true
                    );
                    if ($insert_items !== true) {
                        $transaction->rollBack();
                        return json_encode(['isSuccess' => false, 'error_message' => $insert_items]);
                    }
                    $insert_allotments = $this->insertPrAllotments($model->id, $allotment_items);
                    if ($insert_allotments !== true) {
                        $transaction->rollBack();
                        return json_encode(['isSuccess' => false, 'error_message' => $insert_allotments]);
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'action' => 'pr-purchase-request/update',
            'items' => ArrayHelper::index($this->getPrItems($id), null, 'project_name'),
            'allotment_items' => $this->getPrAllotments($id),
            'requested_by' => $this->employeeData($model->requested_by_id),
            'approved_by' =>  $this->employeeData($model->approved_by_id),
            'divisions_list' => Yii::$app->memem->getDivisions(),
            'mfo_list' => Yii::$app->memem->getMfoPapCode(),
            'fund_source_list' => Yii::$app->memem->getFundSources(),
            'offices' => Yii::$app->memem->getOffices(),
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
    public function getPrNumber($d, $office_id, $division_id)
    {
        $office = Yii::$app->db->createCommand("SELECT office_name FROM office WHERE office.id = :id")->bindValue(':id', $office_id)->queryScalar();
        $division = Yii::$app->db->createCommand("SELECT division FROM divisions WHERE divisions.id = :id")->bindValue(':id', $division_id)->queryScalar();

        $pr_office = $office . '-' . $division;
        $date = DateTime::createFromFormat('Y-m-d', $d)->format('Y-m-d');
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_number,'-',-1) AS UNSIGNED) as last_number 
        FROM pr_purchase_request
        WHERE pr_purchase_request.date = :_date
        AND 
        pr_purchase_request.pr_number LIKE :pr_number
         ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':_date', $date)
            ->bindValue(':pr_number', '%' . $pr_office . '%')
            ->queryScalar();

        $num  = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $final = '';
        for ($i =  strlen($num); $i < 4; $i++) {
            $final .= 0;
        }



        return  strtoupper($pr_office) . '-' . $date . '-' . $final . $num;
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

            // $pr_items_data = Yii::$app->db->createCommand("SELECT 
            //     pr_purchase_request_item.id as pr_item_id,
            //     pr_stock.bac_code,
            // pr_stock.stock_title,
            // unit_of_measure.unit_of_measure,
            // IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
            // pr_purchase_request_item.unit_cost,
            // pr_purchase_request_item.quantity,
            // pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity as total_cost
            // FROM pr_purchase_request_item 
            // LEFT JOIN pr_stock  ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            // LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            // WHERE pr_purchase_request_item.pr_purchase_request_id =:id
            // AND pr_purchase_request_item.is_deleted = 0
            // ")
            //     ->bindValue(':id', $_POST['id'])
            //     ->queryAll();

            $pr_items_data = $this->getPrItems($_POST['id']);
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
    public function actionSearchPpmp($page = 1, $q = null, $id = null, $budget_year = '', $office_id = '', $division_id = '')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
            $division_id = $user_data->divisionName->id;
        }

        if (!is_null($q)) {
            $query = new Query();
            $query->select([" id, UPPER(`stock_or_act_name`) as text"])
                ->from('pr_ppmp_search_view')
                ->where(['like', 'stock_or_act_name', $q])
                ->andwhere('pr_ppmp_search_view.budget_year = :budget_year', ['budget_year' => $budget_year])
                ->andwhere('pr_ppmp_search_view.fk_office_id = :fk_office_id', ['fk_office_id' => $office_id])
                ->andwhere('pr_ppmp_search_view.fk_division_id = :fk_division_id', ['fk_division_id' => $division_id])
                ->orFilterWhere([
                    'and',
                    ['like', 'stock_or_act_name', $q],
                    ['=', 'pr_ppmp_search_view.fk_office_id', 'fixed_expenses'],
                    ['=', 'pr_ppmp_search_view.fk_division_id', 'fixed_expenses']
                ]);
            $query->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            // return json_encode($command->getRawSql());
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }

        return $out;
    }
    public function actionGetPpmpItems()
    {
        if (Yii::$app->request->isPost) {

            $ppmp_id = $_POST['id'];
            $office_id = !empty($_POST['office']) ? $_POST['office'] : '';
            $division_id = !empty($_POST['division']) ? $_POST['division'] : '';
            $id_arr = explode('-', $ppmp_id);
            $id = $id_arr[0];
            $type = $id_arr[1];
            // return json_encode($id_arr);
            $params = [];
            $sql = '';
            // if (!empty($id_arr[2])) {
            //     $sql .= ' AND ';
            //     $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['=', 'supplemental_ppmp_non_cse_items.id', $id_arr[2]], $params);
            //     // return $id_arr[2];
            // }
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $office_id = $user_data->office->id;
                $division_id = $user_data->divisionName->id;
            }

            $res = [];
            if ($type === 'cse') {
                $res = Yii::$app->db->createCommand("SELECT 
                supplemental_ppmp_cse.id as item_id,
                  'cse_item_id' as cse_type,
                '' as `description`,
                pr_stock.id as stock_id,
                pr_stock.stock_title,
                pr_stock.amount as unit_cost,
                IFNULL(unit_of_measure.unit_of_measure,'') as unit_of_measure,
                IFNULL(unit_of_measure.id,'') as unit_of_measure_id,
                supplemental_ppmp_cse.amount,(
                IFNULL(supplemental_ppmp_cse.jan_qty,0)+
                IFNULL(supplemental_ppmp_cse.feb_qty,0)+
                IFNULL(supplemental_ppmp_cse.mar_qty,0)+
                IFNULL(supplemental_ppmp_cse.apr_qty,0)+
                IFNULL(supplemental_ppmp_cse.may_qty,0)+
                IFNULL(supplemental_ppmp_cse.jun_qty,0)+
                IFNULL(supplemental_ppmp_cse.jul_qty,0)+
                IFNULL(supplemental_ppmp_cse.aug_qty,0)+
                IFNULL(supplemental_ppmp_cse.sep_qty,0)+
                IFNULL(supplemental_ppmp_cse.oct_qty,0)+
                IFNULL(supplemental_ppmp_cse.nov_qty,0)+
                IFNULL(supplemental_ppmp_cse.dec_qty,0))- IFNULL(ppmp_in_pr.total_pr_qty,0) as bal_qty,
           
                (IFNULL(supplemental_ppmp_cse.jan_qty,0)+
                IFNULL(supplemental_ppmp_cse.feb_qty,0)+
              IFNULL(supplemental_ppmp_cse.mar_qty,0)+
              IFNULL(supplemental_ppmp_cse.apr_qty,0)+
              IFNULL(supplemental_ppmp_cse.may_qty,0)+
              IFNULL(supplemental_ppmp_cse.jun_qty,0)+
              IFNULL(supplemental_ppmp_cse.jul_qty,0)+
              IFNULL(supplemental_ppmp_cse.aug_qty,0)+
              IFNULL(supplemental_ppmp_cse.sep_qty,0)+
              IFNULL(supplemental_ppmp_cse.oct_qty,0)+
              IFNULL(supplemental_ppmp_cse.nov_qty,0)+
              IFNULL(supplemental_ppmp_cse.dec_qty,0)
            )* IFNULL(supplemental_ppmp_cse.amount,0) - IFNULL(ppmp_in_pr.total_pr_amt,0) as bal_amt    
                 FROM supplemental_ppmp_cse
                LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id  = pr_stock.id
                LEFT JOIN unit_of_measure ON supplemental_ppmp_cse.fk_unit_of_measure_id = unit_of_measure.id
                LEFT JOIN (SELECT 
            pr_purchase_request_item.fk_ppmp_cse_item_id,
            SUM(pr_purchase_request_item.quantity *pr_purchase_request_item.unit_cost) as total_pr_amt,
            SUM(pr_purchase_request_item.quantity) as total_pr_qty
            FROM pr_purchase_request_item
            WHERE pr_purchase_request_item.is_deleted = 0
            GROUP BY pr_purchase_request_item.fk_ppmp_cse_item_id) as ppmp_in_pr ON supplemental_ppmp_cse.id = ppmp_in_pr.fk_ppmp_cse_item_id
                 WHERE supplemental_ppmp_cse.id = :id")
                    ->bindValue(':id', $id)
                    ->queryAll();
            } else if ($type === 'non_cse') {
                $res = Yii::$app->db->createCommand("SELECT 
                supplemental_ppmp_non_cse_items.id as item_id,
                pr_stock.id as stock_id,
                pr_stock.stock_title,
                IFNULL(supplemental_ppmp_non_cse_items.amount,0) as unit_cost,
                IFNULL(unit_of_measure.unit_of_measure,'') as unit_of_measure,
                IFNULL(unit_of_measure.id,'') as unit_of_measure_id,
                IFNULL(supplemental_ppmp_non_cse_items.amount,0) - IFNULL(item_in_pr_total.total_pr_amt,0) as bal_amt,
               1 as bal_qty,
                supplemental_ppmp_non_cse_items.description,
                'non_cse_item_id' as cse_type
                FROM
                supplemental_ppmp_non_cse 
                LEFT JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
                LEFT JOIN unit_of_measure ON supplemental_ppmp_non_cse_items.fk_unit_of_measure_id  = unit_of_measure.id
                LEFT JOIN (SELECT 
									pr_purchase_request_item.fk_ppmp_non_cse_item_id,
									SUM(pr_purchase_request_item.quantity) as ttl_qty,
									SUM(IFNULL(pr_purchase_request_item.unit_cost,0) * IFNULL(pr_purchase_request_item.quantity,0)) as total_pr_amt
									FROM pr_purchase_request_item
									WHERE 
									pr_purchase_request_item.is_deleted =0

									GROUP BY
									pr_purchase_request_item.fk_ppmp_non_cse_item_id
									 ) as item_in_pr_total ON supplemental_ppmp_non_cse_items.id = item_in_pr_total.fk_ppmp_non_cse_item_id
                WHERE supplemental_ppmp_non_cse.id =  :id
                AND supplemental_ppmp_non_cse_items.is_deleted  !=1
                $sql
                
                ", $params)
                    ->bindValue(':id', $id)
                    ->queryAll();
            } else if ($type === 'fixed_expenses') {
                $res = Yii::$app->db->createCommand("SELECT 
                supplemental_ppmp_non_cse_items.id as item_id,
                pr_stock.id as stock_id,
                pr_stock.stock_title,
                IFNULL(supplemental_ppmp_non_cse_items.amount,0) as unit_cost,
                IFNULL(unit_of_measure.unit_of_measure,'') as unit_of_measure,
                IFNULL(unit_of_measure.id,'') as unit_of_measure_id,
                IFNULL(supplemental_ppmp_non_cse_items.amount,0) - IFNULL(item_in_pr_total.total_pr_amt,0) as bal_amt,
               1 as bal_qty,
                supplemental_ppmp_non_cse_items.description,
                'non_cse_item_id' as cse_type
                FROM
                supplemental_ppmp_non_cse 
                LEFT JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
                LEFT JOIN unit_of_measure ON supplemental_ppmp_non_cse_items.fk_unit_of_measure_id  = unit_of_measure.id
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
                LEFT JOIN (SELECT 
									pr_purchase_request_item.fk_ppmp_non_cse_item_id,
									SUM(pr_purchase_request_item.quantity) as ttl_qty,
									SUM(IFNULL(pr_purchase_request_item.unit_cost,0) * IFNULL(pr_purchase_request_item.quantity,0)) as total_pr_amt
									FROM pr_purchase_request_item
									WHERE 
									pr_purchase_request_item.is_deleted =0

									GROUP BY
									pr_purchase_request_item.fk_ppmp_non_cse_item_id
									 ) as item_in_pr_total ON supplemental_ppmp_non_cse_items.id = item_in_pr_total.fk_ppmp_non_cse_item_id
                WHERE supplemental_ppmp_non_cse.type =  'fixed expenses'
                AND supplemental_ppmp.fk_office_id = :office_id AND supplemental_ppmp.fk_division_id = :division_id")
                    ->bindValue(':office_id', $office_id)
                    ->bindValue(':division_id', $division_id)
                    ->queryAll();
            }
            return json_encode($res);
        }
    }
}
