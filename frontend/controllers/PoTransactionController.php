<?php

namespace frontend\controllers;

use Yii;
use app\models\PoTransaction;
use app\models\PoTransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoTransactionController implements the CRUD actions for PoTransaction model.
 */
class PoTransactionController extends Controller
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
     * Lists all PoTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PoTransaction model.
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
     * Creates a new PoTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PoTransaction();

        if ($model->load(Yii::$app->request->post())) {
            $model->tracking_number = $this->getTrackingNumber($model->responsibility_center_id);
            if ($model->save(false)) {
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PoTransaction model.
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
     * Deletes an existing PoTransaction model.
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
     * Finds the PoTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PoTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoTransaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getTrackingNumber($responsibility_center_id)
    {
        $date = date("Y");
        $responsibility_center = (new \yii\db\Query())
            ->select("name")
            ->from('responsibility_center')
            ->where("id =:id", ['id' => $responsibility_center_id])
            ->one();

        $latest_tracking_no = Yii::$app->db->createCommand("SELECT substring_index(substring(tracking_number,instr(tracking_number,'-')+6),' ',1)as q
        FROM `po_transaction` ORDER BY q DESC LIMIT 1")->queryScalar();
        if (!empty($latest_tracking_no)) {
            $last_number = $latest_tracking_no + 1;
        } else {
            $last_number = 1;
        }
        $final_number = '';
        for ($y = strlen($last_number); $y < 3; $y++) {
            $final_number .= 0;
        }
        $final_number .= $last_number;
        $tracking_number = $responsibility_center['name'] . '-' . $date . '-' . $final_number;
        return  $tracking_number;
    }
    public function actionGetTransaction()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = (new \yii\db\Query())
                ->select('*')
                ->from('po_transaction')
                ->where('id =:id', ['id', $id])
                ->one();
            return json_encode(['data' => $query]);
        }
    }
}
