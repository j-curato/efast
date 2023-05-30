<?php

namespace frontend\controllers;

use Yii;
use app\models\CashReceived;
use app\models\CashReceivedSearch;
use app\models\DocumentRecieve;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashReceivedController implements the CRUD actions for CashReceived model.
 */
class CashReceivedController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                    'view',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ]
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
     * Lists all CashReceived models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashReceivedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CashReceived model.
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
     * Creates a new CashReceived model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CashReceived();

        if ($model->load(Yii::$app->request->post())) {
            $document = DocumentRecieve::findOne($model->document_recieved_id);
            if ($document->name === 'NCA - Notice of Cash Allocation') {
            } else if ($document->name === 'NTA - Notice of Transfer Allocation') {
                $model->nta_no = $model->nca_no;
            } else if ($document->name === 'NFT - Notice of Fund Transfer') {
                $model->nft_no = $model->nca_no;
            }
            if ($model->save(false)) {
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CashReceived model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->load(Yii::$app->request->post())) {
                $document = DocumentRecieve::findOne($model->document_recieved_id);
                if ($document->name === 'NCA - Notice of Cash Allocation') {
                    $model->nft_no = null;
                    $model->nta_no = null;
                } else if ($document->name === 'NTA - Notice of Transfer Allocation') {
                    $model->nta_no = $model->nca_no;
                    $model->nca_no = null;
                    $model->nft_no = null;
                } else if ($document->name === 'NFT - Notice of Fund Transfer') {
                    $model->nft_no = $model->nca_no;
                    $model->nca_no = null;
                    $model->nta_no = null;
                }
                if ($model->save(false)) {
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CashReceived model.
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
     * Finds the CashReceived model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CashReceived the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CashReceived::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
