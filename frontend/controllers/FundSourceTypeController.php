<?php

namespace frontend\controllers;

use app\models\AdvancesEntries;
use app\models\ChartOfAccounts;
use Yii;
use app\models\FundSourceType;
use app\models\FundSourceTypeSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FundSourceTypeController implements the CRUD actions for FundSourceType model.
 */
class FundSourceTypeController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'index',
                    'create',
                    'all-fund-source-type',
                    'search'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',

                        ],
                        'allow' => true,
                        'roles' => ['view_fund_source_type']
                    ],
                    [
                        'actions' => [
                            'update',

                        ],
                        'allow' => true,
                        'roles' => ['update_fund_source_type']
                    ],
                    [
                        'actions' => [
                            'create',

                        ],
                        'allow' => true,
                        'roles' => ['create_fund_source_type']
                    ],
                    [
                        'actions' => [
                            'search',
                            'all-fund-source-type'
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
     * Lists all FundSourceType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FundSourceTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FundSourceType model.
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
     * Creates a new FundSourceType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FundSourceType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {



            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FundSourceType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelOldName = $model->name;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            AdvancesEntries::updateAll(['fund_source_type' => $model->name], "`fund_source_type` = '$modelOldName'");
            // return json_encode($model->advancesEntries->);
            return $this->redirect(['view', 'id' => $model->id]);
        }


        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FundSourceType model.
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
     * Finds the FundSourceType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FundSourceType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FundSourceType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionAllFundSourceType()
    {
        $na = (new \yii\db\Query())->select('*')->from('fund_source_type')->all();
        return json_encode($na);
    }
    public function actionSearch($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('fund_source_type.name as id, fund_source_type.name AS text')
                ->from('fund_source_type')
                ->where(['like', 'fund_source_type.name', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        }
        return $out;
    }
    public function actionGetFundSourceTypes()
    {
        if (Yii::$app->request->get()) {
            $qry = Yii::$app->db->createCommand("SELECT id ,`name` as `text` FROM fund_source_type")->queryAll();
            return json_encode($qry);
        }
    }
}
