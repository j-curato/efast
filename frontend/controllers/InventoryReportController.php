<?php

namespace frontend\controllers;

use Yii;
use app\models\InventoryReport;
use app\models\InventoryReportEntries;
use app\models\InventoryReportSearch;
use ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InventoryReportController implements the CRUD actions for InventoryReport model.
 */
class InventoryReportController extends Controller
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
     * Lists all InventoryReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InventoryReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InventoryReport model.
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
     * Creates a new InventoryReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InventoryReport();

        if ($_POST) {

            return json_encode($_POST['pc_number']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing InventoryReport model.
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
     * Deletes an existing InventoryReport model.
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
     * Finds the InventoryReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventoryReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventoryReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionInsert()
    {
        if ($_POST) {
            $pc_numbers = $_POST['pc_number'];
            $transaction = Yii::$app->db->beginTransaction();

            if (!empty($_POST['id'])) {
                $model = InventoryReport::findOne($_POST['id']);
                foreach($model->inventoryReportEntries as $val){
                    $val->delete();
                }

            } else {
                $model = new InventoryReport();
            }


            try {


                if ($flag = $model->save(false)) {


                    foreach ($pc_numbers as $val) {

                        $entries = new InventoryReportEntries();
                        $entries->pc_number = $val;
                        $entries->inventory_report_id = $model->id;
                        if ($entries->save(false)) {
                        }

                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);

                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
