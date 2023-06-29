<?php

namespace frontend\controllers;

use Yii;
use app\models\ItHelpdeskCsf;
use app\models\ItHelpdeskCsfSearch;
use DateTime;
use ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItHelpdeskCsfController implements the CRUD actions for ItHelpdeskCsf model.
 */
class ItHelpdeskCsfController extends Controller
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
    private function getSerialNum($period)
    {
        $dte = DateTime::createFromFormat('Y-m-d', $period);
        $yr = $dte->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(it_helpdesk_csf.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM it_helpdesk_csf  
            WHERE 
            it_helpdesk_csf.serial_number LIKE :yr
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr',  $yr . '%')
            ->queryScalar();
        if (empty($qry)) {
            $qry = 1;
        }
        $num = '';
        if (strlen($qry) < 3) {
            $num .= str_repeat(0, 3 - strlen($qry));
        }
        $num .= $qry;
        return  $dte->format('Y-m') . '-' . $num;
    }
    /**
     * Lists all ItHelpdeskCsf models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItHelpdeskCsfSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItHelpdeskCsf model.
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
     * Creates a new ItHelpdeskCsf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItHelpdeskCsf();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $model->serial_number = $this->getSerialNum($model->date);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save()) {
                    throw new ErrorException('Model Save Failed');
                }
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ItHelpdeskCsf model.
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
     * Deletes an existing ItHelpdeskCsf model.
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
     * Finds the ItHelpdeskCsf model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ItHelpdeskCsf the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ItHelpdeskCsf::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
