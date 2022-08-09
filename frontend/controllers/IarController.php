<?php

namespace frontend\controllers;

use Yii;
use app\models\Iar;
use app\models\IarSearch;
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function signatories($id)
    {

        $query = Yii::$app->db->createCommand("SELECT 
        division_chief.employee_name as division_chief,
        chairperson.employee_name as chairperson,
        inspector.employee_name as inspector,
        property_unit.employee_name as property_unit,
        payee.account_name as payee,
        pr_project_procurement.title as project_title,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y') as from_date,
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y') as to_date
        
        FROM iar
LEFT JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
        LEFT JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
        LEFT JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
        LEFT JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
        LEFT JOIN employee_search_view  as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
        LEFT JOIN employee_search_view as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
        LEFT JOIN employee_search_view as inspector ON request_for_inspection.fk_inspector = inspector.employee_id
        LEFT JOIN pr_office ON request_for_inspection.fk_pr_office_id = pr_office.id
        LEFT JOIN employee_search_view as division_chief ON pr_office.fk_unit_head = division_chief.employee_id
        LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id  = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        
        WHERE iar.id = :id
        GROUP BY 
        division_chief.employee_name,
        chairperson.employee_name,
        inspector.employee_name,
        property_unit.employee_name,
        payee.account_name,
        pr_project_procurement.title,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y'),
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y')")
            ->bindValue(':id', $id)
            ->queryScalar();
        return $query;
    }
    public function items($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 
		pr_stock.bac_code,
        pr_stock.stock_title,
        unit_of_measure.unit_of_measure,
        pr_aoq_entries.amount,
        pr_purchase_request_item.quantity,
        IFNULL(REPLACE(pr_purchase_request_item.specification,'[n]','<br>'),'') as specification
        FROM iar

    LEFT JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
    LEFT  JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
    LEFT JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
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

    /**
     * Lists all Iar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IarSearch();
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
            'signatories' => $this->signatories($id)

        ]);
    }

    /**
     * Creates a new Iar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Iar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

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

        return $this->render('update', [
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

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
}
