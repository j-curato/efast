<?php

namespace frontend\controllers;

use Yii;
use app\models\TrackingSheet;
use app\models\TrackingSheetSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrackingSheetController implements the CRUD actions for TrackingSheet model.
 */
class TrackingSheetController extends Controller
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
                    'delete',
                    'create',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'delete',
                            'create',
                            'update',

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

    /**
     * Lists all TrackingSheet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrackingSheetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrackingSheet model.
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
     * Creates a new TrackingSheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = '';
        if ($_POST) {
            $transaction_type =  $_POST['transaction_type'];
            $ors =  $_POST['ors'];
            $payee = $_POST['payee'];
            $particular =  $_POST['particular'];

            if (!empty($_POST['update_id'])) {
                $model = $this->findModel($_POST['update_id']);
            } else {
                $model = new TrackingSheet();
                $model->tracking_number = $this->getTrackingNumber();
            }

            $model->transaction_type = $transaction_type;
            $model->process_ors_id = $ors;
            $model->payee_id = $payee;
            $model->particular = $particular;
            if ($model->save(false)) {

                // return $this->redirect(['view', 'id' => $model->id]);
                return json_encode([
                    'isSuccess' => true,
                    'id' => $model->id
                ]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TrackingSheet model.
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
     * Deletes an existing TrackingSheet model.
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
     * Finds the TrackingSheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrackingSheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrackingSheet::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetOrs()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT `transaction`.particular,
            `transaction`.payee_id
            FROM process_ors
            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            WHERE process_ors.id = :id
            ")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionGetAllOrs($id)
    {

        $ors = (new \yii\db\Query())
            ->select('process_ors.id,process_ors.serial_number')
            ->from('process_ors')
            ->join('LEFT JOIN', 'tracking_sheet', 'process_ors.id = tracking_sheet.process_ors_id')
            ->where('tracking_sheet.process_ors_id IS NULL');
        if (!empty($id)) {
            // return $id;
            $ors->orWhere('tracking_sheet.id = :id',['id'=>$id]);
        }

        $q = $ors->all();
        // $query =Yii::$app->db->createCommand("SELECT process_ors.id,process_ors.serial_number
        // FROM process_ors 

        // LEFT JOIN tracking_sheet ON process_ors.id = tracking_sheet.process_ors_id
        // WHERE tracking_sheet.process_ors_id  IS NULL OR tracking_sheet.id = :id")
        // ->bindValue(':id',$id)
        // ->queryAll();

        // ob_clean();
        // echo "<pre>";
        // var_dump($q);
        // echo "</pre>";
        // return ob_get_clean();
        return json_encode($q);
    }
    public function getTrackingNumber()
    {
        $query = Yii::$app->db->createCommand("SELECT substring_index(tracking_number,'-',-1) as q
         FROM tracking_sheet ORDER BY q DESC limit 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = $query + 1;
        }
        $string  = substr(str_repeat(0, 4) . $num, -4);
        return date('Y') . '-' . $string;
    }
    public function actionGetAllTrackingSheet()
    {
        $query  = Yii::$app->db->createCommand("SELECT tracking_sheet.*,payee.id as p_id,payee.account_name FROM tracking_sheet 
        LEFT JOIN payee ON tracking_sheet.payee_id = payee.id")->queryAll();
        return json_encode($query);
    }
    public function actionGetTrackingSheet()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT * FROM tracking_sheet WHERE id =:")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionUpdateSheet()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT * FROM tracking_sheet WHERE id =:id")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
}
