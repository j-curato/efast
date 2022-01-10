<?php

namespace frontend\controllers;

use app\models\PrProjectProcurement;
use Yii;
use app\models\PrPurchaseRequest;
use app\models\PrPurchaseRequestItem;
use app\models\PrPurchaseRequestSearch;
use ErrorException;
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
    public function insertPrItems($model_id, $pr_stocks_id, $unit_cost, $quantity)
    {
        foreach ($pr_stocks_id as $i => $val) {
            $item = new PrPurchaseRequestItem();
            $item->pr_purchase_request_id = $model_id;
            $item->pr_stock_id = $val;
            $item->quantity = $quantity[$i];
            $item->unit_cost = $unit_cost[$i];

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

            $pr_stocks_id = [];
            $unit_cost = $_POST['unit_cost'];
            $quantity = $_POST['quantity'];
            if (empty($_POST['pr_stocks_id'])) {
                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
                $pr_stocks_id = $_POST['pr_stocks_id'];
            }
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag =  $this->insertPrItems(
                            $model->id,
                            $pr_stocks_id,
                            $unit_cost,
                            $quantity
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

        if ($model->load(Yii::$app->request->post())) {

            $pr_stocks_id = [];

            $unit_cost = $_POST['unit_cost'];
            $quantity = $_POST['quantity'];
            if (empty($_POST['pr_stocks_id'])) {

                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
            } else {
                $pr_stocks_id = $_POST['pr_stocks_id'];
            }
            $transaction = Yii::$app->db->beginTransaction();

            foreach ($model->prItem as $val) {
                $val->delete();
            }




            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag = $this->insertPrItems(
                            $model->id,
                            $pr_stocks_id,
                            $unit_cost,
                            $quantity
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

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
    public function actionPrNumber()
    {


        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_number,'-',-1) AS UNSIGNED) as last_number FROM pr_purchase_request  ")
            ->queryOne();

        $num  = 1;
        if (!empty($query)) {
            $num = intval($query['last_number']) + 1;
        }
        return $num;
    }
}
