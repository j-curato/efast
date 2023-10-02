<?php

namespace frontend\controllers;

use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\RecordAllotmentDetailedSearch;
use app\models\RecordAllotmentEntries;
use Yii;
use app\models\RecordAllotments;
use app\models\RecordAllotmentsViewSearch;
use ErrorException;
use Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RecordAllotmentsController implements the CRUD actions for RecordAllotments model.
 */
class RecordAllotmentsController extends Controller
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
                    'delete',
                    'create',
                    'create-record-allotments',
                    'update-record-allotment',
                    'import',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'delete',
                            'create',
                            'create-record-allotments',
                            'update-record-allotment',
                            'import',

                        ],
                        'allow' => true,
                        'roles' => ['record_allotment']
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

    private function insertItems($allotment_id, $items, $isUpdate = false)
    {

        try {
            if ($isUpdate) {
                $params = [];
                $item_ids = array_column($items, 'item_id');
                $sql = '';
                if (!empty($item_ids)) {
                    $sql = 'AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                }
                Yii::$app->db->createCommand("UPDATE record_allotment_entries SET is_deleted = 1 WHERE 
                     record_allotment_entries.record_allotment_id = :id  $sql", $params)
                    ->bindValue(':id', $allotment_id)
                    ->execute();
            }
            foreach ($items as $item) {
                if (!empty($item['item_id'])) {
                    $entry = RecordAllotmentEntries::findOne($item['item_id']);
                } else {
                    $entry = new RecordAllotmentEntries();
                }
                $entry->record_allotment_id = $allotment_id;
                $entry->chart_of_account_id = $item['chart_of_account_id'];
                $entry->amount = $item['amount'];
                if (!$entry->validate()) {
                    throw new ErrorException(json_encode($entry->errors));
                }
                if (!$entry->save(false)) {
                    throw new ErrorException('Entry Save Failed');
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    private function getItems($id)
    {
        $query = YIi::$app->db->createCommand("SELECT 
        record_allotment_entries.id as item_id,
        record_allotment_entries.chart_of_account_id,
        record_allotment_entries.amount,
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger
        FROM record_allotment_entries 
        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        WHERE record_allotment_entries.record_allotment_id = :id
        AND record_allotment_entries.is_deleted  = 0")
            ->bindValue(':id', $id)
            ->queryAll();
        return $query;
    }
    /**
     * Lists all RecordAllotments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecordAllotmentDetailedSearch();
        $searchModel->isMaf = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'all', '');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RecordAllotments model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $view = $model->isMaf ? 'maf_view' : 'view';
        return $this->render($view, [
            'model' => $model,
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Creates a new RecordAllotments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RecordAllotments();

        if ($model->load(Yii::$app->request->post())) {
            $items = Yii::$app->request->post('items');
            try {
                $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT 
                    fund_category_and_classification_code.id
                    FROM `fund_category_and_classification_code` 
                    WHERE  :fund_classification_code >=`fund_category_and_classification_code`.`from`
                    AND :fund_classification_code <= `fund_category_and_classification_code`.`to` LIMIT 1 ")
                    ->bindValue(':fund_classification_code', $model->fund_classification)
                    ->queryScalar();
                if (empty($fund_category_and_classification_code_id)) {
                    throw new ErrorException("No Fund Classification for {$model->fund_classification}");
                }
                $model->serial_number =  Yii::$app->memem->getAllotmentNumber($model->reporting_period, $model->book_id);
                $model->fund_category_and_classification_code_id = $fund_category_and_classification_code_id;
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Allotment Save Failed');
                }
                $insItms = $this->insertItems($model->id, $items);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
                }
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RecordAllotments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $items = Yii::$app->request->post('items');
            try {
                $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT 
                    fund_category_and_classification_code.id
                    FROM `fund_category_and_classification_code` 
                    WHERE  :fund_classification_code >=`fund_category_and_classification_code`.`from`
                    AND :fund_classification_code <= `fund_category_and_classification_code`.`to` LIMIT 1 ")
                    ->bindValue(':fund_classification_code', $model->fund_classification)
                    ->queryScalar();
                // $model->serial_number =  Yii::$app->memem->getRaoudSerialNumber($model->reporting_period, $model->book_id, '');
                $model->fund_category_and_classification_code_id = $fund_category_and_classification_code_id;
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Allotment Save Failed');
                }
                $insItms = $this->insertItems($model->id, $items, true);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
                }
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Deletes an existing RecordAllotments model.
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
     * Finds the RecordAllotments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecordAllotments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecordAllotments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreateRecordAllotments()
    {
        $date_issued = $_POST['date_issued'];
        $valid_until = $_POST['valid_until'];
        $reporting_period = $_POST['reporting_period'];
        $particulars = $_POST['particular'];
        $fund_cluster_code_id = $_POST['fund_cluster_code_id'];
        $document_recieve_id = $_POST['document_recieve'];
        $financing_source_code_id = $_POST['financing_source_code'];
        $authorization_code_id = $_POST['authorization_code'];
        $mfo_pap_code_id = $_POST['mfo_pap_code'];
        $fund_source_id = $_POST['fund_source'];
        $book_id = $_POST['book'];
        $responsibility_center_id = !empty($_POST['responsibility_center']) ? $_POST['responsibility_center'] : null;
        $office_id = $_POST['office_id'];
        $division_id = $_POST['division_id'];
        $allotment_type_id = $_POST['allotment_type_id'];
        $transaction = \Yii::$app->db->beginTransaction();
        // COUNTER NI SIYA KUN ASA DAPIT NA CHART_OF_ACCOUNT_ID IYA I UPDATE OG E INSERT KUNG MAG DUNGAG KAG ENTRIES
        $x = 0;
        if (!empty($_POST['update_id'])) {
            $recordAllotment = RecordAllotments::findOne(intval($_POST['update_id']));

            foreach ($recordAllotment->recordAllotmentEntries as $val) {

                $chart_id = $_POST['chart_of_account_id'][$x];

                $ra_entries = RecordAllotmentEntries::findOne($val->id);
                $ra_entries->chart_of_account_id = $chart_id;
                $ra_entries->record_allotment_id = $recordAllotment->id;
                $ra_entries->amount = $_POST['amount'][$x];
                if ($ra_entries->save(false)) {
                }
                $x++;
            }
            // foreach ($ra->recordAllotmentEntries as $val) {
            //     $val->delete();
            // }
            // $recordAllotment->id = $ra->id;
            // $ra->delete();
            // return json_encode($_POST['update_id']);

        } else {
            $recordAllotment = new RecordAllotments();
        }
        $serial_number = Yii::$app->memem->getRaoudSerialNumber($reporting_period, $book_id, $_POST['update_id']);
        $fund_category_and_classification_code_id = Yii::$app->db->createCommand("SELECT * FROM `fund_category_and_classification_code` WHERE  {$_POST['fund_classification_code']}>=`fund_category_and_classification_code`.from  and {$_POST['fund_classification_code']} <= `fund_category_and_classification_code`.to LIMIT 1 ")->queryOne();
        $recordAllotment->date_issued = $date_issued;
        $recordAllotment->fund_classification = $_POST['fund_classification_code'];
        $recordAllotment->serial_number = $serial_number;
        $recordAllotment->valid_until = $valid_until;
        $recordAllotment->reporting_period = $reporting_period;
        $recordAllotment->particulars = $particulars;
        $recordAllotment->fund_cluster_code_id = $fund_cluster_code_id;
        $recordAllotment->document_recieve_id = $document_recieve_id;
        $recordAllotment->authorization_code_id = $authorization_code_id;
        $recordAllotment->financing_source_code_id = $financing_source_code_id;
        $recordAllotment->mfo_pap_code_id = $mfo_pap_code_id;
        $recordAllotment->book_id = $book_id;
        $recordAllotment->responsibility_center_id = $responsibility_center_id;
        $recordAllotment->fund_source_id = $fund_source_id;
        $recordAllotment->office_id = $office_id;
        $recordAllotment->division_id = $division_id;
        $recordAllotment->allotment_type_id = $allotment_type_id;
        $recordAllotment->fund_category_and_classification_code_id = $fund_category_and_classification_code_id['id'];
        if ($recordAllotment->validate()) {
            try {
                if ($flag = $recordAllotment->save(false)) {
                    for ($x; $x < count($_POST['chart_of_account_id']); $x++) {
                        $chart_id = $_POST['chart_of_account_id'][$x];

                        $ra_entries = new RecordAllotmentEntries();
                        $ra_entries->chart_of_account_id = $chart_id;
                        $ra_entries->record_allotment_id = $recordAllotment->id;
                        $ra_entries->amount = $_POST['amount'][$x];
                        if ($ra_entries->validate()) {
                            if ($ra_entries->save()) {
                                $raoud = new Raouds();
                                // $raoud->record_allotment_id = $recordAllotment->id;
                                $raoud->serial_number = $recordAllotment->serial_number;
                                $raoud->reporting_period = $reporting_period;
                                $raoud->record_allotment_entries_id = $ra_entries->id;

                                if ($raoud->validate()) {

                                    if ($raoud->save(false)) {
                                        $raoudEntry = new RaoudEntries();
                                        $raoudEntry->chart_of_account_id = $chart_id;
                                        $raoudEntry->raoud_id = $raoud->id;
                                        $raoudEntry->amount = $_POST['amount'][$x];
                                        if ($raoudEntry->validate()) {
                                            if ($raoudEntry->save(false)) {
                                            } else echo 'qwe';
                                        }
                                        // else{
                                        //   $raoudEntry->errors;
                                        // }
                                    }
                                } else {
                                    return  json_encode(['isSuccess' => false, 'error' => $raoud->errors]);
                                }
                            }
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return json_encode(['isSuccess' => true, 'view_id' => $ra_entries->id]);
                    // return $this->render('view', [
                    //     'model' => $this->findModel($recordAllotment->id),
                    // ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();


                return json_encode(['isSuccess' => false, 'error' => $e]);
            }
        } else {
            return  json_encode(['isSuccess' => false, 'error' => $recordAllotment->errors]);
        }
    }

    public function actionUpdateRecordAllotment()
    {
        // 99889333414133896
        // 99889333414133890
        // 99889333414133890
        // 99889333414133888
        // 99889333414133890



        if ($_POST) {
            $record_allotment_id = $_POST['update_id'];
            $division_id = (new \yii\db\Query())->select('division_id')
                ->from('record_allotments')

                ->where("record_allotments.id = :id", ['id' => $record_allotment_id])
                ->scalar();
            // $query = RecordAllotments::find()->where("id=:id", ['id' => 120]);

            $model = RecordAllotments::findOne($record_allotment_id);

            $d_id = $model->division_id;
            $record_allotment = [
                'date_issued' => $model->date_issued,
                'document_recieve_id' => $model->document_recieve_id,
                'fund_cluster_code_id' => $model->fund_cluster_code_id,
                'financing_source_code_id' => $model->financing_source_code_id,
                'fund_category_and_classification_code_id' => $model->fund_category_and_classification_code_id,
                'authorization_code_id' => $model->authorization_code_id,
                'mfo_pap_code_id' => $model->mfo_pap_code_id,
                'fund_source_id' => $model->fund_source_id,
                'reporting_period' => $model->reporting_period,
                'serial_number' => $model->serial_number,
                'allotment_number' => $model->allotment_number,
                'valid_until' => $model->valid_until,
                'particulars' => $model->particulars,
                'fund_classification' => $model->fund_classification,
                'book_id' => $model->book_id,
                'responsibility_center_id' => $model->responsibility_center_id,
                'office_id' => $model->office_id,
                'division_id' => $division_id,
                'allotment_type_id' => $model->allotment_type_id,

            ];
            $record_allotment_entries = [];
            foreach ($model->recordAllotmentEntries as $val) {

                $record_allotment_entries[] = [
                    'chart_of_account_id' => $val->chart_of_account_id,
                    'amount' => $val->amount,
                    'object_code' => $val->object_code,
                    'lvl' => $val->lvl,
                ];
            }
            // echo "<pre>";
            // var_dump($record_allotment_entries);
            // echo "</pre>";   
            return  json_encode(['record_allotments' => $record_allotment, 'record_allotment_entries' => $record_allotment_entries]);
        }
    }


    public function getSerialNumber($reporting_period, $book_name, $update_id)
    {


        // $fund_cluster = FundClusterCode::findOne($fund_cluster_code_id);

        // $q = RecordAllotments::find()
        // ->orderBy(['id' => SORT_DESC])
        // ->one();

        // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
        $f = (new \yii\db\Query())
            ->select('serial_number')
            ->from('record_allotments');
        !empty($update_id) ? $f->where("id =:id", ['id' => $update_id]) : $f->orderBy("id DESC");
        $q = $f->one();
        if (!empty($q)) {
            $x = explode('-', $q['serial_number']);
            $y = 1;
            if (!empty($update_id)) {
                $y = 0;
            }
            $last_number = $x[3] + $y;
        } else {
            $last_number = 1;
        }

        $serial_number = $book_name . '-' . $reporting_period . '-' . $last_number;
        return  $serial_number;
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
            $excel->setActiveSheetIndexByName('allotment');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;
            // 
            $number_container = [];
            $transaction = \Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(3) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    $cells[] =   $cell->getValue();
                }
                $office = $cells[1];
                $division = $cells[2];
                $allotment_type = $cells[3];
                $mfo_pap_code_name = explode('-', $cells[4])[0];

                $uacs = $cells[5];
                $amount = $cells[6];
                $fund_cluster_code_name = $cells[7];
                $document_recieve_name = $cells[8];
                $financing_source_code_name = $cells[9];
                $authorization_code_name = $cells[10];
                $fund_source_name = $cells[11];
                $fund_classification_code_name = $cells[12];
                $book_name = $cells[13];
                $particular = $cells[14];
                $office_id = (new \yii\db\Query())
                    ->select('id')
                    ->from('office')
                    ->where('office_name =:office', ['office' => $office])
                    ->scalar();
                if (empty($office_id)) {
                    $transaction->rollBack();
                    var_dump($office);
                    return json_encode(['error' => "office Object Code Does Not Exist in row  $key"]);
                    die();
                }
                $division_id = (new \yii\db\Query())
                    ->select('id')
                    ->from('divisions')
                    ->where('division =:division', ['division' => $division])
                    ->scalar();
                if (empty($division_id)) {
                    $transaction->rollBack();
                    var_dump($division);
                    return json_encode(['error' => "division Object Code Does Not Exist in row  $key"]);
                    die();
                }
                $allotment_type_id = (new \yii\db\Query())
                    ->select('id')
                    ->from('allotment_type')
                    ->where('type =:allotment_type', ['allotment_type' => $allotment_type])
                    ->scalar();
                if (empty($allotment_type_id)) {
                    $allotment_type_id = (new \yii\db\Query())
                        ->select('id')
                        ->from('allotment_type')
                        ->where('type =:allotment_type', ['allotment_type' => $allotment_type])
                        ->createCommand()->getRawSql();
                    echo $allotment_type_id;
                    die();
                    $transaction->rollBack();
                    var_dump($allotment_type);
                    return json_encode(['error' => "Allotment type Object Code Does Not Exist in row  $key"]);
                    die();
                }
                $chart = (new \yii\db\Query())
                    ->select('id')
                    ->from('chart_of_accounts')
                    ->where('uacs =:uacs', ['uacs' => $uacs])
                    ->scalar();
                if (empty($chart)) {
                    $transaction->rollBack();
                    var_dump($chart);
                    return json_encode(['error' => "Chart of Account Object Code Does Not Exist in row  $key"]);
                    die();
                }

                $document_recieve = (new \yii\db\Query())
                    ->select('id')
                    ->from('document_recieve')
                    ->where("name LIKE :name", ['name' => $document_recieve_name])
                    ->one();
                if (empty($document_recieve)) {
                    $transaction->rollBack();
                    return json_encode(['error' => "Document Recieve  Does Not Exist in row  $key"]);
                    die();
                }
                $mfo_pap_code = (new \yii\db\Query())
                    ->select('id')
                    ->from('mfo_pap_code')
                    ->where("code = :code", ['code' => $mfo_pap_code_name])
                    ->scalar();
                if (empty($mfo_pap_code)) {
                    $transaction->rollBack();
                    return json_encode(['error' => "MFO/PAP Code  Does Not Exist in row $key $"]);
                    die();
                }
                $fund_cluster_code = (new \yii\db\Query())
                    ->select('id')
                    ->from('fund_cluster_code')
                    ->where('name LIKE :name', ['name' => $fund_cluster_code_name])
                    ->scalar();

                if (empty($fund_cluster_code)) {
                    $transaction->rollBack();
                    return json_encode(['error' => "Fund Cluster Code  Does Not Exist in row  $key"]);
                    die();
                }
                $financing_source_code = (new \yii\db\Query())
                    ->select('id')
                    ->from('financing_source_code')
                    ->where("name LIKE :name", ['name' => $financing_source_code_name])
                    ->scalar();
                if (empty($financing_source_code)) {
                    $transaction->rollBack();
                    return json_encode(['error' => "Fund Source Code  Does Not Exist in row  $key"]);
                    die();
                }
                $authorization_code = (new \yii\db\Query())
                    ->select('id')
                    ->from('authorization_code')
                    ->where("name LIKE :name", ['name' => $authorization_code_name])
                    ->scalar();
                if (empty($authorization_code)) {
                    $transaction->rollBack();
                    return json_encode(['error' => "Authorization Code  Does Not Exist $key"]);
                    die();
                }

                $fund_source = (new \yii\db\Query())
                    ->select('id')
                    ->from('fund_source')
                    ->where("name LIKE :name", ['name' => $fund_source_name])
                    ->scalar();
                if (empty($fund_source)) {
                    $transaction->rollBack();
                    return json_encode(['error' => 'Fund Source  Does Not Exist in row $key']);
                    die();
                }

                // $exist = array_search($group_number, array_column($number_container, 'no'));
                $fund_classification = '';



                $recordAllotment = new RecordAllotments();
                $recordAllotment->serial_number = Yii::$app->memem->getRaoudSerialNumber('2023-01', 5, '');

                $funding_code = $fund_cluster_code_name . $financing_source_code_name . $authorization_code_name . $fund_classification_code_name;

                $recordAllotment->reporting_period = '2023-01';
                $recordAllotment->fund_classification = $fund_classification;
                $recordAllotment->document_recieve_id = $document_recieve;
                $recordAllotment->particulars = $particular;
                $recordAllotment->mfo_pap_code_id = $mfo_pap_code;
                $recordAllotment->fund_cluster_code_id = $fund_cluster_code;
                $recordAllotment->financing_source_code_id = $financing_source_code;
                $recordAllotment->authorization_code_id = $authorization_code;
                $recordAllotment->fund_category_and_classification_code_id = 1;
                $recordAllotment->fund_source_id = $fund_source;
                $recordAllotment->office_id = $office_id;
                $recordAllotment->division_id = $division_id;
                $recordAllotment->allotment_type_id = $allotment_type_id;
                $recordAllotment->book_id = 1;
                $recordAllotment->fund_classification = 101;
                // $recordAllotment->funding_code = $funding_code;
                // $recordAllotment->responsibility_center_id = $responsibility_center['id'];
                // $recordAllotment->date_issued = $date_issued;
                // $recordAllotment->valid_until = $valid_until;


                if ($recordAllotment->save(false)) {
                    $record_allotment_id = $recordAllotment->id;
                    // $number_container[] =  ['id' =>  $recordAllotment->id, 'no' => $group_number];
                    $ra_entries = new RecordAllotmentEntries();
                    $ra_entries->record_allotment_id = $record_allotment_id;
                    $ra_entries->chart_of_account_id =  $chart;
                    $ra_entries->amount = $amount;
                    if ($ra_entries->save(false)) {
                    } else {
                        $transaction->rollBack();
                        return json_encode("error");
                    }
                }
                // }

                // $data[] = [

                //     'record_allotment_id' => $record_allotment_id,
                //     'chart_of_account_id' => $chart['id'],
                //     'amount' => $amount,
                // ];
            }
            // }
            $transaction->commit();
            // $column = [
            //     'record_allotment_id',
            //     'chart_of_account_id',
            //     'amount',

            // ];
            // $ja = Yii::$app->db->createCommand()->batchInsert('record_allotment_entries', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['error' => $data]);
            ob_clean();
            echo "<pre>";
            var_dump('success');
            echo "<pre>";
            return ob_get_clean();
        }
    }
    // private function checkItemTotal($adjustmentItems, $mafItems)
    // {
    //     $adjustmentItemsTtl = floatval(abs(array_sum(array_column($adjustmentItems, 'amount'))));
    //     $mafItemsTtl = floatval(array_sum(array_column($mafItems, 'amount')));
    //     return $mafItemsTtl !== $adjustmentItemsTtl ? 'Not Equal' : true;
    // }
    // public function actionCreateMaf()
    // {
    //     $model = new RecordAllotments();
    //     $model->isMaf = true;

    //     if ($model->load(Yii::$app->request->post()) || Yii::$app->request->post()) {

    //         try {
    //             $txn = Yii::$app->db->beginTransaction();
    //             $mafItems = Yii::$app->request->post('mafItems') ?? [];
    //             $adjustmentItems = Yii::$app->request->post('adjustmentItems') ?? [];

    //             $res =  $this->checkItemTotal($adjustmentItems, $mafItems);
    //             if ($res !== true) {
    //                 throw new ErrorException($res);
    //             }
    //             if (!$model->validate()) {
    //                 throw new ErrorException(json_encode($model->errors));
    //             }
    //             if (!$model->save(false)) {
    //                 throw new ErrorException('Model Save Failed');
    //             }
    //             $insMafItems = $model->insertMafItems($mafItems);
    //             if ($insMafItems !== true) {
    //                 throw new ErrorException($insMafItems);
    //             }
    //             $insAdjstItms = $model->insertAdjsutmentItems($adjustmentItems);
    //             if ($insAdjstItms !== true) {
    //                 throw new ErrorException($insAdjstItms);
    //             }
    //             $txn->commit();
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         } catch (ErrorException $e) {
    //             $txn->rollback();
    //             return $e->getMessage();
    //         }
    //     }

    //     return $this->render('maf_create', [
    //         'model' => $model,
    //     ]);
    // }
    // public function actionUpdateMaf($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) || Yii::$app->request->post()) {

    //         try {
    //             $txn = Yii::$app->db->beginTransaction();
    //             $mafItems = Yii::$app->request->post('mafItems') ?? [];
    //             $adjustmentItems = Yii::$app->request->post('adjustmentItems') ?? [];
    //             $res =  $this->checkItemTotal($adjustmentItems, $mafItems);
    //             if ($res !== true) {
    //                 throw new ErrorException($res);
    //             }
    //             if (!$model->validate()) {
    //                 throw new ErrorException(json_encode($model->errors));
    //             }
    //             if (!$model->save(false)) {
    //                 throw new ErrorException('Model Save Failed');
    //             }
    //             $insMafItems = $model->insertMafItems($mafItems);
    //             if ($insMafItems !== true) {
    //                 throw new ErrorException($insMafItems);
    //             }
    //             $insAdjstItms = $model->insertAdjsutmentItems($adjustmentItems);
    //             if ($insAdjstItms !== true) {
    //                 throw new ErrorException($insAdjstItms);
    //             }
    //             $txn->commit();
    //             return $this->redirect(['view', 'id' => $model->id]);
    //         } catch (ErrorException $e) {
    //             $txn->rollback();
    //             return $e->getMessage();
    //         }
    //     }
    //     return $this->render('maf_update', [
    //         'model' => $model,
    //     ]);
    // }
}
