<?php

namespace frontend\controllers;

use Yii;
use app\models\Radai;
use app\models\RadaiItems;
use app\models\RadaiSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RadaiController implements the CRUD actions for Radai model.
 */
class RadaiController extends Controller
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
                    'delete',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'delete',
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
    private function getSerialNum($period)
    {
        $dte = DateTime::createFromFormat('Y-m-d', $period);
        $yr = $dte->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(radai.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM radai  
            WHERE 
            radai.serial_number LIKE :yr
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr', $yr . '%')
            ->queryScalar();
        if (empty($qry)) {
            $qry = 1;
        }
        $num = '';
        if (strlen($qry) < 3) {
            $num .= str_repeat(0, 3 - strlen($qry));
        }
        $num .= $qry;
        return $dte->format('Y-m') . '-' . $num;
    }
    private function insertItems($model_id, $items, $isUpdate = false)
    {
        try {
            if ($isUpdate === true && !empty(array_column($items, 'item_id'))) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                echo Yii::$app->db->createCommand("UPDATE radai_items SET is_deleted = 1 
                    WHERE 
                    radai_items.is_deleted = 0
                    AND radai_items.fk_radai_id = :id
                    $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {
                if (!empty($itm['item_id'])) {
                    $itmModel = RadaiItems::findOne($itm['item_id']);
                } else {
                    $itmModel = new RadaiItems();
                }
                $itmModel->fk_radai_id = $model_id;
                $itmModel->fk_lddap_ada_id = $itm['lddap_ada_id'];
                if (!$itmModel->validate()) {
                    throw new ErrorException(json_encode($itmModel->errors));
                }
                if (!$itmModel->save(false)) {
                    throw new ErrorException('itemModel Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function getItemsPerDv($id)
    {
        return Yii::$app->db->createCommand("SELECT
        lddap_adas.serial_number as lddap_ada_number,
       cash_disbursement.check_or_ada_no,
       cash_disbursement.ada_number,
       dv_aucs_index.ttlAmtDisbursed,
       dv_aucs_index.ttlTax,
       dv_aucs_index.payee,
       dv_aucs_index.orsNums,
       dv_aucs_index.dv_number,
       mode_of_payments.`name` as mode_of_payment_name,
       CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as uacs,
       cash_disbursement.issuance_date as check_date
       
        FROM radai_items
       JOIN lddap_adas ON radai_items.fk_lddap_ada_id   = lddap_adas.id
       JOIN cash_disbursement ON lddap_adas.fk_cash_disbursement_id = cash_disbursement.id
       JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
       JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
       LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
       LEFT JOIN chart_of_accounts  ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
       WHERE 
       radai_items.is_deleted = 0
       AND cash_disbursement_items.is_deleted = 0
       AND radai_items.fk_radai_id = :id
       ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function getItems($id)
    {
        return Yii::$app->db->createCommand("SELECT
               radai_items.id as item_id,
                lddap_adas.id as lddap_ada_id,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.issuance_date,
                lddap_adas.serial_number as lddap_no,
                mode_of_payments.`name` as mode_of_payment_name,
                acics.serial_number as acic_no
                FROM 
                radai_items
                JOIN lddap_adas ON radai_items.fk_lddap_ada_id = lddap_adas.id
                JOIN cash_disbursement ON lddap_adas.fk_cash_disbursement_id = cash_disbursement.id
                JOIN acics_cash_items ON cash_disbursement.id = acics_cash_items.fk_cash_disbursement_id
                JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
                JOIN acics ON acics_cash_items.fk_acic_id = acics.id
                WHERE radai_items.fk_radai_id = :id
                AND radai_items.is_deleted = 0
        ")->bindValue(':id', $id)
            ->queryAll();
    }
    /**
     * Lists all Radai models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RadaiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Radai model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->getItemsPerDv($id)
        ]);
    }

    /**
     * Creates a new Radai model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Radai();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items');
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                $model->id = Yii::$app->db->createCommand('SELECT UUID_SHORT()')->queryScalar();
                $model->serial_number = $this->getSerialNum($model->date);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItems = $this->insertItems($model->id, $uniqueItems);
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Radai model.
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
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items');
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItems = $this->insertItems($model->id, $uniqueItems, true);
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Deletes an existing Radai model.
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
     * Finds the Radai model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Radai the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Radai::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
