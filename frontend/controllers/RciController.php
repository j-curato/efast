<?php

namespace frontend\controllers;

use Yii;
use app\models\Rci;
use app\models\RciItems;
use app\models\RciSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RciController implements the CRUD actions for Rci model.
 */
class RciController extends Controller
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
                    'update',
                    'create',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete',
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
    private function getItemsPerCheck($id)
    {
        return Yii::$app->db->createCommand("WITH 
     checkTtlAmt as (
     
     SELECT 
     cash_disbursement_items.fk_cash_disbursement_id,
     SUM(dv_aucs_index.ttlAmtDisbursed) as ttlDisbursed,
     SUM(COALESCE(dv_aucs_index.ttlTax,0)) as ttlTax
     FROM cash_disbursement_items
     JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
     WHERE 
     cash_disbursement_items.is_deleted = 0
     GROUP BY cash_disbursement_items.fk_cash_disbursement_id
     )
     SELECT 
     rci_items.id as item_id,
     cash_disbursement.id cash_id,
     cash_disbursement.check_or_ada_no,
     cash_disbursement.ada_number,
     cash_disbursement.issuance_date,
     books.`name` as book_name,
     cash_disbursement.reporting_period,
     mode_of_payments.`name` as mode_name,
     checkTtlAmt.ttlDisbursed,
     checkTtlAmt.ttlTax,
     cash_disbursement.reporting_period

     
     FROM 
rci_items
JOIN cash_disbursement ON rci_items.fk_cash_disbursement_id = cash_disbursement.id
     LEFT JOIN books ON cash_disbursement.book_id = books.id
     LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
     LEFT JOIN checkTtlAmt ON cash_disbursement.id  = checkTtlAmt.fk_cash_disbursement_id
WHERE 
rci_items.fk_rci_id= :id
")->bindValue(':id', $id)
            ->queryAll();
    }
    private  function getItems($model_id)
    {
        return Yii::$app->db->createCommand("SELECT
                rci_items.id,
                cash_disbursement.reporting_period,
                cash_disbursement_items.id as cash_item_id,
               cash_disbursement.check_or_ada_no,
               cash_disbursement.ada_number,
               cash_disbursement.issuance_date,
               dv_aucs_index.dv_number,
               dv_aucs_index.orsNums,
               dv_aucs_index.payee,
               dv_aucs_index.grossAmt,
               dv_aucs_index.ttlAmtDisbursed,
               dv_aucs_index.particular,
               mode_of_payments.`name` as mode_name,
               CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as uacs,
       acics.serial_number as acic_no
        FROM `rci_items`
       JOIN cash_disbursement ON rci_items.fk_cash_disbursement_id = cash_disbursement.id
       JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
       JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
       LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
       LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
       LEFT JOIN acics_cash_items ON cash_disbursement.id = acics_cash_items.fk_cash_disbursement_id
       LEFT JOIN acics ON acics_cash_items.fk_acic_id = acics.id
       
        WHERE 
        rci_items.fk_rci_id = :id
        AND rci_items.is_deleted = 0
        ")
            ->bindValue(':id', $model_id)
            ->queryAll();
    }
    private function insItems($model_id, $items, $isUpdate = false)
    {
        try {
            if ($isUpdate) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                echo Yii::$app->db->createCommand("UPDATE rci_items SET is_deleted = 1 
                WHERE 
                rci_items.is_deleted = 0
                AND rci_items.fk_rci_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }

            foreach ($items as $itm) {
                if (empty($itm['item_id'])) {

                    $model = new RciItems();
                    $model->fk_rci_id = $model_id;
                    $model->fk_cash_disbursement_id = $itm['cash_item_id'];
                    if (!$model->validate()) {
                        throw new ErrorException(json_encode($model->errors));
                    }
                    if (!$model->save(false)) {
                        throw new ErrorException('Item Model Save Failed');
                    }
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function getSerialNum($period)
    {
        $yr = DateTime::createFromFormat('Y-m', $period)->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(rci.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM rci  
            WHERE 
            rci.serial_number LIKE :yr
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr', $yr . '%')
            ->queryScalar();
        if (empty($qry)) {
            $qry = 1;
        }
        $num = '';
        if (strlen($qry) < 5) {
            $num .= str_repeat(0, 5 - strlen($qry));
        }
        $num .= $qry;
        return $period . '-' . $num;
    }
    /**
     * Lists all Rci models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RciSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rci model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->getItems($id),

        ]);
    }

    /**
     * Creates a new Rci model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rci();

        if ($model->load(Yii::$app->request->post())) {

            try {
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                $uniqItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                $model->id = Yii::$app->db->createCommand('SELECT UUID_SHORT()')->queryScalar();
                $model->serial_number = $this->getSerialNum($model->reporting_period);
                if (empty($items)) {
                    throw new ErrorException('Items Cannot be Empty');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItems = $this->insItems($model->id, $uniqItems);
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Rci model.
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
                $txn = YIi::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                $uniqItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                if (empty($items)) {
                    throw new ErrorException('Items Cannot be Empty');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItems = $this->insItems($model->id, $uniqItems, true);
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->getItemsPerCheck($id),
        ]);
    }

    /**
     * Deletes an existing Rci model.
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
     * Finds the Rci model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rci the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rci::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
