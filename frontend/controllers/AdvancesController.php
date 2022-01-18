<?php

namespace frontend\controllers;

use Yii;
use app\models\Advances;
use app\models\AdvancesEntries;
use app\models\AdvancesEntriesSearch;
use app\models\AdvancesSearch;
use app\models\AdvancesViewSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvancesController implements the CRUD actions for Advances model.
 */
class AdvancesController extends Controller
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
                    'logout',
                    'index',
                    'create',
                    'update',

                    'add-data',
                    'insert-advances',
                    'get-all-advances',
                    'import',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'create',
                            'update',
                            'view',
                            'add-data',
                            'insert-advances',
                            'get-all-advances',
                            'import',
                        ],
                        'allow' => true,
                        'roles' => ['super-user', 'create_advances']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['province', 'super-user']
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

    /**
     * Lists all Advances models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new AdvancesViewSearch();
        if (Yii::$app->user->identity->province !== 'ro_admin') {
            $searchModel->province = Yii::$app->user->identity->province;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $searchModel = new AdvancesSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advances model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // $x = AdvancesEntries::findOne($id);
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Advances model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advances();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Advances model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)

    {
        // $x = AdvancesEntries::findOne($id);
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Advances model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Advances model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advances the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advances::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAddData()
    {


        if ($_POST) {
            $selected = $_POST['selection'];
            $params = [];
            $sql = \Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'cash_disbursement.id', $selected], $params);
            //$sql = some_id NOT IN (:qp0, :qp1, :qp2)
            //$params = [':qp0'=>1, ':qp1'=>2, ':qp2'=>3]
            $query = Yii::$app->db->createCommand("SELECT 
           cash_disbursement.id as cash_disbursement_id,
           cash_disbursement.issuance_date,
           cash_disbursement.check_or_ada_no,
           cash_disbursement.mode_of_payment,
           cash_disbursement.ada_number,
           dv_aucs.dv_number,
           dv_aucs.particular,
            dv_entries.total_disbursed,
            payee.account_name as payee,
            '' as fund_source

            FROM cash_disbursement,dv_aucs,payee,
            (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,dv_aucs_entries.dv_aucs_id 
            from dv_aucs_entries GROUP BY dv_aucs_entries.dv_aucs_id) as dv_entries
           WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
           AND dv_aucs.payee_id =payee.id
           AND dv_aucs.id =dv_entries.dv_aucs_id


           AND $sql", $params)->queryAll();


            return json_encode($query);
        }
    }

    public function actionInsertAdvances()
    {
        if ($_POST) {
            $update_id = !empty($_POST['update_id']) ? $_POST['update_id'] : '';
            $entry_id = !empty($_POST['entry_id']) ? $_POST['entry_id'] : '';

            $cash_disbursement_id = $_POST['cash_disbursement_id'];
            $advances_type = '';
            $report_type = $_POST['report_type'];
            $province = $_POST['province'];
            $reporting_period = $_POST['reporting_period'];
            $sub_account1_id = $_POST['sub_account1'];
            $amount = $_POST['amount'];
            $fund_source = $_POST['fund_source'];
            $new_reporting_period = $_POST['new_reporting_period'];
            $fund_source_type = $_POST['fund_source_type'];

            $transaction = Yii::$app->db->beginTransaction();
            // return json_encode(['isSuccess' => false, 'error' => $fund_source_type]);


            if (!empty($update_id)) {
                $advances = Advances::findOne($update_id);

                // foreach ($advances->advancesEntries as $val) {
                //     $val->delete();
                // }
                // $target = Yii::$app->db->createCommand('SELECT * FROM advances_entries WHERE advances_id = :id')
                //     ->bindValue(':id', $update_id)
                //     ->queryAll();
                // // $SourceToTarget = array_map(
                // //     'unserialize',
                // //     array_diff(array_map('serialize', $q2), array_map('serialize', $q1))
                // // );
                $advances_entries_id = Yii::$app->db->createCommand('SELECT id FROM advances_entries WHERE advances_id = :id AND is_deleted = false')
                    ->bindValue(':id', $update_id)
                    ->queryAll();

                $compare = array_map(
                    'unserialize',
                    array_diff(
                        array_map('serialize', array_column($advances_entries_id, 'id')),
                        array_map('serialize', $entry_id)
                    )
                );
                if (!empty($compare)) {
                    foreach ($compare as $val) {

                        // Yii::$app->db->createCommand('DELETE FROM advances_entries where id= :id')
                        //     ->bindValue(':id', $val)
                        //     ->query();
                        $model = AdvancesEntries::findOne($val);
                        $model->is_deleted = 1;
                        if ($model->save(false)) {
                        }
                    }
                }

                // return json_encode(['isSuccess' => false, 'error' => $compare]);
            } else {
                $advances = new Advances();
                $advances->nft_number = $this->getNftNumber();
            }

            $advances->report_type = $advances_type;
            $advances->province = $province;
            $advances->reporting_period = $reporting_period;
            $sourceArray = [];
            if ($advances->validate()) {
                if ($flag = $advances->save(false)) {

                    foreach ($cash_disbursement_id as $index => $val) {
                        if (!empty($entry_id[$index])) {
                            $ad_entry =  AdvancesEntries::findOne($entry_id[$index]);
                        } else {
                            $ad_entry = new AdvancesEntries();
                            $ad_entry->advances_id = $advances->id;
                            $ad_entry->cash_disbursement_id = $cash_disbursement_id[$index];
                        }
                        $ad_entry->fund_source_type = $fund_source_type[$index];
                        $ad_entry->object_code = $sub_account1_id[$index];
                        $ad_entry->fund_source = trim($fund_source[$index], " \r\n\t");
                        $ad_entry->reporting_period = $new_reporting_period[$index];
                        $ad_entry->amount = floatval(preg_replace('/[^\d.]/', '', $amount[$index]));
                        $ad_entry->book_id = $ad_entry->cashDisbursement->book->id;
                        $ad_entry->advances_type = $advances_type;
                        $ad_entry->report_type = $report_type[$index];


                        if ($ad_entry->validate()) {

                            if ($ad_entry->save(false)) {
                            }
                        } else {
                            return json_encode(['isSuccess' => false, 'error' => $ad_entry->errors]);
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return json_encode(['isSuccess' => true, 'id' => $advances->id]);
                }
            } else {

                return json_encode(['isSuccess' => false, 'error' => $advances->errors]);
            }
        }
    }

    public function actionUpdateAdvances()
    {
        if ($_POST) {
            $update_id = $_POST['update_id'];

            $query = (new \yii\db\Query())
                ->select([
                    'advances_entries.id as entry_id',
                    'dv_aucs.dv_number',
                    'cash_disbursement.id as cash_disbursement_id',
                    'cash_disbursement.mode_of_payment',
                    'cash_disbursement.check_or_ada_no',
                    'cash_disbursement.ada_number',
                    'cash_disbursement.issuance_date',
                    'payee.account_name as payee',
                    'dv_aucs.particular',
                    'advances.report_type',
                    'advances_entries.report_type as entry_report_type',
                    'advances.reporting_period',
                    'advances.province',
                    'advances_entries.amount',
                    'advances_entries.object_code',
                    'advances_entries.fund_source',
                    'advances_entries.fund_source_type',
                    'advances_entries.reporting_period as entry_reporting_period'
                ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', 'cash_disbursement', 'advances_entries.cash_disbursement_id = cash_disbursement.id')
                ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
                ->join('LEFT JOIN', 'payee', 'dv_aucs.payee_id = payee.id')
                ->where('advances_entries.advances_id =:advances_id', ['advances_id' => $update_id])
                ->andWhere('advances_entries.is_deleted != 1')
                ->all();

            return json_encode($query);
        }
    }
    public function getNftNumber()
    {
        // $q = Advances::find()->orderBy('id DESC')->one();
        $q = Yii::$app->db->createCommand("SELECT substring_index(nft_number, '-', -1) as q 
        from advances 
        WHERE 
        nft_number NOT LIKE 'S%'
        AND nft_number NOT LIKE 'a%'
        AND nft_number NOT LIKE 'P%'
        AND nft_number NOT LIKE 'R%'
        ORDER BY q DESC
        LIMIT 1")->queryScalar();

        $num = 0;
        if (!empty($q)) {
            // $x = explode('-', $q->nft_number);
            $num = (int) $q + 1;
        } else {
            $num = 1;
        }

        $y = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $y .= 0;
        }
        $y .= $num;
        return date('Y') . '-' . $y;
    }

    public function actionGetAllAdvances()
    {
        $res = (new \yii\db\Query())
            ->select("*")
            ->from('advances')
            ->all();
        return json_encode($res);
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
            $excel->setActiveSheetIndexByName('Advances');
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
            $advances_id = [];
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 7) {
                        $cells[] = $cell->getValue();
                    } else if (

                        $y === 4 ||
                        $y === 13

                    ) {
                        $cells[] = $cell->getCalculatedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }

                    $y++;
                }
                if ($key < 260) {

                    $nft_number = trim($cells[0]);
                    $check_number = trim($cells[1]);
                    $province =  $cells[2];
                    $reporting_period = date("Y-m", strtotime($cells[3]));
                    $fund_source_type = $cells[4];
                    $fund_source = trim($cells[5]);
                    $advance_type = trim($cells[6]);
                    $sl_object_code = trim($cells[7]);
                    $amount = $cells[8];
                    $report_type = trim($cells[11]);
                    // $division = $cells[28];
                    if (empty($nft_number)) {
                        $nft_number = $province;
                    }
                    $sl_id = (new \yii\db\Query())
                        ->select("object_code")
                        ->from('accounting_codes')
                        ->where("accounting_codes.object_code =:object_code", ['object_code' => $sl_object_code])
                        ->one();

                    $advances_id = null;
                    $cd_id = null;
                    $q = (new \yii\db\Query())
                        ->select("nft_number,id")
                        ->from("advances")
                        ->where("advances.nft_number =:nft_number", ['nft_number' => $nft_number])
                        ->one();
                    $cd = (new \yii\db\Query())
                        ->select('id')
                        ->from('cash_disbursement')
                        ->where('cash_disbursement.check_or_ada_no =:check', ['check' => $check_number])
                        ->one();

                    if (empty($sl_id)) {
                        return json_encode("sl not exist $sl_object_code  row $key");
                    }
                    if (!empty($cd)) {
                        $cd_id = $cd['id'];
                    }
                    if (empty($q)) {
                        $advances = new Advances();
                        $advances->nft_number = $nft_number;
                        $advances->reporting_period = $reporting_period;
                        $advances->report_type = $advance_type;

                        $advances->province = $province;
                        if ($advances->save(false)) {
                            $advances_id = $advances->id;
                        }
                    } else {
                        $advances_id = $q['id'];
                    }
                    $data[] = [
                        'advances_id' => $advances_id,
                        'cash_disbursement_id' => $cd_id,
                        // 'sub_account1_id' => $sl_id['id'],
                        'amount' =>  $amount,
                        'object_code' => $sl_id['object_code'],
                        'fund_source' => $fund_source,
                        'reporting_period' => $reporting_period,
                        'report_type' => $report_type,
                        'advances_type' => $advance_type,
                        'fund_source_type' => $fund_source_type

                    ];
                }
            }

            $column = [
                'advances_id',
                'cash_disbursement_id',
                // 'sub_account1_id',
                'amount',
                'object_code',
                'fund_source',
                'reporting_period',
                'report_type',
                'advances_type',
                'fund_source_type'

            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('advances_entries', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            return ob_get_clean();
        }
    }
    public function actionDisable()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $model = AdvancesEntries::findOne($id);
            $model->is_deleted = $model->is_deleted === 0 ? 10 : 0;
            if ($model->save(false)) {
              return json_encode(['isSuccess'=>true]);
            }
        }
    }
}

// $var = floatval(preg_replace('/[^\d.]/', '', $var));
