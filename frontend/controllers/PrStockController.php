<?php

namespace frontend\controllers;

use Yii;
use app\models\PrStock;
use app\models\PrStockSearch;
use app\models\PrStockSpecification;
use ErrorException;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrStockController implements the CRUD actions for PrStock model.
 */
class PrStockController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PrStock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrStock model.
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
     * Creates a new PrStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertSpecs($specification, $model_id)
    {
        if (!empty($specification)) {
            foreach ($specification as $val) {
                $specs = new PrStockSpecification();
                $specs->pr_stock_id = $model_id;
                $specs->description = $val;

                if ($specs->save(false)) {
                } else {
                    return false;
                }
            }
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new PrStock();

        if ($model->load(Yii::$app->request->post())) {

            // $specification = $_POST['specification'];
            $transaction = Yii::$app->db->beginTransaction();
            try {

                if ($flag = true) {

                    if ($model->save()) {
                        // $flag = $this->insertSpecs($specification, $model->id);
                    } else {
                        $flag = false;
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('fail');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode('fail');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // $specification = !empty($_POST['specification']) ? $_POST['specification'] : [];
            $transaction = Yii::$app->db->beginTransaction();

            try {

                foreach ($model->prStockSpecification as $val) {
                    $val->delete();
                }

                if ($flag = true) {

                    if ($model->save()) {
                        // $flag = $this->insertSpecs($specification, $model->id);
                    } else {
                        $flag = false;
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('fail');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode('fail');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrStock model.
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
     * Finds the PrStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrStock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchStock($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" id, `stock` as text"])
                ->from('pr_stock')
                ->where(['like', 'stock', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    public function actionStockInfo()
    {

        if ($_POST) {

            $query = Yii::$app->db->createCommand("SELECT `description`,FORMAT(amount,2) as amount  FROM `pr_stock` WHERE id =:id")
                ->bindValue(':id', $_POST['id'])
                ->queryOne();
            return json_encode($query);
        }
    }
}
