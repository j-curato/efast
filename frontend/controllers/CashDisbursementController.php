<?php

namespace frontend\controllers;

use app\models\CancelledDisbursements;
use app\models\CancelledDisbursementsSearch;
use Yii;
use app\models\CashDisbursement;
use app\models\CashDisbursementItems;
use app\models\CashDisbursementSearch;
use app\models\DvAccountingEntries;
use app\models\DvAucs;
use app\models\DvAucsEntries;
use app\models\DvAucsIndexSearch;
use app\models\LddapAdas;
use app\models\Sliies;
use app\models\VwGoodCashDisbursementsSearch;
use app\models\VwUndisbursedDvsSearch;
use DateTime;
use Error;
use ErrorException;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CashDisbursementController implements the CRUD actions for CashDisbursement model.
 */
class CashDisbursementController extends Controller
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
                    'create',
                    'update',
                    'delete',
                    'view',
                    'insert-cash-disbursement',
                    'import',
                    'get-all-dv',
                    'get-cash-disbursement',
                    'cancel',
                    'cancel-disbursement',
                    'cancel-disbursement-index',
                    'search-dv',
                    'dv-details',
                    'get-dv',
                    'cash-chart-of-accounts'

                ],

                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'view',
                            'insert-cash-disbursement',
                            'import',
                            'get-all-dv',
                            'get-cash-disbursement',
                            'cancel',
                            'cancel-disbursement',
                            'cancel-disbursement-index',
                            'search-dv',
                            'dv-details',
                            'get-dv',
                            'cash-chart-of-accounts'
                        ],
                        'allow' => true,
                        'roles' => ['cash_disbursement']
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
    private function getDvDetails($id)
    {
        $query = Yii::$app->db->createCommand("SELECT 

        dv_aucs.dv_number,
        dv_aucs.particular,
        payee.account_name as payee,
        disburse.ttlDisburse,
        cash.cash_id
        FROM dv_aucs
        LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,
        SUM(dv_aucs_entries.amount_disbursed) as ttlDisburse 
        FROM dv_aucs_entries
         WHERE dv_aucs_entries.is_deleted = 0
        GROUP BY dv_aucs_entries.dv_aucs_id
        ) as 
        disburse ON dv_aucs.id = disburse.dv_aucs_id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        LEFT JOIN (SELECT cash_disbursement.dv_aucs_id,cash_disbursement.id as cash_id FROM cash_disbursement WHERE cash_disbursement.is_cancelled = 0) cash
        ON dv_aucs.id = cash.dv_aucs_id
        
        WHERE 
        dv_aucs.id = :id")
            ->bindValue(':id', $id)
            ->queryOne();
        return $query;
    }
    private function getCheckNumber($fk_ro_check_range_id)
    {
        $qry = Yii::$app->db->createCommand("SELECT ro_check_ranges.to,ro_check_ranges.from FROM ro_check_ranges WHERE id =:id")
            ->bindValue(':id', $fk_ro_check_range_id)
            ->queryOne();
        if (intval($qry['from']) === 0 && intval($qry['to'] == 0)) {
            return 0;
        }
        $checks  = [];
        $x = 0;
        for ($i = $qry['from']; $i <= $qry['to']; $i++) {
            $checks[':qp' . $x][] = $i;
            $x++;
        }


        Yii::$app->db->createCommand("DROP TABLE IF EXISTS tmp_tbl_checks;
        CREATE TABLE tmp_tbl_checks (check_num BIGINT)")
            ->execute();
        Yii::$app->db->createCommand()->batchInsert('tmp_tbl_checks', ['check_num'], $checks)->execute();
        $model_check_num = Yii::$app->db->createCommand("SELECT tmp_tbl_checks.check_num FROM tmp_tbl_checks
        LEFT JOIN cash_disbursement ON tmp_tbl_checks.check_num = cash_disbursement.check_or_ada_no
        WHERE cash_disbursement.id IS NULL ORDER BY tmp_tbl_checks.check_num LIMIT 1")
            ->queryScalar();

        return $model_check_num;
    }
    private function getItems($model_id)
    {
        $qry = Yii::$app->db->createCommand("SELECT
        cash_disbursement_items.id as itemId,
         cash_disbursement_items.fk_chart_of_account_id,
        cash_disbursement_items.fk_dv_aucs_id,
       dv_aucs_index.book_name,
       dv_aucs_index.dv_number,
       dv_aucs_index.particular,
       dv_aucs_index.ttlAmtDisbursed,
       dv_aucs_index.ttlTax,
       dv_aucs_index.grossAmt,
       dv_aucs_index.orsNums,
       dv_aucs_index.payee,
       IFNULL(dv_aucs_index.bank_name,'') as bank_name,
       IFNULL(dv_aucs_index.account_num,'') as account_num,
       CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as chart_of_acc,
       dv_aucs_index.id as dv_id
       FROM cash_disbursement_items
       LEFT JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
       LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id 
       WHERE cash_disbursement_items.fk_cash_disbursement_id = :id
       AND cash_disbursement_items.is_deleted = 0
       ")
            ->bindValue(':id', $model_id)
            ->queryAll();
        return $qry;
    }
    private function getAdaNumber($issuance_date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $issuance_date);

        $ada_number = Yii::$app->db->createCommand("SELECT 
        CAST(SUBSTRING_INDEX(cash_disbursement.ada_number,'-',-1)AS UNSIGNED) +1  as lst
        FROM cash_disbursement WHERE ada_number LIKE :yr
        ORDER BY lst DESC LIMIT 1")
            ->bindValue(':yr', $d->format('Y') . '%')
            ->queryScalar();
        $new_num = '';
        if (strlen($ada_number) < 5) {
            $new_num = str_repeat(0, 5 - strlen($ada_number));
        }
        $new_num .= $ada_number;
        return $d->format('Y-m') . '-' . $new_num;
    }
    private function sliieSerialNum($period)
    {
        $yr = DateTime::createFromFormat('Y-m', $period)->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(sliies.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM sliies  
            WHERE 
            sliies.serial_number LIKE :yr
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
    private function lddapAdaSerialNum($period)
    {
        $yr = DateTime::createFromFormat('Y-m', $period)->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(lddap_adas.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM lddap_adas  
            WHERE 
            lddap_adas.serial_number LIKE  :yr
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
    private function createSliie($model_id, $period)
    {
        try {


            $sliieModel = new Sliies();
            $sliieModel->fk_cash_disbursement_id = $model_id;
            $sliieModel->serial_number = $this->sliieSerialNum($period);

            if (!$sliieModel->validate()) {
                throw new ErrorException(json_encode($sliieModel->errors));
            }

            if (!$sliieModel->save(false)) {
                throw new ErrorException('SLIIE Model Save Failed');
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function createLddapAda($model_id, $period)
    {
        try {

            $lddapAdaModel = new LddapAdas();
            $lddapAdaModel->fk_cash_disbursement_id = $model_id;
            $lddapAdaModel->serial_number = $this->lddapAdaSerialNum($period);

            if (!$lddapAdaModel->validate()) {
                throw new ErrorException(json_encode($lddapAdaModel->errors));
            }
            if (!$lddapAdaModel->save(false)) {
                throw new ErrorException('LDDAP-ADA  Model Save Failed');
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function getSummary($model_id)
    {
        $qry = Yii::$app->db->createCommand("SELECT 
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger,
        SUM(dv_aucs_index.ttlAmtDisbursed) as total
         FROM 
        cash_disbursement_items
        JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
        LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
         WHERE cash_disbursement_items.fk_cash_disbursement_id = :id
        AND cash_disbursement_items.is_deleted = 0
        GROUP BY chart_of_accounts.uacs,
        chart_of_accounts.general_ledger")
            ->bindValue(':id', $model_id)
            ->queryAll();
        return $qry;
    }
    /**
     * Lists all CashDisbursement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashDisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CashDisbursement model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->getItems($id),
            'summary' => $this->getSummary($id),
            'acic_id' => $this->getAcicId($id),
            'rci_id' => $this->getRciId($id),
        ]);
    }
    private function getAcicId($id)
    {
        return Yii::$app->db->createCommand("SELECT acics_cash_items.fk_acic_id FROM acics_cash_items WHERE 
        acics_cash_items.fk_cash_disbursement_id = :id
        AND acics_cash_items.is_deleted = 0")
            ->bindValue(':id', $id)
            ->queryScalar();
    }
    private function getRciId($id)
    {
        return Yii::$app->db->createCommand("SELECT rci_items.fk_rci_id FROM rci_items WHERE 
        rci_items.fk_cash_disbursement_id = :id
        AND rci_items.is_deleted = 0")
            ->bindValue(':id', $id)
            ->queryScalar();
    }

    private function insertItems($model_id, $items, $isUpdate = false)
    {

        try {
            if ($isUpdate === true && !empty(array_column($items, 'item_id'))) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                echo Yii::$app->db->createCommand("UPDATE cash_disbursement_items SET is_deleted = 1 
                WHERE 
                cash_disbursement_items.is_deleted = 0
                AND cash_disbursement_items.fk_cash_disbursement_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {
                if (!empty($itm['item_id'])) {
                    $itmModel = CashDisbursementItems::findOne($itm['item_id']);
                } else {
                    $itmModel = new CashDisbursementItems();
                }
                $itmModel->fk_cash_disbursement_id = $model_id;
                $itmModel->fk_dv_aucs_id = $itm['dv_id'];
                $itmModel->fk_chart_of_account_id = $itm['chart_of_acc_id'];
                $itmModel->is_deleted = 0;
                if (!$itmModel->validate()) {
                    throw new ErrorException(json_encode($itmModel->errors));
                }
                if (!$itmModel->save(false)) {
                    throw new ErrorException('itemModel Save Failed');
                }
                Yii::$app->db->createCommand("UPDATE advances_entries
                JOIN advances ON advances_entries.advances_id = advances.id
                SET advances_entries.is_deleted = 0
                WHERE 
                advances_entries.is_deleted = 9
                AND advances.dv_aucs_id = :dv_id ")
                    ->bindValue('dv_id', $itmModel->fk_dv_aucs_id)
                    ->query();
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    /**
     * Creates a new CashDisbursement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CashDisbursement();
        $dvSearchModel = new VwUndisbursedDvsSearch();
        $dvSearchModel->is_cancelled = 0;
        $dvDataProvider = $dvSearchModel->search(Yii::$app->request->queryParams);
        $dvDataProvider->pagination = ['pageSize' => 10];
        if ($model->load(Yii::$app->request->post())) {
            $items = Yii::$app->request->post('items') ?? [];
            $model->begin_time =  DateTime::createFromFormat('h:i a', $model->begin_time)->format('H:i');
            $model->begin_time =  DateTime::createFromFormat('h:i a', $model->out_time)->format('H:i');
            try {
                $txn = Yii::$app->db->beginTransaction();
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                if (empty($items)) {
                    throw new ErrorException('Items is Required');
                }
                $model_check_num =  $this->getCheckNumber($model->fk_ro_check_range_id);
                // return var_dump($model_check_num);
                if (empty($model_check_num) && $model_check_num !== 0) {
                    throw new ErrorException("No Available Check Number for the selected check range");
                }
                $mode_of_payment_name = strtolower(trim($model->modeOfPayment->name));
                if ($mode_of_payment_name == 'lbp check w/o ada' || $mode_of_payment_name == 'echeck w/o ada') {
                    if (count(array_unique(array_column($items, 'dv_id'))) > 1) {
                        throw new ErrorException('Items Cannot be more than one');
                    }
                }
                if ($mode_of_payment_name == 'lbp check w/ ada' || $mode_of_payment_name == 'echeck w/ ada') {
                    $model->ada_number = $this->getAdaNumber($model->issuance_date);
                }

                $model->check_or_ada_no = $model_check_num;
                $model->is_cancelled = 0;
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $insItms = $this->insertItems($model->id, $items);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
                }
                if ($mode_of_payment_name == 'lbp check w/ ada' || $mode_of_payment_name == 'echeck w/ ada') {
                    $insSliie = $this->createSliie($model->id, DateTime::createFromFormat('Y-m-d', $model->issuance_date)->format('Y-m'));
                    if ($insSliie !== true) {
                        throw new ErrorException($insSliie);
                    }
                    $insLddapAda = $this->createLddapAda($model->id, DateTime::createFromFormat('Y-m-d', $model->issuance_date)->format('Y-m'));
                    if ($insLddapAda !== true) {
                        throw new ErrorException($insLddapAda);
                    }
                }
                Yii::$app->db->createCommand("UPDATE advances_entries 
                    LEFT JOIN advances ON advances_entries.advances_id  = advances.id
                    SET advances_entries.is_deleted = 0,
                    advances_entries.cash_disbursement_id = :cash_id
                    WHERE 
                    advances.dv_aucs_id = :dv_id
                    AND advances_entries.is_deleted = 9
                    ")
                    ->bindValue(':dv_id', $model->dv_aucs_id)
                    ->bindValue(':cash_id', $model->id)
                    ->query();
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error' => true, 'error_message' => $e->getMessage()]);
            }
        }


        return $this->render('create', [
            'model' => $model,
            'dvSearchModel' => $dvSearchModel,
            'dvDataProvider' => $dvDataProvider,
            'items' => []
        ]);
    }

    private function hasAcic($id)
    {
        return Yii::$app->db->createCommand("SELECT EXISTS(SELECT 
        acics_cash_items.id
        FROM acics_cash_items WHERE fk_cash_disbursement_id = :id
        AND
        acics_cash_items.is_deleted = 0
        )")
            ->bindValue(':id', $id)
            ->queryScalar();
    }
    /**
     * Updates an existing CashDisbursement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);
        $dvSearchModel = new VwUndisbursedDvsSearch();
        $dvSearchModel->is_cancelled = 0;
        $dvSearchModel->bookFilter = !empty($model->book->name) ? $model->book->name : '';
        $dvDataProvider = $dvSearchModel->search(Yii::$app->request->queryParams);
        $dvDataProvider->pagination = ['pageSize' => 10];

        $model->begin_time =  DateTime::createFromFormat('H:i:s', $model->begin_time)->format('h:i A');
        $model->out_time =  DateTime::createFromFormat('H:i:s', $model->out_time)->format('h:i a');
        if ($model->load(Yii::$app->request->post())) {
            $items = Yii::$app->request->post('items') ?? [];
            try {
                $txn = Yii::$app->db->beginTransaction();
                $model->begin_time =  DateTime::createFromFormat('h:i a', $model->begin_time)->format('H:i');
                $model->out_time =  DateTime::createFromFormat('h:i a', $model->out_time)->format('H:i');
                if ($this->hasAcic($model->id)) {
                    throw new ErrorException('Cannot Update this Check is already in ACIC');
                }
                if ($model->is_cancelled == true) {
                    throw new ErrorException('Cancelled Check Cannot be Updated');
                }
                if (empty($items)) {
                    throw new ErrorException('Items is Required');
                }
                if (intval($model->fk_ro_check_range_id) !== intval($oldModel->fk_ro_check_range_id)) {

                    $model_check_num =  $this->getCheckNumber($model->fk_ro_check_range_id);
                    if (empty($model_check_num) && $model_check_num !== 0) {
                        throw new ErrorException("No Available Check Number for the selected check range");
                    }
                    $model->check_or_ada_no = $model_check_num;
                }

                $mode_of_payment_name = strtolower(trim($model->modeOfPayment->name));
                if ($mode_of_payment_name == 'lbp check w/o ada' || $mode_of_payment_name == 'echeck w/o ada') {
                    if (count(array_unique(array_column($items, 'dv_id'))) > 1) {
                        throw new ErrorException('Items Cannot be more than one');
                    }
                    $model->ada_number = null;
                }
                $old_mode_of_payment_name = strtolower(trim($oldModel->modeOfPayment->name));
                if ($old_mode_of_payment_name == 'lbp check w/o ada' || $old_mode_of_payment_name == 'echeck w/o ada') {
                    if ($mode_of_payment_name == 'lbp check w/ ada' || $mode_of_payment_name == 'echeck w/ ada') {
                        $model->ada_number = $this->getAdaNumber($model->issuance_date);
                    }
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $insItms = $this->insertItems($model->id, $items, true);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
                }
                if ($old_mode_of_payment_name == 'lbp check w/o ada' || $old_mode_of_payment_name == 'echeck w/o ada') {
                    if ($mode_of_payment_name == 'lbp check w/ ada' || $mode_of_payment_name == 'echeck w/ ada') {
                        $insSliie = $this->createSliie($model->id, DateTime::createFromFormat('Y-m-d', $model->issuance_date)->format('Y-m'));
                        if ($insSliie !== true) {
                            throw new ErrorException($insSliie);
                        }
                        $insLddapAda = $this->createLddapAda($model->id, DateTime::createFromFormat('Y-m-d', $model->issuance_date)->format('Y-m'));
                        if ($insLddapAda !== true) {
                            throw new ErrorException($insLddapAda);
                        }
                    }
                }
                Yii::$app->db->createCommand("UPDATE advances_entries 
                    LEFT JOIN advances ON advances_entries.advances_id  = advances.id
                    SET advances_entries.is_deleted = 0,
                    advances_entries.cash_disbursement_id = :cash_id
                    WHERE 
                    advances.dv_aucs_id = :dv_id
                    AND advances_entries.is_deleted = 9
                    ")
                    ->bindValue(':dv_id', $model->dv_aucs_id)
                    ->bindValue(':cash_id', $model->id)
                    ->query();
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error' => true, 'error_message' => $e->getMessage()]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'dvSearchModel' => $dvSearchModel,
            'dvDataProvider' => $dvDataProvider,
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Deletes an existing CashDisbursement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $model =  $this->findModel($id);
    //     if ($model->is_cancelled === 1) {
    //         $model->delete();
    //     } else {
    //         return $this->redirect(['index']);
    //     }
    //     // $this->findModel($id)->delete();

    //     return $this->redirect(['cancel-disbursement-index']);
    // }

    /**
     * Finds the CashDisbursement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CashDisbursement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CashDisbursement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionInsertCashDisbursement()
    {

        if ($_POST) {

            $reporting_period = $_POST["reporting_period"];
            $book_id = $_POST["book"];
            $check_ada_no = $_POST["check_ada_no"];
            $good_cancelled = empty($_POST["good_cancelled"]) ? $_POST['good_cancelled'] : 0;
            $issuance_date = $_POST["issuance_date"];
            $mode_of_payment = $_POST["mode_of_payment"];
            $ada_number = $_POST["ada_number"];
            $selected_items = !empty($_POST['selection']) ? $_POST['selection'] : '';
            $out_time = date('H:i:s', strtotime($_POST['out_time']));
            $begin_time = date('H:i:s', strtotime($_POST['begin_time']));

            // return json_encode(["isSuccess" => false,'error'=>$begin_time]);
            // if (!empty(count($_POST['selection'])) > 1) {
            //     return json_encode(["error" => "Selected Dv is More Than 1"]);
            // } else {

            if (!empty($_POST['update_id'])) {
                $cd = CashDisbursement::findOne($_POST['update_id']);
            } else {
                $cd = new CashDisbursement();
            }
            if ($good_cancelled == 0) {
                if (empty($selected_items)) {
                    return json_encode(['isSuccess' => false, "error" => "Select DV"]);
                    die();
                }
                if (count($selected_items) > 1) {
                    return json_encode(["error" => "Selected Dv is More Than 1"]);
                    die();
                } else {


                    $check_accounting_in_out = Yii::$app->db->createCommand("SELECT dv_aucs.in_timestamp, dv_aucs.out_timestamp FROM dv_aucs WHERE dv_aucs.id = :id")->bindValue(':id', $_POST['selection'][0])->queryOne();

                    if (empty($check_accounting_in_out['in_timestamp']) || empty($check_accounting_in_out['out_timestamp'])) {
                        return json_encode(['isSuccess' => false, "error" => "There is no time in and time out for DV selected."]);
                        die();
                    }
                }

                $cd->dv_aucs_id = $_POST['selection'][0];
            } else if ($good_cancelled == 1) {
                if (!empty($selected_items)) {
                    return json_encode(["error" => "Select Type is Cancelled "]);
                    die();
                }
            } else if (empty($good_cancelled)) {
                return json_encode(["error" => "Good/Cancelled is Required "]);
                die();
            }
            // SELECT * FROM `cash_disbursement` WHERE cash_disbursement.dv_aucs_id=6697;
            if (empty($_POST['update_id'])) {
                $query = (new \yii\db\Query)
                    ->select("cash_disbursement.id")
                    ->from("cash_disbursement")
                    ->where("cash_disbursement.dv_aucs_id = :dv_aucs_id", ['dv_aucs_id' => $selected_items[0]])
                    ->andWhere("cash_disbursement.is_cancelled=0")
                    ->one();
                if (!empty($query)) {
                    return json_encode(['isSuccess' => 'exist', 'id' => $query['id']]);
                }
            }

            if (empty($good_cancelled)) {
                $good_cancelled = 0;
            }

            $cd->book_id = $book_id;
            $cd->reporting_period = $reporting_period;
            $cd->mode_of_payment = $mode_of_payment;
            $cd->check_or_ada_no = $check_ada_no;
            $cd->is_cancelled = $good_cancelled;
            $cd->issuance_date = $issuance_date;
            $cd->ada_number = $ada_number;
            $cd->begin_time = $begin_time;
            $cd->out_time = $out_time;

            if ($cd->validate()) {
                if ($cd->save()) {

                    $q = Yii::$app->db->createCommand("UPDATE advances_entries 
            LEFT JOIN advances ON advances_entries.advances_id  = advances.id
            SET advances_entries.is_deleted = 0,
            advances_entries.cash_disbursement_id = :cash_id
            WHERE 
            advances.dv_aucs_id = :dv_id
            AND advances_entries.is_deleted = 9
            ")
                        ->bindValue(':dv_id', $cd->dv_aucs_id)
                        ->bindValue(':cash_id', $cd->id)
                        ->execute();
                    return json_encode(["isSuccess" => true, 'id' => $cd->id]);
                }
            } else {

                // echo"<pre>";
                // var_dump($q);
                // echo"</pre>";
                // die();
                return json_encode(["isSuccess" => false, "error" => $cd->errors]);
            }
            // }
        }
    }
    public function actionImport()
    {
        if (!empty($_POST)) {
            // $chart_id = $_POST['chart_id'];
            $name = $_FILES["file"]["name"];
            // var_dump($_FILES['file']);
            // die();
            $id = uniqid();
            $file = "transaction/{$id}_{$name}";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file);
            $excel->setActiveSheetIndexByName('Cash Disbursement');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

            $latest_tracking_no = (new \yii\db\Query())
                ->select('tracking_number')
                ->from('transaction')
                ->orderBy('id DESC')->one();
            if ($latest_tracking_no) {
                $x = explode('-', $latest_tracking_no['tracking_number']);
                $last_number = $x[2] + 1;
            } else {
                $last_number = 1;
            }
            // 
            $qwe = 1;
            foreach ($worksheet->getRowIterator(4) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 7) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $book_name = trim($cells[1]);
                    $reporting_period =  date("Y-m", strtotime($cells[2]));
                    $mode_of_payment = trim($cells[3]);
                    $check_ada_number = trim($cells[4]);
                    $ada_number = trim($cells[5]);
                    $good_cancelled = strtolower(trim($cells[6]));
                    $issuance_date = date('Y-m-d', strtotime($cells[7]));
                    $dv_number = trim($cells[8]);
                    $dv_id = null;
                    return   $cells[7];
                    die();
                    if ($good_cancelled === 'good') {

                        $dv = (new \yii\db\Query)
                            ->select("id")
                            ->from("dv_aucs")
                            ->where("dv_number =:dv_number", ['dv_number' => $dv_number])
                            ->one();

                        if (empty($dv)) {
                            return json_encode(['isSuccess' => false, 'error' => "DV Number Does not exist in line $key"]);
                        }

                        $dv_id = $dv['id'];
                    }
                    $book = (new \yii\db\Query())
                        ->select("books.id")
                        ->from('books')
                        ->where("books.name = :name", ['name' => $book_name])
                        ->one();
                    if (empty($book)) {
                        return json_encode(['isSuccess' => false, 'error' => "Book Does not exist in line $key"]);
                    }
                    strtolower(trim($good_cancelled)) === 'good' ? false : true;
                    $data[] = [
                        'book_id' => $book['id'],
                        'dv_id' => $dv_id,
                        'reporting_period' => $reporting_period,
                        'issuance_date' => $issuance_date,
                        'mode_of_payment' => $mode_of_payment,
                        'check_ada_number' => $check_ada_number,
                        'good_cancelled' => $good_cancelled,
                        'ada_number' => $ada_number

                    ];
                }
            }

            $column = [
                'book_id',
                'dv_aucs_id',
                'reporting_period',
                'issuance_date',
                'mode_of_payment',
                'check_or_ada_no',
                'is_cancelled',
                'ada_number',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('cash_disbursement', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            return ob_get_clean();
        }
    }

    public function actionGetAllDv()
    {
        $query = (new \yii\db\Query())
            ->select(['cash_disbursement.id as cash_id', 'dv_aucs.dv_number'])
            ->from('cash_disbursement')
            ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id  = dv_aucs.id')
            ->where('cash_disbursement.is_cancelled = :is_cancelled', ['is_cancelled' => false])
            ->all();

        // ob_clean();
        // echo "<pre>";
        // var_dump($query);
        // echo "</pre>";
        return json_encode($query);
        // ob_clean();
        // echo "<pre>";
        // var_dump($query);
        // echo "</pre>";
        // return ob_get_clean();
    }
    public function actionGetDv()
    {
        if (Yii::$app->request->post()) {
            $dv_id = Yii::$app->request->post('dv_id');
            $query = (new \yii\db\Query())
                ->select('*')
                ->from('detailed_cash_view')
                ->where('cash_id = :cash_id', ['cash_id' => $dv_id])
                ->one();
            $date = new DateTime($query['issuance_date']);

            $query['issuance_date'] = $date->format('Y-m-d');



            $dv_accounting_entries = [];
            $dv_accounting_entries = Yii::$app->db->createCommand("SELECT 
            dv_accounting_entries.dv_aucs_id,
                                   dv_accounting_entries.debit,
                                  dv_accounting_entries.credit,
                                   dv_accounting_entries.net_asset_equity_id,
                                   dv_accounting_entries.object_code,
                                  dv_accounting_entries.cashflow_id,
           accounting_codes.account_title
           FROM dv_accounting_entries 
           LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
           WHERE dv_accounting_entries.dv_aucs_id = :dv_id")->bindValue(':dv_id', $dv_id)
                ->queryAll();
            return json_encode(['results' => $query, 'dv_accounting_entries' => $dv_accounting_entries]);
        }
    }
    public function actionGetCashDisbursement()
    {
        if ($_POST) {
            $selected = $_POST['selection'];
            $q = "(";
            $x = count($selected);
            foreach ($selected as $key => $val) {

                if ($key + 1 === $x) {
                    $q .= $val;
                } else {
                    $q .= $val . ',';
                }
            }
            $q .= ')';
            $query  = Yii::$app->db->createCommand("SELECT 
            cash_disbursement.check_or_ada_no,
            cash_disbursement.id,
            dv_aucs.dv_number,
            dv_aucs.particular,
            dv.total_disbursed,
            payee.account_name as payee 

             FROM cash_disbursement,dv_aucs,payee,(SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,dv_aucs_entries.dv_aucs_id FROM dv_aucs_entries WHERE dv_aucs_entries.is_deleted = 0 GROUP BY dv_aucs_id) as dv
              WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
              AND dv_aucs.payee_id = payee.id
              AND dv_aucs.id = dv.dv_aucs_id
              AND cash_disbursement.id IN $q")
                // ->bindValue(:id,$q)
                ->queryAll();
            // $query = (new \yii\db\Query())
            //     ->select("*")
            //     ->from("cash_disbursement")
            //     ->where('cash_disbursement.id IN :id', ['id' => $q])
            //     ->all();
            return json_encode(['results' => $query]);
        }
        return json_encode('qwe');
    }

    public function actionCancelDisbursement()
    {
        $searchModel = new VwGoodCashDisbursementsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
        $dataProvider->pagination = ['pageSize' => 10];

        if (Yii::$app->request->post()) {
            $reporting_period = YIi::$app->request->post('reporting_period');
            $selected = YIi::$app->request->post('selection');
            $model = CashDisbursement::findOne($selected[0]);
            $query = Yii::$app->db->createCommand("SELECT EXISTS(
                SELECT *
                FROM cash_disbursement
                WHERE `parent_disbursement` =  :parent_id  AND is_cancelled = 1)")
                ->bindValue(':parent_id', $model->id)
                ->queryScalar();

            try {
                $txn = Yii::$app->db->beginTransaction();
                if (intval($query) === 1) {
                    throw new ErrorException('Check Already Cancelled');
                }
                $new_model  = new CashDisbursement();
                $new_model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $new_model->book_id = $model->book_id;
                $new_model->dv_aucs_id = $model->dv_aucs_id;
                $new_model->reporting_period = $reporting_period;
                $new_model->mode_of_payment = $model->mode_of_payment;
                $new_model->check_or_ada_no = $model->check_or_ada_no;
                $new_model->is_cancelled = 1;
                $new_model->issuance_date = $model->issuance_date;
                $new_model->ada_number = $model->ada_number;
                $new_model->parent_disbursement = $model->id;
                $new_model->begin_time = $model->begin_time;
                $new_model->out_time = $model->out_time;
                $new_model->fk_mode_of_payment_id = $model->fk_mode_of_payment_id;
                $new_model->fk_ro_check_range_id = $model->fk_ro_check_range_id;
                if (!$new_model->validate()) {
                    throw new ErrorException(json_encode($new_model->errors));
                }
                if (!$new_model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
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
                    AND cash_disbursement_items.is_deleted = 0
                ")
                    ->bindValue(':id', $model->id)
                    ->bindValue(':new_id', $new_model->id)
                    ->execute();
                // $insSliie = $this->createSliie($new_model->id, DateTime::createFromFormat('Y-m-d', $new_model->issuance_date)->format('Y-m'));
                // if ($insSliie !== true) {
                //     throw new ErrorException($insSliie);
                // }
                // $insLddapAda = $this->createLddapAda($new_model->id, DateTime::createFromFormat('Y-m-d', $new_model->issuance_date)->format('Y-m'));
                // if ($insLddapAda !== true) {
                //     throw new ErrorException($insLddapAda);
                // }
                $txn->commit();
                $this->redirect(['view', 'id' => $new_model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['isSuccess' => false, 'cancelled' => 'cancel', 'error' => $e->getMessage()]);
            }
        }
        return $this->render('_cancel_disbursement_form', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCancelDisbursementIndex()
    {
        $searchModel = new CancelledDisbursementsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
        return $this->render('cancel_disbursement_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearchDv($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(['cash_disbursement.id as id', 'dv_aucs.dv_number as text'])
                ->from('dv_aucs')
                ->andWhere(['like', 'dv_aucs.dv_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    public function actionDvDetails()
    {
        if (YIi::$app->request->isPost) {
            return json_encode($this->getDvDetails(YIi::$app->request->post('id')));
        }
    }
    public function actionCashChartOfAccounts()
    {

        if (YIi::$app->request->get()) {
            $query = new Query();
            $query->select(["chart_of_accounts.id, CONCAT (chart_of_accounts.uacs ,'-',chart_of_accounts.general_ledger) as text"])
                ->from('chart_of_accounts')

                ->andWhere(
                    [
                        'or',
                        ['=', 'uacs', '5010000000'],
                        ['=', 'uacs', '5020000000'],
                        ['=', 'uacs', '5060000000'],
                    ]

                );
            $command = $query->createCommand();
            $data = $command->queryAll();
            return json_encode($data);
        }
    }
}
