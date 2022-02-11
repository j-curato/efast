<?php

namespace frontend\controllers;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PrRfq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    function insertItems($model_id, $pr_purchase_request_item_id)
    {

        foreach ($pr_purchase_request_item_id as $val) {
            $rfq_item = new PrRfqItem();
            $rfq_item->pr_rfq_id = $model_id;
            $rfq_item->pr_purchase_request_item_id = $val;
            if ($rfq_item->save(false)) {
            } else {
                var_dump($pr_purchase_request_item_id->error);
                return false;
            }
        }
        return true;
    }

    public function actionCreate()
    {


        $model = new PrRfq();

        if ($model->load(Yii::$app->request->post())) {
            $pr_purchase_request_item_id = [];
            $province  = 'RO';
            if (!empty($_POST['pr_purchase_request_item_id'])) {
                $pr_purchase_request_item_id = $_POST['pr_purchase_request_item_id'];
            }
            $pr_items = [];
            $transaction = Yii::$app->db->beginTransaction();
            $pr_date  = Yii::$app->db->createCommand("SELECT `date`  FROM pr_purchase_request  WHERE id = :id")
                ->bindValue(':id', $model->pr_purchase_request_id)
                ->queryOne();
            foreach ($pr_purchase_request_item_id as $val) {
                $pr_items[] = ['id' => $val];
            }
            if (strtotime($model->_date) < strtotime($pr_date['date'])) {

                return $this->render('create', [
                    'model' => $model,
                    'error' => 'RFQ Deadline Should not be less than PR Date',
                    'pr_items' => $pr_items

                ]);
            } else   if (strtotime($model->deadline) < strtotime($pr_date['date'])) {

                return $this->render('create', [
                    'model' => $model,
                    'error' => 'RFQ Date Should not be less than PR Date',
                    'pr_items' => $pr_items

                ]);
            }


            $rbac_id = Yii::$app->db->createCommand("SELECT id FROM 
            bac_composition
            WHERE
            :_date  >= bac_composition.effectivity_date 
            AND 
            :_date<= bac_composition.expiration_date ")
                ->bindValue(':_date', $model->_date)
                ->queryOne();
            if (empty($rbac_id)) {
                return $this->render('create', [
                    'model' => $model,
                    'error' => 'No RBAC Composition For Selected Date',
                    'pr_items' => $pr_items

                ]);
            }
            $model->rbac_composition_id = $rbac_id['id'];

            $model->id  = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->province = $province;
            $model->rfq_number = $this->getRfqNumber($model->_date);

            try {
                if ($flag = $model->save(false)) {

                    $flag  = $this->insertItems($model->id, $pr_purchase_request_item_id);
                } else {
                    return var_dump($model->errors);
                }
                if ($flag) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return "error";
                }
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode($e->getMessage());
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

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            $pr_purchase_request_item_id = [];
            if (!empty($_POST['pr_purchase_request_item_id'])) {
                $pr_purchase_request_item_id = $_POST['pr_purchase_request_item_id'];
            }

            $pr_date  = Yii::$app->db->createCommand("SELECT `date`  FROM pr_purchase_request  WHERE id = :id")
                ->bindValue(':id', $model->pr_purchase_request_id)
                ->queryOne();
            foreach ($pr_purchase_request_item_id as $val) {
                $pr_items[] = ['id' => $val];
            }
            if (strtotime($model->_date) < strtotime($pr_date['date'])) {

                return $this->render('update', [
                    'model' => $model,
                    'error' => 'RFQ Deadline Should not be less than PR Date',

                ]);
            } else   if (strtotime($model->deadline) < strtotime($pr_date['date'])) {

                return $this->render('update', [
                    'model' => $model,
                    'error' => 'RFQ Date Should not be less than PR Date',

                ]);
            }


            $rbac_id = Yii::$app->db->createCommand("SELECT id FROM 
            bac_composition
            WHERE
            :_date  >= bac_composition.effectivity_date 
            AND 
            :_date<= bac_composition.expiration_date ")
                ->bindValue(':_date', $model->_date)
                ->queryOne();
            if (empty($rbac_id)) {
                return $this->render('update', [
                    'model' => $model,
                    'error' => 'No RBAC Composition For Selected Date',
                ]);
            }



            Yii::$app->db->createCommand("DELETE 
             FROM pr_rfq_item WHERE pr_rfq_id = :id")
                ->bindValue(':id', $model->id)
                ->query();

            try {
                if ($flag = $model->save(false)) {

                    $flag  = $this->insertItems($model->id, $pr_purchase_request_item_id);
                } else {
                    return var_dump($model->errors);
                }
                if ($flag) {

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return "error";
                }
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode($e->getMessage());
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
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

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
    public function getRfqNumber($date)
    {
        // RO-2022-01-29-001 
        // $date = '2022-01-05';
        $province = 'ADN';
        $d  = DateTime::createFromFormat('Y-m-d', $date);
        $num  = 1;
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_rfq.rfq_number,'-',-1) AS UNSIGNED)  as last_num FROM pr_rfq WHERE _date=:_date")
            ->bindValue('_date', $date)
            ->queryScalar();
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $zero .= 0;
        }
        return $province . '-' . $date . '-' . $zero . $num;
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

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
}
