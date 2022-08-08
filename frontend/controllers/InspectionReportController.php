<?php

namespace frontend\controllers;

use Yii;
use app\models\InspectionReport;
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
        $searchModel = new InspectionReportSearch();
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
            'itemDetails' => $this->itemDetails($id)
        ]);
    }
    public function itemDetails($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 
        payee.account_name as payee,
        pr_project_procurement.title as project_title,
        request_for_inspection_items.`from` as from_date,
        request_for_inspection_items.`to` as to_date,
        pr_stock.stock_title,
        REPLACE(pr_purchase_request_item.specification,'[n]','<br>') as specification
        FROM `inspection_report`
        LEFT JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
        LEFT JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
        LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN pr_rfq_item  ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
        WHERE inspection_report.id = :id        
    ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $query;
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
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

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
