<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use app\models\Office;
use app\models\Rpcppe;
use yii\db\Expression;
use common\models\User;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\RpcppeSearch;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\components\helpers\MyHelper;

/**
 * RpcppeController implements the CRUD actions for Rpcppe model.
 */
class RpcppeController extends Controller
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
                    'generate'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['view_rpcppe']
                    ],
                    [
                        'actions' => [
                            'update',
                            'generate'
                        ],
                        'allow' => true,
                        'roles' => ['update_rpcppe']
                    ],
                    [
                        'actions' => [
                            'create',
                            'generate'

                        ],
                        'allow' => true,
                        'roles' => ['create_rpcppe']
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
     * Lists all Rpcppe models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RpcppeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rpcppe model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'res' => $this->query($model->fk_chart_of_account_id, $model->fk_actbl_ofr, $model->fk_book_id, $model->fk_office_id, $model->reporting_period)
        ]);
    }

    /**
     * Creates a new Rpcppe model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rpcppe();
        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $model->fk_office_id = $user_data->employee->office->id;
        }
        if ($model->load(Yii::$app->request->post())) {

            $model->id = MyHelper::getUuid();
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Rpcppe model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Rpcppe model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rpcppe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Rpcppe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rpcppe::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getRpcppeNumber()
    {
        $query = Yii::$app->db->createCommand("SELECT substring_index(rpcppe_number,'-',-1) as q FROM rpcppe ORDER BY q DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }

        $string = substr(str_repeat(0, 5) . $num, -5);
        return 'DTI XIII-' . $string;
    }

    private function query($uacs_id, $emp_id = null, $book_id, $office_id = null, $reporting_period)
    {
        $lastDay = date('Y-m-t', strtotime("$reporting_period-01"));
      
        $uacs  = Yii::$app->db->createCommand("SELECT uacs FROM chart_of_accounts WHERE id = :id")->bindValue(':id', $uacs_id)->queryScalar();
        $book_name  = Yii::$app->db->createCommand("SELECT `name` FROM books WHERE id = :id")->bindValue(':id', $book_id)->queryScalar();

        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $office_id = $user_data->employee->office->id;
        }
        $qry = new Query();
        $qry->select([
            'detailed_property_database.rcv_by',
            'detailed_property_database.article',
            'detailed_property_database.description',
            'detailed_property_database.property_number',
            'detailed_property_database.unit_of_measure',
            'detailed_other_property_details.book_val',
            'detailed_property_database.rcv_by_pos',
            new Expression('1 as qty'),
            'detailed_property_database.uacs',
            'detailed_property_database.general_ledger',
            new Expression('IFNULL(detailed_property_database.act_usr,"") as act_usr'),

        ])
            ->from('detailed_property_database')
            ->join('JOIN', 'detailed_other_property_details', 'detailed_property_database.property_id = detailed_other_property_details.property_id')
            // ->andWhere(" detailed_property_database.isUnserviceable = 'serviceable'")
            ->andWhere("detailed_property_database.is_current_user = 1")
            ->andWhere("detailed_property_database.uacs = :uacs", ['uacs' => $uacs])
            ->andWhere("detailed_other_property_details.book_name = :book_name", ['book_name' => $book_name])
            ->andWhere("detailed_property_database.date_acquired <=:_date", ['_date' => $lastDay])
            ->andWhere("DATE_FORMAT(detailed_property_database.derecognition_date,'%Y-%m') >= :reporting_period OR detailed_property_database.derecognition_num IS NULL", ['reporting_period' => $reporting_period]);
        if (!empty($emp_id)) {
            $qry->andWhere("detailed_property_database.rcv_by_id = :emp_id", ['emp_id' => $emp_id]);
        }
        if (!empty($office_id)) {
            $offce_name = Office::findOne($office_id)->office_name;
            $qry->andWhere("detailed_property_database.office_name = :offce_name", ['offce_name' => $offce_name]);
        }

        // echo $qry->createCommand()->getRawSql();
        // die();
        $qry->orderBy('detailed_property_database.rcv_by,detailed_property_database.article');
        $res  = $qry->all();
        $result = ArrayHelper::index($res, null, 'rcv_by');
        return $result;
    }
    public function actionGenerate()
    {
        if (Yii::$app->request->post()) {
            $uacs_id = Yii::$app->request->post('uacs_id');
            $book_id = Yii::$app->request->post('book_id');
            $reporting_period = Yii::$app->request->post('reporting_period');
            $emp_id = !empty(Yii::$app->request->post('emp_id')) ? Yii::$app->request->post('emp_id') : null;
            $office_id = !empty(Yii::$app->request->post('office_id')) ? Yii::$app->request->post('office_id') : null;
            $qry = $this->query($uacs_id, $emp_id, $book_id, $office_id, $reporting_period);
            return json_encode($qry);
        }
    }
}
