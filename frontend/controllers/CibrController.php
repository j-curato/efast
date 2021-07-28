<?php

namespace frontend\controllers;

use Yii;
use app\models\Cibr;
use app\models\CibrSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CibrController implements the CRUD actions for Cibr model.
 */
class CibrController extends Controller
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
                    'view',
                    'update',
                    'delete',
                    'insert-cibr',
                    'final'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'update',
                            'delete',
                            'insert-cibr',
                            'final'
                        ],
                        'allow' => true,
                        'roles' => ['create_cibr']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
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
     * Lists all Cibr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CibrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cibr model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)

    {
        $model = $this->findModel($id);
        $dataProvider = Yii::$app->db->createCommand("SELECT 
        check_date,
        check_number,
        particular,
        amount,
        withdrawals,
        gl_object_code,
        gl_account_title,
        reporting_period
        from advances_liquidation
        where reporting_period <=:reporting_period AND province LIKE :province

        ORDER BY reporting_period,check_date,check_number
        ")
            ->bindValue(':reporting_period',   $model->reporting_period)
            ->bindValue(':province',   $model->province)

            ->queryAll();
        // return $reporting_period;

        // ob_clean();
        // echo "<pre>";
        // var_dump($result);
        // echo "</pre>";
        // return ob_get_clean();
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'province' =>   $model->province,
            'reporting_period' =>   $model->reporting_period,
            'book' =>   $model->book_name,
            'model' => $model

        ]);
    }

    /**
     * Creates a new Cibr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cibr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cibr model.
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
     * Deletes an existing Cibr model.
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
     * Finds the Cibr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cibr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cibr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionInsertCibr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            // $book = $_POST['book'];
            $province = $_POST['province'];


            $q = (new \yii\db\Query())
                ->select('id')
                ->from('cibr')
                ->where('province =:province', ['province' => $province])
                ->andWhere('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                // ->andWhere('book_name =:book_name', ['book_name' => $book])
                ->one();
            if (!empty($q)) {
                return json_encode(['isSuccess' => false, 'error' => 'CIBR already Exist']);
            }
            $cibr = new Cibr();
            $cibr->reporting_period = $reporting_period;
            // $cibr->book_name = $book;
            $cibr->province = $province;
            if ($cibr->validate()) {
                if ($cibr->save(false)) {
                    return json_encode(['isSuccess' => true, 'Successfully Save']);
                }
            }
        }
    }
    public function actionFinal($id)
    {
        $model = $this->findModel($id);

        $model->is_final === 0 ? $x = 1 : $x = 0;

        $model->is_final = $x;

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return 'qwe';
    }
    public function actionGetCibr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $province = $_POST['province'];
            // $book = $_POST['book'];

            if (
                empty($reporting_period)
                || empty($province)
                // || empty($book)
            ) {
                return json_encode(['error' => true, 'message' => 'Reporting Period,Province and Book are Required']);
            }


            $dataProvider = Yii::$app->db->createCommand("SELECT 
                check_date,
                check_number,
                particular,
                amount,
                withdrawals,
                vat_nonvat,
                expanded_tax,
                gl_object_code,
                gl_account_title, 
                reporting_period
                from advances_liquidation
                where reporting_period <=:reporting_period AND province LIKE :province
                ORDER BY reporting_period,check_date,check_number
            ")->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':province', $province)
                ->queryAll();
            $beginning_balance =  Yii::$app->db->createCommand("SELECT 
                SUM(amount)-SUM(withdrawals) as begin_balance
                from advances_liquidation
                where reporting_period <:reporting_period AND province LIKE :province
                ORDER BY reporting_period,check_date,check_number
               ")->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':province', $province)
                ->queryScalar();
            //  AND book_name LIKE :book


            // return $reporting_period;

            // ob_clean();
            // echo "<pre>";
            // var_dump($beginning_balance);
            // echo "</pre>";
            // return ob_get_clean();
            return $this->render('_form', [
                'dataProvider' => $dataProvider,
                'province' => $province,
                'reporting_period' => $reporting_period,
                'book' => '',
                'beginning_balace'=>$beginning_balance

            ]);
        } else {
            return $this->render('_form');
        }
    }
}
