<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\Office;
use app\models\Par;
use app\models\PropertyCard;
use Yii;
use app\models\Ptr;
use app\models\PtrIndexSearch;
use app\models\PtrSearch;
use app\models\TransferType;
use ErrorException;
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
                    'insert-ptr',
                    'get-property-details'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'get-property-details'
                        ],
                        'allow' => true,
                        'roles' => ['ptr']
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
        $searchModel = new PtrIndexSearch();
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
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'propertyDetails' => $this->propertyDetails($model->fk_property_id)
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
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
            $model->fk_office_id = $office_id;
        }
        if ($model->load(Yii::$app->request->post())) {


            try {
                $txn = MyHelper::beginTxn();
                $model->id  = MyHelper::getUuid();
                $model->ptr_number = $this->getPtrNumber($model->fk_office_id);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }

                $par  = new Par();
                $par->id =  MyHelper::getUuid();
                $par->fk_received_by  = $model->fk_received_by;
                $par->fk_property_id = $model->fk_property_id;
                $par->par_number = MyHelper::getParNumber($model->fk_office_id);
                $par->date = $model->date;
                $par->fk_location_id = $model->fk_location_id;
                $par->is_unserviceable = $model->is_unserviceable;
                $par->fk_office_id = $model->fk_office_id;
                $par->fk_issued_by_id = $model->fk_issued_by;
                $par->fk_actual_user = $model->fk_actual_user;
                $par->_year = date('Y');
                $par->fk_ptr_id = $model->id;
                $par->is_current_user = 1;
                if (!$par->validate()) {
                    throw new ErrorException(json_encode($par->errors));
                }
                if (!$par->save(false)) {
                    throw new ErrorException("PAR Model Save Failed");
                }
                MyHelper::UdpateParCurUser($par->id, $par->fk_property_id);
                $pc = new PropertyCard();
                $pc->id = MyHelper::getUuid();
                $pc->serial_number = MyHelper::getPcNumber($model->fk_office_id);
                $pc->fk_par_id = $par->id;
                Myhelper::generateQr($pc->serial_number);
                if (!$pc->validate()) {
                    throw new ErrorException(json_encode($pc->errors));
                }
                if (!$pc->save(false)) {
                    throw new ErrorException('PC Model Save Failed');
                }
                $txn->commit();
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
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
        $model->is_unserviceable = $model->par->is_unserviceable;
        $model->fk_location_id = $model->par->fk_location_id;
        $oldModel = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {;
            try {
                $txn = MyHelper::beginTxn();
                if ($oldModel->fk_office_id != $model->fk_office_id) {
                    $model->ptr_number = $this->getPtrNumber($model->fk_office_id);
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }

                $par  =  Par::findOne($model->par->id);
                if ($oldModel->fk_office_id != $model->fk_office_id) {
                    $par->par_number = MyHelper::getParNumber($model->fk_office_id);
                }
                $par->fk_property_id = $model->fk_property_id;
                $par->date = $model->date;
                $par->fk_location_id = $model->fk_location_id;
                $par->is_unserviceable = $model->is_unserviceable;
                $par->fk_office_id = $model->fk_office_id;
                $par->fk_issued_by_id = $model->fk_issued_by;
                $par->fk_actual_user = $model->fk_actual_user;
                if (!$par->validate()) {
                    throw new ErrorException(json_encode($par->errors));
                }
                if (!$par->save(false)) {
                    throw new ErrorException("PAR Model Save Failed");
                }
                $pc = PropertyCard::findOne($par->pc->id);
                if ($oldModel->fk_office_id != $model->fk_office_id) {
                    $pc->serial_number = MyHelper::getPcNumber($model->fk_office_id);
                }
                Myhelper::generateQr($pc->serial_number);
                if (!$pc->validate()) {
                    throw new ErrorException(json_encode($pc->errors));
                }
                if (!$pc->save(false)) {
                    throw new ErrorException('PC Model Save Failed');
                }
                $txn->commit();
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
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
    public function getPtrNumber($office_id)
    {
        $office_name = Office::findOne($office_id)->office_name;
        $query = Yii::$app->db->createCommand("call getLstPtrNum(:office_id)")
            ->bindValue(':office_id', $office_id)
            ->queryOne();
        $num = 1;
        if (!empty($query['vcnt_num'])) {
            $num = intval($query['vcnt_num']);
        } else if (!empty($query['lst_num'])) {
            $num = intval($query['lst_num']);
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = strtoupper($office_name) . '-PTR-' . $new_num;
        return $string;
    }
    private function propertyDetails($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        property.property_number,
        property.date as acquisition_date,
        property.acquisition_amount,
        property.description,
        property.serial_number,
        unit_of_measure.unit_of_measure,
        IFNULL(property_articles.article_name,property.article) as article,
        par.is_unserviceable,
        prev_user.from_officer
        FROM 
         property
        LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
        LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
        LEFT JOIN par ON property.id = par.fk_property_id
        LEFT JOIN employee_search_view as received_by ON par.fk_received_by = received_by.employee_id
        LEFT JOIN (SELECT 
  property.id,
  received_by.employee_name as from_officer
  FROM 
   property
  LEFT JOIN par ON property.id = par.fk_property_id
  LEFT JOIN employee_search_view as received_by ON par.fk_received_by = received_by.employee_id
  WHERE  par.is_current_user = 0
ORDER BY par.created_at DESC LIMIT 1) as prev_user ON property.id = prev_user.id
        WHERE property.id = :id
        AND par.is_current_user = 1
        ")
            ->bindValue(':id', $id)
            ->queryOne();
    }

    public function actionGetPropertyDetails()
    {
        if (Yii::$app->request->post()) {
            $id  = Yii::$app->request->post('id');
            return json_encode($this->propertyDetails($id));
        }
    }
}
