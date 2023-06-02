<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\CashDisbursement;
use Yii;
use app\models\LddapAdas;
use app\models\LddapAdasSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LddapAdasController implements the CRUD actions for LddapAdas model.
 */
class LddapAdasController extends Controller
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
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['@']
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
    private function cashDetails($model_id)
    {
        $qry = Yii::$app->db->createCommand("SELECT 
        dv_aucs_index.payee,
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger,
        dv_aucs_index.ttlAmtDisbursed,
        dv_aucs_index.ttlTax,
        dv_aucs_index.grossAmt
        
        FROM lddap_adas
        JOIN cash_disbursement ON lddap_adas.fk_cash_disbursement_id = cash_disbursement.id
        JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
        JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
        LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
        
        WHERE cash_disbursement_items.is_deleted = 0
        
        AND lddap_adas.id = :id")
            ->bindValue(':id', $model_id)
            ->queryAll();
        return $qry;
    }

    /**
     * Lists all LddapAdas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LddapAdasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LddapAdas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $model =  $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'cashDetails' => $this->cashDetails($id),
            'acic_no' => MyHelper::getCashDisbursementAcicNo($model->fk_cash_disbursement_id)
        ]);
    }

    /**
     * Creates a new LddapAdas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new LddapAdas();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('create', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Updates an existing LddapAdas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing LddapAdas model.
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
     * Finds the LddapAdas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LddapAdas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LddapAdas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
