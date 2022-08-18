<?php

namespace frontend\controllers;

use app\models\AdvancesEntriesForLiquidationSearch;
use app\models\CancelledChecksView;
use app\models\CancelledChecksViewSearch;
use Yii;
use app\models\Liquidation;
use app\models\LiquidataionSearch;
use app\models\LiquidationEntries;
use app\models\LiquidationEntriesSearch;
use app\models\LiquidationEntriesViewSearch;
use aryelds\sweetalert\SweetAlert;
use DateTime;
use ErrorException;
use Exception;
use kartik\form\ActiveForm;
use Mpdf\Tag\Em;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

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
                    'view',
                    'create',
                    'update',
                    'delete',
                    're-align',
                    'add-advances',
                    'insert-liquidation',
                    'update-liquidation',
                    'cancel',
                    'import',
                    'exclude-raaf',
                    'drafts',
                    'cancelled-check-index',
                    'cancelled-check-update',
                    'cancelled-form',
                    'update-uacs',
                    'export',
                    'add-link',
                ],
                'rules' => [
                    [
                        'actions' => [

                            'create',
                            'update',
                            'delete',
                            're-align',
                            'add-advances',
                            'insert-liquidation',
                            'update-liquidation',
                            'cancel',
                            'import',
                            'cancelled-check-index',
                            'cancelled-Check-update',
                            'cancelled-form',
                            'view'
                        ],
                        'allow' => true,
                        'roles' => ['super-user', 'create_liquidation']
                    ],
                    [
                        'actions' => [
                            'index',
                            'exclude-raaf',
                            'drafts',
                            'cancelled-check-index',
                            'cancelled-check-update',
                            'cancelled-form',
                            'update-uacs',
                            'export',
                            'add-link',
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],

                    [
                        'actions' => [

                            'create',
                            'update',
                            'delete',
                            're-align',
                            'add-advances',
                            'insert-liquidation',
                            'update-liquidation',
                            'cancel',
                            'import',
                            'cancelled-check-index',
                            'cancelled-Check-update',
                            'cancelled-form',
                            'view'
                        ],
                        'allow' => true,
                        'roles' => ['liquidation']
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
    public function insertItems(
        $liquidation_id = null,
        $advances_entries_id = [],
        $new_reporting_period = [],
        $object_codes = [],
        $liq_damages = [],
        $withdrawal = [],
        $vat_nonvat = [],
        $expanded_tax = [],
        $bank_account_id
    ) {



        $province = Yii::$app->user->identity->province;
        foreach ($advances_entries_id as $key => $val) {

            $withdrawals =  !empty($withdrawal[$key]) ? $withdrawal[$key] : 0;
            $reporting_period = gettype($new_reporting_period) === 'array' ? $new_reporting_period[$key] : $new_reporting_period;
            $advances_entries_balance = Yii::$app->db->createCommand("SELECT 
            advances_entries_for_liquidation.balance,
            advances_entries_for_liquidation.fund_source

             FROM advances_entries_for_liquidation
            WHERE advances_entries_for_liquidation.id = :id")
                ->bindValue(':id', $val)
                ->queryOne();
            $partial_balance = floatVal($advances_entries_balance['balance']) - floatval($withdrawals);

            if ($partial_balance < 0) {
                return "Cannot Insert. Advances {$advances_entries_balance['fund_source']} Balance is not enough";
            }
            if (!$this->validateReportingPeriod($reporting_period, $province, $bank_account_id)) {
                return "Reporting is Disable in  Advances {$advances_entries_balance['fund_source']} line";
            };



            $liquidation_entries = new LiquidationEntries();
            $liquidation_entries->liquidation_id = $liquidation_id;
            $liquidation_entries->advances_entries_id = $val;
            $liquidation_entries->withdrawals = $withdrawals;
            $liquidation_entries->vat_nonvat = !empty($vat_nonvat[$key]) ? $vat_nonvat[$key] : 0;
            $liquidation_entries->expanded_tax = !empty($expanded_tax[$key]) ? $expanded_tax[$key] : 0;
            $liquidation_entries->reporting_period = $reporting_period;
            $liquidation_entries->liquidation_damage = !empty($liq_damages[$key]) ? $liq_damages[$key] : 0;
            $liquidation_entries->new_object_code = $object_codes[$key];
            $liquidation_entries->chart_of_account_id = null;

            if ($liquidation_entries->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function validateCheckNumber($check_number = '', $check_range_id = '')
    {


        if ($check_number === '' || empty($check_range_id)) {

            return false;
        }


        $check = (new \yii\db\Query())
            ->select([
                'check_range.from',
                'check_range.to',
            ])
            ->from('check_range')
            ->where("check_range.id = :id", ['id' => $check_range_id])
            ->one();

        if ($check_number >= $check['from'] && $check_number <= $check['to']) {
        } else {
            return false;
        }
        return true;
    }
    public function validateReportingPeriod($reporting_period = '', $province = '', $bank_account_id = '')
    {
        if (empty($bank_account_id)) {
            return 'No Bank Account in Selected Check Range';
        }
        if (empty($reporting_period) || empty($province) || empty($bank_account_id)) {
            return false;
        }
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('liquidation_reporting_period')
            ->where('liquidation_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
            ->andWhere('liquidation_reporting_period.province LIKE :province', ['province' => $province])
            ->andWhere('liquidation_reporting_period.bank_account_id = :bank_account_id', ['bank_account_id' => $bank_account_id])
            ->one();
        if (!empty($query)) {
            return 'Reporting Period is Disabled';
        } else {
            return true;
        }
    }
    public function actionCreate()
    {

        $model = new Liquidation();

        $searchModel = new AdvancesEntriesForLiquidationSearch();
        if (\Yii::$app->user->identity->province !== 'ro_admin') {
            $searchModel->province = \Yii::$app->user->identity->province;
            // echo \Yii::$app->user->identity->province;
        }

        $check_range_id = '';
        if (!empty($_POST['filter_advances']) && $_POST) {

            $check_range_id = $_POST['check_range_id'];
            // echo $check_range_id;
            $bank_account_id = Yii::$app->db->createCommand("SELECT bank_account_id FROM check_range where id=:id")
                ->bindValue(':id', $check_range_id)
                ->queryScalar();
            $searchModel->bank_account_id = $bank_account_id;
        } else if ($_POST) {
            $transaction = Yii::$app->db->beginTransaction();
            $advances_entries_id = !empty($_POST['advances_entries_id']) ? $_POST['advances_entries_id'] : [];
            $new_reporting_period = !empty($_POST['new_reporting_period']) ? $_POST['new_reporting_period'] : [];
            $object_codes = !empty($_POST['object_codes']) ? $_POST['object_codes'] : [];
            $liq_damages = !empty($_POST['liq_damages']) ? $_POST['liq_damages'] : [];
            $withdrawal = !empty($_POST['withdrawal']) ? $_POST['withdrawal'] : [];
            $vat_nonvat = !empty($_POST['vat_nonvat']) ? $_POST['vat_nonvat'] : [];
            $expanded_tax = !empty($_POST['expanded_tax']) ? $_POST['expanded_tax'] : [];
            $reporting_period = $_POST['reporting_period'];
            $check_date = $_POST['check_date'];
            $check_range_id = $_POST['check_range_id'];
            $check_number = $_POST['check_number'];
            $po_transaction_id = $_POST['po_transaction_id'];
            $province = Yii::$app->user->identity->province;



            $model->dv_number = $this->getDvNumber($reporting_period);
            $model->reporting_period = $reporting_period;
            $model->province = $province;
            $model->check_date = $check_date;
            $model->check_range_id = $check_range_id;
            $model->check_number = $check_number;
            $model->po_transaction_id = $po_transaction_id;
            $model->is_cancelled = 0;
            $model->status = 'at_po';

            try {
                $flag = true;
                if ($model->validate()) {
                    if (!$this->validateCheckNumber($check_number, $check_range_id)) {
                        $transaction->rollBack();
                        return json_encode(['check_error' => 'Check Number Not in Range']);
                    }
                    if (empty($advances_entries_id)) {
                        $transaction->rollBack();
                        return json_encode(['check_error' => 'No Entry']);
                    }

                    $validateReportingPeriod = $this->validateReportingPeriod($reporting_period, $province, $model->checkRange->bank_account_id);
                    if ($validateReportingPeriod !== true) {
                        $transaction->rollBack();
                        return json_encode(['check_error' => $validateReportingPeriod]);
                    }
                    // else if ($validateReportingPeriod === 'empty') {
                    //     $transaction->rollBack();
                    //     return json_encode(['check_error' => 'No Reporting Period']);
                    // }


                    if ($model->save(false)) {

                        $flag = $this->insertItems(
                            $model->id,
                            $advances_entries_id,
                            $model->reporting_period,
                            $object_codes,
                            $liq_damages,
                            $withdrawal,
                            $vat_nonvat,
                            $expanded_tax,
                            $model->checkRange->bank_account_id
                        );
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['form_error' => $model->errors]);
                }
                if ($flag) {
                    // return 'sucess';
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode(['check_error' => $flag]);
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination = ['pageSize' => 10];

        return $this->render('create', [
            'model' => $model,
            'update_type' => 'create',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
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

        $model = $this->findModel($id);

        $searchModel = new AdvancesEntriesForLiquidationSearch();
        if (\Yii::$app->user->identity->province !== 'ro_admin') {
            $searchModel->province = \Yii::$app->user->identity->province;
            // echo \Yii::$app->user->identity->province;
        }

        $check_range_id = '';
        if (!empty($_POST['filter_advances']) && $_POST) {
            $check_range_id = $_POST['check_range_id'];
            // echo $check_range_id;
            $bank_account_id = Yii::$app->db->createCommand("SELECT bank_account_id FROM check_range where id=:id")
                ->bindValue(':id', $check_range_id)
                ->queryScalar();
            $searchModel->bank_account_id = $bank_account_id;
        } else if ($_POST) {
            $transaction = Yii::$app->db->beginTransaction();
            $advances_entries_id = !empty($_POST['advances_entries_id']) ? $_POST['advances_entries_id'] : [];
            $new_reporting_period = !empty($_POST['new_reporting_period']) ? $_POST['new_reporting_period'] : [];
            $object_codes = !empty($_POST['object_codes']) ? $_POST['object_codes'] : [];
            $liq_damages = !empty($_POST['liq_damages']) ? $_POST['liq_damages'] : [];
            $withdrawal = !empty($_POST['withdrawal']) ? $_POST['withdrawal'] : [];
            $vat_nonvat = !empty($_POST['vat_nonvat']) ? $_POST['vat_nonvat'] : [];
            $expanded_tax = !empty($_POST['expanded_tax']) ? $_POST['expanded_tax'] : [];

            $reporting_period = $_POST['reporting_period'];
            $check_date = $_POST['check_date'];
            $check_range_id = $_POST['check_range_id'];
            $check_number = $_POST['check_number'];
            $po_transaction_id = $_POST['po_transaction_id'];
            $province = Yii::$app->user->identity->province;

            // if ($province)
            // $model->province = $province;
            $model->check_date = $check_date;
            $model->check_range_id = $check_range_id;
            $model->check_number = $check_number;
            $model->po_transaction_id = $po_transaction_id;
            if ($model->reporting_period !== $reporting_period) {

                $validateReportingPeriod = $this->validateReportingPeriod($reporting_period, $province, $model->checkRange->bank_account_id);
                if ($validateReportingPeriod === false) {
                    $transaction->rollBack();
                    return json_encode(['check_error' => 'Reporting Period is Disabled']);
                } else if ($validateReportingPeriod === 'empty') {
                    $transaction->rollBack();
                    return json_encode(['check_error' => 'No Reporting Period']);
                }
            }

            $model->reporting_period = $reporting_period;
            try {
                $flag = true;
                if ($model->validate()) {
                    if (strtotime($model->reporting_period) >= strtotime('2021-10')) {
                        if (!$this->validateCheckNumber($check_number, $check_range_id)) {
                            $transaction->rollBack();
                            return json_encode(['check_error' => 'Check Number Not in Range']);
                        }
                    }


                    if ($model->save(false)) {

                        $flag = $this->insertItems(
                            $model->id,
                            $advances_entries_id,
                            $new_reporting_period,
                            $object_codes,
                            $liq_damages,
                            $withdrawal,
                            $vat_nonvat,
                            $expanded_tax,
                            $model->checkRange->bank_account_id

                        );
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['form_error' => $model->errors]);
                }

                if ($flag === true) {
                    // return 'sucess';
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode(['check_error' => $flag]);
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        // else{
        //     return 'qqqq';
        // }



        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination = ['pageSize' => 10];

        return $this->render('update', [
            'model' => $model,
            'update_type' => 'update',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
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
    // public function actionReAlign($id)
    // {
    //     $check_range_id = '';

    //     $q  = LiquidationEntries::findOne($id);
    //     $model = $this->findModel($id);

    //     // if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //     //     return $this->redirect(['view', 'id' => $model->id]);
    //     // }
    //     $searchModel = new AdvancesEntriesForLiquidationSearch();
    //     if (\Yii::$app->user->identity->province !== 'ro_admin') {
    //         $searchModel->province = \Yii::$app->user->identity->province;
    //         // echo \Yii::$app->user->identity->province;
    //     }

    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    //     $dataProvider->pagination = ['pageSize' => $check_range_id];
    //     if ($_POST) {
    //         $check_range_id = $_POST['check_range_id'];
    //         $bank_account_id = Yii::$app->db->createCommand("SELECT bank_account_id FROM check_range where id=:id")
    //             ->bindValue(':id', $check_range_id)
    //             ->queryScalar();
    //         $dataProvider->bank_account_id = $bank_account_id;
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //         'update_type' => 're-align',
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider
    //     ]);
    // }

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
                    'books.name as book_name',
                    'advances.province'
                ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', 'books', 'advances_entries.book_id = books.id')
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
            $dv_number = !empty($_POST['dv_number']) ? $_POST['dv_number'] : '';
            $province = Yii::$app->user->identity->province;



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
                if ($check_number >= $check['from'] && $check_number <= $check['to']) {
                } else {
                    return json_encode(['isSuccess' => false, 'error' => 'Check Number Not in Range']);
                }
                // return json_encode(['isSuccess' => false, 'error' => 'less']);
            }

            // return json_encode(['isSuccess' => false, 'error' => 'qweqwr']);
            // if (date('Y', strtotime($reporting_period)) < date('Y')) {
            //     return json_encode(['isSuccess' => false, 'error' => "Invalid Reporting Period"]);
            // } else {

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

            // }

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
            $is_realign = false;
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

                    if (strtotime($reporting_period) > strtotime('2021-08')) {
                        $x = explode('-', $liquidation->dv_number);

                        $x[1] = date('Y', strtotime($reporting_period));
                        $x[2] = date('m', strtotime($reporting_period));
                        $liquidation->dv_number = implode('-', $x);
                    } else {
                        $liquidation->dv_number = $dv_number;
                    }
                }

                $is_realign = true;
            } else {
                if (empty($advances_id)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Please Insert Entries']);
                }
                $liquidation = new Liquidation();

                if (strtotime($reporting_period) > strtotime('2021-08')) {
                    $liquidation->dv_number = $this->getDvNumber($reporting_period);
                } else {
                    $liquidation->dv_number = $dv_number;
                }


                // if (intval($check_number_exist) === 1) {
                //     return json_encode(['isSuccess' => false, 'error' =>  'Check Number Already Inserted']);
                //     die();
                // }
                if (intval($check_number) !== 0) {
                    // return json_encode(['isSuccess' => false, 'error' => $check_number]);
                    $check_number_exist = Yii::$app->db->createCommand("SELECT EXISTS(SELECT * FROM liquidation WHERE check_number = :check_number
                    AND province = :province
                    )
                    ")
                        ->bindValue(':check_number', $check_number)
                        ->bindValue(':province', $province)
                        ->queryScalar();
                    if (intval($check_number_exist) === 1) {
                        return json_encode(['isSuccess' => false, 'error' => 'Check Number Already Use']);
                    }
                }
            }
            $liquidation->check_date = $check_date;
            // $liquidation->payee_id = $payee_id;
            $liquidation->check_number = $check_number;
            // $liquidation->particular = $particular;
            $liquidation->reporting_period = $reporting_period;
            $liquidation->po_transaction_id = $po_transaction_id;
            $liquidation->check_range_id = $check_range;

            // TEMPORRARY RA NI SA AUG E CHANGE RA NI




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
                            $last_lock_reporting_period = Yii::$app->db->createCommand("SELECT reporting_period FROM `liquidation_reporting_period`
                                    WHERE province = :province ORDER BY reporting_period DESC limit 1")
                                ->bindValue(':province', $province)
                                ->queryOne();
                            foreach ($advances_id as $index => $val) {

                                if (!empty($new_reporting_period)) {
                                    $r_period = date('Y-m', strtotime($new_reporting_period[$index]));
                                    $line = $index + 1;
                                    if (date('Y', strtotime($r_period)) < date('Y', strtotime($last_lock_reporting_period['reporting_period']))) {
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
                                $advances_entries_balance = Yii::$app->db->createCommand("SELECT 
                                advances_entries_for_liquidation.balance
                                 FROM advances_entries_for_liquidation
                                WHERE advances_entries_for_liquidation.id = :id")
                                    ->bindValue(':id', $val)
                                    ->queryScalar();
                                $res = $advances_entries_balance - $withd;

                                if ($res < 0) {
                                    $transaction->rollBack();
                                    return json_encode(['isSuccess' => false, 'error' => 'Cannot Insert Advances Balance is not enough in line ' . $line]);
                                }
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
                    'IFNULL(liquidation_entries.new_chart_of_account_id,liquidation_entries.chart_of_account_id) chart_of_account_id',

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

    public function getDvNumber($reporting_period = '')
    {

        if (empty($reporting_period)) {
            return null;
        }
        $province = Yii::$app->user->identity->province;

        $year = DateTime::createFromFormat('Y-m', $reporting_period)->format('Y');
        $province = Yii::$app->user->identity->province;




        $q = Yii::$app->db->createCommand("SELECT CAST( substring_index(substring(dv_number, instr(dv_number, '-')+1), '-', -1) as UNSIGNED) as q 
        from liquidation
        WHERE liquidation.province = :province
        AND liquidation.reporting_period >= '2021-09'
        AND liquidation.reporting_period LIKE :_year
        ORDER BY q DESC  LIMIT 1")
            ->bindValue(':province', $province)
            ->bindValue(':_year', $year . '%')
            ->queryScalar();



        $num = 1;

        if (!empty($q)) {
            $num = $q + 1;
        }
        $liq = Yii::$app->db->createCommand(" SELECT CAST( substring_index(substring(dv_number, instr(dv_number, '-')+1), '-', -1) as UNSIGNED) as num
        from liquidation
        WHERE liquidation.province = :province
        AND liquidation.reporting_period >= '2021-09'
        AND liquidation.reporting_period LIKE :_year
        ORDER BY num
        ")
            ->bindValue(':province', $province)
            ->bindValue(':_year', $year . '%')
            ->queryAll();
        if (!empty($liq)) {
            $number_sequnce = [];
            foreach (range(1, max($liq)['num']) as $val) {
                $number_sequnce[] = $val;
            }

            $diff = array_diff($number_sequnce, array_column($liq, 'num'));
            if (!empty($diff)) {
                $num = $diff[min(array_keys($diff))];
            }
        }





        if (strlen($num) < 4) {

            $string = substr(str_repeat(0, 4) . $num, -4);
        } else {
            $string = $num;
        }

        return strtoupper($province) . '-' . $reporting_period . '-' . $string;
    }
    public function actionExcludeRaaf($id)
    {
        $model = Liquidation::findOne($id);

        $model->exclude_in_raaf = $model->exclude_in_raaf === 1 ? 0 : 1;
        if ($model->save(false)) {
            return   $this->actionView($id);
        }
    }
    // public function actionCancel()
    // {
    //     if ($_POST) {
    //         $id = $_POST['cancelId'];
    //         $reporting_period =!empty($_POST['reporting_period'])? date('Y-m', strtotime($_POST['reporting_period'])):null;
    //         $liq = Liquidation::findOne($id);
    //         // echo "<pre>";
    //         // var_dump($liq);
    //         // echo "</pre>";
    //         // die();

    //         $liq->cancel_reporting_period = $reporting_period;
    //         if ($liq->save(false)) {
    //             return json_encode(['isSuccess' => true, 'error' => 'none']);
    //         }
    //         // $transaction = Yii::$app->db->beginTransaction();
    //         // try {
    //         //     $l = Liquidation::findOne($id);
    //         //     // $l->is_cancelled = $l->is_cancelled === 0 ? 1 : 0;

    //         //     if ($flag = $l->save(false)) {
    //         //         if ($l->is_cancelled === 0) {

    //         //             $liquidation = new Liquidation();
    //         //             $liquidation->check_date = $l->check_date;
    //         //             $liquidation->check_number = $l->check_number;
    //         //             $liquidation->reporting_period = $reporting_period;
    //         //             $liquidation->po_transaction_id = $l->po_transaction_id;
    //         //             $liquidation->check_range_id = $l->check_range_id;
    //         //             $liquidation->dv_number = $l->dv_number;
    //         //             $liquidation->province = $l->province;
    //         //             // $liquidation->is_cancelled = 1;
    //         //             if ($liquidation->save(false)) {
    //         //                 foreach ($l->liquidationEntries as $val) {
    //         //                     $liq_entries = new LiquidationEntries();
    //         //                     $liq_entries->liquidation_id = $liquidation->id;
    //         //                     $liq_entries->chart_of_account_id = $val->chart_of_account_id;
    //         //                     $liq_entries->advances_entries_id = $val->advances_entries_id;
    //         //                     $liq_entries->withdrawals = 0 - $val->withdrawals;
    //         //                     $liq_entries->vat_nonvat = 0 - $val->vat_nonvat;
    //         //                     $liq_entries->expanded_tax = 0 - $val->expanded_tax;
    //         //                     $liq_entries->liquidation_damage = 0 - $val->liquidation_damage;
    //         //                     $liq_entries->reporting_period = $reporting_period;
    //         //                     if ($liq_entries->save(false)) {
    //         //                     }
    //         //                 }
    //         //             }
    //         //         } else {
    //         //             $transaction->rollBack();
    //         //             return json_encode(['isSuccess' => false, 'error' => 'save error in liquidation entries']);
    //         //         }
    //         //     }
    //         //     if ($flag) {
    //         //         $transaction->commit();
    //         //         return json_encode(['isSuccess' => true, 'error' => 'none']);
    //         //     }
    //         // } catch (ErrorException $e) {
    //         //     return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
    //         // }
    //     }
    // }

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
                    if ($y === 4) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $province = $cells[0];
                    $transaction_number = $cells[1];
                    $reporting_period = $cells[2];
                    $dv_number = $cells[3];
                    $check_date = $cells[4];
                    $check_number = $cells[5];
                    $fund_source = $cells[6];
                    $object_code = $cells[9];
                    $withdrawal = $cells[11];
                    $vat_nonvat = $cells[12];
                    $expanded_tax = $cells[13];
                    $liquidation_damage = $cells[14];





                    // return json_encode($key);
                    // return json_encode('qwer');
                    $po_transaction_id = Yii::$app->db->createCommand("SELECT id FROM po_transaction WHERE po_transaction.tracking_number =:tracking_number")
                        ->bindValue(':tracking_number', $transaction_number)
                        ->queryScalar();
                    if (empty($po_transaction_id)) {
                        $transaction->rollback();
                        return "po transaction $key";
                    }
                    $po_transaction_id = Yii::$app->db->createCommand("SELECT id FROM po_transaction WHERE po_transaction.tracking_number =:tracking_number")
                        ->bindValue(':tracking_number', $transaction_number)
                        ->queryScalar();
                    if (empty($po_transaction_id)) {
                        $transaction->rollback();
                        return "po transaction $key";
                    }
                    $check_range_id = Yii::$app->db->createCommand("SELECT
                     id FROM check_range
                    WHERE
                   :check_number >= check_range.`from`
                   AND :check_number  <=check_range.`to`
                   AND check_range.province = :province
                   ")
                        ->bindValue(':check_number', $check_number)
                        ->bindValue(':province', $province)
                        ->queryScalar();
                    if (empty($check_range_id)) {
                        $transaction->rollback();
                        return "Check Range $key";
                    }
                    $chart_of_account_id = Yii::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :object_code")
                        ->bindValue(':object_code', $object_code)
                        ->queryScalar();
                    if (empty($chart_of_account_id)) {
                        $transaction->rollback();
                        return "chart of account $key";
                    }

                    $advances_entries_id = Yii::$app->db->createCommand("SELECT * FROM `advances_entries`
                    WHERE
                    advances_entries.fund_source = :fund_source")
                        ->bindValue(':fund_source', $fund_source)
                        ->queryScalar();
                    if (empty($advances_entries_id)) {
                        $transaction->rollBack();
                        return "advances entries $key";
                    }
                    $liq_id = Yii::$app->db->createCommand("SELECT id FROM liquidation WHERE liquidation.dv_number =:dv_number")
                        ->bindValue(':dv_number', $dv_number)
                        ->queryScalar();

                    // (new \yii\db\Query())
                    //     ->select('id')
                    //     ->from('liquidation')
                    //     ->where('liquidation.dv_number =:dv_number', ['dv_number' => $dv_number])
                    //     ->one();


                    $liquidation_id = '';
                    if (empty($liq_id)) {
                        $liquidation = new Liquidation();
                        $liquidation->province = $province;
                        $liquidation->check_date = $check_date;
                        $liquidation->check_number = $check_number;
                        $liquidation->po_transaction_id = $po_transaction_id;
                        $liquidation->dv_number = $dv_number;
                        $liquidation->reporting_period = $reporting_period;
                        $liquidation->check_range_id = $check_range_id;

                        if ($liquidation->save(false)) {
                            $liquidation_id = $liquidation->id;
                            $liq_entries = new  LiquidationEntries();
                            $liq_entries->liquidation_id = $liquidation->id;
                            $liq_entries->chart_of_account_id = $chart_of_account_id;
                            $liq_entries->withdrawals = $withdrawal;
                            $liq_entries->vat_nonvat = $vat_nonvat;
                            $liq_entries->expanded_tax = $expanded_tax;
                            $liq_entries->reporting_period = $reporting_period;
                            $liq_entries->advances_entries_id = $advances_entries_id;
                            $liq_entries->liquidation_damage = $liquidation_damage;
                            if ($liq_entries->save(false)) {
                            } else {
                                $transaction->rollback();
                                return  $liq_entries->errors .  " Error pag save sa entries $key";
                            }
                        }
                    } else {
                        // $liquidation =  Liquidation::findOne($liq_id);
                        // $liquidation->province = $province;
                        // $liquidation->check_date = $check_date;
                        // $liquidation->check_number = $check_number;
                        // $liquidation->po_transaction_id = $po_transaction_id;
                        // $liquidation->dv_number = $dv_number;
                        // $liquidation->reporting_period = $reporting_period;
                        // $liquidation->check_range_id = $check_range_id;

                        // if ($liquidation->save(false)) {
                        // $liquidation_id = $liquidation->id;
                        $liq_entries = new  LiquidationEntries();
                        $liq_entries->liquidation_id = $liq_id;
                        $liq_entries->chart_of_account_id = $chart_of_account_id;
                        $liq_entries->withdrawals = $withdrawal;
                        $liq_entries->vat_nonvat = $vat_nonvat;
                        $liq_entries->expanded_tax = $expanded_tax;
                        $liq_entries->reporting_period = $reporting_period;
                        $liq_entries->advances_entries_id = $advances_entries_id;
                        $liq_entries->liquidation_damage = $liquidation_damage;
                        if ($liq_entries->save(false)) {
                        } else {
                            $transaction->rollback();
                            return  $liq_entries->errors .  " Error pag save sa entries $key";
                        }
                        // } else {
                        //     $transaction->rollback();
                        //     return  $liquidation->errors .  " Error pag update sa liquidation $key";
                        // }
                    }
                }
            }

            // $column = [
            //     'liquidation_id',
            //     'chart_of_account_id',
            //     'withdrawals',
            //     'vat_nonvat',
            //     'expanded_tax',
            //     'reporting_period',
            //     'advances_entries_id'
            // ];
            // $ja = Yii::$app->db->createCommand()->batchInsert('liquidation_entries', $column, $data)->execute();

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


    public function actionCancelledCheckIndex()
    {
        $searchModel = new CancelledChecksViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('cancelled_checks_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCancelledCheckUpdate($id)
    {


        // $q  = Liquidation::findOne($id);
        $model = $this->findModel($id);
        return $this->renderAjax('_cancelled_form', [
            'model' => $model,
            'update_type' => 'update'
        ]);
    }
    public function actionCancelledForm()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $check_date = $_POST['check_date'];
            $check_range = $_POST['check_range'];
            $check_number = $_POST['check_number'];
            $payee = 'CANCELLED';
            $update_id = $_POST['update_id'];
            $province = Yii::$app->user->identity->province;

            $year = date('Y');
            $r_year = date('Y', strtotime($reporting_period));
            // if ($r_year < $year) {
            //     return json_encode(['isSuccess' => false, 'error' => "Please Insert Reporting Period in $year "]);
            // }
            $last_lock_reporting_period = Yii::$app->db->createCommand("SELECT reporting_period FROM `liquidation_reporting_period`
            WHERE province = :province ORDER BY reporting_period DESC limit 1")
                ->bindValue(':province', $province)
                ->queryOne();
            $r_period = date('Y-m', strtotime($reporting_period));
            if (date('Y', strtotime($r_period)) < date('Y', strtotime($last_lock_reporting_period['reporting_period']))) {
                return json_encode(['isSuccess' => false, 'error' => "Please Insert Reporting Period in $year "]);
            }
            $check = (new \yii\db\Query())
                ->select([
                    'check_range.from',
                    'check_range.to',
                ])
                ->from('check_range')
                ->where("check_range.id = :id", ['id' => $check_range])
                ->one();
            $disabled_reporting_period = (new \yii\db\Query())
                ->select('*')
                ->from('liquidation_reporting_period')
                ->where('liquidation_reporting_period.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                ->andWhere('liquidation_reporting_period.province LIKE :province', ['province' => $province])
                ->one();
            if ($check_number >= $check['from'] && $check_number <= $check['to']) {
            } else {
                return json_encode(['isSuccess' => false, 'error' => 'Check Number Not in Range']);
            }
            if (!empty($update_id)) {
                $liquidation = Liquidation::findOne($update_id);
                if ($liquidation->reporting_period !== $reporting_period) {

                    if (!empty($disabled_reporting_period)) {
                        return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
                    }
                }
            } else {
                $check_number_exist = Yii::$app->db->createCommand("
                SELECT EXISTS(SELECT * FROM liquidation WHERE check_number = :check_number
                AND province = :province
                AND reporting_period LIKE :year
                AND payee LIKE 'cancelled%'
                )
                ")
                    ->bindValue(':check_number', $check_number)
                    ->bindValue(':province', $province)
                    ->bindValue(':year', $year . '%')
                    ->queryScalar();
                if (intval($check_number_exist) === 1) {
                    return json_encode(['isSuccess' => false, 'error' => 'Check Number Already Use']);
                }


                if (!empty($disabled_reporting_period)) {
                    return json_encode(['isSuccess' => false, 'error' => 'Disabled Reporting Period']);
                }
                $liquidation = new Liquidation();
            }
            $liquidation->reporting_period = $reporting_period;
            $liquidation->check_date = $check_date;
            $liquidation->check_range_id  = $check_range;
            $liquidation->payee = $payee;
            $liquidation->check_number = $check_number;
            $liquidation->province = $province;


            if ($liquidation->validate()) {

                if ($liquidation->save(false)) {
                    return json_encode(['isSuccess' => true, 'id' => $liquidation->id]);
                }
            } else {
                return json_encode(['isSuccess' => false, 'error' => $liquidation->errors]);
            }
        }

        return $this->renderAjax('_cancelled_form', []);
    }
    public function actionUpdateUacs()
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
            $excel->setActiveSheetIndexByName('For Adjustment');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

            // 

            $transaction = Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(3) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    // if ($y === 1) {
                    //     $cells[] = $cell->getFormattedValue();
                    // } else {
                    $cells[] =   $cell->getValue();
                    // }
                    $y++;
                }
                if (!empty($cells)) {

                    $id = $cells[0];
                    $new_object_code = $cells[1];


                    $chart_of_account_id = Yii::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :uacs")
                        ->bindValue(':uacs', $new_object_code)
                        ->queryScalar();
                    $check_object_code = Yii::$app->db->createCommand("SELECT object_code FROM accounting_codes WHERE object_code = :object_code")
                        ->bindValue(':object_code', $new_object_code)
                        ->queryScalar();
                    if (empty($chart_of_account_id) && empty($check_object_code)) {
                        $new_object_code = $cells[1];
                        return json_encode(['isSuccess' => false, 'error' => "Object Code Does not Exist in Line $key $new_object_code"]);
                    }

                    $entry = LiquidationEntries::findOne($id);
                    $entry->new_chart_of_account_id = empty($chart_of_account_id) ? null : $chart_of_account_id;
                    $entry->new_object_code = $new_object_code;
                    if ($entry->save(false)) {
                    }
                }
            }
            $transaction->commit();
            return json_encode(['isSuccess' => true, 'error' => "Success"]);
            // $column = [
            //     'liquidation_id',
            //     'chart_of_account_id',
            //     'withdrawals',
            //     'vat_nonvat',
            //     'expanded_tax',
            //     'reporting_period',
            //     'advances_entries_id'
            // ];
            // $ja = Yii::$app->db->createCommand()->batchInsert('liquidation_entries', $column, $data)->execute();

            // // return $this->redirect(['index']);
            // // return json_encode(['isSuccess' => true]);
            // $transaction->commit();
            ob_clean();
            echo "<pre>";
            var_dump('success');
            echo "</pre>";
            return ob_get_clean();
        }
    }
    public function actionExport()
    {

        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];



            $province = strtolower(Yii::$app->user->identity->province);


            // $province = strtolower(Yii::$app->user->identity->province);
            $q = (new \yii\db\Query())
                ->select(["*",])
                ->from('liquidation_entries_view')
                ->where(
                    'liquidation_entries_view.reporting_period BETWEEN :from_reporting_period AND :to_reporting_period',

                    ['from_reporting_period' => $from_reporting_period, 'to_reporting_period' => $to_reporting_period]
                );

            if (
                $province === 'adn' ||
                $province === 'ads' ||
                $province === 'sdn' ||
                $province === 'sds' ||
                $province === 'pdi'
            ) {
                $q->andWhere('province = :province', ['province' => $province]);
            }

            $query = $q->all();

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->setAutoFilter('A1:P1');
            $sheet->setCellValue('A1', "ID");
            $sheet->setCellValue('B1', "Reporting Period");
            $sheet->setCellValue('C1', "DV Number");
            $sheet->setCellValue('D1', "Check Date");
            $sheet->setCellValue('E1', "Check Number");
            $sheet->setCellValue('F1', "Fund Source");
            $sheet->setCellValue('G1', 'Particular');
            $sheet->setCellValue('H1', 'Payee');
            $sheet->setCellValue('I1', 'Object Code');
            $sheet->setCellValue('J1', 'Account Title');
            $sheet->setCellValue('K1', 'Withdrawals');
            $sheet->setCellValue('L1', 'Vat-NonVat');
            $sheet->setCellValue('M1', 'Expanded Tax');
            $sheet->setCellValue('N1', 'Liquidation Damage');
            $sheet->setCellValue('O1', 'Gross Payment');
            $sheet->setCellValue('P1', 'Province');
            $sheet->setCellValue('Q1', 'Original Reporting Period');
            $sheet->setCellValue('R1', 'Bank Account');


            $x = 7;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );


            $row = 2;

            foreach ($query  as  $val) {

                $sheet->setCellValueByColumnAndRow(1, $row,  $val['id']);
                $sheet->setCellValueByColumnAndRow(2, $row,  $val['reporting_period']);
                $sheet->setCellValueByColumnAndRow(3, $row,  $val['dv_number']);
                $sheet->setCellValueByColumnAndRow(4, $row,  $val['check_date']);
                $sheet->setCellValueByColumnAndRow(5, $row,  $val['check_number']);
                $sheet->setCellValueByColumnAndRow(6, $row,  $val['fund_source']);
                $sheet->setCellValueByColumnAndRow(7, $row,  $val['particular']);
                $sheet->setCellValueByColumnAndRow(8, $row,  $val['payee']);
                $sheet->setCellValueByColumnAndRow(9, $row,  $val['object_code']);
                $sheet->setCellValueByColumnAndRow(10, $row,  $val['account_title']);
                $sheet->setCellValueByColumnAndRow(11, $row,  $val['withdrawals']);
                $sheet->setCellValueByColumnAndRow(12, $row,  $val['vat_nonvat']);
                $sheet->setCellValueByColumnAndRow(13, $row,  $val['expanded_tax']);
                $sheet->setCellValueByColumnAndRow(14, $row,  $val['liquidation_damage']);
                $sheet->setCellValueByColumnAndRow(15, $row,  $val['gross_payment']);
                $sheet->setCellValueByColumnAndRow(16, $row,  $val['province']);
                $sheet->setCellValueByColumnAndRow(17, $row,  $val['orig_reporting_period']);
                $sheet->setCellValueByColumnAndRow(18, $row,  $val['bank_account']);

                $row++;
            }

            date_default_timezone_set('Asia/Manila');
            // return date('l jS \of F Y h:i:s A');
            $id = uniqid() . '_' . date('Y-m-d h A');
            $file_name = "liquidation_$id.xlsx";
            // header('Content-Type: application/vnd.ms-excel');
            // header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            // header('Content-Transfer-Encoding: binary');
            // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            // header('Pragma: public'); // HTTP/1.0
            // echo readfile($file);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            $path = Yii::getAlias('@webroot') . '/transaction';

            $file = $path . "/liquidation_$id.xlsx";
            $file2 = "transaction/liquidation_$id.xlsx";
            $writer->save($file);
            // return ob_get_clean();
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            // echo "<script>window.open('$file2','_self')</script>";

            return json_encode($file2);
            // }
            // Yii::$app->response->xSendFile($path);

            // echo "/afms/transaction/liquidation.xlsx";
            // flush(); // Flush system output buffer

            // echo "<script> window.location.href = '$file';</script>";
            // echo "<script>window.open($file2)</script>";

            exit();
            // return json_encode(['res' => "transaction\ckdj_excel_$id.xlsx"]);
            // return json_encode($file);
            // exit;
        }
    }
    public function actionAddLink()
    {
        if ($_POST) {
            $link = $_POST['link'];
            $id = $_POST['id'];
            $dv  = Liquidation::findOne($id);

            $dv->document_link = $link;
            if ($dv->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => 'save success']);
            }
            return json_encode(['isSuccess' => true, 'cancelled' => $link]);
        }
    }
    public function advancesEntriesDataProvider($check_range_id)
    {

        if (empty($check_range)) return;

        if (!empty($check_range_id)) {
            $bank_account_id  = new Query();
            $bank_account_id->select('bank_account_id')
                ->from('check_range')
                ->where('check_range.id = :id', ['id' => $check_range_id])
                ->one();
        }
        $searchModel = new AdvancesEntriesForLiquidationSearch();
        if (\Yii::$app->user->identity->province !== 'ro_admin') {
            $searchModel->province = \Yii::$app->user->identity->province;
            // echo \Yii::$app->user->identity->province;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => $check_range_id];
        return  [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ];
    }
    public function actionCheckAdvancesBook()
    {
        if ($_POST) {
            $id = $_POST['id'];

            $query = Yii::$app->db->createCommand("SELECT books.name FROM advances_entries
            LEFT JOIN books ON advances_entries.book_id = books.id
            WHERE advances_entries.id = :id
            ")
                ->bindValue(':id', $id)
                ->queryScalar();
            return json_encode($query);
        }
    }
}
