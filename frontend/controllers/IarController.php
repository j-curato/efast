<?php

namespace frontend\controllers;

use Yii;
use app\models\Iar;
use app\models\IarIndexSearch;
use app\models\IarSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IarController implements the CRUD actions for Iar model.
 */
class IarController extends Controller
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
                    'search-iar'

                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['iar']
                    ],
                    [
                        'actions' => [
                            'search-iar'
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
    private function paymentTracking($id)

    {
        return YIi::$app->db->createCommand("SELECT 
        iar.iar_number,
        `transaction`.id as transaction_id,
        `transaction`.tracking_number as txn_num,
        process_ors.id as ors_id,
        process_ors.serial_number as ors_num,
        process_ors.is_cancelled as ors_is_cancelled,
        dv_aucs.id  as dv_id,
        dv_aucs.dv_number as dv_num,
        dv_aucs.is_cancelled as dv_is_cancelled,
        cash_disbursement.id as cash_id,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.issuance_date,
        cancelled_cash.created_at as cancelled_at,
        cancelled_cash.reporting_period as cancelled_period,
        cancelled_cash.is_cancelled as cash_cancelled
        
         FROM 
        iar
        LEFT JOIN transaction_iars ON iar.id   = transaction_iars.fk_iar_id
        LEFT JOIN `transaction` ON transaction_iars.fk_transaction_id  = `transaction`.id
        LEFT JOIN process_ors ON `transaction`.id = process_ors.transaction_id
        LEFT JOIN (SELECT 
        dv_aucs_entries.process_ors_id,
        dv_aucs_entries.dv_aucs_id
        FROM 
        dv_aucs_entries
        WHERE 
        dv_aucs_entries.is_deleted = 0
        
        GROUP BY 
        dv_aucs_entries.process_ors_id,
        dv_aucs_entries.dv_aucs_id) as dv_entry ON process_ors.id = dv_entry.process_ors_id
        
        LEFT JOIN dv_aucs ON dv_entry.dv_aucs_id = dv_aucs.id
        LEFT JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
        LEFT JOIN cash_disbursement as cancelled_cash ON cash_disbursement.id = cancelled_cash.parent_disbursement
        WHERE 
        transaction_iars.is_deleted = 0
        
        AND iar.id = :id
        AND cash_disbursement.is_cancelled = 0
        ORDER BY iar.iar_number ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function signatories($id)
    {

        $query = Yii::$app->db->createCommand("SELECT 
        unit_head.employee_name as unit_head,
        chairperson.employee_name as chairperson,
        inspector.employee_name as inspector,
        property_unit.employee_name as property_unit,
        payee.registered_name as payee,
        pr_project_procurement.title as project_title,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y') as inspection_from_date,
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y') as inspection_to_date,
        DATE_FORMAT(iar.created_at,'%M %d, %Y') as date_generated,
        DATE_FORMAT(pr_purchase_order.po_date,'%M %d, %Y') as po_date,
        pr_purchase_order.po_number,
        responsibility_center.name as department

					
        FROM iar
        INNER JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
        INNER JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
        INNER JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
        INNER JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
        LEFT JOIN employee_search_view  as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
        LEFT JOIN employee_search_view as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
        LEFT JOIN employee_search_view as inspector ON request_for_inspection.fk_inspector = inspector.employee_id
        LEFT JOIN pr_office ON request_for_inspection.fk_pr_office_id = pr_office.id
        LEFT JOIN employee_search_view as unit_head ON pr_office.fk_unit_head = unit_head.employee_id
        LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id  = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
		LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
        LEFT JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id = pr_purchase_order.id
        LEFT JOIN responsibility_center ON request_for_inspection.fk_responsibility_center_id = responsibility_center.id
        WHERE iar.id = :id
        GROUP BY 
        unit_head.employee_name,
        chairperson.employee_name ,
        inspector.employee_name ,
        property_unit.employee_name ,
        payee.registered_name ,
        pr_project_procurement.title ,
        pr_purchase_order.po_number,
        responsibility_center.`name`,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y') ,
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y') ,
        DATE_FORMAT(iar.created_at,'%M %d, %Y') ,
        DATE_FORMAT(pr_purchase_order.po_date,'%M %d, %Y') ,
        CONCAT(pr_office.division,'-',pr_office.unit)")
            ->bindValue(':id', $id)
            ->queryOne();
        return $query;
    }
    public function noPOsignatories($id)
    {
        return YIi::$app->db->createCommand("SELECT 

        chairperson.employee_name as chairperson,
        inspector.employee_name as inspector,
        property_unit.employee_name as property_unit,
        payee.registered_name as payee,
        rfi_without_po_items.project_name as project_title,
         DATE_FORMAT(rfi_without_po_items.`from_date`,'%M %d, %Y') as inspection_from_date,
        DATE_FORMAT(rfi_without_po_items.`to_date`,'%M %d, %Y') as inspection_to_date,
        DATE_FORMAT(iar.created_at,'%M %d, %Y') as date_generated,
        responsibility_center.name as department
        FROM iar
        INNER JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
        INNER JOIN inspection_report_no_po_items ON inspection_report.id = inspection_report_no_po_items.fk_inspection_report_id
        INNER JOIN rfi_without_po_items ON inspection_report_no_po_items.fk_rfi_without_po_item_id = rfi_without_po_items.id
        INNER JOIN request_for_inspection ON rfi_without_po_items.fk_request_for_inspection_id = request_for_inspection.id
        LEFT JOIN responsibility_center ON request_for_inspection.fk_responsibility_center_id = responsibility_center.id
        LEFT JOIN employee_search_view  as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
        LEFT JOIN employee_search_view as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
        LEFT JOIN employee_search_view as inspector ON request_for_inspection.fk_inspector = inspector.employee_id 
        LEFT JOIN payee ON rfi_without_po_items.fk_payee_id = payee.id
        WHERE iar.id = :id
        GROUP BY 
        chairperson.employee_name ,
        inspector.employee_name ,
        property_unit.employee_name ,
        payee.registered_name ,
        rfi_without_po_items.project_name ,
        responsibility_center.name,
         DATE_FORMAT(rfi_without_po_items.`from_date`,'%M %d, %Y') ,
        DATE_FORMAT(rfi_without_po_items.`to_date`,'%M %d, %Y') ,
        DATE_FORMAT(iar.created_at,'%M %d, %Y') 
     ")->bindValue(':id', $id)
            ->queryOne();
    }
    public function items($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 
		pr_stock.bac_code,
        pr_stock.stock_title,
        unit_of_measure.unit_of_measure,
        pr_aoq_entries.amount,
        request_for_inspection_items.quantity,
        IFNULL(REPLACE(pr_purchase_request_item.specification,'[n]','<br>'),'') as specification
        FROM iar

    INNER JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
    INNER  JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
    INNER JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
    LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
    LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id =pr_aoq_entries.id
    LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id =pr_rfq_item.id
    LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
    LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
    LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
        WHERE iar.id = :id
    ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $query;
    }
    public function noPOItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
                iar.iar_number,
                pr_stock.bac_code,
                pr_stock.stock_title,
                unit_of_measure.unit_of_measure,
                rfi_without_po_items.unit_cost,
                rfi_without_po_items.quantity,
                IFNULL(REPLACE(rfi_without_po_items.specification,'[n]','<br>'),'') as specification
                FROM iar
        
            INNER JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
            INNER  JOIN inspection_report_no_po_items ON inspection_report.id = inspection_report_no_po_items.fk_inspection_report_id
            INNER JOIN rfi_without_po_items ON inspection_report_no_po_items.fk_rfi_without_po_item_id = rfi_without_po_items.id
            LEFT JOIN pr_stock ON rfi_without_po_items.fk_stock_id= pr_stock.id
            LEFT JOIN unit_of_measure ON rfi_without_po_items.fk_unit_of_measure_id= unit_of_measure.id
            WHERE iar.id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }

    /**
     * Lists all Iar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IarIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Iar model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->items($id),
            'signatories' => $this->signatories($id),
            'noPOItems' => $this->noPOItems($id),
            'noPOsignatories' => $this->noPOsignatories($id),
            'paymentTracking' => $this->paymentTracking($id)



        ]);
    }

    /**
     * Creates a new Iar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new Iar();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing Iar model.
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Iar model.
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
     * Finds the Iar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Iar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Iar::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchIar($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('iar_index.id, iar_index.iar_number AS text')
                ->from('iar_index')
                ->where(['like', 'iar_index.iar_number', $q]);

            $user_data = Yii::$app->memem->getUserData();
            if (!Yii::$app->user->can('ro_accounting_admin')) {
                $query->andWhere('division = :division', ['division' => $user_data->divisionName->division]);
            }

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
