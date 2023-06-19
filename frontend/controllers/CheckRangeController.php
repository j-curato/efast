<?php

namespace frontend\controllers;

use Yii;
use app\models\CheckRange;
use app\models\CheckRangeSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CheckRangeController implements the CRUD actions for CheckRange model.
 */
class CheckRangeController extends Controller
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
                    'update',
                    'create',
                    'delete'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [
                            'index',
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
     * Lists all CheckRange models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CheckRangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CheckRange model.
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
     * Creates a new CheckRange model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CheckRange();
        if ($_POST) {
            $from =  intval($_POST['from']);
            $to =  intval($_POST['to']);
            $reporting_period = $_POST['reporting_period'];
            $begin_balance = $_POST['begin_balance'];
            $bank_account_id = !empty($_POST['bank_account_id']) ? $_POST['bank_account_id'] : null;
            $province = Yii::$app->user->identity->province;
            if ($province === 'ro_admin') {
                $province = $_POST['province'];
            }
            if (!empty($_POST['model_id'])) {
                $check = CheckRange::findOne($_POST['model_id']);
            } else {
                $check = new CheckRange();
            }

            if ($from > 0 && $to > 0) {
                $x = $to  -  $from + 1;
                if ($x !== 100) {
                    return json_encode(['success' => false, 'error' => 'Not 100']);
                }
                $q = new Query();
                $q->select('*')
                    ->from('check_range')
                    ->where(':from_num BETWEEN check_range.`from` AND check_range.`to`', ['from_num' => $from])
                    ->orWhere(':to_num BETWEEN check_range.`from` AND check_range.`to`', ['to_num' => $to])
                    ->andWhere('check_range.province = :province ', ['province' => $province]);
                if (!empty($_POST['model_id'])) {
                    $q->andWhere('check_range.id != :model_id ', [':model_id' => $_POST['model_id']]);
                }
                $query = $q->all();
                // $query = Yii::$app->db->createCommand("SELECT 
                // *
                // FROM 
                // check_range
                // WHERE 
                // :from_num  BETWEEN check_range.`from` AND check_range.`to`
                // OR :to_num  BETWEEN check_range.`from` AND check_range.`to`
                // AND check_range.province = :province 
                // ")
                //     ->bindValue(':from_num', $from)
                //     ->bindValue(':to_num', $to)
                //     ->bindvalue(':province', $province)
                //     ->queryAll();
                if (!empty($query)) {
                    return json_encode(['success' => false, 'error' => 'Check Range exists a case where either the `From` or `To` values are in between other Check Range']);
                }
            }
            $check->province = $province;
            $check->from = $from;
            $check->to = $to;
            $check->reporting_period = $reporting_period;
            $check->begin_balance = $begin_balance;
            $check->bank_account_id = $bank_account_id;

            if ($check->save()) {
                return $this->redirect(['view', 'id' => $check->id]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing CheckRange model.
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
     * Deletes an existing CheckRange model.
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
     * Finds the CheckRange model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CheckRange the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CheckRange::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
