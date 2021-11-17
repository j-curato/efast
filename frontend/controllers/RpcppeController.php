<?php

namespace frontend\controllers;

use Yii;
use app\models\Rpcppe;
use app\models\RpcppeSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
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


        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionInsert()
    {

        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $ppe_condition = $_POST['ppe_condition'];


            $certified_by = $_POST['certified_by'];
            $aprroved_by = $_POST['aprroved_by'];
            $verified_by = $_POST['verified_by'];
            $verified_by_pos = null;
            if (!empty($_POST['rpcppe_id'])) {
                $model = Rpcppe::findOne($_POST['rpcppe_id']);
            } else {

                $model = new Rpcppe();
            }

            $model->rpcppe_number = $this->getRpcppeNumber();
            $model->reporting_period = $reporting_period;
            $model->book_id = $book_id;
            $model->certified_by = $certified_by;
            $model->approved_by = $aprroved_by;
            $model->verified_by = $verified_by;
            $model->verified_pos = $verified_by_pos;
            if ($model->save()) {

                return $this->redirect(['view', 'id' => $model->rpcppe_number]);
            }
        }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rpcppe_number]);
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
    public function actionGenerate()
    {
        if ($_POST) {
            $book = $_POST['book_id'];
            $ppe_condition = $_POST['ppe_condition'];

            $query = Yii::$app->db->createCommand("SELECT 
            UPPER(employee_search_view.employee_name) as employee_name,
            property.*,
            
            IFNULL(ptr.ptr_number,'') as ptr_number,
            IFNULL(transfer_type.type,'') as transfer_type
            
            FROM property
            INNER JOIN par ON property.property_number = par.property_number
            LEFT JOIN employee_search_view ON par.employee_id = employee_search_view.employee_id
            LEFT JOIN ptr ON par.par_number = ptr.par_number
            LEFT JOIN transfer_type ON ptr.transfer_type_id = transfer_type.id
            WHERE property.book_id = :book_id
            ORDER BY employee_search_view.employee_id")
                ->bindValue(':book_id', $book)
                ->queryAll();
            return json_encode($query);
        }
    }
}
