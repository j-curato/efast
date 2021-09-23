<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\OrsReportingPeriod;
use Yii;
use app\models\ProcessBurs;
use app\models\ProcessBursRaoudsSearch;
use app\models\ProcessBursSearch;
use app\models\ProcessBursViewSearch;
use app\models\ProcessOrs;
use app\models\ProcessOrsEntries;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\Raouds2Search;
use app\models\RecordAllotmentForOrsSearch;
use app\models\RecordAllotmentsViewSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessBursController implements the CRUD actions for ProcessBurs model.
 */
class ProcessBursController extends Controller
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
                    'delete',
                    'view',
                    'update',
                    'create',
                    're-align',
                    'get-raoud',
                    'insert-process-burs',
                    'update-burs',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'delete',
                            'view',
                            'update',
                            'create',
                            're-align',
                            'get-raoud',
                            'insert-process-burs',
                            'update-burs',

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
     * Lists all ProcessBurs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessBursViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->sort = ['defaultOrder' => ['entry_id' => 'DESC']];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProcessBurs model.
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
     * Creates a new ProcessBurs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new ProcessBurs();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }
        // $book = Books::find()->where('books.name =:name', ['name' => "Fund 07"])->one();
        $searchModel = new RecordAllotmentsViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'burs');

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
            'update' => 'create',
        ]);
    }

    /**
     * Updates an existing ProcessBurs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {


        $searchModel = new RecordAllotmentsViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'burs');
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'update' => 'update'
        ]);
    }
    public function actionAdjust($id)
    {
        $raoud = Raouds::findOne($id);
        $searchModel = new RecordAllotmentsViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'burs');
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'update' => 'adjust',

        ]);
    }

    /**
     * Deletes an existing ProcessBurs model.
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
    public function actionReAlign($id)
    {
        $searchModel = new RecordAllotmentsViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'burs');
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'update' => 're_align'
            // 'adjust-id'=>$id
        ]);
    }

    /**
     * Finds the ProcessBurs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessBurs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProcessOrs::findOne($id)) !== null) {
            return $model;
        }


        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetRaoud()
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
                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id=record_allotments.id")
                ->join("LEFT JOIN", "chart_of_accounts", "record_allotment_entries.chart_of_account_id=chart_of_accounts.id")
                ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
                ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                raouds.id, raouds.process_ors_id,
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


    public function actionInsertProcessBurs()
    {
        // return json_encode($_POST['reporting_period']);
        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];
            $transaction_id = $_POST['transaction_id'];
            $book_id = $_POST['book_id'];
            $date = $_POST['date'];

            $q = OrsReportingPeriod::find()->where("reporting_period = :reporting_period", ['reporting_period' => $reporting_period])->one();

            $y = date('Y', strtotime($reporting_period));
            if ($y < date('Y')) {
                return json_encode(['isSuccess' => false, 'error' => 'Reporting Period Year  Must be ' . date('Y')]);
            }
            // return json_encode(['isSuccess' => false, 'error' => 'Success']);


            if (!empty($q)) {
                if ($q->disabled === 1) {
                    return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
                    die();
                }
            }



            // return json_encode($_POST['chart_of_account_id']);
            $transaction = \Yii::$app->db->beginTransaction();
            // KUNG NAAY SULOD ANG UPDATE ID  MAG ADD OG RAOUD OG ENTRY NIYA  PARA E ADJUST
            if ($_POST['update'] === 're_align') {

                try {
                    foreach ($_POST['raoud_id'] as $index => $value) {
                        $t = explode(',', $_POST['obligation_amount'][$index]);
                        list($amount) = sscanf(implode($t), "%f");
                        $ors_entries = new ProcessOrsEntries();
                        $ors_entries->record_allotment_entries_id = $value;
                        $ors_entries->process_ors_id = $_POST['update_id'];
                        $ors_entries->amount = $amount;
                        $ors_entries->chart_of_account_id = $_POST['chart_of_account_id'][$index];;
                        $ors_entries->reporting_period =  $_POST['new_reporting_period'][$index];
                        $ors_entries->is_realign = true;
                        if ($flag = $ors_entries->validate()) {
                            if ($ors_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return json_encode(["error" => 'Error sa save']);
                            }
                        } else {
                            $transaction->rollBack();
                            return json_encode(["error" => 'Error']);
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return json_encode([
                            'isSuccess' => true,
                            'id' => $ors_entries->process_ors_id
                        ]);
                    }
                } catch (ErrorException $e) {
                    return json_encode(["error" => $e]);
                }
            } else if ($_POST['update'] === 'update') {
                $ors_update = ProcessOrs::findOne($_POST['update_id']);
                $book = Books::findOne($book_id);
                $ors_update->reporting_period = $reporting_period;
                $ors_update->transaction_id = $transaction_id;
                $ors_update->book_id = $book_id;
                $ors_update->date = $date;

                $x = explode('-', $ors_update->serial_number);
                $serial = $book->name . '-' . $x[1] . '-' . $x[2] . '-' . $x[3];
                $ors_update->serial_number = $serial;
                if ($ors_update->save(false)) {
                    $transaction->commit();
                    return json_encode(['isSuccess' => true, 'id' => $ors_update->id]);
                }

                // return json_encode($book->toArray());
            }
            // KUNG WLAY SULOD ANG UPDATE_ID DRI MO SULOD MAG BUHAT OG BAG.O NA DATA
            else {


                try {

                    $burs = new ProcessOrs();
                    $burs->reporting_period = $reporting_period;
                    $burs->transaction_id = $transaction_id;
                    $burs->book_id = $book_id;
                    $burs->date = $date;
                    $burs->type = 'burs';
                    $burs->serial_number = $this->getBursSerialNumber($reporting_period, $book_id);
                    if ($burs->validate()) {
                        if ($flag = $burs->save()) {
                            // echo $reporting_period;
                            foreach ($_POST['chart_of_account_id'] as $index => $value) {

                                $t = explode(',', $_POST['obligation_amount'][$index]);
                                list($amount) = sscanf(implode($t), "%f");

                                $burs_entries = new ProcessOrsEntries();
                                $burs_entries->record_allotment_entries_id =  $_POST['raoud_id'][$index];
                                $burs_entries->process_ors_id = $burs->id;
                                $burs_entries->amount = $amount;
                                $burs_entries->chart_of_account_id = $value;
                                $burs_entries->reporting_period =  $burs->reporting_period;
                                if ($flag = $burs_entries->validate()) {
                                    if ($burs_entries->save(false)) {
                                    } else {
                                        $transaction->rollBack();
                                        return json_encode(["error" => 'Error sa save']);
                                    }
                                } else {
                                    $transaction->rollBack();
                                    return json_encode(["error" => 'Error']);
                                }
                            }
                        }
                        if ($flag) {

                            $transaction->commit();
                            return json_encode(['isSuccess' => true, 'id' => $burs->id]);
                        }
                    } else {
                        return json_encode(['isSuccess' => false, 'error' => $burs->errors]);
                    }
                } catch (ErrorException $e) {
                    $transaction->rollBack();
                    return json_encode(["error" => $e->getMessage()]);
                }
            }
        }
    }
    public function getBursSerialNumber($reporting_period, $book_id)
    {
        $book = Books::findOne($book_id);
        // $query = (new \yii\db\Query())
        //     ->select("process_ors.serial_number")
        //     ->from('process_ors')
        //     ->where("process_ors.type=:type",['type'=>'burs'])
        //     ->orderBy("id DESC")
        //     ->one();
        $query = Yii::$app->db->createCommand("SELECT substring_index(substring(serial_number, instr(serial_number, '-')+9), ' ', 1) as q 
        from process_ors
        where type ='burs'
        ORDER BY q DESC  LIMIT 1")->queryScalar();
        // $reporting_period = "2021-01";
        $year = date('Y', strtotime($reporting_period));
        if (empty($query)) {
            $x = 1;
        } else {
            // $last_number = explode('-', $query['serial_number']);
            $x = (int)$query + 1;
        }

        $y = '';
        for ($i = strlen($x); $i < 3; $i++) {
            $y .= 0;
        }
        $y .= $x;
        $serial_number = $book->name . '-' . $reporting_period . '-' . $y;
        return $serial_number;
    }
    public function actionUpdateBurs()
    {
        if ($_POST) {
            $process_ors_id = $_POST['update_id'];
            $query = (new \yii\db\Query())
                ->select([
                    'process_ors.reporting_period',
                    'process_ors.transaction_id',
                    'process_ors.book_id',
                    'process_ors.date',
                    'record_allotments_view.entry_id as raoud_id',
                    'process_ors.transaction_id',
                    'record_allotments_view.mfo_code AS mfo_pap_code_code',
                    'record_allotments_view.mfo_name AS mfo_pap_name',
                    'record_allotments_view.fund_source AS fund_source_name',
                    'chart_of_accounts.uacs as object_code',
                    'chart_of_accounts.general_ledger',
                    'chart_of_accounts.id as chart_of_account_id',
                    'record_allotments_view.entry_id AS raoud_id',
                    'process_ors_entries.amount as obligation_amount',
                    'process_ors_entries.reporting_period as entry_reporting_period',
                    'transaction.tracking_number'


                ])
                ->from('process_ors')
                ->join("LEFT JOIN", "process_ors_entries", "process_ors.id = process_ors_entries.process_ors_id")
                ->join("LEFT JOIN", "chart_of_accounts", "process_ors_entries.chart_of_account_id = chart_of_accounts.id")
                ->join("LEFT JOIN", "transaction", "process_ors.transaction_id = transaction.id")

                ->join(
                    "LEFT JOIN",
                    "record_allotments_view",
                    "process_ors_entries.record_allotment_entries_id =record_allotments_view.entry_id"
                )

                ->where("process_ors.id = :id", ['id' =>   $process_ors_id])
                ->all();


            // ob_clean();
            // echo "<pre>";
            // var_dump($query);
            // echo "</pre>";
            // return ob_get_clean();

            return json_encode(["result" => $query]);
        }
    }
}
