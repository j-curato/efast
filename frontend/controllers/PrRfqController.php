<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use yii\db\Query;
use ErrorException;
use app\models\PrRfq;
use app\models\Office;
use yii\web\Response;

use common\models\User;
use yii\web\Controller;
use app\models\PrRfqItem;
use app\models\PrRfqSearch;
use yii\filters\VerbFilter;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use app\models\PrPurchaseRequest;
use yii\web\NotFoundHttpException;
use app\components\helpers\MyHelper;



/**
 * PrRfqController implements the CRUD actions for PrRfq model.
 */
class PrRfqController extends Controller
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
                    'search-rfq',
                    'cancel',
                    'get-pr-items'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'search-rfq',
                            'cancel',
                            'get-pr-items'
                        ],
                        'allow' => true,
                        'roles' => ['rfq']
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
     * Lists all PrRfq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrRfqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrRfq model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $rbac =  MyHelper::getRbac($model->bac_composition_id);


        return $this->render('view', [
            'model' => $model,
            'rbac' => $rbac
        ]);
    }

    /**
     * Creates a new PrRfq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    private function getSelectedItems($items)
    {
        $filterFunction = function ($item) {
            return isset($item['is_selected']) && $item['is_selected'] === 'on';
        };
        return $filterFunction;
    }

    private function validateDates()
    {
    }
    public function actionCreate()
    {

        $model = new PrRfq();

        $user_data = User::getUserDetails();
        $model->fk_office_id  = $user_data->employee->office->id;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $items = Yii::$app->request->post('items');
            $observers = Yii::$app->request->post('observers') ?? [];
            $selectedItems = !empty($items) ? array_filter($items, $this->getSelectedItems($items)) : [];

            try {
                $transaction = Yii::$app->db->beginTransaction();


                $province  = 'RO';
                if (strtotime($model->deadline) < strtotime($model->_date)) {
                    throw new ErrorException('Deadline  must be greater than the RFQ date.');
                }
                $rbac_id = Yii::$app->db->createCommand("SELECT id FROM bac_composition
                 WHERE :_date  >= bac_composition.effectivity_date 
                 AND :_date<= bac_composition.expiration_date
                 AND fk_office_id = :office_id 
                 AND is_disabled = 0")
                    ->bindValue(':_date', $model->_date)
                    ->bindValue(':office_id',   $model->fk_office_id)
                    ->queryOne();
                if (empty($rbac_id)) {
                    throw new ErrorException('No RBAC for selected Date');
                }


                $model->bac_composition_id = $rbac_id['id'];
                $model->province = $province;
                if (!$model->validate()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return  ActiveForm::validate($model);
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }

                $insertItems = $model->insertItems($selectedItems);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                // $insertObservers = $model->insertObservers($observers);
                // if ($insertObservers !== true) {
                //     throw new ErrorException($insertObservers);
                // }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    private function validateTotal($items, $abcAmount)
    {
        $query = (new Query())
            ->select(['SUM(quantity * unit_cost) as total'])
            ->from('pr_purchase_request_item')
            ->where(['id' => array_column($items, 'pr_purchase_request_item_id')]);
        if (floatval($query->scalar()) !== floatval($abcAmount)) {
            return "The selected items and the sum of the CO amount and MOOE amount are not equal.";
        }
        return true;
    }
    /**
     * Updates an existing PrRfq model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $oldmodel = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            try {
                $transaction = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items');
                $selectedItems = array_filter($items, $this->getSelectedItems($items));
                $observers = Yii::$app->request->post('observers') ?? [];
                $mfoItems = Yii::$app->request->post('mfoItems') ?? [];

                $province  = 'RO';
                $validateTotal = $this->validateTotal($selectedItems, floatval($model->mooe_amount) + floatval($model->co_amount));
                if ($validateTotal !== true) {
                    throw new ErrorException($validateTotal);
                }

                // $rbac_id = Yii::$app->db->createCommand("SELECT id FROM bac_composition WHERE :_date  >= bac_composition.effectivity_date AND :_date<= bac_composition.expiration_date ")
                //     ->bindValue(':_date', $model->_date)
                //     ->queryOne();
                // if (empty($rbac_id)) {
                //     throw new ErrorException('No RBAC for selected Date');
                // }
                // if (!$oldmodel->_date != $model->_date) {
                //     $model->rfq_number = $this->getRfqNumber($model->_date, $model->fk_office_id);
                // }
                // $model->bac_composition_id = $rbac_id['id'];
                $model->province = $province;

                if (!$model->validate()) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return  ActiveForm::validate($model);
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                $insertItems = $model->insertItems($selectedItems);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }
                $insertObservers = $model->insertObservers($observers);
                if ($insertObservers !== true) {
                    throw new ErrorException($insertObservers);
                }
                $insertMfoItems = $model->insertMfoItems($mfoItems);
                if ($insertMfoItems !== true) {
                    throw new ErrorException($insertMfoItems);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }
        $items = $model->getItems();
        $prItems = $this->formatPrItems($model->purchaseRequest->getPrItems());
        $prItemIds = array_column($items, 'pr_item_id');
        $itemsNotSelected = array_filter($prItems, function ($item) use ($prItemIds) {
            return  !in_array($item['pr_item_id'], $prItemIds);
        });
        $items = array_merge($items, $itemsNotSelected);
        return $this->render('update', [
            'model' => $model,
            'items' => $items,
        ]);
    }
    private function formatPrItems($items)
    {
        $res = [];
        foreach ($items as $item) {
            $res[] = [
                "pr_item_id" => $item['item_id'],
                "specification" => $item['specification'],
                "quantity" => $item['quantity'],
                "unit_cost" => $item['unit_cost'],
                "stock_title" => $item['stock_title'],
                "bac_code" => $item['bac_code'],
                "unit_of_measure" => $item['unit_of_measure'],
            ];
        }
        return $res;
    }

    /**
     * Deletes an existing PrRfq model.
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
     * Finds the PrRfq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrRfq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrRfq::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function getRfqNumber($date, $office_id)
    {
        $office  = Office::findOne($office_id);
        $d  = DateTime::createFromFormat('Y-m-d', $date);
        $num  = 1;
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_rfq.rfq_number,'-',-1) AS UNSIGNED)  as last_num FROM pr_rfq 
        WHERE rfq_number LIKE :_date 
        AND fk_office_id = :office_id
        ORDER BY last_num DESC LIMIT 1")
            ->bindValue('_date', '%' . $d->format('Y') . '%')
            ->bindValue('office_id', $office_id)
            ->queryScalar();
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $zero .= 0;
        }
        return strtoupper($office->office_name) . '-' . $date . '-' . $zero . $num;
    }
    public function actionSearchRfq($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" CAST(id as CHAR(50)) as id, `rfq_number` as text"])
                ->from('pr_rfq')
                ->where(['like', 'rfq_number', $q]);
            if (!Yii::$app->user->can('ro_procurement_admin')) {
                $user_data = User::getUserDetails();
                $query->andWhere('fk_office_id = :fk_office_id', ['fk_office_id' =>  $user_data->employee->office->id]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionCancel($id)
    {
        if (Yii::$app->request->post()) {
            try {
                $model = $this->findModel($id);
                $model->is_cancelled =  $model->is_cancelled ? 0 : 1;
                $model->cancelled_at = date('Y-m-d H:i:s');
                if ($model->is_cancelled === 1) {
                    $qry = Yii::$app->db->createCommand("SELECT 
                        GROUP_CONCAT(pr_aoq.aoq_number) as pr_nums
                        FROM pr_aoq
                        WHERE  pr_aoq.is_cancelled = 0
                        AND pr_aoq.pr_rfq_id = :id
                        GROUP BY 
                        pr_aoq.pr_rfq_id")
                        ->bindValue(':id', $model->id)
                        ->queryScalar();

                    if (!empty($qry)) {
                        throw new ErrorException("Unable to cancel RFQ,AOQ No./s $qry is/are not Cancelled.");
                    }
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                return json_encode(['error' => false, 'message' => 'Successfuly Save']);
            } catch (ErrorException $e) {
                return json_encode(['error' => true, 'message' => $e->getMessage()]);
            }
        }
    }
    public function actionGetPrItems()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $pr = PrPurchaseRequest::findOne($id);
            return json_encode([
                'prItems' =>   $this->formatPrItems($pr->getPrItems()),
                'prDetails' => $pr->getPrDetails()
            ]);
        }
    }
    public function actionSearchNopRfq($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" CAST(id as CHAR(50)) as id, `rfq_number` as text"])
                ->from('pr_rfq')
                ->where(['like', 'rfq_number', $q])
                ->andWhere("NOT EXISTS (SELECT fk_rfq_id FROM notice_of_postponement_items 
                    WHERE notice_of_postponement_items.is_deleted = 0 
                    AND notice_of_postponement_items.fk_rfq_id = pr_rfq.id)");
            if (!Yii::$app->user->can('ro_procurement_admin')) {
                $user_data = User::getUserDetails();
                $query->andWhere('fk_office_id = :fk_office_id', ['fk_office_id' =>  $user_data->employee->office->id]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
