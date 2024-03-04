<?php

namespace frontend\controllers;

use app\models\AcicCancelledItems;
use app\models\AcicCashReceiveItems;
use Yii;
use app\models\Acics;
use app\models\AcicsCashItems;
use app\models\AcicsSearch;
use app\models\Books;
use app\models\CashDisbursement;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AcicsController implements the CRUD actions for Acics model.
 */
class AcicsController extends Controller
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

                        ],
                        'allow' => true,
                        'roles' => ['view_acic']
                    ],
                    [
                        'actions' => [

                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_acic']
                    ],
                    [
                        'actions' => [

                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_acic']
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
    private function getAcicInBankId($id)
    {
        return Yii::$app->db->createCommand("SELECT acic_in_bank_items.fk_acic_in_bank_id FROM acics
        JOIN acic_in_bank_items ON acics.id = acic_in_bank_items.fk_acic_id
        WHERE 
        acic_in_bank_items.is_deleted = 0
        AND acics.id = :id
        ")->bindValue(':id', $id)
            ->queryScalar();
    }
    private function getCancelledItemsDetails($id)
    {
        return Yii::$app->db->createCommand("WITH checkTtlAmt as (
        
            SELECT 
            cash_disbursement_items.fk_cash_disbursement_id,
            SUM(dv_aucs_index.ttlAmtDisbursed) * -1 as ttlDisbursed ,
            SUM(COALESCE(dv_aucs_index.ttlTax,0)) as ttlTax
            FROM cash_disbursement_items
            JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
            WHERE 
            cash_disbursement_items.is_deleted = 0
            GROUP BY cash_disbursement_items.fk_cash_disbursement_id
            )
                SELECT 
                acic_cancelled_items.id  as item_id,
                cash_disbursement.id as cash_id,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.reporting_period,
                cash_disbursement.ada_number,
                cash_disbursement.issuance_date,
                books.`name` as book_name,
                mode_of_payments.`name` as mode_name,
                checkTtlAmt.ttlDisbursed,
                checkTtlAmt.ttlTax
                FROM acic_cancelled_items 
                JOIN cash_disbursement ON acic_cancelled_items.fk_cash_disbursement_id = cash_disbursement.id
                LEFT JOIN books ON cash_disbursement.book_id = books.id
                LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
                LEFT JOIN checkTtlAmt ON cash_disbursement.id  = checkTtlAmt.fk_cash_disbursement_id
                WHERE 
                acic_cancelled_items.fk_acic_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function getSerialNum($period, $book_id)
    {
        $book = Books::findOne($book_id);
        $dte = DateTime::createFromFormat('Y-m-d', $period);
        $yr = $dte->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(acics.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM acics  
            WHERE 
            acics.serial_number LIKE :yr
            AND acics.fk_book_id = :book_id
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr', '%' . $yr . '%')
            ->bindValue(':book_id', $book_id . '%')
            ->queryScalar();
        if (empty($qry)) {
            $qry = 1;
        }
        $num = '';
        if (strlen($qry) < 3) {
            $num .= str_repeat(0, 3 - strlen($qry));
        }
        $num .= $qry;
        return $book->name  . '-' . $dte->format('Y-m') . '-' . $num;
    }
    private function getViewCashItems($id)
    {
        return YIi::$app->db->createCommand("SELECT 
        cash_disbursement.check_or_ada_no,
        cash_disbursement.issuance_date,
        dv_aucs_index.payee,
        dv_aucs_index.grossAmt,
        dv_aucs_index.ttlTax,
        dv_aucs_index.ttlAmtDisbursed,
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger
        
        
        FROM 
        acics_cash_items
        JOIN cash_disbursement ON acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id
        JOIN cash_disbursement_items ON cash_disbursement.id  = cash_disbursement_items.fk_cash_disbursement_id
        JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
        LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
        WHERE 
        acics_cash_items.is_deleted = 0
        AND cash_disbursement_items.is_deleted = 0
        AND acics_cash_items.fk_acic_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function getCashRcvItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        acic_cash_receive_items.id as cash_rcv_itm_id,
        acic_cash_receive_items.fk_cash_receive_id,
        vw_cash_received.date,
        vw_cash_received.reporting_period,
        vw_cash_received.valid_from,
        vw_cash_received.valid_to,
        vw_cash_received.purpose,
        acic_cash_receive_items.amount,
        vw_cash_received.document_receive_name,
        vw_cash_received.book_name,
        vw_cash_received.mfo_name,
        vw_cash_received.nca_no,
        vw_cash_received.nta_no,
        vw_cash_received.amount as cash_amt,
        vw_cash_received.balance

        
        FROM acic_cash_receive_items
        JOIN vw_cash_received ON acic_cash_receive_items.fk_cash_receive_id = vw_cash_received.id
        WHERE 
        acic_cash_receive_items.fk_acic_id = :id
        AND  acic_cash_receive_items.is_deleted = 0
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }

    private function insCashItems($model_id, $items, $isUpdate = false)
    {


        try {
            if ($isUpdate === true && !empty(array_column($items, 'item_id'))) {

                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'acics_cash_items.id', $itemIds], $params);
                Yii::$app->db->createCommand("UPDATE acics_cash_items
                JOIN cash_disbursement ON acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id
               
                SET acics_cash_items.is_deleted = 1 
               WHERE 
               acics_cash_items.is_deleted = 0
               AND acics_cash_items.fk_acic_id = :id
               AND NOT EXISTS (SELECT rci_items.fk_cash_disbursement_id FROM rci_items WHERE rci_items.is_deleted = 0 AND rci_items.fk_cash_disbursement_id=cash_disbursement.id)
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }

            foreach ($items as $itm) {

                if (!empty($itm['item_id'])) {
                    $model = AcicsCashItems::findOne($itm['item_id']);
                } else {
                    $model = new AcicsCashItems();
                }
                $model->fk_acic_id = $model_id;
                $model->fk_cash_disbursement_id = $itm['cash_id'];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Cash Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function insCashRcvItems($model_id, $items, $isUpdate = false)
    {

        try {

            if ($isUpdate === true && !empty(array_column($items, 'item_id'))) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                Yii::$app->db->createCommand("UPDATE acic_cash_receive_items SET is_deleted = 1 
                WHERE 
                acic_cash_receive_items.is_deleted = 0
                AND acic_cash_receive_items.fk_acic_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }

            foreach ($items as $itm) {
                $checkBal =  $this->checkCashReceiveBal($itm['csh_rcv_id'], $itm['item_id'] ?? null, $itm['amount']);
                if ($checkBal !== true) {
                    throw new ErrorException($checkBal);
                }
                if (!empty($itm['item_id'])) {
                    $model = AcicCashReceiveItems::findOne($itm['item_id']);
                } else {
                    $model = new AcicCashReceiveItems();
                }

                $model->fk_acic_id = $model_id;
                $model->fk_cash_receive_id = $itm['csh_rcv_id'];
                $model->amount = $itm['amount'];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }

                if (!$model->save(false)) {
                    throw new ErrorException('Cash Receive Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {

            return $e->getMessage();
        }
    }
    private function checkCashReceiveBal($cash_rcv_id, $amt, $acic_item_id = '' )
    {
        $sql = '';
        $params = [];
        if (!empty($acic_item_id)) {
            $sql = ' AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'acic_cash_receive_items.id', $acic_item_id], $params);
        }
        $cashBal = Yii::$app->db->createCommand("SELECT 
            cash_received.amount - COALESCE(ttlInAcic.ttl,0) as balance
                    FROM cash_received
            LEFT JOIN (SELECT 
            acic_cash_receive_items.fk_cash_receive_id,
            SUM(acic_cash_receive_items.amount) as ttl
            FROM acic_cash_receive_items
            WHERE acic_cash_receive_items.is_deleted = 0
            $sql
            GROUP BY
            acic_cash_receive_items.fk_cash_receive_id) as ttlInAcic ON cash_received.id = ttlInAcic.fk_cash_receive_id
            WHERE cash_received.id = :id", $params)
            ->bindValue(':id', $cash_rcv_id)
            ->queryScalar();

        $bal  = floatval($cashBal) - floatval($amt);
        return ($bal < 0) ? "Balance is Only " . number_format($cashBal, 2) : true;
    }
    private function getCashItems($id)
    {
        $qry = Yii::$app->db->createCommand("SELECT 
        acics_cash_items.id as item_id,
        cash_disbursement.id as cash_id,
        cash_disbursement.reporting_period,
        mode_of_payments.`name` as mode_name,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.issuance_date,
        books.`name` as book_name,
        cashTtl.ttlDisbursed,
        cashTtl.ttlTax
         FROM 
        acics_cash_items
        JOIN cash_disbursement ON acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id
        LEFT JOIN  (
        SELECT 
        cash_disbursement_items.fk_cash_disbursement_id,
        SUM(dv_aucs_index.ttlAmtDisbursed) as ttlDisbursed,
        SUM(COALESCE(dv_aucs_index.ttlTax,0)) as ttlTax
        FROM cash_disbursement_items
        JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
        WHERE 
        cash_disbursement_items.is_deleted = 0
        GROUP BY cash_disbursement_items.fk_cash_disbursement_id
        ) as cashTtl ON cash_disbursement.id = cashTtl.fk_cash_disbursement_id
        LEFT JOIN books ON cash_disbursement.book_id = books.id
        LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id  = mode_of_payments.id
        WHERE acics_cash_items.is_deleted = 0 
        AND acics_cash_items.fk_acic_id  = :id")
            ->bindValue(':id', $id)
            ->queryAll();
        return $qry;
    }
    private function insCancelledItems($model_id, $items, $date)
    {
        $reporting_period = DateTime::createFromFormat('Y-m-d', $date)->format('Y-m');


        try {
            foreach ($items as $itm) {
                $cash_id = $itm['cash_id'];
                $chkIfCanceled = Yii::$app->db->createCommand("SELECT EXISTS(
                    SELECT cash_disbursement.id
                    FROM cash_disbursement
                    WHERE `parent_disbursement` =  :parent_id  AND is_cancelled = 1)")
                    ->bindValue(':parent_id', $cash_id)
                    ->queryScalar();
                if (intval($chkIfCanceled) === 1) {
                    throw new ErrorException('Check Already Cancelled');
                }
                $new_id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                Yii::$app->db->createCommand("INSERT INTO cash_disbursement
                (
                   id,
                   book_id,
                   dv_aucs_id,
                   reporting_period,
                   mode_of_payment,
                   check_or_ada_no,
                   is_cancelled,
                   issuance_date,
                   ada_number,
                   begin_time,
                   out_time,
                   parent_disbursement,
                   fk_mode_of_payment_id,
                   fk_ro_check_range_id
               )
                SELECT
                :new_id,
                book_id,
                dv_aucs_id,
                :reporting_period,
                mode_of_payment,
                check_or_ada_no,
                1 as is_cancelled,
                issuance_date,
                ada_number,
                begin_time,
                out_time,
                id,
                fk_mode_of_payment_id,
                fk_ro_check_range_id
                FROM cash_disbursement
                WHERE id  = :cash_id")
                    ->bindValue(':cash_id', $cash_id)
                    ->bindValue(':reporting_period', $reporting_period)
                    ->bindValue(':new_id', $new_id)
                    ->execute();
                Yii::$app->db->createCommand("INSERT INTO cash_disbursement_items (fk_cash_disbursement_id,
                    fk_chart_of_account_id,
                    fk_dv_aucs_id
                    )
                    SELECT 
                    :new_id,
                        cash_disbursement_items.fk_chart_of_account_id,
                        cash_disbursement_items.fk_dv_aucs_id
                        FROM cash_disbursement_items
                        WHERE 
                        cash_disbursement_items.fk_cash_disbursement_id = :id
                        AND cash_disbursement_items.is_deleted = 0")
                    ->bindValue(':id', $cash_id)
                    ->bindValue(':new_id', $new_id)
                    ->execute();
                $model = new AcicCancelledItems();
                $model->fk_acic_id = $model_id;
                $model->fk_cash_disbursement_id = $new_id;
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Cancelled Model Save Failed');
                }
            }



            // $insSliie = $this->createSliie($new_model->id, DateTime::createFromFormat('Y-m-d', $new_model->issuance_date)->format('Y-m'));
            // if ($insSliie !== true) {
            //     throw new ErrorException($insSliie);
            // }
            // $insLddapAda = $this->createLddapAda($new_model->id, DateTime::createFromFormat('Y-m-d', $new_model->issuance_date)->format('Y-m'));
            // if ($insLddapAda !== true) {
            //     throw new ErrorException($insLddapAda);
            // }
            return true;
        } catch (ErrorException $e) {
            return json_encode(['isSuccess' => false, 'cancelled' => 'cancel', 'error' => $e->getMessage()]);
        }
    }
    private function getCashDisbursementTttl($items)
    {
        $params = [];
        $sql = ' AND ';
        $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'cash_disbursement.id', $items], $params);
        return Yii::$app->db->createCommand("  SELECT 
        SUM(dv_aucs_index.ttlAmtDisbursed) as ttl
         FROM cash_disbursement
        JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
        JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
        WHERE cash_disbursement_items.is_deleted = 0
        $sql", $params)
            ->queryScalar() ?? 0;
    }
    private function checkItemsIfBalance($uniqueCashItems, $casRcvItems, $cancellItems)
    {
        $cashRcvAmtsTtl = array_sum(array_column($casRcvItems, 'amount'));
        $cash_ids = array_column($uniqueCashItems, 'cash_id');
        $cancelled_ids = array_column($cancellItems, 'cash_id');
        $cashItemsTtl = $this->getCashDisbursementTttl($cash_ids);
        $cnclItemsTtl = $this->getCashDisbursementTttl($cancelled_ids) * -1;
        // echo number_format(floatval($cashItemsTtl) + floatval($cnclItemsTtl), 2);

        return number_format($cashRcvAmtsTtl, 2) !== number_format(floatval($cashItemsTtl) + floatval($cnclItemsTtl), 2) ? 'Cash disbursements and cash receipts totals must balance' : true;
    }
    /**
     * Lists all Acics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AcicsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Acics model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'cashItems' => $this->getViewCashItems($id),
            'cancelledItems' => $this->getCancelledItemsDetails($id),
            'acicInBankId' => $this->getAcicInBankId($id)
        ]);
    }

    /**
     * Creates a new Acics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Acics();

        if ($model->load(Yii::$app->request->post())) {
            $cashItems = Yii::$app->request->post('cashItems') ?? [];
            $uniqueCashItems = array_map("unserialize", array_unique(array_map("serialize", $cashItems)));
            $cashRcvItms =  Yii::$app->request->post('cshRcvItems') ?? [];
            $cancellItems =  Yii::$app->request->post('cancelItems') ?? [];
            $uniqueCancelledItems = array_map("unserialize", array_unique(array_map("serialize", $cancellItems)));

            try {
                $txn  = Yii::$app->db->beginTransaction();
                $model->id  = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $chkItm = $this->checkItemsIfBalance($uniqueCashItems, $cashRcvItms, $uniqueCancelledItems);
                if ($chkItm !== true) {
                    throw new ErrorException($chkItm);
                }
                if (empty($cashRcvItms)) {
                    throw new ErrorException('Cash Receive is Required');
                }
                $model->serial_number = $this->getSerialNum($model->date_issued, $model->fk_book_id);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insCashItms = $this->insCashItems($model->id, $uniqueCashItems);

                if ($insCashItms !== true) {
                    throw new ErrorException($insCashItms);
                }

                $insCashRcvItms = $this->insCashRcvItems($model->id, $cashRcvItms);

                if ($insCashRcvItms !== true) {

                    throw new ErrorException($insCashRcvItms);
                }
                $inCnclItms = $this->insCancelledItems($model->id, $uniqueCancelledItems, $model->date_issued);
                if ($inCnclItms !== true) {
                    throw new ErrorException($inCnclItms);
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
     * Updates an existing Acics model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $cashItems = Yii::$app->request->post('cashItems') ?? [];
            $uniqueCashItems = array_map("unserialize", array_unique(array_map("serialize", $cashItems)));
            $cashRcvItms =  Yii::$app->request->post('cshRcvItems') ?? [];
            $cancellItems =  Yii::$app->request->post('cancelItems') ?? [];
            $uniqueCancelledItems = array_map("unserialize", array_unique(array_map("serialize", $cancellItems)));
            try {
                $txn  = Yii::$app->db->beginTransaction();
                if (intval($oldModel->fk_book_id) !== intval($model->fk_book_id)) {
                    $model->serial_number = $this->getSerialNum($model->date_issued, $model->fk_book_id);
                }
                $chkItm = $this->checkItemsIfBalance($uniqueCashItems, $cashRcvItms, $uniqueCancelledItems);
                if ($chkItm !== true) {
                    throw new ErrorException($chkItm);
                }
                if (empty($cashRcvItms)) {
                    throw new ErrorException('Cash Receive is Required');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }

                $insCashItms = $this->insCashItems($model->id, $uniqueCashItems, true);

                if ($insCashItms !== true) {
                    throw new ErrorException($insCashItms);
                }

                $insCashRcvItms = $this->insCashRcvItems($model->id, $cashRcvItms, true);

                if ($insCashRcvItms !== true) {

                    throw new ErrorException($insCashRcvItms);
                }
                $inCnclItms = $this->insCancelledItems($model->id, $uniqueCancelledItems, $model->date_issued);
                if ($inCnclItms !== true) {
                    throw new ErrorException($inCnclItms);
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
            'cashItems' => $this->getCashItems($model->id),
            'cashRcvItems' => $this->getCashRcvItems($model->id),
            'cancelledItems' => $this->getCancelledItemsDetails($id)

        ]);
    }

    /**
     * Deletes an existing Acics model.
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
     * Finds the Acics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Acics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Acics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
