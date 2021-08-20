<?php

namespace frontend\controllers;

use Yii;
use app\models\Liquidation;
use app\models\LiquidataionSearch;
use app\models\LiquidationEntries;
use app\models\LiquidationEntriesSearch;
use app\models\LiquidationEntriesViewSearch;
use ErrorException;
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

    // public function beforeAction($action)
    // {
    //     $formTokenName = \Yii::$app->params['form_token_param'];

    //     if ($formTokenValue = \Yii::$app->request->post($formTokenName)) {
    //         $sessionTokenValue = \Yii::$app->session->get($formTokenName);

    //         if ($formTokenValue != $sessionTokenValue) {
    //             throw new \yii\web\HttpException(400, 'The form token could not be verified.');
    //         }

    //         \Yii::$app->session->remove($formTokenName);
    //     }

    //     return parent::beforeAction($action);
    // }
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
        $session = Yii::$app->session;
        $session->set('form_token', md5(uniqid()));

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
            $session = Yii::$app->session;
            $token = Yii::$app->session->get('form_token');
            // if ($token !== $_POST['token']) {
            //     return json_encode(['isSuccess' => false, 'error' => $token]);
            //     die();
            // }


            // destroys all data registered to a session.
            // $session->destroy();

            // return json_encode(['isSuccess' => false, 'error' =>  $session->get('form_token')]);


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
            $dv_number = $_POST['dv_number'];
            $province = Yii::$app->user->identity->province;


            // if (date('m', strtotime($check_date)) >= 7) {
            //     // return json_encode(['isSuccess' => false, 'error' => 'Check Number Not in Range']);
            //     // die();
            //     if (empty($check_range)) {
            //         return json_encode(['isSuccess' => false, 'error' => 'Check Number Is Required']);
            //     }
            //     $check = (new \yii\db\Query())
            //         ->select([
            //             'check_range.from',
            //             'check_range.to',
            //         ])
            //         ->from('check_range')
            //         ->where("check_range.id = :id", ['id' => $check_range])
            //         ->one();
            //     if ($check_number >= $check['from'] && $check_number <= ['to']) {
            //     } else {
            //         return json_encode(['isSuccess' => false, 'error' => 'Check Number Not in Range']);
            //     }
            // }

            if (strtotime($check_date) > strtotime('2021-06-20')) {
                if (empty($po_transaction_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Transaction is Required']);
                }
                if (empty($check_range)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Check Number Is Required']);
                }
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
                }
                // return json_encode(['isSuccess' => false, 'error' => 'less']);
            }

            // return json_encode(['isSuccess' => false, 'error' => 'qweqwr']);
            if (date('Y', strtotime($reporting_period)) < date('Y')) {
                return json_encode(['isSuccess' => false, 'error' => "Invalid Reporting Period"]);
            } else {

                $xyz = (new \yii\db\Query())
                    ->select('*')
                    ->from('liquidation_reporting_period')
                    ->where('liquidation_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                    ->andWhere('liquidation_reporting_period.province LIKE :province', ['province' => $province])
                    ->one();

                if (!empty($update_id)) {
                    $liq = Liquidation::findOne($update_id);

                    if ($reporting_period !== $liq->reporting_period) {
                        if (!empty($xyz)) {
                            return json_encode(['isSuccess' => false, 'error' => " Reporting Period is Disabled"]);
                        }
                    }
                } else {
                    if (!empty($xyz)) {
                        return json_encode(['isSuccess' => false, 'error' => " Reporting Period is Disabled"]);
                    }
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
            $check_number_exist = Yii::$app->db->createCommand("
            SELECT EXISTS(SELECT * FROM liquidation WHERE check_number = :check_number)
            ")
                ->bindValue(':check_number', $check_number)
                ->queryScalar();


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
            $is_realign = null;
            if (!empty($update_id)) {
                $liquidation = Liquidation::findOne($update_id);


                if ($liquidation->reporting_period !== $reporting_period) {

                    $check_r_period = (new \yii\db\Query())
                        ->select('*')
                        ->from('liquidation_reporting_period')
                        ->where('liquidation_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $liquidation->reporting_period])
                        ->andWhere('liquidation_reporting_period.province LIKE :province', ['province' => $province])
                        ->one();
                    if (empty($check_r_period)) {

                        Yii::$app->db->createCommand(
                            "UPDATE liquidation_entries SET reporting_period = :reporting_period WHERE
                                liquidation_id = :liquidation_id
                                AND is_realign = 0
                                "
                        )
                            ->bindValue(':reporting_period', $reporting_period)
                            ->bindValue(':liquidation_id', $liquidation->id)
                            ->query();
                    } else {
                        return json_encode(['isSuccess' => false, 'error' => "Cannot Update "]);
                    }

                    if ($liquidation->is_locked === 1) {
                        return json_encode(['isSuccess' => false, 'error' => "Liquidation is Disabled"]);
                    }
                }

                $is_realign = true;
                // $x = explode('-', $liquidation->dv_number);
                // $x[1] = date('Y', strtotime($reporting_period));
                // $x[2] = date('m', strtotime($reporting_period));
                // $liquidation->dv_number = implode('-', $x);
            } else {
                $liquidation = new Liquidation();
                // $liquidation->dv_number = $this->getDvNumber($reporting_period);



                // if (intval($check_number_exist) === 1) {
                //     return json_encode(['isSuccess' => false, 'error' =>  'Check Number Already Inserted']);
                //     die();
                // }
            }
            $liquidation->check_date = $check_date;
            // $liquidation->payee_id = $payee_id;
            $liquidation->check_number = $check_number;
            // $liquidation->particular = $particular;
            $liquidation->reporting_period = $reporting_period;
            $liquidation->po_transaction_id = $po_transaction_id;
            $liquidation->check_range_id = $check_range;

            // TEMPORRARY RA NI SA AUG E CHANGE RA NI
            $liquidation->dv_number = $dv_number;


            // list($withd) = sscanf(implode(explode(',', $withdrawal[0])), "%f");
            // list($vat) = sscanf(implode(explode(',', $vat_nonvat[0])), "%f");
            // list($e) = sscanf(implode(explode(',', $ewt[0])), "%f");
            // $liquidation->chart_of_account_id = $chart_of_account[0];
            // $liquidation->withdrawals = $withd;
            // $liquidation->vat_nonvat = $vat;
            // $liquidation->ewt_goods_services = $e;
            // $liquidation->advances_entries_id = $advances_id[0];
            $liquidation->reporting_period =  $reporting_period;
            $liquidation->province = $province;

            try {
                if ($liquidation->validate()) {
                    if ($flag = $liquidation->save(false)) {


                        if (!empty($advances_id)) {

                            foreach ($advances_id as $index => $val) {

                                if (!empty($new_reporting_period)) {
                                    $r_period = date('Y-m', strtotime($new_reporting_period[$index]));
                                    $line = $index + 1;
                                    if (date('Y', strtotime($r_period)) < date('Y')) {
                                        return json_encode(['isSuccess' => false, 'error' => "Invalid Reporting Period On Line $line"]);
                                    } else {
                                        $qqq = (new \yii\db\Query())
                                            ->select('*')
                                            ->from('liquidation_reporting_period')
                                            ->where('liquidation_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $r_period])
                                            ->andWhere('liquidation_reporting_period.province LIKE :province', ['province' => $province])
                                            ->one();
                                        if (!empty($qqq)) {
                                            return json_encode(['isSuccess' => false, 'error' => " Reporting Period is Disabled in Line $line "]);
                                        }
                                    }
                                } else {
                                    $r_period = $reporting_period;
                                }
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
                                $liq_entries->reporting_period = $r_period;
                                $liq_entries->is_realign = $is_realign;

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
                        $session->set('form_token', md5(uniqid()));
                        return json_encode(['isSuccess' => true, 'id' => $liquidation->id]);
                    }
                    if ($flag) {

                        $transaction->commit();
                        $session->set('form_token', md5(uniqid()));
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
        $province = Yii::$app->user->identity->province;
        $q = Yii::$app->db->createCommand("SELECT substring_index(substring(dv_number, instr(dv_number, '-')+1), '-', -1) as q 
        from liquidation
        WHERE liquidation.province = :province
        ORDER BY q DESC  LIMIT 1")
            ->bindValue(':province', $province)
            ->queryScalar();
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
        return strtoupper($province) . '-' . $reporting_period . '-' . $string;
    }
    public function actionCancel()
    {
        if ($_POST) {
            $id = $_POST['cancelId'];
            $reporting_period = date('Y-m', strtotime($_POST['reporting_period']));
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $l = Liquidation::findOne($id);
                // $l->is_cancelled = $l->is_cancelled === 0 ? 1 : 0;

                if ($flag = $l->save(false)) {
                    if ($l->is_cancelled === 0) {

                        $liquidation = new Liquidation();
                        $liquidation->check_date = $l->check_date;
                        $liquidation->check_number = $l->check_number;
                        $liquidation->reporting_period = $reporting_period;
                        $liquidation->po_transaction_id = $l->po_transaction_id;
                        $liquidation->check_range_id = $l->check_range_id;
                        $liquidation->dv_number = $l->dv_number;
                        $liquidation->province = $l->province;
                        // $liquidation->is_cancelled = 1;
                        if ($liquidation->save(false)) {
                            foreach ($l->liquidationEntries as $val) {
                                $liq_entries = new LiquidationEntries();
                                $liq_entries->liquidation_id = $liquidation->id;
                                $liq_entries->chart_of_account_id = $val->chart_of_account_id;
                                $liq_entries->advances_entries_id = $val->advances_entries_id;
                                $liq_entries->withdrawals = 0 - $val->withdrawals;
                                $liq_entries->vat_nonvat = 0 - $val->vat_nonvat;
                                $liq_entries->expanded_tax = 0 - $val->expanded_tax;
                                $liq_entries->liquidation_damage = 0 - $val->liquidation_damage;
                                $liq_entries->reporting_period = $reporting_period;
                                if ($liq_entries->save(false)) {
                                }
                            }
                        }
                    } else {
                        $transaction->rollBack();
                        return json_encode(['isSuccess' => false, 'error' => 'save error in liquidation entries']);
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return json_encode(['isSuccess' => true, 'error' => 'none']);
                }
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
            }
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
                    if ($y === 1) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $province = $cells[0];
                    $check_date = date("Y-m-d", strtotime($cells[1]));
                    $check_number = trim($cells[2]);

                    $is_cancel =  $cells[3];
                    $dv_number = $cells[4];
                    $reporting_period = date("Y-m", strtotime($cells[5]));
                    $fund_source = trim($cells[6]);
                    $payee = trim($cells[7]);
                    $particular = trim($cells[8]);
                    $object_code = trim($cells[9]);
                    // $res_center = trim($cells[8]);
                    $withdrawal = trim($cells[12]);
                    $vat = trim($cells[13]);
                    $expanded = trim($cells[14]);
                    $advances_entries_id = null;
                    $chart_id = (new \yii\db\Query())
                        ->select("id")
                        ->from('chart_of_accounts')
                        ->where("chart_of_accounts.uacs =:uacs", ['uacs' => $object_code])
                        ->one();
                    $c_id = null;
                    if (!empty($chart_id)) {
                        $c_id = $chart_id['id'];
                    }
                    // $payee_id = (new \yii\db\Query())
                    //     ->select('id')
                    //     ->from('payee')
                    //     ->where("payee.account_name LIKE :account_name", ['account_name' => $payee])
                    //     ->one();
                    if (strtolower($is_cancel) === 'good') {
                        $advances_entries_id = (new \yii\db\Query())
                            ->select("id")
                            ->from("advances_entries")
                            ->where("advances_entries.fund_source LIKE :fund_source", ['fund_source' => $fund_source])
                            ->one();
                        if (empty($advances_entries_id)) {
                            ob_clean();
                            echo "<pre>";
                            var_dump($key . " yawa" . $fund_source);
                            echo "</pre>";
                            return ob_get_clean();
                        }
                    } else {
                        $advances_entries_id['id'] = null;
                    }

                    $liq_id = (new \yii\db\Query())
                        ->select('id')
                        ->from('liquidation')
                        ->where('liquidation.check_number =:check_number', ['check_number' => $check_number])
                        ->one();


                    $liquidation_id = null;
                    if (empty($liq_id)) {
                        $liquidation = new Liquidation();
                        $liquidation->province = $province;
                        $liquidation->check_date = $check_date;
                        $liquidation->check_number = $check_number;
                        $liquidation->particular = $particular;
                        $liquidation->is_cancelled = strtolower($is_cancel) === 'good' ? 0 : 1;
                        $liquidation->payee = $payee;
                        $liquidation->dv_number = $dv_number;
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

    public function actionDrafts()
    {
        $searchModel = new LiquidationEntriesViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('liquidation_drafts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
