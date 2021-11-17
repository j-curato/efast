<?php

namespace frontend\controllers;

use app\models\Par;
use app\models\PropertyCard;
use Yii;
use app\models\Ptr;
use app\models\PtrSearch;
use app\models\TransferType;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PtrController implements the CRUD actions for Ptr model.
 */
class PtrController extends Controller
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
                    'insert-ptr'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'insert-ptr'
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
     * Lists all Ptr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PtrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ptr model.
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
     * Creates a new Ptr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ptr();

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionInsertPtr()
    {
        if ($_POST) {

            $date = $_POST['date'];
            $transfer_type = $_POST['transfer_type'];
            $par_number = $_POST['par_number'];
            $reason = trim($_POST['reason']);
            $transfer_type_name = strtolower(TransferType::findOne($transfer_type)->type);
            $agency_to = $_POST['agency_to'];
            $employee_id = $_POST['employee_id'];


            if (!empty($_POST['model_id'])) {
                $model =  $this->findModel($_POST['model_id']);
            } else {
                $model = new Ptr();
                $model->ptr_number = $this->getPtrNumber();
            }

            if (
                $transfer_type_name === 'donation'
                || $transfer_type_name === 'relocate'
                || $transfer_type_name === 'disposal'

            ) {
                $model->agency_to_id = $agency_to;
                $model->employee_to = null;
            } else {
                $model->employee_to = $employee_id;
                $model->agency_to_id = null;
            }
            $model->par_number = $par_number;
            $model->transfer_type_id = $transfer_type;
            $model->date = $date;
            $model->reason = $reason;
            if ($model->validate()) {
                if ($model->save(false)) {
                    if (empty($_POST['model_id'])) {
                        $parModel = new Par();
                        $parModel->par_number = Yii::$app->memem->getParNumber();
                        $parModel->property_number = $model->par->property_number;
                        $parModel->agency_id = 1;
                        $parModel->date = $model->date;
                        $em = Yii::$app->db->createCommand("SELECT employee_id FROM employee WHERE office ='ro'")->queryScalar();
                        if (!empty($em)) {
                            $parModel->employee_id = $em;
                        }

                        if ($parModel->save()) {
                            $pc = new PropertyCard();
                            $pc->pc_number = Yii::$app->memem->getPcNumber();
                            $pc->par_number = $model->par_number;
                            Yii::$app->memem->generatePcQr($pc->pc_number);
                            if ($pc->save()) {
                            } else {
                                return json_encode($pc->errors);
                            }
                        } else {
                            return  json_encode($parModel->errors);
                        }
                    }
                    return $this->redirect(['view', 'id' => $model->ptr_number]);
                } else {
                    return json_encode('wala na save');
                }
            } else {
                return json_encode($model->errors);
            }
        }
    }

    /**
     * Updates an existing Ptr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ptr_number]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ptr model.
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
     * Finds the Ptr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Ptr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ptr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getPtrNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT 
        substring_index(ptr_number,'-',-1) as p
        FROM ptr
        ORDER BY p DESC LIMIT 1
        ")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $string = substr(str_repeat(0, 5) . $num, -5);

        return 'DTI XII-' . $string;
    }
}
