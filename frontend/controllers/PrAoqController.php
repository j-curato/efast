<?php

namespace frontend\controllers;

use Yii;
use app\models\PrAoq;
use app\models\PrAoqSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PrAoq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PrAoq();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrAoq model.
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

    public function aoqNumber($reporting_period)
    {

        $last_num  = Yii::$app->db->createCommand("SELECT CAST(substring_index(aoq_number,'-',-1) AS UNSIGNED)as last_id
        FROM pr_aoq ORDER BY last_id DESC LIMIT 1
        ")->queryOne();

        if (!empty($last_num)) {
            $last_num  = intval($last_num) + 1;
        } else {
            $last_num = 1;
        }
        $i = strlen($last_num);
        $zero = '';
        while ($i  < 4) {
            $zero .= 0;
        }

        return 'RO-' . $reporting_period . '-' . $zero . $last_num;
    }
    public function actionGetRfqInfo()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT

            pr_stock.bac_code,
            unit_of_measure.unit_of_measure,
            pr_stock.stock_title,
            pr_purchase_request_item.specification,
            pr_purchase_request_item.quantity
             FROM pr_rfq_item
            LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
            LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            
            WHERE pr_rfq_id = :id")
                ->bindValue(':id', $id)
                ->queryAll();

            return json_encode($query);
        }
    }
}
