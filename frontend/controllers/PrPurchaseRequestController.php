<?php

namespace frontend\controllers;

use app\models\PrProjectProcurement;
use Yii;
use app\models\PrPurchaseRequest;
use app\models\PrPurchaseRequestItem;
use app\models\PrPurchaseRequestSearch;
use DateTime;
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
    public function insertPrItems($model_id, $pr_stocks_id, $unit_cost, $quantity, $specification)
    {
        foreach ($pr_stocks_id as $i => $val) {
            $item = new PrPurchaseRequestItem();
            $item->pr_purchase_request_id = $model_id;
            $item->pr_stock_id = $val;
            $item->quantity = $quantity[$i];
            $item->unit_cost = $unit_cost[$i];
            $item->specification = empty($specification[$i]) ? null : $specification[$i];
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

            $model->pr_number = $this->getPrNumber($model->date);

            $pr_stocks_id = [];
            $specification = [];
            $unit_cost = $_POST['unit_cost'];
            $quantity = $_POST['quantity'];
            if (empty($_POST['pr_stocks_id'])) {
                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
            } else {
                $pr_stocks_id = $_POST['pr_stocks_id'];
                $specification = $_POST['specification'];
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag =  $this->insertPrItems(
                            $model->id,
                            $pr_stocks_id,
                            $unit_cost,
                            $quantity,
                            $specification
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
            $old = $this->findModel($id);
            $old_date =  $old->date;
            $new_date = $model->date;


            $pr_stocks_id = [];
            $specification = [];
            $unit_cost = $_POST['unit_cost'];
            $quantity = $_POST['quantity'];
            if (empty($_POST['pr_stocks_id'])) {
                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
            } else {
                $pr_stocks_id = $_POST['pr_stocks_id'];
                $specification = $_POST['specification'];
            }
            if ($old_date !== $new_date) {
                $arr =  explode('-', $model->pr_number);
                $number = $arr[4];
                $province = $arr[0];

                $model->pr_number  = $province . '-' . $model->date . '-' . $number;
            }


            // var_dump($specification);
            // echo "<br>";
            // var_dump($pr_stocks_id);
            // die();
            $transaction = Yii::$app->db->beginTransaction();

            Yii::$app->db->createCommand("DELETE FROM pr_purchase_request_item WHERE pr_purchase_request_id = :id")
                ->bindValue(':id', $model->id)
                ->query();
            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag = $this->insertPrItems(
                            $model->id,
                            $pr_stocks_id,
                            $unit_cost,
                            $quantity,
                            $specification
                        );
                    } else {
                        return 'error';
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
                return json_encode($e->getMessage());
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
    public function getPrNumber($date)
    {

        $province = 'RO';
        $date = '2022-07-03';
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_number,'-',-1) AS UNSIGNED) as last_number FROM pr_purchase_request ORDER BY last_number DESC LIMIT 1")
            ->queryScalar();

        $num  = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $final = '';
        for ($i =  strlen($num); $i < 4; $i++) {
            $final .= 0;
        }



        return $province . '-' . $date . '-' . $final . $num;
    }
}
