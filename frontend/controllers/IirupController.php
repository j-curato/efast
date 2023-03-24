<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use Yii;
use app\models\Iirup;
use app\models\IirupIndexSearch;
use app\models\IirupItems;
use app\models\IirupSearch;
use app\models\Office;
use ErrorException;
use yii\db\Query;
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
                $q = Yii::$app->db->createCommand("UPDATE iirup_items SET is_deleted = 1 WHERE 
                       iirup_items.fk_iirup_id = :id  $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->query();
            }
            foreach ($items as $key => $itm) {
                $qry =  new Query();
                $qry->select('id')
                    ->from('iirup_items')
                    ->andWhere('iirup_items.is_deleted = 0')
                    ->andWhere('iirup_items.fk_other_property_detail_item_id = :opd_id', ['opd_id' => $itm['other_property_detail_item_id']]);



                if (!empty($itm['item_id'])) {
                    $qry->andWhere('iirup_items.id !=:item_id', ['item_id' => $itm['item_id']]);
                    $iirupItem = IirupItems::findOne($itm['item_id']);
                } else {
                    $iirupItem = new IirupItems();
                }
                $f_qry = $qry->all();
                $row = $key + 1;
                if (!empty($f_qry)) {
                    throw new ErrorException("Row " . $row . " already has an IIRUP.");
                }
                $iirupItem->fk_iirup_id = $model_id;
                $iirupItem->fk_other_property_detail_item_id = $itm['other_property_detail_item_id'];
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
        return Yii::$app->db->createCommand("CALL getIirupItems(:id)")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    /**
     * Lists all Iirup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IirupIndexSearch();
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
            'items' => $this->getIirupItems($id),
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
            $model->serial_number = $this->getSerialNumber($model->fk_office_id, $model->reporting_period);
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
        $oldModel =  $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $items = !empty(MyHelper::post('items')) ? MyHelper::post('items') : [];
            if ($oldModel->fk_office_id != $model->fk_office_id) {
                $model->serial_number =  $this->getSerialNumber($model->fk_office_id, $model->reporting_period);
            }
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
            'items' => $this->getIirupItems($id)
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

            // $query = YIi::$app->db->createCommand("WITH depreciations  as (

            //     SELECT 
            //     dep.pty_id,
            //     SUM(dep.mnthly_depreciation) as ttlDep
            //     FROM 
            //     (
            //         SELECT 
            //         property.id as pty_id,
            //         @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
            //         ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
            //         CAST((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life AS DECIMAL(10))as mnthly_depreciation
            //         FROM property
            //         LEFT JOIN other_property_details ON property.id  = other_property_details.fk_property_id
            //         LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id

            //         WHERE 
            //         (CASE
            //         WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
            //         ELSE DATE_FORMAT(property.date, '%Y-%m')
            //         END ) <= :reporting_period
            //         AND 
            //         (CASE
            //         WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life+1 MONTH), '%Y-%m')
            //         ELSE DATE_FORMAT(  DATE_ADD( property.date,INTERVAL other_property_details.useful_life MONTH), '%Y-%m')
            //         END ) >= :reporting_period
            //         )
            //          as dep
            //         GROUP BY dep.pty_id

            //     )

            //     SELECT 
            //     par.id as par_id,
            //     property.property_number,
            //     par.par_number,
            //     IFNULL(property_articles.article_name,property.article) as article_name,
            //     property.description,
            //     property.serial_number,
            //     property.date as date_acquired,
            //     property.acquisition_amount,
            //     property.date,
            //     par.is_unserviceable,
            //     IFNULL(depreciations.ttlDep,0) as ttlDep
            //     FROM property
            //     LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            //     JOIN par ON property.id = par.fk_property_id
            //     LEFT JOIN depreciations ON property.id = depreciations.pty_id
            //     WHERE 
            //       par.is_current_user = 1
            //     AND par.fk_received_by = :emp_id ")
            //     ->bindValue(':reporting_period', $reporting_period)
            //     ->bindValue(':emp_id', $employee_id)
            //     ->queryAll();
            $query = YIi::$app->db->createCommand("SELECT 
            other_property_detail_items.id as other_property_detail_item_id,
            property.property_number,
            par.par_number,
            IFNULL(property_articles.article_name,property.article) as article_name,
            property.description,
            property.serial_number,
            property.date as date_acquired,
            property.acquisition_amount,
            books.`name` as book_name,

            other_property_detail_items.amount,
            property.date,
            other_property_details.useful_life,
            @start_month :=(CASE
            WHEN DAY(property.date) > 15 THEN DATE_FORMAT(  DATE_ADD( property.date,INTERVAL 1 MONTH), '%Y-%m')
            ELSE DATE_FORMAT(property.date, '%Y-%m')
            END ) as strt_mnth,
            @last_month :=  DATE_FORMAT(DATE_ADD(CONCAT(@start_month,'-01'),INTERVAL other_property_details.useful_life MONTH), '%Y-%m') as lst_mth,
             DATE_FORMAT(DATE_SUB(CONCAT(@last_month,'-01'),INTERVAL 1 MONTH), '%Y-%m') as sec_lst_mth,
            @slvg_val := ROUND((other_property_details.salvage_value_prcnt/100)*other_property_detail_items.amount,2) as salage_value,
            ROUND(other_property_detail_items.amount - @slvg_val,2) as depreciable_amount,
            ROUND((other_property_detail_items.amount - @slvg_val)/other_property_details.useful_life)as mnthly_depreciation,
            par.is_unserviceable

            FROM property
              JOIN other_property_details ON property.id  = other_property_details.fk_property_id
            LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
            LEFT JOIN books ON other_property_detail_items.book_id = books.id
            LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
             JOIN par ON property.id = par.fk_property_id
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
           AND par.is_current_user = 1
            AND par.fk_received_by = :emp_id ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':emp_id', $employee_id)
                ->queryAll();
            return json_encode($query);
        }
    }
    private function getSerialNumber($office_id, $reporting_period)
    {
        $office_name = Office::findOne($office_id)->office_name;
        $query = Yii::$app->db->createCommand("call getIirupNo(:office_id)")
            ->bindValue(':office_id', $office_id)
            ->queryOne();

        // var_dump($query);
        // die();
        $num = 1;
        if (!empty($query['vcnt_num'])) {
            $num = intval($query['vcnt_num']);
        } else if (!empty($query['lst_num'])) {
            $num = intval($query['lst_num']);
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = strtoupper($office_name) . "-IIRUP-$reporting_period-" . $new_num;
        return $string;
    }
    public function actionSearchIirup($page = 1, $q = null, $id = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('iirup.id, iirup.serial_number AS text')
                ->from('iirup')
                ->where(['like', 'iirup.serial_number', $q]);
            $query->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
}
