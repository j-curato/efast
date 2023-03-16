<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use Yii;
use app\models\Iirup;
use app\models\IirupItems;
use app\models\IirupSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IirupController implements the CRUD actions for Iirup model.
 */
class IirupController extends Controller
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
    private function InsertItems($model_id, $items = [], $isUpdate = false)
    {

        try {
            if ($isUpdate === true) {
                $item_ids = array_column($items, 'item_id');
                $params = [];
                $sql = '';
                if (!empty($item_ids)) {
                    $sql = 'AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                }
                Yii::$app->db->createCommand("UPDATE iirup_items SET is_deleted = 1 WHERE 
                       iirup_items.fk_iirup_id = :id  $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {
                if (!empty($itm['item_id'])) {
                    $iirupItem = IirupItems::findOne($itm['item_id']);
                } else {
                    $iirupItem = new IirupItems();
                }
                $iirupItem->fk_iirup_id = $model_id;
                $iirupItem->fk_par_id = $itm['par_id'];
                $iirupItem->is_deleted = 0;
                if (!$iirupItem->validate()) {
                    throw new ErrorException(json_encode($iirupItem->errors));
                }
                if (!$iirupItem->save(false)) {
                    throw new ErrorException("Item Save Failed");
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    private function getIirupItems($id)
    {
    }
    /**
     * Lists all Iirup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IirupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Iirup model.
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
     * Creates a new Iirup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Iirup();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = MyHelper::getUuid();
            $model->serial_number = MyHelper::getUuid();
            $items = !empty(MyHelper::post('items')) ? MyHelper::post('items') : [];
            try {
                $txn = MyHelper::beginTxn();
                if (empty($items)) {
                    throw new ErrorException("Items Must be More than or Equal to 1 ");
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItms = $this->InsertItems($model->id, $items);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
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
     * Updates an existing Iirup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $items = !empty(MyHelper::post('items')) ? MyHelper::post('items') : [];
            try {
                $txn = MyHelper::beginTxn();
                if (empty($items)) {
                    throw new ErrorException("Items Must be More than or Equal to 1 ");
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItms = $this->InsertItems($model->id, $items, true);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
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
        ]);
    }

    /**
     * Deletes an existing Iirup model.
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
     * Finds the Iirup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Iirup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Iirup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetProperties()
    {
        if (YIi::$app->request->post()) {
            $reporting_period = MyHelper::post('reporting_period');
            $employee_id = MyHelper::post('employee_id');

            $query = YIi::$app->db->createCommand("WITH depreciations  as (

                SELECT 
                dep.pty_id,
                SUM(dep.mnthly_depreciation) as ttlDep
                FROM 
                (
                    SELECT 
                    property.id as pty_id,
                    @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
                    ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
                    CAST((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life AS DECIMAL(10))as mnthly_depreciation
                    FROM property
                    LEFT JOIN other_property_details ON property.id  = other_property_details.fk_property_id
                    LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
                
                    WHERE 
                    (CASE
                    WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
                    ELSE DATE_FORMAT(property.date, '%Y-%m')
                    END ) <= :reporting_period
                    AND 
                    (CASE
                    WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life+1 MONTH), '%Y-%m')
                    ELSE DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life MONTH), '%Y-%m')
                    END ) >= :reporting_period
                    )
                     as dep
                    GROUP BY dep.pty_id
                
                )
                
                SELECT 
                par.id as par_id,
                property.property_number,
                par.par_number,
                IFNULL(property_articles.article_name,property.article) as article_name,
                property.description,
                property.serial_number,
                property.date as date_acquired,
                property.acquisition_amount,
                property.date,
                par.is_unserviceable,
                IFNULL(depreciations.ttlDep,0) as ttlDep
                FROM property
                LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
                JOIN par ON property.id = par.fk_property_id
                LEFT JOIN depreciations ON property.id = depreciations.pty_id
                WHERE 
                  par.is_current_user = 1
                AND par.fk_received_by = :emp_id ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':emp_id', $employee_id)
                ->queryAll();
            // $query = YIi::$app->db->createCommand("SELECT 
            // par.id as par_id,
            // property.property_number,
            // par.par_number,
            // IFNULL(property_articles.article_name,property.article) as article_name,
            // property.description,
            // property.serial_number,
            // property.date as date_acquired,
            // property.acquisition_amount,
            // books.`name` as book_name,

            // other_property_detail_items.amount,
            // property.date,
            // other_property_details.useful_life,
            // @start_month :=(CASE
            // WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
            // ELSE DATE_FORMAT(property.date, '%Y-%m')
            // END ) as strt_mnth,
            // @last_month :=  DATE_FORMAT(DATE_ADD(CONCAT(@start_month,'-01'),INTERVAL other_property_details.useful_life MONTH), '%Y-%m') as lst_mth,
            //  DATE_FORMAT(DATE_SUB(CONCAT(@last_month,'-01'),INTERVAL 1 MONTH), '%Y-%m') as sec_lst_mth,
            // @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
            // ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
            // ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as mnthly_depreciation,
            // par.is_unserviceable

            // FROM property
            //  LEFT JOIN other_property_details ON property.id  = other_property_details.fk_property_id
            // LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id

            // LEFT JOIN books ON other_property_detail_items.book_id = books.id
            // LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id  = chart_of_accounts.id
            // LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            // LEFT JOIN par ON property.id = par.fk_property_id
            // WHERE 
            // (CASE
            // WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
            // ELSE DATE_FORMAT(property.date, '%Y-%m')
            // END ) <= :reporting_period

            //  AND 
            //  (CASE
            // WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life+1 MONTH), '%Y-%m')
            // ELSE DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life MONTH), '%Y-%m')
            // END ) >= :reporting_period
            // AND par.is_current_user = 1
            // AND par.fk_received_by = :emp_id ")
            //     ->bindValue(':reporting_period', $reporting_period)
            //     ->bindValue(':emp_id', $employee_id)
            //     ->queryAll();
            return json_encode($query);
        }
    }
}
