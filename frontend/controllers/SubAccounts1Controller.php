<?php

namespace frontend\controllers;

use Yii;
use app\models\SubAccounts1;
use app\models\SubAccounts1Search;
use app\models\SubAccounts2;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubAccounts1Controller implements the CRUD actions for SubAccounts1 model.
 */
class SubAccounts1Controller extends Controller
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
     * Lists all SubAccounts1 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubAccounts1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubAccounts1 model.
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
     * Creates a new SubAccounts1 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubAccounts1();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SubAccounts1 model.
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
     * Deletes an existing SubAccounts1 model.
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
     * Finds the SubAccounts1 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubAccounts1 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubAccounts1::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionCreateSubAccount()
    {

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {

        // }
        if ($_POST) {
            $model = new SubAccounts2();
            $account_title = $_POST['account_title'];
            $id = $_POST['id'];

            $sub_acc1_ojc_code = SubAccounts1::find()
                ->where("id = :id", ['id' => $id])->one()->object_code;
            $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;
            
            $uacs = $sub_acc1_ojc_code . '_';
            // echo $uacs;
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }
            $model->sub_accounts1_id = $id;
            $model->object_code = $uacs . $last_id;
            $model->name = $account_title;
            if ($model->validate()) {

                if ($model->save()) {
                    return 'success';
                }
            } else {
                $errors = $model->errors;
                return json_encode($errors);
            }
            // return $this->redirect(['view', 'id' => $model->id]);
        }
    }
}
