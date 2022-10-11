<?php

namespace frontend\controllers;

use Yii;
use app\models\InspectionReport;
use app\models\InspectionReportIndexSearch;
use app\models\InspectionReportSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InspectionReportController implements the CRUD actions for InspectionReport model.
 */
class InspectionReportController extends Controller
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
                    'index',
                    'delete',
                    'create',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'view',
                            'index',
                            'delete',
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['inspection-report']
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
     * Lists all InspectionReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InspectionReportIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InspectionReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'itemDetails' => $this->itemDetails($id),
            'noPoItemDetails' => $this->noPoItemDetails($id),
            'signatories' => $this->signatories($id),
            'no_po_signatories' => $this->noPOsignatories($id),
            'rfi_id' => $this->rfiId($id),
            'iar_id' => $this->iarId($id),
        ]);
    }
    public function itemDetails($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 
    pr_stock.stock_title,
    IFNULL(REPLACE(pr_purchase_request_item.specification,'[n]','<br>'),'') as specification
    FROM inspection_report
    INNER  JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
    INNER JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
    INNER JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
    INNER JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id =pr_aoq_entries.id
    INNER JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id =pr_rfq_item.id
    INNER JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
    INNER JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
    WHERE inspection_report.id = :id
    ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $query;
    }
    public function noPoItemDetails($id)
    {
        return Yii::$app->db->createCommand("SELECT
        pr_stock.stock_title  , 
       IFNULL( REPLACE(rfi_without_po_items.specification,'[n]','<br>'),'') as specification
        FROM inspection_report
        INNER JOIN inspection_report_no_po_items ON inspection_report.id = inspection_report_no_po_items.fk_inspection_report_id
         INNER JOIN rfi_without_po_items ON inspection_report_no_po_items.fk_rfi_without_po_item_id = rfi_without_po_items.id
        LEFT JOIN pr_stock ON rfi_without_po_items.fk_stock_id = pr_stock.id
        WHERE
        inspection_report.id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function noPOsignatories($id)
    {
        return Yii::$app->db->createCommand("SELECT 
            rfi_without_po_items.project_name as project_title,
            payee.account_name as payee,
            requested_by.employee_name as requested_by,
            inspected_by.employee_name as inspector,
            responsibility_center.`name` as responsible_center,
            chairperson.employee_name as chairperson,
            property_unit.employee_name as property_unit,
            DATE_FORMAT(rfi_without_po_items.`from_date`,'%M %d, %Y') as from_date,
            DATE_FORMAT(rfi_without_po_items.`to_date`,'%M %d, %Y') as to_date
      
            FROM inspection_report_no_po_items 
            INNER JOIN rfi_without_po_items ON inspection_report_no_po_items.fk_rfi_without_po_item_id = rfi_without_po_items.id
            INNER JOIN request_for_inspection ON rfi_without_po_items.fk_request_for_inspection_id = request_for_inspection.id
            INNER JOIN payee ON rfi_without_po_items.fk_payee_id  = payee.id
            LEFT JOIN employee_search_view as requested_by ON request_for_inspection.fk_requested_by = requested_by.employee_id
            LEFT JOIN employee_search_view as inspected_by ON request_for_inspection.fk_inspector = inspected_by.employee_id
            LEFT JOIN employee_search_view as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
            LEFT JOIN employee_search_view as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
            LEFT JOIN responsibility_center ON request_for_inspection.fk_responsibility_center_id = responsibility_center.id
            WHERE inspection_report_no_po_items.fk_inspection_report_id = :id
            GROUP BY
            inspection_report_no_po_items.fk_inspection_report_id,
            request_for_inspection.rfi_number,
            rfi_without_po_items.project_name ,
            payee.account_name ,
            requested_by.employee_name ,
            inspected_by.employee_name ,
            responsibility_center.`name`")
            ->bindValue(':id', $id)
            ->queryOne();
    }
    public function signatories($id)
    {
        $query  = Yii::$app->db->createCommand("SELECT 
        requested_by.employee_name as requested_by,
        chairperson.employee_name as chairperson,
        inspector.employee_name as inspector,
        property_unit.employee_name as property_unit,
        payee.account_name as payee,
        pr_purchase_request.purpose as project_title,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y') as from_date,
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y') as to_date
        
        FROM inspection_report
        INNER JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
        INNER JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
        INNER JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
        LEFT JOIN employee_search_view  as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
        LEFT JOIN employee_search_view as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
        LEFT JOIN employee_search_view as inspector ON request_for_inspection.fk_inspector = inspector.employee_id
        LEFT JOIN pr_office ON request_for_inspection.fk_pr_office_id = pr_office.id
        LEFT JOIN employee_search_view as requested_by ON request_for_inspection.fk_requested_by = requested_by.employee_id
        LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id  = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        
        WHERE inspection_report.id = :id
        GROUP BY 
        requested_by.employee_name,
        chairperson.employee_name,
        inspector.employee_name,
        property_unit.employee_name,
        payee.account_name,
        pr_project_procurement.title,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y'),
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y')
        
        ")
            ->bindValue(':id', $id)
            ->queryOne();
        // var_dump($query);
        // die();
        return $query;
    }
    public function rfiId($id)
    {
        $q = Yii::$app->db->createCommand("SELECT 

        request_for_inspection.id
        FROM inspection_report_items
        LEFT JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
        LEFT JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
        WHERE inspection_report_items.fk_inspection_report_id = :id
        GROUP BY
        request_for_inspection.id")
            ->bindValue(':id', $id)
            ->queryScalar();
        return $q;
    }
    public function iarId($id)
    {

        return  YIi::$app->db->createCommand("SELECT 
				iar.id as iar_id
        FROM inspection_report 
		LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
        WHERE 
        inspection_report.id = :id

        ")
            ->bindValue(':id', $id)
            ->queryScalar();
    }
    /**
     * Creates a new InspectionReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new InspectionReport();

    //     if ($model->load(Yii::$app->request->post())) {
    //         $model->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
    //         $model->ir_number = $this->irNumber();
    //         if ($model->save(false)) {
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         }
    //     }
    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing InspectionReport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InspectionReport model.
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
     * Finds the InspectionReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InspectionReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InspectionReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function irNumber()
    {

        $num = 1;
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(ir_number,'-',-1) AS UNSIGNED) as last_num FROM inspection_report ORDER BY last_num DESC LIMIT 1")->queryScalar();
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i <= 4; $i++) {
            $zero .= 0;
        }
        return date('Y') . '-' . $zero . $num;
    }
}
