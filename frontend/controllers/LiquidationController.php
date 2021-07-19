<?php

namespace frontend\controllers;

use Yii;
use app\models\Liquidation;
use app\models\LiquidataionSearch;
use app\models\LiquidationEntries;
use app\models\LiquidationEntriesSearch;
use app\models\LiquidationEntriesViewSearch;
use Exception;
use Mpdf\Tag\Em;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * LiquidationController implements the CRUD actions for Liquidation model.
 */
class LiquidationController extends Controller
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
                    're-align',
                    'add-advances',
                    'insert-liquidation',
                    'update-liquidation',
                    'cancel',
                    'import',
                    'view'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            're-align',
                            'add-advances',
                            'insert-liquidation',
                            'update-liquidation',
                            'cancel',
                            'import',
                        ],
                        'allow' => true,
                        'roles' => ['super-user', 'create_liquidation']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view'
                        ],
                        'allow' => true,
                        'roles' => ['super-user', 'liquidation']
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
     * Lists all Liquidation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LiquidationEntriesViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Liquidation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // $q = LiquidationEntries::findOne(($id));
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Liquidation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Liquidation();


        return $this->render('create', [
            'model' => $model,
            'update_type' => 'create'
        ]);
    }

    /**
     * Updates an existing Liquidation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $q  = LiquidationEntries::findOne($id);
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('update', [
            'model' => $model,
            'update_type' => 'update'
        ]);
    }

    /**
     * Deletes an existing Liquidation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        // return $this->redirect(['index']);
    }
    public function actionReAlign($id)
    {
        $q  = LiquidationEntries::findOne($id);
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('update', [
            'model' => $model,
            'update_type' => 're-align'
        ]);
    }

    /**
     * Finds the Liquidation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Liquidation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Liquidation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAddAdvances()
    {

        if ($_POST) {
            $selected = $_POST['selection'];
            $params = [];

            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'advances_entries.id', $selected], $params);

            $query = (new \yii\db\Query())
                ->select([
                    'advances_entries.*',
                    'advances.nft_number',
                    'advances.report_type',
                    'advances.province'
                ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->where("$sql", $params)
                ->all();

            return json_encode($query);
        }
    }
    public function actionInsertLiquidation()
    {
        if ($_POST) {
            $advances_id = !empty($_POST['advances_id']) ? $_POST['advances_id'] : '';
            // $payee_id = $_POST['payee'];
            $check_date = $_POST['check_date'];
            $check_number = $_POST['check_number'];
            // $particular = $_POST['particular'];
            $po_transaction_id = $_POST['transaction'];
            $chart_of_account = !empty($_POST['chart_of_account_id']) ? $_POST['chart_of_account_id'] : '';
            $withdrawal = !empty($_POST['withdrawal']) ? $_POST['withdrawal'] : '';
            $vat_nonvat = !empty($_POST['vat_nonvat']) ? $_POST['vat_nonvat'] : '';
            $liq_damages = !empty($_POST['liq_damages']) ? $_POST['liq_damages'] : '';
            $expanded_tax = !empty($_POST['ewt']) ? $_POST['ewt'] : '';
            $update_id = !empty($_POST['update_id']) ? $_POST['update_id'] : '';
            $type = !empty($_POST['update_type']) ? $_POST['update_type'] : '';
            $new_reporting_period = !empty($_POST['new_reporting_period']) ? $_POST['new_reporting_period'] : '';
            $reporting_period = $_POST['reporting_period'];
            $check_range = $_POST['check_range'];

            $check = (new \yii\db\Query())
                ->select([
                    'check_range.from',
                    'check_range.to',
                ])
                ->from('check_range')
                ->where("check_range.id = :id", ['id' => $check_range])
                ->one();
            if ($check_number >= $check['from'] && $check_number <= ['to']) {
            } else {
                return json_encode(['isSuccess' => false, 'error' => 'Check Number Not in Range']);
                die();
            }

            if (date('Y', strtotime($reporting_period)) < date('Y')) {
                return json_encode(['isSuccess' => false, 'error' => "Invalid Reporting Period"]);
            } else {
                $xyz = (new \yii\db\Query())
                    ->select('*')
                    ->from('liquidation_reporting_period')
                    ->where('liquidation_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                    ->andWhere('liquidation_reporting_period.province LIKE :province', ['province' => Yii::$app->user->identity->province])
                    ->one();
                if (!empty($xyz)) {
                    return json_encode(['isSuccess' => false, 'error' => " Reporting Period is Disabled"]);
                }
                // else
                // {
                //     return json_encode(['isSuccess' => false, 'error' => ]);
                // }

            }

            $liq_r_period = (new \yii\db\Query())
                ->select('reporting_period')
                ->from('liquidation_reporting_period')
                ->where('province LIKE :province', ['province' => Yii::$app->user->identity->province])
                ->all();
            $r = ArrayHelper::getColumn($liq_r_period, 'reporting_period');

            // if (in_array('2021-04', $r)) {
            //     ob_clean();
            //     echo "<pre>";
            //     var_dump('qwer');
            //     echo "</pre>";
            //     die();
            // }
            // ob_clean();
            // echo "<pre>";
            // var_dump($r);
            // echo "</pre>";
            // die();
            $transaction = Yii::$app->db->beginTransaction();
            $id = '';
            if (!empty($update_id)) {
                $liquidation = Liquidation::findOne($update_id);

                if ($liquidation->is_locked === 1) {
                    return json_encode(['isSuccess' => false, 'error' => "Liquidation is Disabled"]);
                }
                // foreach ($liquidation->liquidationEntries as $val) {
                //     $val->delete();
                // }
            } else {
                $liquidation = new Liquidation();
            }
            $liquidation->check_date = $check_date;
            // $liquidation->payee_id = $payee_id;
            $liquidation->check_number = $check_number;
            // $liquidation->particular = $particular;
            $liquidation->reporting_period = $reporting_period;
            $liquidation->po_transaction_id = $po_transaction_id;
            $liquidation->check_range_id = $check_range;
            $liquidation->dv_number = $this->getDvNumber($reporting_period);
            // list($withd) = sscanf(implode(explode(',', $withdrawal[0])), "%f");
            // list($vat) = sscanf(implode(explode(',', $vat_nonvat[0])), "%f");
            // list($e) = sscanf(implode(explode(',', $ewt[0])), "%f");
            // $liquidation->chart_of_account_id = $chart_of_account[0];
            // $liquidation->withdrawals = $withd;
            // $liquidation->vat_nonvat = $vat;
            // $liquidation->ewt_goods_services = $e;
            // $liquidation->advances_entries_id = $advances_id[0];
            $liquidation->reporting_period =  $reporting_period;

            try {
                if ($liquidation->validate()) {
                    if ($flag = $liquidation->save(false)) {
                        

                        if (!empty($advances_id)) {

                            foreach ($advances_id as $index => $val) {
                                list($withd) = sscanf(implode(explode(',', $withdrawal[$index])), "%f");
                                list($vat) = sscanf(implode(explode(',', $vat_nonvat[$index])), "%f");
                                list($e) = sscanf(implode(explode(',', $expanded_tax[$index])), "%f");
                                list($liq) = sscanf(implode(explode(',', $liq_damages[$index])), "%f");
                                $liq_entries = new LiquidationEntries();
                                $liq_entries->liquidation_id = $liquidation->id;
                                $liq_entries->chart_of_account_id = $chart_of_account[$index];
                                $liq_entries->advances_entries_id = $val;
                                $liq_entries->withdrawals = $withd;
                                $liq_entries->vat_nonvat = $vat;
                                $liq_entries->expanded_tax = $e;
                                $liq_entries->liquidation_damage = $liq;
                                $liq_entries->reporting_period = !empty($new_reporting_period) ? $new_reporting_period[$index] : $reporting_period;

                                if ($liq_entries->validate()) {
                                    // if (!in_array($liq_entries->reporting_period, $r)) {
                                    if ($liq_entries->save(false)) {
                                    }
                                    // }
                                } else {
                                    $transaction->rollBack();
                                    return json_encode(['isSuccess' => false, 'error' => $liq_entries]);
                                }
                            }
                        }
                        $transaction->commit();
                        return json_encode(['isSuccess' => true, 'id' => $liquidation->id]);
                    }
                    if ($flag) {

                        $transaction->commit();
                        return json_encode(['isSuccess' => true, 'id' => $liquidation->id]);
                    }
                } else {
                    $transaction->rollback();
                    return json_encode(['isSuccess' => false, 'error' => $liquidation->errors]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    public function actionUpdateLiquidation()
    {
        if ($_POST) {
            $id = $_POST['update_id'];

            $query = (new \yii\db\Query())
                ->select([
                    'liquidation_entries.advances_entries_id as id',
                    'advances_entries.fund_source',
                    'advances.nft_number',
                    'advances.province',
                    'advances.report_type',
                    'liquidation_entries.chart_of_account_id',
                    'liquidation_entries.expanded_tax',
                    'liquidation_entries.vat_nonvat',
                    'liquidation_entries.withdrawals',
                    'liquidation_entries.liquidation_damage',
                    'liquidation_entries.reporting_period'
                ])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'advances_entries', 'liquidation_entries.advances_entries_id = advances_entries.id')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->where("liquidation_entries.liquidation_id =:liquidation_id", ['liquidation_id' => $id])
                ->all();
            $liq = Yii::$app->db->createCommand("SELECT * FROM liquidation where id=:id")
                ->bindValue(':id', $id)
                ->queryOne();

            return json_encode(['entries' => $query, 'liquidation' => $liq]);
        }
    }

    public function getDvNumber($reporting_period)
    {
        // $reporting_period = '2021-02';
        // $q = (new \yii\db\Query())
        //     ->select('liquidation.dv_number')
        //     ->from('liquidation')
        //     ->orderBy("liquidation.id DESC")
        //     ->one();
        $q = Yii::$app->db->createCommand("SELECT substring_index(substring(dv_number, instr(dv_number, '-')+4), ' ', 1) as q 
        from liquidation
        
        ORDER BY q DESC  LIMIT 1")->queryScalar();
        // return $q;
        // die();
        $num = 0;

        if (!empty($q)) {
            // $x = explode('-', $q['dv_number']);
            $num = $q + 1;
        } else {
            $num = 1;
        }

        $string = substr(str_repeat(0, 4) . $num, -4);
        return $reporting_period . '-' . $string;
    }
    public function actionCancel()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $l = Liquidation::findOne($id);
            $l->is_cancelled = $l->is_cancelled === 0 ? 1 : 0;

            if ($l->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => $l->is_cancelled]);
            }
            return json_encode(['isSuccess' => false, 'cancelled' => false]);
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
            $excel->setActiveSheetIndexByName('Liquidation');
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

            $transaction = Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 0) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $check_date = date("Y-m-d", strtotime($cells[0]));
                    $check_number = trim($cells[1]);
                    $is_cancel =  $cells[2];
                    $reporting_period = date("Y-m", strtotime($cells[3]));
                    $fund_source = trim($cells[4]);
                    $payee = trim($cells[5]);
                    $particular = trim($cells[6]);
                    $object_code = trim($cells[7]);
                    $res_center = trim($cells[8]);
                    $withdrawal = trim($cells[9]);
                    $vat = trim($cells[10]);
                    $expanded = trim($cells[11]);

                    $chart_id = (new \yii\db\Query())
                        ->select("id")
                        ->from('chart_of_accounts')
                        ->where("chart_of_accounts.uacs =:uacs", ['uacs' => $object_code])
                        ->one();
                    $c_id = null;
                    if (!empty($chart_id)) {
                        $c_id = $chart_id['id'];
                    }
                    $payee_id = (new \yii\db\Query())
                        ->select('id')
                        ->from('payee')
                        ->where("payee.account_name LIKE :account_name", ['account_name' => $payee])
                        ->one();

                    $advances_entries_id = (new \yii\db\Query())
                        ->select("id")
                        ->from("advances_entries")
                        ->where("advances_entries.fund_source LIKE :fund_source", ['fund_source' => $fund_source])
                        ->one();
                    $liq_id = (new \yii\db\Query())
                        ->select('id')
                        ->from('liquidation')
                        ->where('liquidation.check_number =:check_number', ['check_number' => $check_number])
                        ->one();
                    if (empty($payee_id)) {
                        ob_clean();
                        echo "<pre>";
                        var_dump($key . " " . $payee);
                        echo "</pre>";
                        return ob_get_clean();
                    }

                    $liquidation_id = null;
                    if (empty($liq_id)) {
                        $liquidation = new Liquidation();
                        $liquidation->check_date = $check_date;
                        $liquidation->check_number = $check_number;
                        $liquidation->particular = $particular;
                        $liquidation->is_cancelled = $is_cancel;
                        $liquidation->payee_id = $payee_id['id'];
                        $liquidation->dv_number = $this->getDvNumber($reporting_period);
                        $liquidation->reporting_period = $reporting_period;
                        // $liquidation->advances_entries_id = $advances_entries_id['id'];
                        // $liquidation->chart_of_account_id = $advances_entries_id['id'];
                        // $liquidation->responsibility_center_id = $res_center;
                        if ($liquidation->save(false)) {
                            $liquidation_id = $liquidation->id;
                        }
                    } else {
                        $liquidation_id = $liq_id['id'];
                    }
                    $data[] = [
                        'liquidation_id' => $liquidation_id,
                        'chart_of_account_id' => $c_id,
                        'withdrawals' => $withdrawal,
                        'vat_nonvat' => $vat,
                        'expanded_tax' => $expanded,
                        'reporting_period' => $reporting_period,
                        'advances_entries_id' => $advances_entries_id['id']

                    ];
                }
            }

            $column = [
                'liquidation_id',
                'chart_of_account_id',
                'withdrawals',
                'vat_nonvat',
                'expanded_tax',
                'reporting_period',
                'advances_entries_id'
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('liquidation_entries', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            $transaction->commit();
            ob_clean();
            echo "<pre>";
            var_dump('success');
            echo "</pre>";
            return ob_get_clean();
        }
    }
}
