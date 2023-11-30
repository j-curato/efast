<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\db\Expression;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\FmiLguLiquidations;
use yii\web\NotFoundHttpException;
use app\models\FmiLguLiquidationsSearch;
use common\models\User;

/**
 * FmiLguLiquidationsController implements the CRUD actions for FmiLguLiquidations model.
 */
class FmiLguLiquidationsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['view_fmi_lgu_liquidation'],
                    ],
                    [
                        'actions' => [

                            'update',

                        ],
                        'allow' => true,
                        'roles' => ['update_fmi_lgu_liquidation'],
                    ],
                    [
                        'actions' => [
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_fmi_lgu_liquidation'],
                    ],
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
     * Lists all FmiLguLiquidations models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FmiLguLiquidationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FmiLguLiquidations model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $items = $model->getFmiLguLiquidationItemsA([
            'date',
            'check_number',
            'payee',
            'particular',
            'grant_amount',
            'equity_amount',
            'other_fund_amount',
            new Expression("DATE_FORMAT(CONCAT(reporting_period,'-01'), '%M %Y') AS formatted_period")
        ]);
        return $this->render('view', [
            'model' => $model,
            'items' => $items
        ]);
    }

    /**
     * Creates a new FmiLguLiquidations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FmiLguLiquidations();
        $user_data = User::getUserDetails();
        $model->fk_office_id = $user_data->employee->office->id;
        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items');
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $insertItems = $model->insertItems($items, true);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {

                $txn->rollBack();
                return $e->getMessage();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FmiLguLiquidations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            try {
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items');
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $insertItems = $model->insertItems($items);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }
        $items = $model->getFmiLguLiquidationItemsA([
            'id',
            'date',
            'check_number',
            'payee',
            'particular',
            'grant_amount',
            'equity_amount',
            'other_fund_amount',
            new Expression("DATE_FORMAT(CONCAT(reporting_period,'-01'), '%M %Y') AS formatted_period")
        ]);
        return $this->render('update', [
            'model' => $model,
            'items' => $items,
        ]);
    }

    /**
     * Deletes an existing FmiLguLiquidations model.
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
     * Finds the FmiLguLiquidations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FmiLguLiquidations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FmiLguLiquidations::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
