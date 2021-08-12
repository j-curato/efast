<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\OrsReportingPeriod;
use app\models\ProcessOrs;
use Yii;
use app\models\ProcessOrsEntries;
use app\models\ProcessOrsEntriesSearch;
use app\models\ProcessOrsRaoudsSearch;
use app\models\ProcessOrsViewSearch;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\Raouds2Search;
use app\models\RaoudsSearch;
use app\models\RaoudsSearch2;
use app\models\RecordAllotmentEntries;
use app\models\RecordAllotmentForOrsSearch;
use ErrorException;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessOrsEntriesController implements the CRUD actions for ProcessOrsEntries model.
 */
class ProcessOrsEntriesController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                    'adjust',
                    're-align',
                    'delete',
                    'add-data',
                    'insert-process-ors',
                    'import',
                    'get-raoud',
                    'cancel',
                    'update-ors',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'view',
                            'create',
                            'adjust',
                            're-align',
                            'delete',
                            'add-data',
                            'insert-process-ors',
                            'import',
                            'get-raoud',
                            'cancel',
                            'update-ors',

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
     * Lists all ProcessOrsEntries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessOrsViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->sort = ['defaultOrder' => ['process_ors_id' => 'DESC']];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProcessOrsEntries model.
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
     * Creates a new ProcessOrsEntries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new ProcessOrsEntries();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {p
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
        $searchModel = new RecordAllotmentForOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
            'update' => 'create',
        ]);
    }

    /**
     * Updates an existing ProcessOrsEntries model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        // $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }
        // return $this->render('update', [
        //     'model' => $model,
        // ]);
        $raoud = Raouds::findOne($id);
        $searchModel = new RecordAllotmentForOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'update' => 'update'
        ]);
    }
    // MAG ADJUST SA MGA CHARGING
    public function actionAdjust($id)
    {
        $raoud = Raouds::findOne($id);
        $searchModel = new RecordAllotmentForOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'update' => 'adjust',

        ]);
    }
    // MAG RE ALIGN SA CHARGING SA ORS OG ORS
    public function actionReAlign($id)
    {
        $searchModel = new RecordAllotmentForOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
            'update' => 're_align'
            // 'adjust-id'=>$id
        ]);
    }

    /**
     * Deletes an existing ProcessOrsEntries model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // if (Yii::$app->user->can('delete-ors')) {
        //     $this->findModel($id)->delete();
        //     return $this->redirect(['index']);
        // }
    }

    /**
     * Finds the ProcessOrsEntries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessOrsEntries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Raouds::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    // PAG KUHA SA RAOUDS DATA NGA BUHATAN OG OBLIGATION
    public function actionAddData()
    {
        $x = [];
        if (!empty($_POST)) {

            foreach ($_POST['selection'] as $val) {

                $query = (new \yii\db\Query())
                    ->select([
                        // 'mfo_pap_code.code AS mfo_pap_code_code',
                        // 'mfo_pap_code.name AS mfo_pap_name',
                        // 'fund_source.name AS fund_source_name',
                        // 'chart_of_accounts.uacs as object_code',
                        // 'chart_of_accounts.general_ledger',
                        // 'major_accounts.name',
                        // 'chart_of_accounts.id as chart_of_account_id',
                        // // 'raouds.id AS raoud_id',
                        // 'chart_of_accounts.uacs as record_allotment_object_code',

                        'raouds.id as raoud_id',
                        'record_allotment_for_ors.serial_number',
                        'record_allotment_for_ors.mfo_code as mfo_pap_code_code',
                        'record_allotment_for_ors.mfo_name as mfo_pap_name',
                        'record_allotment_for_ors.fund_source_name as fund_source_name',
                        'record_allotment_for_ors.uacs as object_code',
                        'record_allotment_for_ors.general_ledger',
                        'record_allotment_for_ors.amount',
                        'record_allotment_for_ors.balance as remain',
                        'record_allotment_for_ors.chart_of_account_id',
                    ])
                    ->from('record_allotment_for_ors')
                    ->join("LEFT JOIN", "raouds", "record_allotment_for_ors.id = raouds.record_allotment_entries_id ")
            
                    ->where("record_allotment_for_ors.id = :id", ['id' => $val])
                    ->andWhere('raouds.process_ors_id IS NULL')
                    ->one();
                $query['obligation_amount'] =  $_POST['amount'][$val];
                $x[] = $query;
            }

            // return json_encode($_POST['selection']);
            // $query=Yii::$app->db->createCommand("SELECT * FROM raouds where id IN ('1','2')")->queryAll();

            return json_encode(['results' => $x]);
        }
    }

    public function actionInsertProcessOrs()
    {
        // return json_encode($_POST['reporting_period']);
        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];
            $transaction_id = $_POST['transaction_id'];
            $book_id = $_POST['book_id'];
            $date = $_POST['date'];
            $transaction_timestamp = $_POST['transaction_timestamp'];



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
                    // KUHAON ANG PARENT NA RAOUD NA E ADJUST
                    $raoud_to_adjust = Raouds::find()->where("id =:id", ['id' => $_POST['update_id']])->one();
                    // $ors_id = ::find($_POST['update_id'])->recordAllotment->id;
                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total_adjustment
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = :raoud_id
                     ")
                        ->bindValue(":raoud_id", $raoud_to_adjust->id)
                        ->queryOne();

                    $total_amount = array_sum($_POST['obligation_amount']);
                    $adjust_total = $total_amount + $query['total_adjustment'];
                    $remaining_balance = $raoud_to_adjust->raoudEntries->amount -  $query['total_adjustment'];
                    // return  json_encode($query['total_adjustment']); 
                    // if ($raoud_to_adjust->raoudEntries->amount >= $adjust_total) {
                    $raoud_to_adjust->isActive = false;
                    $raoud_to_adjust->save();
                    // echo $reporting_period;
                    foreach ($_POST['chart_of_account_id'] as $index => $value) {
                        // KANI MAO NI ANG RAOUDS KUNG ASA E CHARGE ANG GE ADJUST
                        $raoud_to_charge_adjustment = Raouds::find()
                            ->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                        // $raoud_to_charge_adjustment->isActive = 0;
                        // $raoud_to_charge_adjustment->save();

                        // for ($i = 0; $i < 2; $i++) {
                        $raoud = new Raouds();
                        // $raoud->record_allotment_id = $raoud_to_charge_adjustment->record_allotment_id;
                        // $amount = intval(str_replace(', .', '', $_POST['obligation_amount'][$index]));
                        // $t = explode(',', $_POST['obligation_amount'][$index]);

                        // $amount = number_format(implode($t),2);
                        $t = explode(',', $_POST['obligation_amount'][$index]);
                        list($amount) = sscanf(implode($t), "%f");
                        // $amount = $_POST['obligation_amount'][$index];
                        // $amount=floatval(preg_replace('/[^\d.]/', '',  $_POST['obligation_amount'][$index]));
                        // return json_encode(['error' => $amount]); 
                        // if ($i === 0) {
                        //     if ($raoud_to_adjust->raoudEntries->amount > $total_amount) {

                        //         $amount = -$_POST['obligation_amount'][$index];
                        //     } else {
                        //         $amount = -$raoud_to_adjust->raoudEntries->amount;
                        //     }
                        //     $chart_of_account_id = $raoud_to_adjust->raoudEntries->chart_of_account_id;
                        //     $record_allotment_entries_id = $raoud_to_adjust->record_allotment_entries_id;
                        // } else {
                        //     $amount = $_POST['obligation_amount'][$index];
                        //     $chart_of_account_id = $value;
                        //     $record_allotment_entries_id = $raoud_to_charge_adjustment->record_allotment_entries_id;
                        // }
                        $raoud->record_allotment_entries_id = $raoud_to_charge_adjustment->record_allotment_entries_id;
                        $raoud->is_parent = false;
                        $raoud->isActive = false;
                        $raoud->process_ors_id = $raoud_to_adjust->process_ors_id;
                        $raoud->reporting_period = $_POST['new_reporting_period'][$index];
                        $raoud->obligated_amount = $amount;
                        if ($flag = $raoud->validate()) {
                            if ($raoud->save()) {

                                $raoud_entry = new RaoudEntries();
                                $raoud_entry->raoud_id = $raoud->id;
                                $raoud_entry->chart_of_account_id = $value;
                                $raoud_entry->amount = $amount;
                                $raoud_entry->parent_id_from_raoud = $raoud_to_adjust->id;

                                if ($raoud_entry->validate()) {
                                    if ($raoud_entry->save()) {
                                    }
                                } else {
                                    $transaction->rollBack();
                                    return json_encode(['error' => $raoud_entry->errors]);
                                }
                            }
                        } else {

                            $transaction->rollBack();
                            return json_encode(["error" => 'Error']);
                        }
                        // }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return json_encode([
                            'isSuccess' => true,
                            'id' => $raoud->id
                        ]);
                    }
                    // } else {
                    //     return json_encode(['isSuccess' => false, 'error' =>
                    //     "Obligation Amount is Less than Adjust Amount  Remaining Balance is  $remaining_balance"]);
                    // }
                } catch (ErrorException $e) {
                    return json_encode(["error" => $e]);
                }
            }
            // else if ($_POST['update'] === 'adjust') {
            //     $reporting_period = $_POST['new_reporting_period'][0];
            //     $raoud = Raouds::findOne($_POST['update_id']);
            //     // $ors = ProcessOrs::findOne($raoud->process_ors_id);
            //     // $ors->reporting_period = $reporting_period;
            //     // $ors->transaction_id = $transaction_id;
            //     // $ors->date = $date;
            //     // if ($ors->save(false)) {
            //     //     $transaction->commit();
            //     //     return json_encode(['isSuccess' => true, 'ors_id' => $ors->id]);
            //     // }
            //     try {
            //         foreach ($_POST['chart_of_account_id'] as $index => $value) {
            //         }

            //         $new_raoud = new Raouds();
            //         $new_raoud->process_ors_id = $raoud->process_ors_id;
            //         $new_raoud->reporting_period = $reporting_period;
            //         $new_raoud->obligated_amount =  $_POST['obligation_amount'][0];
            //         $new_raoud->record_allotment_entries_id = $raoud->record_allotment_entries_id;
            //         $new_raoud->isActive = false;

            //         if ($flag = $new_raoud->save(false)) {
            //             $raoud_entry = new RaoudEntries();
            //             $raoud_entry->raoud_id = $new_raoud->id;
            //             $raoud_entry->amount = $_POST['obligation_amount'][0];
            //             $raoud_entry->chart_of_account_id = $raoud->raoudEntries->chart_of_account_id;
            //             $raoud_entry->parent_id_from_raoud = $raoud->id;

            //             if ($raoud_entry->save(false)) {
            //                 $transaction->commit();
            //                 return json_encode(['isSuccess' => true]);
            //             }
            //         }
            //         if ($flag) {
            //             $transaction->commit();
            //             return json_encode(['isSuccess' => true, 'id' => $new_raoud->id]);
            //         }
            //     } catch (Exception $e) {
            //         $transaction->rollBack();
            //         return json_encode(['isSuccess' => false]);
            //     }
            // } 
            else if ($_POST['update'] === 'update') {
                $rao = Raouds::findOne($_POST['update_id']);
                $ors_update = ProcessOrs::findOne($rao->process_ors_id);
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
                    $r = Raouds::find()->where('raouds.process_ors_id = process_ors_id', ['process_ors_id' => $ors_update->id])->one();
                    return json_encode(['isSuccess' => true, 'id' => $r->id]);
                }

                // return json_encode($book->toArray());
            }
            // KUNG WLAY SULOD ANG UPDATE_ID DRI MO SULOD MAG BUHAT OG BAG.O NA DATA
            else {


                try {

                    $ors = new ProcessOrs();
                    $ors->reporting_period = $reporting_period;
                    $ors->transaction_id = $transaction_id;
                    $ors->book_id = $book_id;
                    $ors->date = $date;
                    $ors->transaction_begin_time = $transaction_timestamp;

                    $ors->serial_number = $this->getOrsSerialNumber($reporting_period, $book_id);
                    if ($ors->validate()) {
                        if ($flag = $ors->save()) {
                            // echo $reporting_period;
                            foreach ($_POST['chart_of_account_id'] as $index => $value) {
                                $q = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();

                                // $amount = intval(str_replace(', .', '', $_POST['obligation_amount'][$index]));
                                // $amount = $_POST['obligation_amount'][$index];
                                $t = explode(',', $_POST['obligation_amount'][$index]);
                                list($amount) = sscanf(implode($t), "%f");
                                // $amount=floatval(preg_replace('/[^\d.]/', '',  $_POST['obligation_amount'][$index]));
                                // KUNG ASA E CHARGE NA RAOUD ANG GE OBLIGATE
                                // $q->isActive = 0;
                                // $q->save();
                                $qwe = explode('-', $q->serial_number);
                                $raoud = new Raouds();
                                // $raoud->record_allotment_id = $q->record_allotment_id;
                                $raoud->record_allotment_entries_id = $q->record_allotment_entries_id;
                                $raoud->is_parent = false;
                                // $raoud->serial_number = Yii::$app->memem->getOrsBursRaoudSerialNumber($q->serial_number);
                                $raoud->isActive = false;
                                $raoud->process_ors_id = $ors->id;
                                $raoud->reporting_period = $ors->reporting_period;
                                $raoud->obligated_amount = $amount;
                                if ($raoud->validate()) {
                                    if ($raoud->save()) {
                                        $raoud_entry = new RaoudEntries();
                                        $raoud_entry->raoud_id = $raoud->id;
                                        $raoud_entry->chart_of_account_id = $value;
                                        $raoud_entry->amount = $amount;
                                        if ($raoud_entry->validate()) {
                                            if ($raoud_entry->save()) {
                                            }
                                        } else {
                                            $transaction->rollBack();
                                            return json_encode(['error' => $raoud_entry->errors]);
                                        }
                                    }
                                } else {

                                    $transaction->rollBack();
                                    return json_encode(["error" => 'Error']);
                                }


                                // $ors_entry = new ProcessOrsEntries();
                                // $ors_entry->chart_of_account_id = $value;
                                // $ors_entry->process_ors_id = $ors->id;
                                // $ors_entry->amount = $_POST['obligation_amount'][$index];
                                // if ($ors_entry->validate()) {

                                //     if ($ors_entry->save()) {
                                //     }
                                // } else {
                                //     $transaction->rollBack();
                                //     return json_encode(["error" => 'yawa sa ors entry']);
                                // }
                            }
                        }
                        if ($flag) {

                            $transaction->commit();
                            return json_encode(['isSuccess' => true, 'id' => $raoud->id]);
                        }
                    } else {
                        return json_encode(['isSuccess' => false, 'error' => $ors->errors]);
                    }
                } catch (ErrorException $e) {
                    $transaction->rollBack();
                    return json_encode(["error" => "wla ni sulod"]);
                }
            }

            // $ors->reporting_period = $reporting_period

        }
    }

    public function getOrsSerialNumber($reporting_period, $book_id)
    {
        $book = Books::findOne($book_id);
        //         select substring_index(substring(serial_number, instr(serial_number, "-")+9), " ", 1) as q from process_ors

        // WHERE process_ors.type LIKE 'ors'
        // ORDER BY q DESC
        $query = (new \yii\db\Query())
            ->select("serial_number")
            ->from('process_ors')
            ->where("process_ors.type=:type", ['type' => 'ors'])
            ->orderBy("id DESC")
            ->one();
        // $reporting_period = "2021-01";
        $year = date('Y', strtotime($reporting_period));
        if (empty($query['serial_number'])) {
            $x = 1;
        } else {
            $last_number = explode('-', $query['serial_number']);
            $x = intval($last_number[3]) + 1;
        }

        $serial_number = $book->name . '-' . $reporting_period . '-' . $x;
        // ob_start();
        // echo "<pre>";
        // var_dump( $last_number[1]);
        // echo "</pre>";
        // return ob_get_clean();
        return $serial_number;
    }
    public function getRaoudSerialNumber($serial_number)
    {
        // $serial_number = "Fund 01-2021-01-3";
        $raoud = (new \yii\db\Query())
            ->select("serial_number")
            ->from('raouds')
            ->orderBy('id DESC')
            ->one();

        $x = explode('-', $serial_number);
        $x[3] = explode('-', $raoud['serial_number'])[3] + 1;
        $y = implode('-', $x);

        return $y;
    }

    public function actionImport()
    {
        if (!empty($_POST)) {
            // $chart_id = $_POST['chart_id'];
            $name = $_FILES["file"]["name"];
            // var_dump($_FILES['file']);
            // die();
            $id = uniqid();
            $file = "process-ors/{$id}_{$name}";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file);
            $excel->setActiveSheetIndexByName('Import Process ORS');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

            // $latest_tracking_no = (new \yii\db\Query())
            //     ->select('tracking_number')
            //     ->from('transaction')
            //     ->orderBy('id DESC')->one();
            // if (!empty($latest_tracking_no)) {
            //     $x = explode('-', $latest_tracking_no['tracking_number']);
            //     $last_number = $x[3] + 1;
            // } else {
            //     $last_number = 1;
            // }
            // 
            $group_container = [];
            $transaction = \Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(4) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;

                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 12) {
                        $cells[] = $cell->getCalculatedValue();
                    } else if ($y === 4) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }

                $cluster = $cells[0];
                $reporting_period = date("Y-m", strtotime($cells[1]));
                $date = $cells[2];
                $transaction_number = $cells[3];
                $obligation_number = $cells[4];
                $allotment_number = explode("-", trim($cells[5]))[3];
                $allotment_uacs = trim($cells[6]);
                $obligation_uacs = trim($cells[7]);
                $amount = $cells[11];


                if (
                    !empty($cluster)
                    || !empty($reporting_period)
                    || !empty($date)
                    || !empty($transaction_number)
                    || !empty($obligation_number)
                    || !empty($allotment_number)
                    || !empty($allotment_uacs)
                    || !empty($obligation_uacs)

                ) {
                    //     return json_encode(['isSuccess' => false, 'error' => "Error Somthing is Missing in Line $key"]);
                    //     // die();
                    // } else {


                    $allotment_chart = $this->getChartOfAccount($allotment_uacs);
                    $obligation_chart = $this->getChartOfAccount($obligation_uacs);
                    // $y=explode('-',$transaction_number);
                    // $tt = $y[0] . '-' . $y[1] . '-' .$y[3];
                    $transaction = (new \yii\db\Query())
                        ->select('id')
                        ->from('transaction')
                        ->where("tracking_number LIKE :tracking_number", ['tracking_number' => "%$transaction_number%"])
                        ->one();
                    // ASA NA RAOUD NKO E CHARGE

                    $raoud = (new \yii\db\Query())
                        ->select(['raouds.id AS raoud_id', 'record_allotment_entries.id AS rea_id'])
                        ->from('record_allotment_entries')
                        ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id = record_allotments.id")
                        ->join("LEFT JOIN", "raouds", "record_allotment_entries.id = raouds.record_allotment_entries_id")
                        ->where("record_allotments.serial_number LIKE :serial_number", ['serial_number' => "%$allotment_number"])
                        ->andWhere("record_allotment_entries.chart_of_account_id = :chart_of_account_id", ['chart_of_account_id' => $allotment_chart['id']])
                        ->andWhere("raouds.is_parent = :is_parent", ['is_parent' => true])
                        ->one();

                    $isInserted = array_search($obligation_number, array_column($group_container, 'serial_number'));

                    if ($isInserted === false) {
                        $ors = new ProcessOrs();
                        $ors->reporting_period = $reporting_period;
                        $ors->transaction_id = $transaction['id'];
                        $ors->serial_number = $obligation_number;
                        if ($ors->save(false)) {
                            $group_container[] = ['id' => $ors->id, 'serial_number' => $ors->serial_number];
                        }
                        // $raoud = new Raouds();
                        // $raoud->record_allotment_entries_id = $q->record_allotment_entries_id;
                        // $raoud->is_parent = false;
                        // $raoud->isActive = false;
                        // $raoud->process_ors_id = $ors->id;
                        // $raoud->reporting_period = $ors->reporting_period;
                        // $raoud->obligated_amount = $_POST['obligation_amount'][$index];
                        $ors_id = $ors->id;
                    } else {
                        $ors_id = $group_container[$isInserted]['id'];
                    }
                    // for ($i = 0; $i < 2; $i++) {
                    $raoud_insert = new Raouds();
                    $raoud_insert->record_allotment_entries_id = $raoud['rea_id'];
                    $raoud_insert->is_parent = false;
                    // $raoud->serial_number = Yii::$app->memem->getOrsBursRaoudSerialNumber($q->serial_number);
                    $raoud_insert->isActive = false;
                    $raoud_insert->process_ors_id = $ors_id;
                    $raoud_insert->reporting_period = $ors->reporting_period;
                    $raoud_insert->obligated_amount =  $amount;
                    if ($raoud_insert->save(false)) {
                        $raoud_entries = new RaoudEntries();
                        $raoud_entries->raoud_id = $raoud_insert->id;
                        $raoud_entries->chart_of_account_id = $obligation_chart['id'];
                        $raoud_entries->amount = $amount;
                        if ($raoud_entries->save(false)) {
                        }
                    }
                    // }



                    // }

                }
                // $last_number++;
            }

            $column = [
                'responsibility_center_id',
                'payee_id',
                'particular',
                'gross_amount',
                'tracking_number',
                // 'earmark_no',
                'payroll_number',
                // 'transaction_date',
            ];
            $transaction->commit();
            // $ja = Yii::$app->db->createCommand()->batchInsert('transaction', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump('success');
            echo "<pre>";
            return ob_get_clean();
        }
    }
    public function getChartOfAccount($uacs)
    {


        $chart = (new \yii\db\Query())
            ->select('id')
            ->from('chart_of_accounts')
            ->where("uacs = :uacs", ['uacs' => $uacs])
            ->one();
        return $chart;
    }

    public function actionGetRaoud()
    {

        // $raoud = (new \yii\db\Query())
        //     ->select("")
        //     ->from('raouds')
        //     ->join("LEFT JOIN", 'raoud_entries', 'raouds.id = raoud_entries.raoud_id')
        //     ->join("LEFT JOIN", 'process_ors', 'raouds.process_ors_id = process_ors.id')
        //     // ->where("raouds.process_ors_id IS NULL " )
        //     ->where("raouds.id = :id", ['id' => $_POST['update_id']])
        //     ->all();
        $query = (new \yii\db\Query())
            ->select([
                'process_ors.reporting_period',
                'process_ors.book_id',
                'process_ors.date',
                'raouds.id as raoud_id',
                'process_ors.transaction_id',
                'mfo_pap_code.code AS mfo_pap_code_code',
                'mfo_pap_code.name AS mfo_pap_name',
                'fund_source.name AS fund_source_name',
                'chart_of_accounts.uacs as object_code',
                'chart_of_accounts.general_ledger',
                'major_accounts.name',
                'chart_of_accounts.id as chart_of_account_id',
                'raouds.id AS raoud_id',
                'entry.total',
                'raoud_entries.amount as obligation_amount',

                'record_allotment_entries.amount',
                '(record_allotment_entries.amount - entry.total) AS remain'
            ])
            ->from('raouds')
            ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
            ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id=record_allotments.id")
            ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
            ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
            ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
            ->join("LEFT JOIN", "chart_of_accounts", "raoud_entries.chart_of_account_id=chart_of_accounts.id")
            ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
            ->join("LEFT JOIN", "process_ors", "raouds.process_ors_id = process_ors.id")
            ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
        raouds.id, raouds.process_ors_id,
        raouds.record_allotment_entries_id
        FROM raouds,raoud_entries,process_ors
        WHERE raouds.process_ors_id= process_ors.id
        AND raouds.id = raoud_entries.raoud_id
        AND raouds.process_ors_id IS NOT NULL 
        GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
            // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")
            ->where("raouds.id = :id", ['id' => $_POST['update_id']])
            ->all();
        return json_encode(["result" => $query]);
    }



    public function actionCancel()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $is_cancelled_dv = 0;
            $model = ProcessOrs::findOne($id);
            if (!empty($model->dvAucsEntries)) {

                foreach ($model->dvAucsEntries as $val) {
                    $is_cancelled_dv = $val->dvAucs->is_cancelled;
                    if ($is_cancelled_dv === 0) {
                        return json_encode(['isSuccess' => false, "error" => 'DV Number ' . $val->dvAucs->dv_number . 'is not Cancelled']);
                        break;
                    }
                }
            }
            if ($model->is_cancelled) {

                $model->is_cancelled = false;
            } else {
                $model->is_cancelled = true;
            }
            if ($model->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => $model->is_cancelled]);
            }
            return $id;
        }
    }
    public function actionUpdateOrs()
    {
        if ($_POST) {
            $process_ors_id = Raouds::findOne($_POST['update_id'])->process_ors_id;

            $query = (new \yii\db\Query())
                ->select([
                    'process_ors.reporting_period',
                    'process_ors.book_id',
                    'process_ors.date',
                    'raouds.id as raoud_id',
                    'process_ors.transaction_id',
                    'mfo_pap_code.code AS mfo_pap_code_code',
                    'mfo_pap_code.name AS mfo_pap_name',
                    'fund_source.name AS fund_source_name',
                    'chart_of_accounts.uacs as object_code',
                    'chart_of_accounts.general_ledger',
                    'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id',
                    'raouds.id AS raoud_id',
                    'entry.total',
                    'raoud_entries.amount as obligation_amount',

                    'record_allotment_entries.amount',
                    '(record_allotment_entries.amount - entry.total) AS remain'
                ])
                ->from('raouds')
                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id=record_allotments.id")
                ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
                ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                ->join("LEFT JOIN", "chart_of_accounts", "raoud_entries.chart_of_account_id=chart_of_accounts.id")
                ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                ->join("LEFT JOIN", "process_ors", "raouds.process_ors_id = process_ors.id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
            
                    raouds.record_allotment_entries_id
                    FROM raouds,raoud_entries,process_ors
                    WHERE raouds.process_ors_id= process_ors.id
                    AND raouds.id = raoud_entries.raoud_id
                    AND raouds.process_ors_id IS NOT NULL 
                    GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")
                ->where("raouds.process_ors_id = :process_ors_id", ['process_ors_id' =>   $process_ors_id])
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
