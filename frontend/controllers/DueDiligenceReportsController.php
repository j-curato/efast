<?php

namespace frontend\controllers;

use Yii;
use app\models\DueDiligenceReports;
use app\models\DueDiligenceReportsSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DueDiligenceReportsController implements the CRUD actions for DueDiligenceReports model.
 */
class DueDiligenceReportsController extends Controller
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
                        'roles' => ['view_due_diligence_report']
                    ],
                    [
                        'actions' => [

                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_due_diligence_report']
                    ],
                    [
                        'actions' => [

                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_due_diligence_report']
                    ],
                    [
                        'actions' => [

                            'search-due-diligence-report',
                        ],
                        'allow' => true,
                        'roles' => ['@']
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
     * Lists all DueDiligenceReports models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DueDiligenceReportsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DueDiligenceReports model.
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
     * Creates a new DueDiligenceReports model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DueDiligenceReports();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
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

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DueDiligenceReports model.
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
                $items = Yii::$app->request->post('items') ?? [];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DueDiligenceReports model.
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
     * Finds the DueDiligenceReports model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DueDiligenceReports the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DueDiligenceReports::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchDueDiligenceReport()
    {
        if (Yii::$app->request->get()) {
            $page = Yii::$app->request->get("page") ?? 1;
            $text = Yii::$app->request->get("text") ?? null;
            return  DueDiligenceReports::searchSerialNumber($page, $text);
        }
    }
}
