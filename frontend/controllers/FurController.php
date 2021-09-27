<?php

namespace frontend\controllers;

use Yii;
use app\models\Fur;
use app\models\FurSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * FurController implements the CRUD actions for Fur model.
 */
class FurController extends Controller
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
                    'delete',
                    'create',
                    'generate-fur',
                    'insert-fur',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'insert-fur',

                        ],
                        'allow' => true,
                        'roles' => ['create_fur']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'generate-fur',
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['province']
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
     * Lists all Fur models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fur model.
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
     * Creates a new Fur model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionInsertFur()
    {

        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $province = $_POST['province'];
            $model = new Fur();

            $check  = Yii::$app->db->createCommand("SELECT EXISTS(SELECT * FROM fur WHERE reporting_period = :reporting_period AND province = :province)")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':province', $province)
                ->queryScalar();
            // return json_encode(['isSuccess' => false, 'error' => intval($check)]);
            if (intval($check) === 1) {
                return json_encode(['isSuccess' => false, 'error' => 'Saved na']);
            } else {
                $model->reporting_period = $reporting_period;
                $model->province = $province;
                if ($model->save()) {
                    return json_encode(['isSuccess' => true, 'error' => 'Save na']);
                }
            }
        }
    }
    public function actionCreate()
    {
        $model = new Fur();


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fur model.
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
     * Deletes an existing Fur model.
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
     * Finds the Fur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fur::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionFindFur()
    {
        if ($_POST) {


            return json_encode($this->findModel($_POST['id'])->toArray());
        }
    }
    public function actionGenerateFur()
    {
        if ($_POST) {
            $province = $_POST['province'];
            $reporting_period = $_POST['reporting_period'];
            $x = explode('-', $reporting_period);
            $x[1] =  '0' . ($x[1] - 1);

            $prev = implode('-', $x);

            $query = Yii::$app->db->createCommand("CALL fur(:province,:reporting_period)")
                ->bindValue(':province', $province)
                ->bindValue(':reporting_period', $reporting_period)
                ->queryAll();
            $dataProvider = $query;
            // $conso_fur = YIi::$app->db->createCommand('CALL conso_fur(:province,:reporting_period,:prev_r_period)')
            //     ->bindValue(':province', $province)
            //     ->bindValue(':reporting_period', $reporting_period)
            //     ->bindValue(':prev_r_period', $prev)
            //     ->queryAll();
            $conso_fur = [];
            // $result = ArrayHelper::index($query, null, 'advances_type');
            $result = ArrayHelper::index($query, null, 'report_type');




            foreach ($result as $index => $val) {

                $beginning_balance = floatval(array_sum(array_column($result[$index], 'begining_balance')));
                $total_advances = floatval(array_sum(array_column($result[$index], 'total_advances')));
                $total_withdrawals = floatval(array_sum(array_column($result[$index], 'total_withdrawals')));
                $conso_fur[] = [

                    'report_type' => $index,
                    'begining_balance' => $beginning_balance,
                    'total_advances' => $total_advances,
                    'total_withdrawals' => $total_withdrawals
                ];
            }

            // $conso_fur[] = [
            //     'advances_type' => 'Advances for Operating Expenses',
            //     'begining_balance' => $opex_beginning_balance,
            //     'total_advances' => $opex_total_advances,
            //     'total_withdrawals' => $opex_total_withdrawals
            // ];
            // $conso_fur[] = [
            //     'advances_type' => 'Advances to Special Disbursing Officer',
            //     'begining_balance' => $sdo_beginning_balance,
            //     'total_advances' => $sdo_total_advances,
            //     'total_withdrawals' => $sdo_total_withdrawals
            // ];
            // ob_clean();
            // echo "<pre>";
            // var_dump($conso_fur);
            // echo "</pre>";
            // return ob_get_clean();

            return json_encode([
                'fur' => $dataProvider,
                'conso_fur' =>  $conso_fur,
            ]);
        }
    }
}
