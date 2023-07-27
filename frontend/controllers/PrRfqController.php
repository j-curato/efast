<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\Office;
use Yii;
use app\models\PrRfq;
use app\models\PrRfqItem;
use app\models\PrRfqSearch;

use DateTime;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



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
                        ],
                        'allow' => true,
                        'roles' => ['rfq', 'super-user']
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
        $rbac =  MyHelper::getRbac();
        // return json_encode($rbac);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'rbac' => $rbac
        ]);
    }

    /**
     * Creates a new PrRfq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    private function insertItems($model_id, $items)
    {

        foreach ($items as $itm) {

            // CHECK IF THE PURCHASE REQUEST ID ALREADY EXISTS IN PR_RFQ_ITEM_TABLE WITH THE SAME RFQ_ID
            $check = Yii::$app->db->createCommand("SELECT EXISTS(SELECT 1 FROM pr_rfq_item WHERE pr_rfq_id = :rfq_id AND pr_purchase_request_item_id  = :pr_item_id)")
                ->bindValue(':rfq_id', $model_id)
                ->bindValue(':pr_item_id', $itm['pr_id'])
                ->queryScalar();
            if ($check != 1) {
                $rfq_item = new PrRfqItem();
                $rfq_item->id = MyHelper::getUuid();
                $rfq_item->pr_rfq_id = $model_id;
                $rfq_item->pr_purchase_request_item_id = $itm['pr_id'];
                if (!$rfq_item->validate()) {
                    return $rfq_item->errors;
                }
                if (!$rfq_item->save(false)) {
                    return 'RFQ Item save failed';
                }
            }
        }
        return true;
    }

    public function actionCreate()
    {

        $model = new PrRfq();
        $model->fk_office_id  = Yii::$app->user->identity->fk_office_id ?? '';
        if ($model->load(Yii::$app->request->post())) {
            $items = Yii::$app->request->post('items');

            try {
                $transaction = Yii::$app->db->beginTransaction();
                $province  = 'RO';
                $pr_date  = Yii::$app->db->createCommand("SELECT `date`  FROM pr_purchase_request  WHERE id = :id")
                    ->bindValue(':id', $model->pr_purchase_request_id)
                    ->queryOne();
                if (
                    strtotime($model->_date) < strtotime($pr_date['date'])
                    || strtotime($model->deadline) < strtotime($pr_date['date'])
                ) {
                    throw new ErrorException('RFQ and Deadline date should not be before the PR Date.');
                }
                if (strtotime($model->deadline) < strtotime($model->_date)) {
                    throw new ErrorException('Deadline  must be greater than the RFQ date.');
                }
                // $rbac_id = Yii::$app->db->createCommand("SELECT id FROM bac_composition WHERE :_date  >= bac_composition.effectivity_date AND :_date<= bac_composition.expiration_date ")
                //     ->bindValue(':_date', $model->_date)
                //     ->queryOne();
                // if (empty($rbac_id)) {
                //     throw new ErrorException('No RBAC for selected Date');
                // }
                $model->id  = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                // $model->bac_composition_id = $rbac_id['id'];
                $model->province = $province;
                $model->rfq_number = $this->getRfqNumber($model->_date, $model->fk_office_id);

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                if (!$ins = $this->insertItems($model->id, $items)) {
                    throw new ErrorException($ins);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['errors' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
            $items = Yii::$app->request->post('items');

            try {
                $transaction = Yii::$app->db->beginTransaction();
                $province  = 'RO';
                $pr_date  = Yii::$app->db->createCommand("SELECT `date`  FROM pr_purchase_request  WHERE id = :id")
                    ->bindValue(':id', $model->pr_purchase_request_id)
                    ->queryOne();

                if (
                    strtotime($model->_date) < strtotime($pr_date['date'])
                    || strtotime($model->deadline) < strtotime($pr_date['date'])
                ) {
                    throw new ErrorException('RFQ and Deadline date should not be before the PR Date.');
                }

                // $rbac_id = Yii::$app->db->createCommand("SELECT id FROM bac_composition WHERE :_date  >= bac_composition.effectivity_date AND :_date<= bac_composition.expiration_date ")
                //     ->bindValue(':_date', $model->_date)
                //     ->queryOne();
                // if (empty($rbac_id)) {
                //     throw new ErrorException('No RBAC for selected Date');
                // }
                if (!$oldmodel->_date != $model->_date) {
                    $model->rfq_number = $this->getRfqNumber($model->_date, $model->fk_office_id);
                }
                // $model->bac_composition_id = $rbac_id['id'];
                $model->province = $province;


                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                if (!$ins = $this->insertItems($model->id, $items)) {
                    throw new ErrorException($ins);
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode(['errors' => $e->getMessage()]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
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
            ->bindValue('_date', '%' . $date . '%')
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

            $query->select([" id, `rfq_number` as text"])
                ->from('pr_rfq')
                ->where(['like', 'rfq_number', $q]);
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $query->andWhere('fk_office_id = :fk_office_id', ['fk_office_id' =>  $user_data->office->id]);
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
}
