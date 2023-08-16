<?php

namespace frontend\controllers;

use app\models\Iar;
use app\models\InspectionReport;
use app\models\InspectionReportItems;
use app\models\InspectionReportNoPoItems;
use app\models\NoPoInspectionReportItems;
use app\models\Office;
use app\models\PurchaseOrdersForRfiSearch;
use Yii;
use app\models\RequestForInspection;
use app\models\RequestForInspectionItems;
use app\models\RequestForInspectionSearch;
use app\models\RfiWithoutPoItems;
use aryelds\sweetalert\SweetAlert;
use DateTime;
use ErrorException;
use kartik\form\ActiveForm;
use PHPUnit\Util\Log\JSON as LogJSON;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

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
                    'final',
                    'search-rfi'

                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'create',
                            'update',
                            'final',
                        ],
                        'allow' => true,
                        'roles' => ['request_for_inspection', 'super-user', 'ro-admin']
                    ],
                    [
                        'actions' => [
                            'view',
                            'index',
                            'create',
                            'update',
                            'final',
                        ],
                        'allow' => true,
                        'roles' => ['ro-common-user']
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
    private function irLinks($id)
    {

        return  YIi::$app->db->createCommand("SELECT 
                    inspection_report.id,
                    inspection_report.ir_number,
                    iar.iar_number,
                    iar.id as iar_id
                    FROM request_for_inspection
                    INNER JOIN request_for_inspection_items ON request_for_inspection.id = request_for_inspection_items.fk_request_for_inspection_id
                    INNER JOIN inspection_report_items ON request_for_inspection_items.id = inspection_report_items.fk_request_for_inspection_item_id
                    INNER JOIN inspection_report ON inspection_report_items.fk_inspection_report_id = inspection_report.id
                    LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
                    WHERE 
                    request_for_inspection.id = :id
                    GROUP BY 
                    inspection_report.id,
                    inspection_report.ir_number,
                    iar.iar_number,
                    iar.id 
                    UNION
                    SELECT 
                    inspection_report.id,
                    inspection_report.ir_number,
                    iar.iar_number,
                    iar.id as iar_id
                    FROM request_for_inspection
                    INNER JOIN rfi_without_po_items ON request_for_inspection.id = rfi_without_po_items.fk_request_for_inspection_id
                    INNER JOIN inspection_report_no_po_items ON rfi_without_po_items.id = inspection_report_no_po_items.fk_rfi_without_po_item_id
                    INNER JOIN inspection_report ON inspection_report_no_po_items.fk_inspection_report_id = inspection_report.id
                    LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
                    WHERE 
                    request_for_inspection.id = :id
                    GROUP BY 
                    inspection_report.id,
                    inspection_report.ir_number,
                    iar.iar_number,
                    iar.id 

        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function actionView($id)
    {

        $model =  $this->findModel($id);
        $po_details = [];
        $no_po_items = [];
        if ($model->transaction_type === 'with_po') {
            $po_details = $this->poDetails($id);
        } else {
            $no_po_items = $this->noPo_items($id);
        }
        return $this->render('view', [
            'model' => $model,
            'purchase_orders' => $po_details,
            'no_po_items' => $no_po_items,
            'ir_links' => $this->irLinks($id)
        ]);
    }
    private function poDetails($id)
    {

        $purchase_orders = Yii::$app->db->createCommand("SELECT
                request_for_inspection_items.id,
                pr_purchase_order_items_aoq_items.id as po_aoq_item_id,
                pr_purchase_order_item.serial_number as po_number,
                payee.account_name as payee,
                pr_stock.stock_title,
                REPLACE(REPLACE(pr_purchase_request_item.specification,'[n][n]','<br>'),'[n]','<br>') as specification,
                pr_purchase_request.purpose,
                request_for_inspection_items.quantity,
                request_for_inspection_items.from as date_from,
                request_for_inspection_items.to as date_to,
                pr_project_procurement.title as project_title,
                pr_office.division,
                pr_office.unit,
                pr_purchase_request_item.quantity - IFNULL(aoq_items_quantity.quantity,0) as balance_quantity,
                unit_of_measure.unit_of_measure,
                pr_aoq_entries.amount as unit_cost,
                pr_purchase_order_item.fk_pr_purchase_order_id as po_id
               
        FROM 
        request_for_inspection_items						
        INNER JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
        INNER JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
        INNER JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        INNER JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        INNER JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT  JOIN  payee ON pr_aoq_entries.payee_id = payee.id
        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        LEFT JOIN pr_office ON pr_project_procurement.pr_office_id = pr_office.id
        LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
        
    
        LEFT JOIN (SELECT 
            request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id,
            SUM(request_for_inspection_items.quantity) as quantity
            FROM request_for_inspection_items GROUP BY request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id) as aoq_items_quantity
             ON pr_purchase_order_items_aoq_items.id = aoq_items_quantity.fk_pr_purchase_order_items_aoq_item_id
        
        
        
        
        WHERE request_for_inspection_items.fk_request_for_inspection_id = :id
        AND request_for_inspection_items.is_deleted !=1
        ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $purchase_orders;
    }
    private function  CheckPOqtyBal($qty, $po_itm_id, $rfi_item_id = '')
    {
        $sql = '';
        $params = [];

        if (!empty($rfi_item_id)) {
            $sql = ' AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'request_for_inspection_items.id', $rfi_item_id], $params);
        }
        $q = YIi::$app->db->createCommand("SELECT 
        pr_purchase_request_item.quantity - IFNULL(aoq_items_quantity.quantity,0) as remaining_quantity
        FROM 
        pr_purchase_order_items_aoq_items
        INNER JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        INNER JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        INNER JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN (SELECT 
                        request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id,
                        SUM(request_for_inspection_items.quantity) as quantity
                        FROM request_for_inspection_items 
                        WHERE 
                         request_for_inspection_items.is_deleted !=1
                         $sql
        GROUP BY request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id) as aoq_items_quantity 
        ON pr_purchase_order_items_aoq_items.id = aoq_items_quantity.fk_pr_purchase_order_items_aoq_item_id
        WHERE 
        pr_purchase_order_items_aoq_items.id  = :id", $params)
            ->bindValue(':id', $po_itm_id)
            ->queryScalar();

        if (intval($qty) > intval($q)) {
            return "Quantity should not be greater than {$q} ";
        }
        return true;
    }
    /**
     * Creates a new RequestForInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function insertItems($rfi_id, $items = [])
    {
        try {
            $i = 1;
            foreach ($items as $index => $itm) {
                if (!empty($itm['item_id'])) {
                    $rfi_item = RequestForInspectionItems::findOne($itm['item_id']);
                    $chk  = $this->CheckPOqtyBal($itm['quantity'], $itm['purchase_order_id'], $rfi_item->id);
                    if ($chk !== true) {
                        throw new ErrorException($chk . "in line $index");
                    }
                } else {

                    $rfi_item = new RequestForInspectionItems();
                    $chk  = $this->CheckPOqtyBal($itm['quantity'], $itm['purchase_order_id']);
                    if ($chk !== true) {
                        throw new ErrorException($chk . "in line $index");
                    }
                }

                $rfi_item->fk_request_for_inspection_id = $rfi_id;
                $rfi_item->fk_pr_purchase_order_items_aoq_item_id = $itm['purchase_order_id'];
                $rfi_item->quantity =  $itm['quantity'];
                $rfi_item->from =  $itm['date_from'];
                $rfi_item->to =  $itm['date_to'];

                if (!$rfi_item->validate()) {
                    throw new ErrorException(json_encode($rfi_item->errors));
                }
                if (!$rfi_item->save(false)) {
                    throw new ErrorException('WIth PO Item Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function withPoInsertInspectionReport($rfi_id)
    {
        $query = Yii::$app->db->createCommand("SELECT 
        request_for_inspection_items.id as rfi_item_id,
        pr_purchase_order_items_aoq_items.fk_purchase_order_item_id as po_id,
        CONCAT(request_for_inspection_items.`from`,'-',request_for_inspection_items.`to`) as inspection_date
         FROM `request_for_inspection_items`
        INNER JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
        WHERE 
        request_for_inspection_items.fk_request_for_inspection_id = :id
        AND request_for_inspection_items.is_deleted = 0
        ")
            ->bindValue(':id', $rfi_id)
            ->queryAll();

        $res = ArrayHelper::index($query, null, [function ($element) {
            return $element['po_id'];
        }, 'inspection_date']);
        // var_dump($res);
        // die();

        try {
            foreach ($res as $po_id_items) {

                foreach ($po_id_items as $val) {

                    $ir = new InspectionReport();
                    $ir->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                    $ir->ir_number = YIi::$app->memem->irNumber();
                    if ($ir->validate()) {
                        $iar = new Iar();
                        $iar->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                        $iar->iar_number = Yii::$app->memem->iarNumber();
                        $iar->fk_ir_id = $ir->id;
                        if ($iar->save(false)) {
                        }
                        if ($ir->save(false)) {
                            foreach ($val  as $val2) {
                                $ir_item = new InspectionReportItems();
                                $ir_item->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                                $ir_item->fk_inspection_report_id = $ir->id;
                                $ir_item->fk_request_for_inspection_item_id = $val2['rfi_item_id'];
                                if ($ir_item->save(false)) {
                                }
                            }
                        }
                    } else {
                        return ['isSuccess' => false, 'error_message' => $ir->errors];
                    }
                }
            }
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }

        return ['isSuccess' => true];
    }
    private function noPoInsertInspectionReport($rfi_id)
    {
        $items = Yii::$app->db->createCommand("SELECT 
        rfi_without_po_items.id,
        CONCAT(rfi_without_po_items.`from_date`,'-',rfi_without_po_items.`to_date`) as inspection_date,
        rfi_without_po_items.project_name,
        rfi_without_po_items.fk_payee_id

        FROM rfi_without_po_items
        WHERE 
        rfi_without_po_items.fk_request_for_inspection_id = :id
        AND rfi_without_po_items.is_deleted !=1")
            ->bindValue(':id', $rfi_id)
            ->queryAll();

        $res = ArrayHelper::index($items, null, [function ($element) {
            return $element['project_name'];
        }, 'inspection_date', 'fk_payee_id']);
        // print_r(json_encode($res));
        // die();

        try {
            foreach ($res as $items) {

                foreach ($items as $val) {
                    foreach ($val  as $val2) {

                        $ir = new InspectionReport();
                        $ir->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                        $ir->ir_number = YIi::$app->memem->irNumber();
                        if ($ir->validate()) {

                            if ($ir->save(false)) {
                                $iar = new Iar();
                                $iar->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                                $iar->iar_number = Yii::$app->memem->iarNumber();
                                $iar->fk_ir_id = $ir->id;
                                if ($iar->save(false)) {
                                }

                                foreach ($val2 as $val3) {

                                    $ir_item = new InspectionReportNoPoItems();
                                    $ir_item->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                                    $ir_item->fk_inspection_report_id = $ir->id;
                                    $ir_item->fk_rfi_without_po_item_id = $val3['id'];
                                    if ($ir_item->save(false)) {
                                    }
                                }
                            }
                        } else {
                            return ['isSuccess' => false, 'error_message' => $ir->errors];
                        }
                    }
                }
            }
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }


        return ['isSuccess' => true];
    }
    private function validateNoPoItems(
        $project_name = '',
        $stock_name = '',
        $specification = '',
        $unit_of_measure = '',
        $payee = '',
        $unit_cost = '',
        $no_po_quantity = '',
        $from_date = '',
        $to_date = ''
    ) {

        if (empty($project_name)) {
            return 'Project Name is required in line ';
        }
        if (empty($stock_name)) {
            return 'Stock is required in line ';
        }
        if (empty($specification)) {
            return 'specification is required in line ';
        }
        if (empty($unit_of_measure)) {
            return 'unit_of_measure is required in line ';
        }
        if (empty($payee)) {
            return 'payee is required in line ';
        }
        if (empty($unit_cost)) {
            return 'unit_cost is required in line ';
        }
        if (empty($no_po_quantity)) {
            return 'no_po_quantity is required in line ';
        }
        if (empty($from_date)) {
            return 'from_date is required in line ';
        }
        if (empty($to_date)) {
            return 'to_date is required in line ';
        }

        return true;
    }
    private function insertNoPoItems($rfi_id, $items = [])
    {

        try {
            foreach ($items as $index => $itm) {
                if (!empty($itm['item_id'])) {
                    $rfi_item =  RfiWithoutPoItems::findOne($itm['item_id']);
                } else {
                    $rfi_item = new RfiWithoutPoItems();
                }
                $rfi_item->fk_request_for_inspection_id = $rfi_id;
                $rfi_item->project_name = $itm['project_name'];
                $rfi_item->fk_stock_id = $itm['stock_name'];
                $rfi_item->specification = $itm['specification'];
                $rfi_item->fk_unit_of_measure_id = $itm['unit_of_measure'];
                $rfi_item->fk_payee_id = $itm['payee'];
                $rfi_item->unit_cost = $itm['unit_cost'];
                $rfi_item->quantity = $itm['no_po_quantity'];
                $rfi_item->from_date = $itm['from_date'];
                $rfi_item->to_date = $itm['to_date'];
                if (!$rfi_item->validate()) {
                    throw new ErrorException(json_encode($rfi_item->errors));
                }
                if (!$rfi_item->save(false)) {
                    throw new ErrorException('No PO Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }
    }
    private function noPo_items($rfi_id = '')
    {

        return Yii::$app->db->createCommand("SELECT  
        rfi_without_po_items.id,
        rfi_without_po_items.project_name,
       IFNULL(pr_stock.stock_title,'') as stock_title,
       pr_stock.id as stock_id,
       rfi_without_po_items.specification,
       REPLACE(rfi_without_po_items.specification,'[n]','\n') as specification_view,
       unit_of_measure.unit_of_measure,
       unit_of_measure.id as unit_of_measure_id,
       payee.account_name as payee_name,
       payee.id as payee_id,
       rfi_without_po_items.unit_cost,
       rfi_without_po_items.quantity,
       rfi_without_po_items.from_date,
       rfi_without_po_items.to_date
       
       FROM request_for_inspection
       LEFT JOIN rfi_without_po_items ON request_for_inspection.id = rfi_without_po_items.fk_request_for_inspection_id
       LEFT JOIN pr_stock ON rfi_without_po_items.fk_stock_id = pr_stock.id
       LEFT JOIN payee ON rfi_without_po_items.fk_payee_id = payee.id
       LEFT JOIN unit_of_measure ON rfi_without_po_items.fk_unit_of_measure_id = unit_of_measure.id
       WHERE 
       request_for_inspection.id = :id
       AND rfi_without_po_items.is_deleted !=1
       ")
            ->bindValue(':id', $rfi_id)
            ->queryAll();
    }

    public function actionCreate()
    {
        $model = new RequestForInspection();
        $searchModel = new PurchaseOrdersForRfiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model->fk_property_unit = 100099198938013553;
        $model->fk_chairperson = 99684622555676844;
        $user_data = Yii::$app->memem->getUserData();
        $model->fk_office_id = $user_data->office->id;
        $model->fk_division_id = $user_data->divisionName->id ?? '';
        $model->fk_created_by =  Yii::$app->user->identity->id;

        if (!Yii::$app->user->can('super-user')) {
            $user_division = strtolower(Yii::$app->user->identity->division ?? '');
            $division_id = Yii::$app->db->createCommand("SELECT id FROM responsibility_center WHERE responsibility_center.name=:division")
                ->bindValue(':division', $user_division)
                ->queryScalar();
            $model->fk_responsibility_center_id = $division_id;
        }
        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                $model->rfi_number = $this->rfiNumber($model->fk_office_id);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                if ($model->transaction_type !== 'with_po') {
                    $noPoItems = Yii::$app->request->post('noPoItems');
                    $res = $this->insertNoPoItems(
                        $model->id,
                        $noPoItems
                    );
                    if ($res !== true) {
                        throw new ErrorException(json_encode($res));
                    }
                } else {
                    $poItems = Yii::$app->request->post('poItems');
                    $res = $this->insertItems(
                        $model->id,
                        $poItems
                    );
                    if ($res !== true) {
                        throw new ErrorException(json_encode($res));
                    }
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'action' => 'request-for-inspection/create',
        ]);
    }

    /**t
     * Updates an existing RequestForInspection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionValidate($id)
    // {
    //     $model = $this->findModel($id);
    //     if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
    //         $model->company_id = Yii::$app->user->identity->company_id;
    //         $model->created_at = time();
    //         \Yii::$app->response->format = Response::FORMAT_JSON;
    //         return ActiveForm::validate($model);
    //     }
    // }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new PurchaseOrdersForRfiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // if (YIi::$app->request->isAjax){
        //     return 1;
        // }
        if ($model->is_final && !Yii::$app->user->can('super-user')) {
            return $this->redirect(['index']);
        }
        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                if ($model->transaction_type !== 'with_po') {
                    $noPoItems = Yii::$app->request->post('noPoItems');
                    $res = $this->insertNoPoItems(
                        $model->id,
                        $noPoItems
                    );

                    if ($res !== true) {
                        throw new ErrorException(json_encode($res));
                    }
                } else {
                    $poItems = Yii::$app->request->post('poItems');
                    $res = $this->insertItems(
                        $model->id,
                        $poItems
                    );
                    if ($res !== true) {
                        throw new ErrorException(json_encode($res));
                    }
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }


        return $this->render('update', [
            'model' => $model,
            'items' => $this->poDetails($id),
            'no_po_items' => $this->noPo_items($id),
            'action' => 'request-for-inspection/update',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing RequestForInspection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionFinal($id)
    {

        $model = $this->findModel($id);
        $model->is_final = 1;
        // $this->noPoInsertInspectionReport($model->id);
        if ($model->save(false)) {
            if ($model->transaction_type === 'with_po') {

                $insert = $this->withPoInsertInspectionReport($model->id)['isSuccess'];
                if ($insert === true) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return var_dump($insert);
                }
            } else {
                $insert_iars = $this->noPoInsertInspectionReport($model->id);

                if ($insert_iars['isSuccess'] === true) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return var_dump($insert_iars);
                }
            }
        }
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
    private function rfiNumber($office_id)
    {
        $ofc = Office::findOne($office_id);
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(rfi_number,'-',-1)  AS UNSIGNED) as last_number
         FROM request_for_inspection
         WHERE 
            fk_office_id = :office_id
         ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':office_id', $office_id)
            ->queryScalar();

        $num = 1;
        if (!empty($query)) {
            $num  = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {

            $zero .= 0;
        }
        return strtoupper($ofc->office_name) . '-' . date('Y') . '-' . $zero . $num;
    }
    public function actionSearchRfi($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('request_for_inspection.id, request_for_inspection.rfi_number AS text')
                ->from('request_for_inspection')
                ->where(['like', 'rfi_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
