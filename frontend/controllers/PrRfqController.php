<?php

namespace frontend\controllers;

use Yii;
use app\models\PrRfq;
use app\models\PrRfqItem;
use app\models\PrRfqSearch;
use ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrRfqController implements the CRUD actions for PrRfq model.
 */
class PrRfqController extends Controller
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
     * Lists all PrRfq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrRfqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrRfq model.
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
     * Creates a new PrRfq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    function insertItems($model_id, $pr_purchase_request_item_id)
    {

        foreach ($pr_purchase_request_item_id as $val) {
            $rfq_item = new PrRfqItem();
            $rfq_item->pr_rfq_id = $model_id;
            $rfq_item->pr_purchase_request_item_id = $val;
            if ($rfq_item->save(false)) {
            } else {
                var_dump($pr_purchase_request_item_id->error);
                return false;
            }
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new PrRfq();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $model->id  = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();

            $pr_purchase_request_item_id = [];
            if (!empty($_POST['pr_purchase_request_item_id'])) {
                $pr_purchase_request_item_id = $_POST['pr_purchase_request_item_id'];
            }

            try {
                if ($flag = $model->save(false)) {

                    $flag  = $this->insertItems($model->id, $pr_purchase_request_item_id);
                } else {
                    return var_dump($model->errors);
                }
                if ($flag) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return "error";
                }
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrRfq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $model->id  = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();

            $pr_purchase_request_item_id = [];
            if (!empty($_POST['pr_purchase_request_item_id'])) {
                $pr_purchase_request_item_id = $_POST['pr_purchase_request_item_id'];
            }
            Yii::$app->db->createCommand("DELETE 
             FROM pr_rfq_item WHERE pr_rfq_id = :id")
            ->bindValue(':id',$model->id)
            ->query();

            try {
                if ($flag = $model->save(false)) {

                    $flag  = $this->insertItems($model->id, $pr_purchase_request_item_id);
                } else {
                    return var_dump($model->errors);
                }
                if ($flag) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return "error";
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
     * Deletes an existing PrRfq model.
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
     * Finds the PrRfq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrRfq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrRfq::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
