<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\Books;
use Yii;
use app\models\ProcessOrs;
use app\models\ProccessOrsSearch;
use app\models\ProcessOrsEntries;
use app\models\ProcessOrsEntriesSearch;
use app\models\ProcessOrsIndexSearch;
use app\models\ProcessOrsTxnItems;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\Raouds2Search;
use app\models\RaoudsSearch;
use app\models\RecordAllotmentDetailed;
use app\models\RecordAllotmentDetailedSearch;
use app\models\RecordAllotmentsViewSearch;
use DateTime;
use ErrorException;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;
use yii\db\ForeignKeyConstraint;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessOrsController implements the CRUD actions for ProcessOrs model.
 */
class ProcessOrsController extends Controller
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
                    'delete',
                    'update',
                    'view',
                    'sample',
                    'insert-process-ors',
                    'qwe',
                    'search-ors'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'delete',
                            'update',
                            'view',
                            'sample',
                            'insert-process-ors',
                            'qwe',
                            'search-ors'

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
    private function checkAllotmentBal($allotment_id, $amt)
    {
        $bal = YIi::$app->db->createCommand("SELECT 
        record_allotment_detailed.balAfterObligation
         FROM record_allotment_detailed WHERE record_allotment_detailed.allotment_entry_id =:id")
            ->bindValue(':id', $allotment_id)
            ->queryScalar();
        $f_bal = floatval($bal) - floatval($amt);
        if ($f_bal < 0) {
            return  "Allotment Amount Cannot be more than " . number_format($bal, 2);
        }
        return true;
    }
    private function InsertEntries($orsId, $items, $reporting_period = '')
    {
        $cnt = 1;
        try {
            foreach ($items as $item) {

                if (!empty($item['item_id'])) {

                    $entry = ProcessOrsEntries::findOne($item['item_id']);
                } else {
                    $entry = new ProcessOrsEntries();
                }

                $validate = $this->checkAllotmentBal(
                    $item['allotment_id'],
                    $item['gross_amount'],

                );
                if ($validate !== true) {
                    throw new ErrorException($validate . ' in item No. ' . $cnt);
                }
                $entry->chart_of_account_id = intval($item['chart_of_account_id']);
                $entry->process_ors_id = $orsId;
                $entry->amount = floatval($item['gross_amount']);
                $entry->reporting_period = !empty($reporting_period) ? $reporting_period : $item['reporting_period'];
                $entry->record_allotment_entries_id = intval($item['allotment_id']);
                if (!$entry->validate()) {
                    throw new ErrorException(json_encode($entry->errors));
                }
                if (!$entry->save(false)) {
                    throw new ErrorException('Entry Save Error');
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    private function checkTxnItmBal($txnItmId, $amt, $item_id = '')
    {

        $params = [];
        $sql = '';
        if (!empty($item_id)) {
            $sql .= ' AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', "process_ors_txn_items.id", $item_id], $params);
        }
        $bal = Yii::$app->db->createCommand("SELECT 
             transaction_items.amount - IFNULL(ttlObligated.ttl,0) as balance
            FROM transaction_items 
            LEFT JOIN (SELECT 
            process_ors_txn_items.fk_transaction_item_id,
            SUM(process_ors_txn_items.amount) * -1 as ttl
            FROM 
            process_ors_txn_items
            WHERE process_ors_txn_items.is_deleted = 0
            $sql
            GROUP BY process_ors_txn_items.fk_transaction_item_id) as ttlObligated ON transaction_items.id  = ttlObligated.fk_transaction_item_id
            WHERE  `transaction_items`.id = :id
         ", $params)
            ->bindValue(':id', $txnItmId)
            ->queryScalar();

        $finalBal = floatval($bal) - (floatval($amt) * -1);

        if ($finalBal < 0) {
            return "Allotment Balance Cannot be less than " . number_format($bal, 2);
        }
        return true;
    }
    private function InsertOrsTxnItems($orsId, $items)
    {
        try {
            foreach ($items as $item) {
                $itemId = '';
                $amt = floatval($item['txnAmount']) > 0 ? $item['txnAmount'] * -1 : $item['txnAmount'];
                if (!empty($item['item_id'])) {
                    $itemId = $item['item_id'];
                    $txnItem = ProcessOrsTxnItems::findOne($item['item_id']);
                } else {
                    $txnItem = new ProcessOrsTxnItems();
                }
                $val = $this->checkTxnItmBal($item['txnItemId'], $amt, $itemId);
                if ($val !== true) {
                    throw new ErrorException($val);
                }
                $txnItem->fk_process_ors_id = $orsId;
                $txnItem->fk_transaction_item_id = $item['txnItemId'];
                $txnItem->amount = $amt;
                if (!$txnItem->validate()) {
                    throw new ErrorException(json_encode($txnItem->errors));
                }
                if (!$txnItem->save(false)) {
                    throw new ErrorException('Transaction Item Save Error');
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    private function GetOrsTxnItems($id)
    {
        return YIi::$app->db->createCommand("SELECT 
                     process_ors_txn_items.id as item_id,
                    `transaction_items`.id as transactionItemId,
                    `transaction`.tracking_number,
                    `transaction`.particular,
                    record_allotments.serial_number as allotment_number,
                    transaction_items.amount as txnItemAmt,
                    responsibility_center.`name` as responsibilityCenter,
                    CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
                    fund_source.`name` as fund_source_name,
                    chart_of_accounts.general_ledger as account_title,
                    chart_of_accounts.uacs ,
                    books.`name` as book_name,
                    payee.account_name as payee,
                    process_ors_txn_items.amount as itemAmt,
                    transaction_items.amount - IFNULL(ttlObligated.ttl,0) as balance

                    FROM process_ors_txn_items
                    INNER JOIN transaction_items ON `process_ors_txn_items`.fk_transaction_item_id = transaction_items.id
                    LEFT JOIN `transaction` ON transaction_items.fk_transaction_id  =`transaction`.id
                    LEFT JOIN record_allotment_entries ON transaction_items.fk_record_allotment_entries_id = record_allotment_entries.id
                    LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
                    LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
                    LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
                    LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
                    LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id
                    LEFT JOIN books ON `transaction`.fk_book_id = books.id
                    LEFT JOIN payee ON `transaction`.payee_id = payee.id
                    LEFT JOIN (SELECT 
                    process_ors_txn_items.fk_transaction_item_id,
                    SUM(process_ors_txn_items.amount) *-1 as ttl
                    FROM 
                    process_ors_txn_items
                    WHERE process_ors_txn_items.is_deleted = 0
                    GROUP BY process_ors_txn_items.fk_transaction_item_id) as ttlObligated ON transaction_items.id  = ttlObligated.fk_transaction_item_id
                    WHERE  
                    process_ors_txn_items.is_deleted = 0
                    AND process_ors_txn_items.fk_process_ors_id = :id")

            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function GetOrsItems($id)
    {
        return YIi::$app->db->createCommand("SELECT 

        process_ors_entries.reporting_period,
        record_allotment_detailed.mfo_code,
        record_allotment_detailed.mfo_name,
        record_allotment_detailed.fund_source_name as fund_source,
        chart_of_accounts.general_ledger,
        process_ors_entries.chart_of_account_id,
        chart_of_accounts.uacs,
        process_ors_entries.amount,
        process_ors_entries.record_allotment_entries_id as allotment_id,
        record_allotment_detailed.uacs as allotment_uacs,
        record_allotment_detailed.account_title as allotment_ledger,
        record_allotment_detailed.allotmentNumber as serial_number,
        record_allotment_detailed.balance
        
        FROM 
        process_ors_entries
        LEFT JOIN chart_of_accounts ON process_ors_entries.chart_of_account_id  = chart_of_accounts.id
        LEFT JOIN record_allotment_detailed ON process_ors_entries.record_allotment_entries_id  = record_allotment_detailed.allotment_entry_id
     
        WHERE 
        process_ors_entries.process_ors_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }


    /**
     * Lists all ProcessOrs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessOrsIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'ors'
        ]);
    }
    public function actionBursIndex()
    {

        $searchModel = new ProcessOrsIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'burs');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'type' => 'burs'
        ]);
    }

    /**
     * Displays a single ProcessOrs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'orsTxnAllotments' => $this->GetOrsTxnItems($id),
            'GetOrsItems' => $this->GetOrsItems($id),

        ]);
    }

    /**
     * Creates a new ProcessOrs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = 'ors')
    {
        $model = new ProcessOrs();
        if ($model->load(Yii::$app->request->post())) {
            $model->type = $type;
            $orsTxnItems = Yii::$app->request->post('orsTxnItems') ?? [];
            $orsItems = Yii::$app->request->post('orsItems') ?? [];
            $model->serial_number = $this->getOrsSerialNumber($model->reporting_period, $model->book_id);

            try {
                $transaction = Yii::$app->db->beginTransaction();
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Error");
                }
                $insertEntries = $this->InsertEntries($model->id, $orsItems, $model->reporting_period);
                if ($insertEntries !== true) {
                    throw new ErrorException($insertEntries . ' in Record Allotments Table ');
                }
                $insertOrsTxnItems = $this->InsertOrsTxnItems($model->id, $orsTxnItems);
                if ($insertOrsTxnItems !== true) {
                    throw new ErrorException($insertOrsTxnItems);
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(["error" => $e->getMessage()]);
            }
        }


        $searchModel = new RecordAllotmentDetailedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $type);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'type' => $type
        ]);
    }

    /**
     * Updates an existing ProcessOrs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $orsTxnItems = Yii::$app->request->post('orsTxnItems') ?? [];
            $orsItems = Yii::$app->request->post('orsItems') ?? [];
            // return json_encode($orsItems);
            try {
                $transaction = Yii::$app->db->beginTransaction();


                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }

                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Error");
                }

                $insertEntries = $this->InsertEntries($model->id, $orsItems);
                if ($insertEntries !== true) {
                    throw new ErrorException($insertEntries . ' in Record Allotments Table ');
                }
                $insertOrsTxnItems = $this->InsertOrsTxnItems($model->id, $orsTxnItems);
                if ($insertOrsTxnItems !== true) {
                    throw new ErrorException($insertOrsTxnItems);
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(["error" => $e->getMessage()]);
            }
        }


        $searchModel = new RecordAllotmentDetailedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->type);

        return $this->render('update', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'orsTxnAllotments' => $this->GetOrsTxnItems($id),
            'GetOrsItems' => $this->GetOrsItems($id),

        ]);
    }

    /**
     * Deletes an existing ProcessOrs model.
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
    public function actionCreateBurs()
    {
        return $this->redirect(['create', 'type' => 'burs']);
    }

    /**
     * Finds the ProcessOrs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessOrs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProcessOrs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSample()
    {
        $x = [];
        foreach ($_POST['selection'] as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    'mfo_pap_code.code AS mfo_pap_code_code', 'mfo_pap_code.name AS mfo_pap_name', 'fund_source.name AS fund_source_name',
                    'chart_of_accounts.uacs as object_code', 'chart_of_accounts.general_ledger', 'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id', 'raouds.id AS raoud_id',
                    'entry.total', 'record_allotment_entries.amount', '(record_allotment_entries.amount - entry.total) AS remain'
                ])
                ->from('raouds')
                ->join("LEFT JOIN", "record_allotments", "raouds.record_allotment_id=record_allotments.id")
                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                ->join("LEFT JOIN", "chart_of_accounts", "record_allotment_entries.chart_of_account_id=chart_of_accounts.id")
                ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
                ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                raouds.id, raouds.record_allotment_id,raouds.process_ors_id,
                raouds.record_allotment_entries_id
                FROM raouds,raoud_entries,process_ors
                WHERE raouds.process_ors_id= process_ors.id
                AND raouds.id = raoud_entries.raoud_id
                AND raouds.process_ors_id IS NOT NULL 
                GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")

                ->where("raouds.id = :id", ['id' => $val])->one();
            $query['obligation_amount'] =  $_POST['amount'][$val];
            $x[] = $query;
        }

        // return json_encode($_POST['selection']);
        // $query=Yii::$app->db->createCommand("SELECT * FROM raouds where id IN ('1','2')")->queryAll();

        return json_encode(['results' => $x]);
    }

    public function actionInsertProcessOrs()
    {
        // return json_encode($_POST['reporting_period']);

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];

            $ors = new ProcessOrs();
            $ors->reporting_period = $reporting_period;
            if ($ors->validate()) {
                if ($ors->save(false)) {
                    // return json_encode($q);
                    // $raoud = new Raouds();
                    foreach ($_POST['chart_of_account_id'] as $index => $value) {

                        $q = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                        $q->isActive = 0;
                        $q->save();
                        $raoud = new Raouds();
                        $raoud->record_allotment_id = $q->record_allotment_id;
                        $raoud->record_allotment_entries_id = $q->record_allotment_entries_id;
                        $raoud->process_ors_id = $ors->id;
                        $raoud->reporting_period = $ors->reporting_period;
                        $raoud->obligated_amount = $_POST['final_amount'][$index];

                        if ($raoud->save()) {
                            $raoud_entry = new RaoudEntries();
                            $raoud_entry->raoud_id = $raoud->id;
                            $raoud_entry->chart_of_account_id = $value;
                            $raoud_entry->amount = $_POST['final_amount'][$index];
                            if ($raoud_entry->save()) {
                                echo $raoud->id;
                            }
                        }

                        $ors_entry = new ProcessOrsEntries();
                        $ors_entry->chart_of_account_id = $value;
                        $ors_entry->process_ors_id = $ors->id;
                        $ors_entry->amount = $_POST['final_amount'][$index];
                        if ($ors_entry->save(false)) {
                        } else {
                            return 'qweqwe';
                        }
                    }
                }
            } else {
                return json_encode(['isSuccess' => false, 'error' => $ors->errors]);
            }


            // $ors->reporting_period = $reporting_period

        }
    }
    public function actionQwe()
    {
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('raouds')
            ->where("id= :id", ['id' => 44])
            ->one();
        return $query['id'];
    }
    public function actionSearchOrs($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ProcessOrs::findOne($id)->serial_number];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('process_ors.id, process_ors.serial_number AS text')
                ->from('process_ors')
                ->where(['like', 'process_ors.serial_number', $q])
                ->andWhere('process_ors.is_cancelled != 1');
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionGetTxnAllotments()
    {
        if (Yii::$app->request->isPost) {

            $id = YIi::$app->request->post('id');
            $query = Yii::$app->db->createCommand("SELECT 
            `transaction_items`.id as transactionItemId,
            `transaction`.tracking_number,
            `transaction`.particular,
            record_allotments.serial_number as allotment_number,
            transaction_items.amount as txnItemAmt,
            responsibility_center.`name` as responsibilityCenter,
            CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
            fund_source.`name` as fund_source_name,
            chart_of_accounts.general_ledger as account_title,
            chart_of_accounts.uacs ,
            books.`name` as book_name,
            payee.account_name as payee,
            ttlObligated.ttl,
            transaction_items.amount - IFNULL(ttlObligated.ttl,0) as balance
            
            FROM `transaction`
            INNER JOIN transaction_items ON `transaction`.id = transaction_items.fk_transaction_id
            LEFT JOIN record_allotment_entries ON transaction_items.fk_record_allotment_entries_id = record_allotment_entries.id
            LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
            LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
            LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
            LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id
            LEFT JOIN books ON `transaction`.fk_book_id = books.id
            LEFT JOIN payee ON `transaction`.payee_id = payee.id
            LEFT JOIN (SELECT 
            process_ors_txn_items.fk_transaction_item_id,
            SUM(process_ors_txn_items.amount) * -1 as ttl
            FROM 
            process_ors_txn_items
            WHERE process_ors_txn_items.is_deleted = 0
            GROUP BY process_ors_txn_items.fk_transaction_item_id) as ttlObligated ON transaction_items.id  = ttlObligated.fk_transaction_item_id
            WHERE  `transaction`.id = :id
            AND transaction_items.is_deleted = 0
            ")
                ->bindValue(':id', $id)
                ->queryAll();
            return json_encode($query);
        }
    }
    private function getOrsSerialNumber($reporting_period, $book_id)
    {
        $book = Books::findOne($book_id);
        $year = DateTime::createFromFormat('Y-m', $reporting_period)->format('Y');

        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(process_ors.serial_number,'-',-1) AS UNSIGNED) last_number
        FROM process_ors
        WHERE
        
        process_ors.type = 'ors'
        AND process_ors.reporting_period LIKE :_year
        ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':_year', $year . '%')
            ->queryScalar();
        if (empty($query)) {
            $x = 1;
        } else {

            $x = intval($query) + 1;
        }
        $final_number = '';
        for ($y = strlen($x); $y < 3; $y++) {
            $final_number .= 0;
        }

        $serial_number = $book->name . '-' . $reporting_period . '-' . $final_number . $x;

        return $serial_number;
    }
}
