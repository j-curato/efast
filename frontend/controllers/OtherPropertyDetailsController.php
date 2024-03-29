<?php

namespace frontend\controllers;

use app\models\ChartOfAccounts;
use app\models\OtherPropertyDetailItems;
use Yii;
use app\models\OtherPropertyDetails;
use app\models\OtherPropertyDetailsIndexSearch;
use app\models\OtherPropertyDetailsSearch;
use app\models\SubAccounts1;
use Behat\Gherkin\Filter\RoleFilter;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * OtherPropertyDetailsController implements the CRUD actions for OtherPropertyDetails model.
 */
class OtherPropertyDetailsController extends Controller
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
                    'index',
                    'update',
                    'delete',
                    'create',
                    'search-chart-of-accounts',
                    'items',
                    'property-details',
                    'get-frt-mth-dep',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',

                        ],
                        'allow' => true,
                        'roles' => ['view_other_property_details']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_other_property_details']
                    ],
                    [
                        'actions' => [
                            'create',

                        ],
                        'allow' => true,
                        'roles' => ['create_other_property_details']
                    ],
                    [
                        'actions' => [
                            'search-chart-of-accounts',
                            'items',
                            'property-details',
                            'get-frt-mth-dep',
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
    private function firstMonthDepreciation($property_id)
    {
        return YIi::$app->db->createCommand("SELECT first_month_depreciation FROM `other_property_details` 
        WHERE other_property_details.fk_property_id =:property_id 
        AND other_property_details.depreciation_schedule = 1")
            ->bindValue(':property_id', $property_id)
            ->queryScalar();
    }

    /**
     * Lists all OtherPropertyDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OtherPropertyDetailsIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OtherPropertyDetails model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $this->render('view', [
            'model' => $model,
            'items' => $this->findItems($id, $model->fk_property_id),
            'propertyDetails' => $this->propertyDetails($model->fk_property_id),
        ]);
    }
    public function effectOfAdjustment(
        $property_id,
        $start_month_depreciation,
        $depreciation_schedule
    ) {
        return Yii::$app->db->createCommand("SELECT 
   
        ROUND((q.amount - ROUND((other_property_details.salvage_value_prcnt/100)*q.amount,2))
        /
        (ppe_useful_life.life_from *12 ))
        * TIMESTAMPDIFF(MONTH,  CONCAT(q.first_month_depreciation,'-01'),:start_month_depreciation) total_depreciated,


        other_property_details.depreciation_schedule,
        q.amount,
        q.book_name


        FROM 
        other_property_details
        LEFT JOIN (
        SELECT 
        other_property_details.fk_property_id,
        books.`name`as book_name,
        other_property_details.first_month_depreciation,
        other_property_detail_items.amount
        
         FROM other_property_details
        LEFT JOIN other_property_detail_items ON other_property_details.id = other_property_detail_items.fk_other_property_details_id
        LEFT JOIN books ON other_property_detail_items.book_id = books.id
        WHERE 
        other_property_details.fk_property_id = :property_id
        AND other_property_details.depreciation_schedule = 1
        ) as q ON other_property_details.fk_property_id = q.fk_property_id
        LEFT JOIN sub_accounts1 ON other_property_details.fk_sub_account1_id = sub_accounts1.id
        LEFT JOIN sub_accounts1 as depreciation_sub_account ON other_property_details.fk_depreciation_sub_account1_id = depreciation_sub_account.id
        LEFT JOIN chart_of_accounts ON other_property_details.fk_chart_of_account_id  = chart_of_accounts.id
        LEFT JOIN ppe_useful_life ON chart_of_accounts.fk_ppe_useful_life_id = ppe_useful_life.id
        WHERE other_property_details.fk_property_id = :property_id
         AND other_property_details.depreciation_schedule <=:depreciation_schedule
        ")
            ->bindValue(':property_id', $property_id)
            ->bindValue(':start_month_depreciation', $start_month_depreciation . '-01')
            ->bindValue(':depreciation_schedule', $depreciation_schedule)
            ->queryAll();
    }
    public function findItems($id, $property_id = '')
    {
        return Yii::$app->db->createCommand('SELECT 
            other_property_detail_items.id,
            other_property_detail_items.fk_other_property_details_id,
            other_property_detail_items.book_id,
            other_property_detail_items.amount,
            books.name as book_name
        FROM other_property_detail_items
        LEFT JOIN books ON other_property_detail_items.book_id = books.id
        WHERE 
        other_property_detail_items.fk_other_property_details_id = :id
        AND other_property_detail_items.is_deleted !=1
        ')
            ->bindValue(':id', $id)
            ->queryAll();
    }

    /**
     * Creates a new OtherPropertyDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function insertItems($id = '', $items = [])

    {

        if (empty($id)) {
            return ['isSuccess' => false, 'error_message' => 'Parent ID Required'];
        }
        if (empty(array_column($items, 'book')[0])) {
            return ['isSuccess' => false, 'error_message' => 'Items Required'];
        }
        try {
            foreach ($items as $val) {

                if (!empty($val['item_id'])) {
                    $item = OtherPropertyDetailItems::findOne($val['item_id']);
                } else {

                    $item = new OtherPropertyDetailItems();
                }
                $item->fk_other_property_details_id = $id;
                $item->book_id = $val['book'];
                $item->amount = $val['amount'];
                if (!$item->validate()) {
                    throw new ErrorException(json_encode($item->errors));
                }
                if (!$item->save(false)) {
                    throw new ErrorException('Item Save Failed');
                }
            }
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }

        return ['isSuccess' => true];
    }
    public function createSubAccount($chart_of_account_id = '', $property_number = '')
    {
        try {
            if (empty($chart_of_account_id) || empty($property_number)) {
                throw new ErrorException('Chart of Account and Property Number Cannot be empty');
            }
            $chart_uacs = ChartOfAccounts::find()
                ->where("id = :id", ['id' => $chart_of_account_id])->one();
            $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;
            $uacs = $chart_uacs->uacs . '_';
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }

            $account_title = $chart_uacs->general_ledger . '-' . $property_number;

            $check_if_exists = Yii::$app->db->createCommand("SELECT id FROM sub_accounts1 WHERE sub_accounts1.name = :account_title")
                ->bindValue(':account_title', $account_title)
                ->queryScalar();

            if (!empty($check_if_exists)) {
                return ['isSuccess' => true, 'id' => $check_if_exists];
            }
            $model = new SubAccounts1();
            $model->chart_of_account_id = $chart_of_account_id;
            $model->object_code = $uacs . $last_id;
            $model->name = $account_title;
            $model->is_active = 1;
            if (!$model->validate()) {
                throw new ErrorException(json_encode($model->errors));
            }
            if (!$model->save(false)) {

                throw new ErrorException("Save Sub Account Failed");
            }
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }

        return ['isSuccess' => true, 'id' => $model->id];
    }
    public function actionCreate()
    {
        $model = new OtherPropertyDetails();


        if ($model->load(Yii::$app->request->post())) {
            $items = !empty(Yii::$app->request->post('items')) ? Yii::$app->request->post('items') : [];
            $model->id = Yii::$app->db->createCommand('SELECT UUID_SHORT()')->queryScalar();
            try {
                $transaction = Yii::$app->db->beginTransaction();
                $ptyIsExists = Yii::$app->db->createCommand("SELECT EXISTS(SELECT id FROM other_property_details WHERE other_property_details.fk_property_id = :id)")
                    ->bindValue(':id', $model->fk_property_id)
                    ->queryScalar();
                if (!empty($ptyIsExists)) {
                    throw new ErrorException("naa nay other propperty details");
                }
                $acq_amt = YIi::$app->db->createCommand("SELECT property.acquisition_amount FROM property WHERE property.id = :id")
                    ->bindValue(':id', $model->fk_property_id)
                    ->queryScalar();
                $itemsSum = array_sum(array_column($items, 'amount'));
                if (floatval($acq_amt) !== floatval($itemsSum)) {
                    throw new ErrorException('Property Acquisition Amount is not Equal to the  Total Amount of the Items');
                }
                $property_query = Yii::$app->db->createCommand("SELECT property_number FROM property WHERE property.id = :id")
                    ->bindValue(':id', $model->fk_property_id)
                    ->queryOne();
                $property_number = !empty($property_query['property_number']) ? $property_query['property_number'] : '';
                $createSubAccount = $this->createSubAccount($model->fk_chart_of_account_id, $property_number);
                if ($createSubAccount['isSuccess'] === true) {
                    $model->fk_sub_account1_id = $createSubAccount['id'];
                }
                $create_depreciation_sub_account = $this->createSubAccount($model->fk_chart_of_account_id, $model->property->property_number);
                $decpreciation_account_id = YIi::$app->db->createCommand("SELECT fk_depreciation_id FROM chart_of_accounts WHERE chart_of_accounts.id = :id")
                    ->bindValue(':id', $model->fk_chart_of_account_id)
                    ->queryScalar();
                $create_depreciation_sub_account = $this->createSubAccount($decpreciation_account_id, $model->property->property_number);
                if ($create_depreciation_sub_account['isSuccess'] === true) {
                    $model->fk_depreciation_sub_account1_id = $create_depreciation_sub_account['id'];
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $insert_item = $this->insertItems($model->id, $items);
                if ($insert_item['isSuccess'] !== true) {
                    throw new ErrorException($insert_item['error_message']);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OtherPropertyDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $old_model =  $this->findModel($id);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $items = !empty(Yii::$app->request->post('items')) ? Yii::$app->request->post('items') : [];
            try {
                $transaction = Yii::$app->db->beginTransaction();
                $ptyIsExists = Yii::$app->db->createCommand("SELECT EXISTS(SELECT id 
                FROM other_property_details 
                WHERE other_property_details.fk_property_id = :property_id
                AND other_property_details.id !=:id)")
                    ->bindValue(':id', $model->id)
                    ->bindValue(':property_id', $model->fk_property_id)
                    ->queryScalar();
                if (!empty($ptyIsExists)) {
                    throw new ErrorException("naa nay other propperty details ang property number " . $model->property->property_number);
                }
                $acq_amt = YIi::$app->db->createCommand("SELECT property.acquisition_amount FROM property WHERE property.id = :id")
                    ->bindValue(':id', $model->fk_property_id)
                    ->queryScalar();
                $itemsSum = array_sum(array_column($items, 'amount'));
                if (floatval($acq_amt) !== floatval($itemsSum)) {
                    throw new ErrorException('Property Acquisition Amount is not Equal to the  Total Amount of the Items');
                }

                if ($old_model->fk_chart_of_account_id != $model->fk_chart_of_account_id) {
                    $property_query = Yii::$app->db->createCommand("SELECT property_number FROM property WHERE property.id = :id")
                        ->bindValue(':id', $model->fk_property_id)
                        ->queryOne();
                    $property_number = !empty($property_query['property_number']) ? $property_query['property_number'] : '';
                    $createSubAccount = $this->createSubAccount($model->fk_chart_of_account_id, $property_number);
                    if ($createSubAccount['isSuccess'] === true) {
                        $model->fk_sub_account1_id = $createSubAccount['id'];
                    }
                    $create_depreciation_sub_account = $this->createSubAccount($model->fk_chart_of_account_id, $model->property->property_number);
                    $decpreciation_account_id = YIi::$app->db->createCommand("SELECT fk_depreciation_id FROM chart_of_accounts WHERE chart_of_accounts.id = :id")
                        ->bindValue(':id', $model->fk_chart_of_account_id)
                        ->queryScalar();
                    $create_depreciation_sub_account = $this->createSubAccount($decpreciation_account_id, $model->property->property_number);
                    if ($create_depreciation_sub_account['isSuccess'] === true) {
                        $model->fk_depreciation_sub_account1_id = $create_depreciation_sub_account['id'];
                    }
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $insert_item = $this->insertItems($model->id, $items);
                if ($insert_item['isSuccess'] !== true) {
                    throw new ErrorException($insert_item['error_message']);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'items' => $this->findItems($id)
        ]);
    }

    /**
     * Deletes an existing OtherPropertyDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the OtherPropertyDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OtherPropertyDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OtherPropertyDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearchChartOfAccounts($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $uacs = [
            1060101000,
            1060201000,
            1060202000,
            1060299000,
            1060301000,
            1060302000,
            1060303000,
            1060304000,
            1060305000,
            1060306000,
            1060307000,
            1060308000,
            1060309000,
            1060399000,
            1060401000,
            1060402000,
            1060403000,
            1060404000,
            1060405000,
            1060406000,
            1060499000,
            1060501000,
            1060502000,
            1060503000,
            1060504000,
            1060505000,
            1060506000,
            1060507000,
            1060508000,
            1060509001,
            1060509002,
            1060509003,
            1060509004,
            1060509005,
            1060510000,
            1060511000,
            1060512000,
            1060513000,
            1060514000,
            1060599000,
            1060601000,
            1060602000,
            1060603000,
            1060604000,
            1060699000,
            1060701000,
            1060702000,
            1060801000,
            1060802000,
            1060803000,
            1060804000,
            1060805000,
            1060899000,
            1060901000,
            1060902000,
            1060999000,
            1061101000,
            1061102000,
            1061199000,
            1069901000,
            1069999000,

        ];
        $params = [];
        $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'chart_of_accounts.uacs', $uacs], $params);
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(["id as id, CONCAT (uacs ,'-',general_ledger) as text"])
                ->from('chart_of_accounts')
                ->where(['like', 'general_ledger', $q])
                ->orWhere(['like', 'uacs', $q])
                ->andWhere('is_active = 1')
                ->andWhere($sql, $params);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {


            $query = new Query();
            $query->select(["chart_of_accounts.id as id, CONCAT (chart_of_accounts.uacs ,'-',chart_of_accounts.general_ledger) as text,
                ppe_useful_life.life_from,
                ppe_useful_life.life_to"])
                ->from('chart_of_accounts')
                ->join('LEFT JOIN', 'ppe_useful_life', ' chart_of_accounts.fk_ppe_useful_life_id = ppe_useful_life.id')
                ->where('chart_of_accounts.id=:id', ['id' => $id]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['sub_accout'] = array_values($data);
        }
        return $out;
    }
    public function actionItems()
    {
        if (Yii::$app->request->isPost) {
            return YIi::$app->db->createCommand("SELECT `date` FROM property WHERE property.id = :id")
                ->bindValue(':id', Yii::$app->request->post('id'))
                ->queryScalar();
        }
    }
    public function propertyDetails($property_id)
    {
        return  Yii::$app->db->createCommand("SELECT 
        property.property_number,
        property.article,
        property.model,
        property.acquisition_amount,
        REPLACE(property.description,'[n]','<br>') as `description`,
        property.serial_number,
        property.date
         FROM property
         WHERE property.id = :id
        ")
            ->bindValue(':id', $property_id)
            ->queryOne();
    }
    public function actionPropertyDetails()
    {
        if (Yii::$app->request->isPost) {
            $id = $_POST['property_id'];
            return json_encode($this->propertyDetails($id));
        }
    }
    public function actionGetFrtMthDep()
    {

        if (Yii::$app->request->isPost) {
            return $this->firstMonthDepreciation($_POST['property_id']);
        }
    }
}
