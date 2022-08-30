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
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index'
                        ],
                        'allow' => true,
                        'roles' => ['iar']
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
    public function signatories($id)
    {

        $query = Yii::$app->db->createCommand("SELECT 
        unit_head.employee_name as unit_head,
        chairperson.employee_name as chairperson,
        inspector.employee_name as inspector,
        property_unit.employee_name as property_unit,
        payee.account_name as payee,
        pr_project_procurement.title as project_title,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y') as inspection_from_date,
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y') as inspection_to_date,
        DATE_FORMAT(iar.created_at,'%M %d, %Y') as date_generated,
        DATE_FORMAT(pr_purchase_order.po_date,'%M %d, %Y') as po_date,
        CONCAT(pr_office.division,'-',pr_office.unit) as department

					
        FROM iar
        LEFT JOIN  inspection_report ON iar.fk_ir_id = inspection_report.id
        LEFT JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
        LEFT JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
        LEFT JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
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
        
        WHERE iar.id = :id
        GROUP BY 
        unit_head.employee_name,
        chairperson.employee_name ,
        inspector.employee_name ,
        property_unit.employee_name ,
        payee.account_name ,
        pr_project_procurement.title ,
         DATE_FORMAT(request_for_inspection_items.`from`,'%M %d, %Y') ,
        DATE_FORMAT(request_for_inspection_items.`to`,'%M %d, %Y') ,
        DATE_FORMAT(iar.created_at,'%M %d, %Y') ,
        DATE_FORMAT(pr_purchase_order.po_date,'%M %d, %Y') ,
        CONCAT(pr_office.division,'-',pr_office.unit)")
            ->bindValue(':id', $id)
            ->queryOne();
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
            'signatories' => $this->signatories($id)

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
            $query->select('iar.id, iar.iar_number AS text')
                ->from('iar')
                ->where(['like', 'iar.iar_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
