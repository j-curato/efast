<?php

namespace frontend\controllers;

use Yii;
use app\models\Transmittal;
use app\models\TransmittalEntries;
use app\models\TransmittalSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransmittalController implements the CRUD actions for Transmittal model.
 */
class TransmittalController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                    'insert-transmittal'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'view',
                            'create',
                            'insert-transmittal'
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
     * Lists all Transmittal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transmittal model.
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
     * Creates a new Transmittal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transmittal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Transmittal model.
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

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transmittal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        // return $this->redirect(['index']);
    }

    /**
     * Finds the Transmittal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transmittal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transmittal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionInsertTransmittal()
    {
        if ($_POST) {
            $date = $_POST['date'];
            $cash_disbursement_id = $_POST['cash_disbursement_id'];
            $update_id  = !empty($_POST['update_id']) ? $_POST['update_id'] : '';

            $transmittal_number =  $this->getTransmittalNumber($date);

            if (!empty($update_id)) {
                $tr = Transmittal::findOne($update_id);
                foreach ($tr->transmittalEntries as $q) {
                    $q->delete();
                }
            } else {

                $tr = new Transmittal();
                $tr->transmittal_number = $transmittal_number;
            }
            $tr->location = 'COA';
            $tr->date = $date;
            if ($tr->validate()) {
                if ($tr->save(false)) {

                    foreach ($cash_disbursement_id as $val) {
                        $tr_entries = new TransmittalEntries();
                        $tr_entries->cash_disbursement_id = $val;
                        $tr_entries->transmittal_id = $tr->id;
                        if ($tr_entries->validate()) {
                            if ($tr_entries->save(false)) {
                            }
                        } else {
                            return json_encode(['isSuccess' => false, 'error' => $tr_entries->errors]);
                            die();
                        }
                    }
                }
            } else {

                return json_encode(['isSucces' => false, 'error' => $tr->errors]);
            }

            return json_encode(['isSuccess' => true, 'id' => $tr->id]);
        }
    }
    public function getTransmittalNumber($date)
    {
        $query = (new \yii\db\Query())
            ->select("*")
            ->from("transmittal")
            ->orderBy('id DESC')
            ->one();
        $id = 0;
        if (empty($query)) {
            $id = 1;
        } else {
            $x = explode('-', $query['transmittal_number']);
            $id = $x[1] + 1;
        }
        $final_id = '';
        for ($y = strlen($id); $y < 4; $y++) {
            $final_id .= 0;
        }
        $final_id .= $id;
        $transmittal_number = date('Y', strtotime($date)) . '-' . $final_id;
        return $transmittal_number;
    }
}
