<?php

namespace frontend\controllers;

use app\models\Iar;
use app\models\InspectionReport;
use app\models\InspectionReportItems;
use app\models\InspectionReportNoPoItems;
use app\models\NoPoInspectionReportItems;
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
                        'roles' => ['request-for-inspection']
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
    public function irLinks($id)
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
    public function poDetails($id)
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
    /**
     * Creates a new RequestForInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertItems($rfi_id, $po_ids = [], $item_ids = [], $quantity = [], $date_from = [], $date_to = [])
    {
        if (!empty($po_ids)) {
            try {
                $i = 1;
                foreach ($po_ids as $index => $val) {
                    if (!empty($item_ids[$index])) {
                        $item = RequestForInspectionItems::findOne($item_ids[$index]);
                        if (!empty($quantity[$index])) {
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
                                            WHERE request_for_inspection_items.id !=:rfi_id
                                            AND request_for_inspection_items.is_deleted !=1
                            GROUP BY request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id) as aoq_items_quantity 
                            ON pr_purchase_order_items_aoq_items.id = aoq_items_quantity.fk_pr_purchase_order_items_aoq_item_id
                            WHERE 
                            pr_purchase_order_items_aoq_items.id  = :id")
                                ->bindValue(':id', $val)
                                ->bindValue(':rfi_id', $item->id)
                                ->queryScalar();

                            if (intval($quantity[$index]) > intval($q)) {
                                return ['isSuccess' => false, 'error_message' => "Quantity should not be greater than {$q} in line $i"];
                                // return "Quantity should not be greater than {$q} in line $index";
                            }
                        }
                    } else {

                        $item = new RequestForInspectionItems();
                        if (!empty($quantity[$index])) {
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
                                            WHERE request_for_inspection_items.is_deleted !=1
                            GROUP BY request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id) as aoq_items_quantity 
                            ON pr_purchase_order_items_aoq_items.id = aoq_items_quantity.fk_pr_purchase_order_items_aoq_item_id
                            WHERE 
                            pr_purchase_order_items_aoq_items.id  = :id")
                                ->bindValue(':id', $val)
                                ->queryScalar();

                            if (intval($quantity[$index]) > intval($q)) {
                                return ['isSuccess' => false, 'error_message' => "Quantity should not be greater than {$q} in line $i"];
                            }
                        }
                    }



                    $item->fk_request_for_inspection_id = $rfi_id;
                    $item->fk_pr_purchase_order_items_aoq_item_id = $val;
                    $item->quantity = !empty($quantity[$index]) ? $quantity[$index] : 0;
                    $item->from = !empty($date_from[$index]) ?  $date_from[$index] : null;
                    $item->to = !empty($date_to[$index]) ?  $date_to[$index] : null;
                    $new_record = $item->isNewRecord;
                    if (empty($date_from[$index])) {
                        return ['isSuccess' => false, 'error_message' => "From Date is Required in line $i"];
                    }
                    if (empty($date_to[$index])) {
                        return ['isSuccess' => false, 'error_message' => "To Date is Required in line $i"];
                    }
                    if ($item->validate()) {

                        if ($item->save(false)) {
                            // echo $item->fk_purchase_order_item_id;
                            // echo '<br>';
                            if ($new_record) {
                                // $this->withPoInsertInspectionReport($item->id);
                            }
                        }
                    } else {
                        return $item->errors;
                    }
                    $i++;
                }
                // die();
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }
        return ['isSuccess' => true];
    }
    public function withPoInsertInspectionReport($rfi_id)
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
    public function noPoInsertInspectionReport($rfi_id)
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
    public function validateNoPoItems(
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
    public function insertNoPoItems(
        $rfi_id,
        $project_name = [],
        $stock_name = [],
        $specification = [],
        $unit_of_measure = [],
        $payee = [],
        $unit_cost = [],
        $no_po_quantity = [],
        $from_date = [],
        $to_date = [],
        $no_po_item_ids = []

    ) {
        $line_no = 1;
        try {
            foreach ($project_name as $index => $val) {

                if (!empty($no_po_item_ids[$index])) {
                    $items =  RfiWithoutPoItems::findOne($no_po_item_ids[$index]);
                } else {

                    $items = new RfiWithoutPoItems();
                }

                $validateItems = $this->validateNoPoItems(
                    $val,
                    $stock_name[$index],
                    $specification[$index],
                    $unit_of_measure[$index],
                    $payee[$index],
                    $unit_cost[$index],
                    $no_po_quantity[$index],
                    $from_date[$index],
                    $to_date[$index]
                );

                if ($validateItems !== true) {
                    return ['isSuccess' => false, 'error_message' => $validateItems . $line_no];
                }
                $items->fk_request_for_inspection_id = $rfi_id;
                $items->project_name = $val;
                $items->fk_stock_id = $stock_name[$index];
                $items->specification = $specification[$index];
                $items->fk_unit_of_measure_id = $unit_of_measure[$index];
                $items->fk_payee_id = $payee[$index];
                $items->unit_cost = $unit_cost[$index];
                $items->quantity = $no_po_quantity[$index];
                $items->from_date = $from_date[$index];
                $items->to_date = $to_date[$index];

                if ($items->validate()) {
                    if ($items->save(false)) {
                    }
                } else {
                    return ['isSuccess' => false, 'error_message' => $items->errors];
                }
                $line_no++;
            }
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }
        return ['isSuccess' => true];
    }
    public function noPo_items($rfi_id = '')
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
    public function checkInputs()
    {
    }
    public function actionCreate()
    {
        $model = new RequestForInspection();
        $searchModel = new PurchaseOrdersForRfiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model->fk_property_unit = 99684622555676819;
        $model->fk_chairperson = 99684622555676844;

        if (!Yii::$app->user->can('super-user')) {
            $user_division = strtolower(Yii::$app->user->identity->division);
            $division_id = Yii::$app->db->createCommand("SELECT id FROM responsibility_center WHERE responsibility_center.name=:division")
                ->bindValue(':division', $user_division)
                ->queryScalar();
            $model->fk_responsibility_center_id = $division_id;
        }
        if (Yii::$app->request->isPost) {
            $date = !empty($_POST['date']) ? $_POST['date'] : '';
            $fk_responsibility_center_id = !empty($_POST['fk_responsibility_center_id']) ? $_POST['fk_responsibility_center_id'] : '';
            $transaction_type = !empty($_POST['transaction_type']) ? $_POST['transaction_type'] : '';
            $fk_requested_by = !empty($_POST['fk_requested_by']) ? $_POST['fk_requested_by'] : '';
            $fk_chairperson = !empty($_POST['fk_chairperson']) ? $_POST['fk_chairperson'] : '';
            $fk_inspector = !empty($_POST['fk_inspector']) ? $_POST['fk_inspector'] : '';
            $fk_property_unit = !empty($_POST['fk_property_unit']) ? $_POST['fk_property_unit'] : '';
            // return JSON::encode(array('isSuccess' => false, 'error_message' => 'qwe'));
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            // return $this->redirect(['view', 'id' => $model->id]);
            $model->rfi_number = $this->rfiNumber();
            $model->date =  $date;
            $model->fk_chairperson = $fk_chairperson;
            $model->fk_inspector =  $fk_inspector;
            $model->fk_property_unit = $fk_property_unit;
            $model->fk_requested_by =  $fk_requested_by;
            if (Yii::$app->user->can('super-user')) {
                $model->fk_responsibility_center_id =  $fk_responsibility_center_id;
            }
            $model->transaction_type = $transaction_type;
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->validate()) {


                    if ($flag = $model->save(false)) {
                        if ($model->transaction_type !== 'with_po') {
                            $project_name = !empty($_POST['project_name']) ? $_POST['project_name'] : [];
                            $stock_name = !empty($_POST['stock_name']) ? $_POST['stock_name'] : [];
                            $specification = !empty($_POST['specification']) ? $_POST['specification'] : [];
                            $unit_of_measure = !empty($_POST['unit_of_measure']) ? $_POST['unit_of_measure'] : [];
                            $payee = !empty($_POST['payee']) ? $_POST['payee'] : [];
                            $unit_cost = !empty($_POST['unit_cost']) ? $_POST['unit_cost'] : [];
                            $no_po_quantity = !empty($_POST['no_po_quantity']) ? $_POST['no_po_quantity'] : [];
                            $from_date = !empty($_POST['from_date']) ? $_POST['from_date'] : [];
                            $to_date = !empty($_POST['to_date']) ? $_POST['to_date'] : [];
                            $res = $this->insertNoPoItems(
                                $model->id,
                                $project_name,
                                $stock_name,
                                $specification,
                                $unit_of_measure,
                                $payee,
                                $unit_cost,
                                $no_po_quantity,
                                $from_date,
                                $to_date

                            );
                            if ($res['isSuccess']) {



                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id]);
                            } else {
                                $transaction->rollBack();
                                return JSON::encode(array('isSuccess' => false, 'error_message' => $res['error_message']));
                            }
                        } else {
                            $po_ids = !empty($_POST['purchase_order_id']) ? $_POST['purchase_order_id'] : [];
                            $date_from = !empty($_POST['date_from']) ? $_POST['date_from'] : [];
                            $date_to = !empty($_POST['date_to']) ? $_POST['date_to'] : [];
                            $quantity = !empty($_POST['quantity']) ? $_POST['quantity'] : [];
                            if (empty($po_ids)) {
                                $transaction->rollBack();
                                return JSON::encode(array('isSuccess' => false, 'error_message' => 'Select PO'));
                            }
                            $success = $this->insertItems(
                                $model->id,
                                array_unique($po_ids),
                                [],
                                $quantity,
                                $date_from,
                                $date_to
                            );
                            if ($success['isSuccess']) {

                                // $insert_ir = $this->withPoInsertInspectionReport($model->id, $model->rfi_number);
                                // if ($insert_ir['isSuccess']) {

                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id]);
                                // } else {
                                //     $transaction->rollBack();
                                //     return JSON::encode(array('isSuccess' => false, 'error_message' => $insert_ir['error_message']));
                                // }
                            } else {
                                $transaction->rollBack();
                                return JSON::encode(array('isSuccess' => false, 'error_message' => $success['error_message']));
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                    }
                } else {
                    $transaction->rollBack();
                    return JSON::encode(array('isSuccess' => false, 'error_message' => $model->errors));
                }
            } catch (ErrorException $e) {
                return JSON::encode(array('isSuccess' => false, 'error_message' => $e->getMessage()));
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
        if (YIi::$app->request->isPost) {

            // if ($model->is_final !== true) {

            $date = !empty($_POST['date']) ? $_POST['date'] : '';
            $fk_responsibility_center_id = !empty($_POST['fk_responsibility_center_id']) ? $_POST['fk_responsibility_center_id'] : '';
            $transaction_type = !empty($_POST['transaction_type']) ? $_POST['transaction_type'] : '';
            $fk_requested_by = !empty($_POST['fk_requested_by']) ? $_POST['fk_requested_by'] : '';
            $fk_chairperson = !empty($_POST['fk_chairperson']) ? $_POST['fk_chairperson'] : '';
            $fk_inspector = !empty($_POST['fk_inspector']) ? $_POST['fk_inspector'] : '';
            $fk_property_unit = !empty($_POST['fk_property_unit']) ? $_POST['fk_property_unit'] : '';
            // return JSON::encode(array('isSuccess' => false, 'error_message' => 'qwe'));
            // return $this->redirect(['view', 'id' => $model->id]);
            $model->date =  $date;
            $model->fk_chairperson = $fk_chairperson;
            $model->fk_inspector =  $fk_inspector;
            $model->fk_property_unit = $fk_property_unit;
            $model->fk_requested_by =  $fk_requested_by;
            if (Yii::$app->user->can('super-user')) {
                $model->fk_responsibility_center_id =  $fk_responsibility_center_id;
            }
            $model->transaction_type = $transaction_type;
            $no_po_item_id = !empty($_POST['no_po_item_ids']) ? $_POST['no_po_item_ids'] : [];

            $transaction = Yii::$app->db->beginTransaction();
            // return json_encode($item_ids);
            try {

                if ($model->validate()) {
                    if ($flag = $model->save(false)) {
                        if (!empty($item_ids)) {

                            $params = [];
                            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                            Yii::$app->db->createCommand("UPDATE request_for_inspection_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                                $sql AND request_for_inspection_items.fk_request_for_inspection_id = :id", $params)
                                ->bindValue(':id', $model->id)->query();
                        }
                        if (!empty($no_po_item_id)) {

                            $params = [];
                            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $no_po_item_id], $params);
                            Yii::$app->db->createCommand("UPDATE rfi_without_po_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                                $sql AND rfi_without_po_items.fk_request_for_inspection_id = :id", $params)
                                ->bindValue(':id', $model->id)->query();
                        }
                        if ($model->transaction_type !== 'with_po') {
                            $project_name = !empty($_POST['project_name']) ? $_POST['project_name'] : [];
                            $stock_name = !empty($_POST['stock_name']) ? $_POST['stock_name'] : [];
                            $specification = !empty($_POST['specification']) ? $_POST['specification'] : [];
                            $unit_of_measure = !empty($_POST['unit_of_measure']) ? $_POST['unit_of_measure'] : [];
                            $payee = !empty($_POST['payee']) ? $_POST['payee'] : [];
                            $unit_cost = !empty($_POST['unit_cost']) ? $_POST['unit_cost'] : [];
                            $no_po_quantity = !empty($_POST['no_po_quantity']) ? $_POST['no_po_quantity'] : [];
                            $from_date = !empty($_POST['from_date']) ? $_POST['from_date'] : [];
                            $to_date = !empty($_POST['to_date']) ? $_POST['to_date'] : [];


                            $res = $this->insertNoPoItems(
                                $model->id,
                                $project_name,
                                $stock_name,
                                $specification,
                                $unit_of_measure,
                                $payee,
                                $unit_cost,
                                $no_po_quantity,
                                $from_date,
                                $to_date,
                                $no_po_item_id

                            );
                            if ($res['isSuccess']) {



                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id]);
                            } else {
                                $transaction->rollBack();
                                return JSON::encode(array('isSuccess' => false, 'error_message' => $res['error_message']));
                            }
                        } else {
                            $po_ids = !empty($_POST['purchase_order_id']) ? $_POST['purchase_order_id'] : [];
                            $date_from = !empty($_POST['date_from']) ? $_POST['date_from'] : [];
                            $date_to = !empty($_POST['date_to']) ? $_POST['date_to'] : [];
                            $quantity = !empty($_POST['quantity']) ? $_POST['quantity'] : [];
                            $item_ids = !empty($_POST['item_id']) ? $_POST['item_id'] : [];
                            if (empty($po_ids)) {
                                $transaction->rollBack();
                                return JSON::encode(array('isSuccess' => false, 'error_message' => 'Select PO'));
                            }
                            $success = $this->insertItems(
                                $model->id,
                                array_unique($po_ids),
                                $item_ids,
                                $quantity,
                                $date_from,
                                $date_to
                            );
                            if ($success['isSuccess']) {
                                $transaction->commit();
                                return $this->redirect(['view', 'id' => $model->id]);
                            } else {
                                $transaction->rollBack();
                                return JSON::encode(array('isSuccess' => false, 'error_message' => $success['error_message']));
                            }
                        }
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
            // }
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
