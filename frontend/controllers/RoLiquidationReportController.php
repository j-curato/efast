<?php

namespace frontend\controllers;

use app\models\DvForLiquidationReport;
use app\models\DvForLiquidationReportSearch;
use Yii;
use app\models\RoLiquidationReport;
use app\models\RoLiquidationReportItems;
use app\models\RoLiquidationReportRefunds;
use app\models\RoLiquidationReportSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoLiquidationReportController implements the CRUD actions for RoLiquidationReport model.
 */
class RoLiquidationReportController extends Controller
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
                    'update',
                    'create',
                    'delete',
                    'index',
                    'view',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'create',
                            'delete',
                            'index',
                            'view',
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

    /**
     * Lists all RoLiquidationReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoLiquidationReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RoLiquidationReport model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->itemsQuery($id),
            'refund_items' => $this->refundItemsQuery($id)
        ]);
    }

    public function insertLiquidationReportItems($id, $entries = [], $amount = [], $object_code = [], $reporting_period = [], $items_id = [], $type = '')
    {
        foreach ($entries as $i => $val) {

            if (!empty($items_id[$i])) {
                $items = RoLiquidationReportItems::findOne($items_id[$i]);
            } else {
                $items = new RoLiquidationReportItems();
                $items->fk_ro_liquidation_report_id = $id;
            }
            $items->fk_cash_disbursement_id = $val;
            $items->amount = !empty($amount[$i]) ? $amount[$i] : 0;
            $items->object_code = $object_code[$i];
            if ($type === 'create') {
                $items->reporting_period = $reporting_period;
            } else if ($type = 'update') {
                $items->reporting_period = $reporting_period[$i];
            }

            if ($items->save(false)) {
            }
        }
    }
    public function insertLiquidationReportRefunds($id, $entries = [], $amount = [], $or_number = [], $reporting_period = [], $refund_id = [], $or_dates = [], $type = '')
    {

        foreach ($entries as $i => $val) {
            $refund_items = new RoLiquidationReportRefunds();

            if (!empty($refund_id[$i])) {
                $refund_items = RoLiquidationReportRefunds::findOne($refund_id[$i]);
            } else {
                $refund_items = new RoLiquidationReportRefunds();
                $refund_items->fk_ro_liquidation_report_id = $id;
            }
            $refund_items->fk_cash_disbursement_id = $val;
            $refund_items->amount = !empty($amount[$i]) ? $amount[$i] : 0;
            $refund_items->or_number = $or_number[$i];
            $refund_items->or_date = $or_dates[$i];
            if ($type === 'create') {
                $refund_items->reporting_period = $reporting_period;
            } else if ($type === 'update') {
                $refund_items->reporting_period = $reporting_period[$i];
            }

            if ($refund_items->save(false)) {
            }
        }
    }
    /**
     * Creates a new RoLiquidationReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RoLiquidationReport();

        $searchModel = new DvForLiquidationReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $refund_cash_id = !empty($_POST['refund_cash_id']) ? $_POST['refund_cash_id'] : [];
            $refund_reporting_period = !empty($_POST['refund_reporting_period']) ? $_POST['refund_reporting_period'] : [];
            $refund_or_date = !empty($_POST['refund_or_date']) ? $_POST['refund_or_date'] : [];
            $refund_or_number = !empty($_POST['refund_or_number']) ? $_POST['refund_or_number'] : [];
            $refund_amount = !empty($_POST['refund_amount']) ? $_POST['refund_amount'] : [];


            $entry_cash_id = !empty($_POST['entry_cash_id']) ? $_POST['entry_cash_id'] : [];
            $entry_amount = !empty($_POST['entry_amount']) ? $_POST['entry_amount'] : [];
            $entry_reporting_period = !empty($_POST['entry_reporting_period']) ? $_POST['entry_reporting_period'] : [];
            $entry_object_code = !empty($_POST['entry_object_code']) ? $_POST['entry_object_code'] : [];



            $model->liquidation_report_number = $this->lrNumber($model->reporting_period);
            if ($model->save(false)) {

                if (!empty($entry_cash_id)) {

                    $this->insertLiquidationReportItems(
                        $model->id,
                        $entry_cash_id,
                        $entry_amount,
                        $entry_object_code,
                        $model->reporting_period,
                        [],
                        'create'
                    );
                }
                if (!empty($refund_cash_id)) {
                    $this->insertLiquidationReportRefunds(
                        $model->id,
                        $refund_cash_id,
                        $refund_amount,
                        $refund_or_number,
                        $model->reporting_period,
                        [],
                        $refund_or_date,
                        'create'
                    );
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'entries' => [],
            'refund_items' =>[]
        ]);
    }

    /**
     * Updates an existing RoLiquidationReport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new DvForLiquidationReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($model->load(Yii::$app->request->post())) {

            $item_ids = !empty($_POST['item_ids']) ? $_POST['item_ids'] : [];
            $refund_ids = !empty($_POST['refund_ids']) ? $_POST['refund_ids'] : [];

            $refund_cash_id = !empty($_POST['refund_cash_id']) ? $_POST['refund_cash_id'] : [];
            $refund_reporting_period = !empty($_POST['refund_reporting_period']) ? $_POST['refund_reporting_period'] : [];
            $refund_or_date = !empty($_POST['refund_or_date']) ? $_POST['refund_or_date'] : [];
            $refund_or_number = !empty($_POST['refund_or_number']) ? $_POST['refund_or_number'] : [];
            $refund_amount = !empty($_POST['refund_amount']) ? $_POST['refund_amount'] : [];


            $entry_cash_id = !empty($_POST['entry_cash_id']) ? $_POST['entry_cash_id'] : [];
            $item_ids = !empty($_POST['item_ids']) ? $_POST['item_ids'] : [];
            $entry_amount = !empty($_POST['entry_amount']) ? $_POST['entry_amount'] : [];
            $entry_reporting_period = !empty($_POST['entry_reporting_period']) ? $_POST['entry_reporting_period'] : [];
            $entry_object_code = !empty($_POST['entry_object_code']) ? $_POST['entry_object_code'] : [];

            $params = [];
            $sql = '';
            if (!empty($item_ids)) {
                $sql = 'AND' . Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'ro_liquidation_report_items.id', $item_ids], $params);
            }

            $query = Yii::$app->db->createCommand("UPDATE ro_liquidation_report_items SET is_deleted = 1, deleted_at = now() WHERE
             fk_ro_liquidation_report_id =:id
            $sql
            ", $params)
                ->bindValue(':id', $model->id)->execute();
            $params = [];
            $sql2 = '';
            if (!empty($refund_ids)) {
                $sql2 = 'AND' . Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'ro_liquidation_report_refunds.id', $refund_ids], $params);
            }

            $query = Yii::$app->db->createCommand("UPDATE ro_liquidation_report_refunds SET is_deleted = 1, deleted_at = now() WHERE
             fk_ro_liquidation_report_id =:id
            $sql2
            ", $params)
                ->bindValue(':id', $model->id)->execute();



            if ($model->save(false)) {

                if (!empty($entry_cash_id)) {

                    $this->insertLiquidationReportItems(
                        $model->id,
                        $entry_cash_id,
                        $entry_amount,
                        $entry_object_code,
                        $entry_reporting_period,
                        $item_ids,
                        'update'
                    );
                }
                if (!empty($refund_cash_id)) {
                    $this->insertLiquidationReportRefunds(
                        $model->id,
                        $refund_cash_id,
                        $refund_amount,
                        $refund_or_number,
                        $refund_reporting_period,
                        $refund_ids,
                        $refund_or_date,
                        'update'
                    );
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'entries' => $this->itemsQuery($id),
            'refund_items' => $this->refundItemsQuery($id)
        ]);
    }

    /**
     * Deletes an existing RoLiquidationReport model.
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
     * Finds the RoLiquidationReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoLiquidationReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RoLiquidationReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function lrNumber($reporting_period)
    {

        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(liquidation_report_number,'-',-1) AS UNSIGNED) as last_num FROM ro_liquidation_report
        ORDER BY last_num DESC LIMIT 1
        ")->queryScalar();
        if (!empty($last_num)) {
            $last_num  = intval($last_num) + 1;
        } else {
            $last_num = 1;
        }
        $zero = '';
        for ($i  = strlen($last_num); $i < 4; $i++) {
            $zero .= 0;
        }

        return "$reporting_period-" . $zero . $last_num;
    }
    public function itemsQuery($id)
    {

        $entries = YIi::$app->db->createCommand("SELECT
        ro_liquidation_report_items.id,
        ro_liquidation_report_items.fk_cash_disbursement_id,
        ro_liquidation_report_items.amount,
        ro_liquidation_report_items.object_code,
        accounting_codes.account_title,
        ro_liquidation_report_items.reporting_period,
        payee.account_name as payee,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        dv_aucs.particular,
        cash_disbursement.issuance_date,
        cash_disbursement.id as cash_id,
        total_dv_amount.total_disbursed
        FROM 
        `ro_liquidation_report_items`
        LEFT JOIN cash_disbursement ON ro_liquidation_report_items.fk_cash_disbursement_id = cash_disbursement.id
        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        LEFT JOIN accounting_codes ON ro_liquidation_report_items.object_code = accounting_codes.object_code
        LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,
                dv_aucs_entries.dv_aucs_id 
                FROM dv_aucs_entries GROUP BY 
                dv_aucs_entries.dv_aucs_id
                ) as total_dv_amount ON dv_aucs.id = total_dv_amount.dv_aucs_id
        WHERE ro_liquidation_report_items.fk_ro_liquidation_report_id = :id
        AND ro_liquidation_report_items.is_deleted !=1
        ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $entries;
    }
    public function refundItemsQuery($id)
    {
        $refund_items = YIi::$app->db->createCommand("SELECT
                ro_liquidation_report_refunds.id,
                ro_liquidation_report_refunds.fk_cash_disbursement_id,
                ro_liquidation_report_refunds.amount,
                ro_liquidation_report_refunds.reporting_period,

                ro_liquidation_report_refunds.or_number,
                ro_liquidation_report_refunds.or_date,
                payee.account_name as payee,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.ada_number,
                dv_aucs.particular,
                cash_disbursement.issuance_date,
                cash_disbursement.id as cash_id,
                total_dv_amount.total_disbursed
                FROM 
                `ro_liquidation_report_refunds`
                LEFT JOIN cash_disbursement ON ro_liquidation_report_refunds.fk_cash_disbursement_id = cash_disbursement.id
                LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                LEFT JOIN payee ON dv_aucs.payee_id = payee.id
                LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,
                        dv_aucs_entries.dv_aucs_id 
                        FROM dv_aucs_entries GROUP BY 
                        dv_aucs_entries.dv_aucs_id
                        ) as total_dv_amount ON dv_aucs.id = total_dv_amount.dv_aucs_id
                WHERE ro_liquidation_report_refunds.fk_ro_liquidation_report_id = :id
                AND ro_liquidation_report_refunds.is_deleted !=1
                ")
            ->bindValue(':id', $id)
            ->queryAll();
        return $refund_items;
    }
}
