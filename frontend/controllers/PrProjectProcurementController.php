<?php

namespace frontend\controllers;

use Yii;
use app\models\PrProjectProcurement;
use app\models\PrProjectProcurementSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrProjectProcurementController implements the CRUD actions for PrProjectProcurement model.
 */
class PrProjectProcurementController extends Controller
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
                    'view',
                    'create',
                    'index',
                    'delete',
                    'update',

                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'create',
                            'index',
                            'delete',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'view',
                            'create',
                            'index',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['project_procurement']
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
     * Lists all PrProjectProcurement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrProjectProcurementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrProjectProcurement model.
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

    /**`
     * Creates a new PrProjectProcurement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PrProjectProcurement();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $host = gethostname();
            $ip = gethostbyname($host);
            if ($ip !== '10.20.17.35') {
                $model->is_cloud = 1;
            }
            if ($model->save()) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrProjectProcurement model.
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrProjectProcurement model.
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
     * Finds the PrProjectProcurement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrProjectProcurement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrProjectProcurement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchProject($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);
        $division = strtolower(Yii::$app->user->identity->division);

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(' pr_project_procurement.id, pr_project_procurement.title AS text')
                ->from('pr_project_procurement')
                ->join("LEFT JOIN", 'pr_office', 'pr_project_procurement.pr_office_id = pr_office.id')
                ->where(['like', 'pr_project_procurement.title', $q]);
            if (

                $user_province === 'ro' &&
                $division === 'sdd' ||
                $division === 'cpd' ||
                $division === 'idd' ||
                $division === 'ord'


            ) {

                $query->andWhere('pr_office.division =:division ', ['division' => $division]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        // elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        // }
        return $out;
    }
}
