<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use Yii;
use app\models\Derecognition;
use app\models\DerecognitionIndexSearch;
use app\models\DerecognitionSearch;
use ErrorException;
use Mpdf\Tag\Select;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DerecognitionController implements the CRUD actions for Derecognition model.
 */
class DerecognitionController extends Controller
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
                    'update',
                    'index',
                    'create',
                    'delete',
                    'get-iirup-items',
                    'get-property-details'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'update',
                            'index',
                            'create',
                            'delete',
                            'get-iirup-items',
                            'get-property-details'
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
    // CHECK IF PROPERTY IS ALREADY DERECOGNIZE
    private function checkDerecognized($property_id, $modelId = null)
    {
        $params = [];
        $sql = '';
        if (!empty($modelId)) {
            $sql = ' AND ';
            $sql .= YIi::$app->db->getQueryBuilder()->buildCondition(['!=', 'derecognition.id', $modelId], $params);
        }

        $qry = YIi::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `derecognition` WHERE derecognition.fk_property_id = :property_id $sql)", $params)
            ->bindValue(':property_id', $property_id)
            ->queryScalar();
        return $qry;
    }
    private function getDerecognizePropertyDetails($date, $property_id)
    {
        $qry = Yii::$app->db->createCommand("CALL derecognitionProperty(:drctn_date,:property_id,NULL)")
            ->bindValue(':drctn_date', $date)
            ->bindValue(':property_id', $property_id)
            ->queryAll();
        return $qry;
    }
    private  function getSerialNo()
    {
        $query = Yii::$app->db->createCommand("SELECT
        CAST(SUBSTRING_INDEX(derecognition.serial_number,'-',-1) AS UNSIGNED) as lstNum 
        FROM derecognition
        ORDER BY lstNum DESC
        LIMIT 1")
            ->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = date('Y') . '-' . $new_num;
        return $string;
    }
    /**
     * Lists all Derecognition models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DerecognitionIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Derecognition model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'propertyDetails' => $this->getDerecognizePropertyDetails($model->date, $model->fk_property_id)
        ]);
    }

    /**
     * Creates a new Derecognition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Derecognition();
        // $validator = new \yii\validators\RequiredValidator();

        // if (intval($model->type) == 1) {
        //     $validator->attributes = [
        //         'fk_iirup_id',
        //     ];
        // }
        // $model->validators[] = $validator;
        if ($model->load(Yii::$app->request->post())) {
            $txn = MyHelper::beginTxn();

            $model->id = MyHelper::getUuid();
            $model->serial_number = $this->getSerialNo();
            try {

                $q = $this->checkDerecognized($model->fk_property_id);
                if ($q) {
                    throw new ErrorException("Property is Already Derecoginize");
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Derecognition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = MyHelper::beginTxn();
                $q = $this->checkDerecognized($model->fk_property_id, $model->id);
                if ($q) {
                    throw new ErrorException("Property is Already Derecoginize");
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'propertyDetails' => $this->getDerecognizePropertyDetails($model->date, $model->fk_property_id)
        ]);
    }

    /**
     * Deletes an existing Derecognition model.
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
     * Finds the Derecognition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Derecognition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Derecognition::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetIirupItems()
    {
        if (Yii::$app->request->isPost) {
            $id = YIi::$app->request->post('id');
            $qry = Yii::$app->db->createCommand("CALL getIirupItems(:id)")
                ->bindValue(':id', $id)
                ->queryAll();
            return json_encode($qry);
        }
    }
    private function getPropertyDetails($id)
    {
        return   Yii::$app->db->createCommand("SELECT 
        property.property_number,
        property.date as date_acquired,
        property.serial_number,
        IFNULL(property_articles.article_name,property.article) as article_name,
        property.description,
        property.acquisition_amount,
        unit_of_measure.unit_of_measure,
        @start_month :=(CASE
        WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
        ELSE DATE_FORMAT(property.date, '%Y-%m')
        END ) as strt_mnth,
        @last_month :=  DATE_FORMAT(DATE_ADD(CONCAT(@start_month,'-01'),INTERVAL other_property_details.useful_life MONTH), '%Y-%m') as lst_mth,
        DATE_FORMAT(DATE_SUB(CONCAT(@last_month,'-01'),INTERVAL 1 MONTH), '%Y-%m') as sec_lst_mth,
        @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salvage_value,
        ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
        ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as mnthly_depreciation,
        other_property_detail_items.amount as book_amt,
        books.`name` as book_name
        FROM property
        LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
        LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
        LEFT JOIN other_property_details ON property.id = other_property_details.fk_property_id
        LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
        LEFT JOIN books ON other_property_detail_items.book_id = books.id
        WHERE 
        property.id = :id
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function actionGetPropertyDetails()
    {
        if (Yii::$app->request->post()) {

            $id = MyHelper::post('id');
            return json_encode($this->getPropertyDetails($id));
        }
    }
}
