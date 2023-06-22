<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use yii\db\Query;
use app\models\Cdr;
use app\models\Par;
use ErrorException;
use yii\helpers\Url;
use app\models\Books;
use app\models\DvAucs;
use app\models\Office;
use yii\db\Expression;
use app\models\Property;
use app\models\RaoSearch;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use app\models\PropertyCard;
use yii\helpers\ArrayHelper;
use common\models\UploadForm;
use yii\filters\AccessControl;
use app\components\EmailSender;
use app\models\ChartOfAccounts;
use app\models\PrSummarySearch;
use Symfony\Component\Mime\Email;
use app\models\TransactionTracking;
use Symfony\Component\Mime\Address;
use app\components\helpers\MyHelper;
use app\models\DetailedDvAucsSearch;
use app\models\OtherPropertyDetails;
use app\models\UnpaidObligationSearch;
use app\models\OtherPropertyDetailItems;
use app\models\ProcurementSummarySearch;
use app\models\TransactionArchiveSearch;
use app\models\AdvancesLiquidationSearch;
use app\models\TransactionTrackingSearch;
use app\models\PoTransmittalsPendingSearch;
use yii\symfonymailer\MessageWrapperInterface;
use app\models\WithholdingAndRemittanceSummarySearch;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;

class ReportController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'pending-ors',
                    'unobligated-transaction',
                    'pending-dv',
                    'unpaid-obligation',
                    'saob',
                    'get-cash',
                    'cibr',
                    'cdr',
                    'advances-liquidation',
                    'insert-cdr',
                    'get-cdr',
                    'temp',
                    'temp-import',
                    'detailed-dv-aucs',
                    'conso-detailed-dv',
                    'fund-source-fur',
                    'summary-fund-source-fur',
                    'budget-year-fur',
                    'saobs',
                    'division-fur',
                    'fur-mfo',
                    'git-pull',
                    'cadadr',
                    'annex3',
                    'annex-A',
                    'raaf',
                    'trial-balance',
                    'detailed-financial-position',
                    'withholding-and-remittance-summary',
                    'transaction-tracking',
                    'dv-time-monitoring',
                    'dv-time-monitoring-summary',
                    'province-fund-source-balance',
                    'liquidation-report-annex',
                    'dv-time-monitoring-export',
                    'dv-transmittal-summary',
                    'po-transmittal-summary',
                    'province-adequacy',
                    'upload-property',
                    'rpcppe',
                    'ppelc',
                    'user-properties',
                    'rao'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'pending-ors',
                            'unobligated-transaction',
                            'pending-dv',
                            'unpaid-obligation',
                            'saob',
                            'insert-cdr',
                            'get-cdr',
                            'temp',
                            'temp-import',
                            'detailed-dv-aucs',
                            'conso-detailed-dv',
                            'trial-balance',
                            'detailed-financial-position',
                            'withholding-and-remittance-summary',
                            'transaction-tracking',
                            'dv-time-monitoring',
                            'dv-time-monitoring-summary',
                            'province-fund-source-balance',
                            'liquidation-report-annex',
                            'dv-time-monitoring-export',
                            'dv-transmittal-summary',
                            'set-cookie',
                            'show-cookie',
                            'detailed-transmittal-summary',
                            'po-transmittal-summary',
                            'pr-summary',
                            'roc-summary',
                            'rao'

                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],

                    [
                        'actions' => [
                            'index',
                            'pending-ors',
                            'unobligated-transaction',
                            'pending-dv',
                            'unpaid-obligation',
                            'conso-detailed-dv',
                            'get-cash',
                            'tax-remittance',
                            'fund-source-fur',
                            'summary-fund-source-fur',
                            'budget-year-fur',
                            'git-pull',
                            'cadadr',
                            'annex3',
                            'annex-A',
                            'raaf',
                            'ppelc',
                            'user-properties'

                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [
                            'division-fur',
                            'saobs',
                            'transaction-tracking'

                        ],
                        'allow' => true,
                        'roles' => ['department-offices', 'super-user']
                    ],

                    [
                        'actions' => [
                            'cibr',
                            'cdr',
                            'advances-liquidation',
                            'province-adequacy'

                        ],
                        'allow' => true,
                        'roles' => ['province', 'super-user']
                    ],
                    [
                        'actions' => [
                            'saobs',
                            'division-fur',
                            'fur-mfo',

                        ],
                        'allow' => true,
                        'roles' => ['saob', 'fur-mfo', 'fur-ro', 'super-user']
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
    public function beforeAction($action)
    {
        if ($action->id == 'get-cdr') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id == 'dv-transmittal-summary') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPendingOrs()
    {

        return $this->render('pending_ors');
    }

    public function actionUnobligatedTransaction()
    {

        return $this->render('unobligated_transaction');
    }
    public function actionPendingDv()
    {

        return $this->render('pending_dv');
    }
    public function generateUnpaidObligation($sql, $year)
    {
        $q = Yii::$app->db->createCommand("
        $sql unpaid_obligation (
            SELECT 
            process_ors.reporting_period,
            process_ors.serial_number,
            IFNULL(ors_amount.total_amount,0),
            IFNULL(total_dv.total_amount_disbursed,0),
            IFNULL(ors_amount.total_amount,0) - IFNULL(total_dv.total_amount_disbursed,0) as unpaid_obligation,
            dv_aucs.dv_number,
            cash_disbursement.check_or_ada_no as check_number,
            cash_disbursement.is_cancelled,
            IFNULL(dv_detailed_amounts.amount_disbursed,0),
            IFNULL(dv_detailed_amounts.vat_nonvat,0),
            IFNULL(dv_detailed_amounts.ewt_goods_services,0),
            IFNULL(dv_detailed_amounts.compensation,0),
            IFNULL(dv_detailed_amounts.other_trust_liabilities,0),
            IFNULL(dv_detailed_amounts.total_withheld,0)
            FROM process_ors
            LEFT JOIN (
            SELECT process_ors_entries.process_ors_id ,
            SUM(process_ors_entries.amount) as total_amount
            FROM process_ors_entries 
            GROUP BY process_ors_entries.process_ors_id)
            as ors_amount ON process_ors.id =  ors_amount.process_ors_id
            LEFT JOIN (SELECT 
            dv_aucs_entries.process_ors_id,
            SUM(dv_aucs_entries.amount_disbursed) as total_amount_disbursed 
            FROM dv_aucs_entries 
            INNER JOIN dv_aucs ON dv_aucs_entries.dv_aucs_id = dv_aucs.id
            WHERE
            dv_aucs.is_cancelled = 0
            GROUP BY dv_aucs_entries.process_ors_id
            ) as total_dv ON process_ors.id  = total_dv.process_ors_id
            LEFT JOIN (
            SELECT
            dv_aucs_entries.dv_aucs_id,
            dv_aucs_entries.process_ors_id,
            SUM(dv_aucs_entries.amount_disbursed) as amount_disbursed,
            SUM(dv_aucs_entries.vat_nonvat) as vat_nonvat,
            SUM(dv_aucs_entries.ewt_goods_services) as ewt_goods_services,
            SUM(dv_aucs_entries.compensation) as compensation,
            SUM(dv_aucs_entries.other_trust_liabilities) as other_trust_liabilities,
            SUM(dv_aucs_entries.total_withheld) as total_withheld
            FROM dv_aucs_entries
            GROUP BY 
            dv_aucs_entries.dv_aucs_id,
            dv_aucs_entries.process_ors_id
            ) as dv_detailed_amounts ON process_ors.id = dv_detailed_amounts.process_ors_id
            LEFT JOIN dv_aucs ON dv_detailed_amounts.dv_aucs_id = dv_aucs.id
            LEFT JOIN cash_disbursement ON dv_detailed_amounts.dv_aucs_id = cash_disbursement.dv_aucs_id
            WHERE process_ors.reporting_period LIKE :year
            )
            ")
            ->bindValue(":year", $year . '%')
            ->query();
    }
    public function actionUnpaidObligation()
    {



        $check_table = intval(Yii::$app->db->createCommand("SELECT COUNT(*)
        FROM information_schema.tables 
        WHERE table_schema = DATABASE()
        AND table_name = 'unpaid_obligation'")->queryScalar());

        if ($_POST) {

            $year = !empty($_POST['year']) ? $_POST['year'] : '';
            if (!empty($year)) {
                $sql = '';
                if ($check_table === 1) {

                    Yii::$app->db->createCommand("TRUNCATE TABLE unpaid_obligation")->query();
                    $sql = 'INSERT INTO';
                } else {
                    $sql = 'CREATE TABLE';
                }
                $this->generateUnpaidObligation($sql, $year);
            }
        }


        $searchModel = '';
        if ($check_table !== 1) {
            $dataProvider = new \yii\data\ArrayDataProvider();
        } else {
            $searchModel = new UnpaidObligationSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }



        return $this->render('unpaid_obligation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // public function actionSaob()
    // {
    //     if ($_POST) {
    //         $reporting_period_this_month =  $_POST['reporting_period'];

    //         $reporting_period = date_create($_POST['reporting_period']);
    //         date_sub($reporting_period, date_interval_create_from_date_string("1 month"));
    //         $reporting_period_last_month = date_format($reporting_period, "Y-m");



    //         $q = Yii::$app->db->createCommand("SELECT ors.*,
    //         record_allotment_entries.amount,
    //         major_accounts.name as major_account,
    //         chart_of_accounts.uacs,
    //         chart_of_accounts.major_account_id,
    //         chart_of_accounts.general_ledger from record_allotment_entries,chart_of_accounts,major_accounts,
    //         (
    //        SELECT SUM(raoud_entries.amount) as total_obligation,raouds.record_allotment_entries_id,
    //             process_ors.reporting_period
    //             from raouds,raoud_entries,process_ors
    //             where raouds.id = raoud_entries.raoud_id

    //             AND raouds.process_ors_id = process_ors.id
    //             AND process_ors.reporting_period IN (:this_month,:last_month)
    //             GROUP BY raouds.record_allotment_entries_id,process_ors.reporting_period

    //             ORDER BY raouds.record_allotment_entries_id

    //     ) as ors
    //     where record_allotment_entries.id = ors.record_allotment_entries_id
    //     AND chart_of_accounts.major_account_id = major_accounts.id
    //     AND record_allotment_entries.chart_of_account_id =chart_of_accounts.id

    //     ORDER BY record_allotment_entries.id")
    //             ->bindValue(':this_month', $reporting_period_this_month)
    //             ->bindValue(':last_month', $reporting_period_last_month)
    //             ->queryAll();

    //         $result = ArrayHelper::index($q, null, 'record_allotment_entries_id');
    //         $qwer = [];

    //         foreach ($q as $val) {
    //             $id = $val['record_allotment_entries_id'];
    //             $x = array_key_exists($val['record_allotment_entries_id'], $qwer);
    //             if (!$x) {
    //                 $qwer[$val['record_allotment_entries_id']] = [
    //                     'major_account' => $val['major_account'],
    //                     'object_code' => $val['uacs'],
    //                     'general_ledger' => $val['general_ledger'],
    //                     'allotment_amount' => $val['amount'],
    //                 ];
    //             }

    //             $qwer[$id][$val['reporting_period']] = $val['total_obligation'];
    //         }

    //         $result = ArrayHelper::index($qwer, null, [function ($element) {
    //             return $element['major_account'];
    //         },]);
    //         // ob_clean();
    //         // echo "<pre>";
    //         // var_dump($result);
    //         // echo "</pre>";
    //         // return ob_get_clean();
    //         return $this->render('saob', [
    //             'query' => $result,
    //             'reporting_period_this_month' => $reporting_period_this_month,
    //             'reporting_period_last_month' => $reporting_period_last_month
    //         ]);
    //     } else {

    //         // ob_clean();
    //         // echo "<pre>";
    //         // var_dump($result);
    //         // echo "</pre>";
    //         // return ob_get_clean();
    //         return $this->render('saob', [
    //             'query' => ''
    //         ]);
    //     }
    // }


    public function actionGetCash()
    {
        $total_cash_disbursed = Yii::$app->db->createCommand("SELECT books.`name`,
         SUM(dv_aucs_entries.amount_disbursed)as total_disbursed 
        FROM cash_disbursement,dv_aucs,dv_aucs_entries,books
        WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
        AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
        AND cash_disbursement.book_id = books.id
        GROUP BY cash_disbursement.book_id")->queryAll();
        // $cash_received = Yii::$app->db->createCommand("SELECT SUM(cash_received.amount) as total_cash_received from cash_received")->queryOne();
        $date = '2021-01-05';
        $query = (new \yii\db\Query())
            ->select([
                'SUM(dv_aucs_entries.amount_disbursed) as total_disbursed',
                "(SELECT SUM(cash_received.amount) as total_cash_received from cash_received) as total_cash_received",
                "( (SELECT SUM(cash_received.amount) as total_cash_received from cash_received) - SUM(dv_aucs_entries.amount_disbursed)) as cash_balance"
            ])
            ->from('cash_disbursement')
            ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
            ->join('LEFT JOIN', 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
            // ->where('cash_disbursement.issuance_date =:issuance_date',['issuance_date'=>$date])
            ->one();

        $total_amount_pending = (new \yii\db\Query())
            ->select("SUM(dv_aucs_entries.amount_disbursed) as total_amount_pending")
            ->from('dv_aucs')
            ->join('LEFT JOIN', 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
            ->where("dv_aucs.id NOT IN 
              (SELECT DISTINCT cash_disbursement.dv_aucs_id from cash_disbursement WHERE cash_disbursement.dv_aucs_id IS NOT NULL)")
            ->one();
        $query['total_amount_pending'] = $total_amount_pending['total_amount_pending'];
        $query['cash_balance_per_accounting'] = $query['cash_balance'] - $total_amount_pending['total_amount_pending'];

        return json_encode($query);
    }

    public function actionSample()
    {
        // date_default_timezone_set('UTC');


    }


    public function actionCibr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $province = $_POST['province'];
            $book = $_POST['book'];

            if (
                empty($reporting_period)
                || empty($province)
                || empty($book)
            ) {
                return json_encode(['error' => true, 'message' => 'Reporting Period,Province and Book are Required']);
            }


            $dataProvider = Yii::$app->db->createCommand("SELECT 
                check_date,
                check_number,
                particular,
                amount,
                withdrawals,
                gl_object_code,
                gl_account_title,
                reporting_period
            from advances_liquidation
             where reporting_period <=:reporting_period AND province LIKE :province
             AND book_name LIKE :book
             ORDER BY reporting_period,check_date,check_number
            ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':province', $province)
                ->bindValue(':book', $book)
                ->queryAll();
            // return $reporting_period;

            // ob_clean();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();
            return $this->render('cibr', [
                'dataProvider' => $dataProvider,
                'province' => $province,
                'reporting_period' => $reporting_period,
                'book' => $book

            ]);
        } else {

            return $this->render('cibr');
        }
    }
    public function actionAdvancesLiquidation()
    {


        $searchModel = new AdvancesLiquidationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);



        return $this->render('advances_liquidation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCdr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_name  = $_POST['book'];
            $province = $_POST['province'];
            $report_type = $_POST['report_type'];

            $cdr = Yii::$app->memem->cdrFilterQuery($reporting_period, $book_name, $province, $report_type);
            $query = (new \yii\db\Query())
                ->select(
                    'check_date,
                    check_number,
                    particular,
                    amount,
                    withdrawals,
                    gl_object_code,
                    gl_account_title,
                    reporting_period,
                    vat_nonvat,
                    expanded_tax
                '
                )
                ->from('advances_liquidation')
                ->where('reporting_period <=:reporting_period', ['reporting_period' => $reporting_period])
                ->andWhere('book_name =:book_name', ['book_name' => $book_name])
                ->andWhere('province LIKE :province', ['province' => $province])
                ->andWhere('report_type LIKE :report_type', ['report_type' => $report_type])
                ->orderBy('reporting_period')
                ->all();


            $result = ArrayHelper::index($query, null, [function ($element) {
                return $element['reporting_period'];
            }, 'gl_object_code']);
            // ob_clean();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";

            // return ob_get_clean();
            $consolidated = [];
            if (!empty($result[$reporting_period])) {

                foreach ($result[$reporting_period] as $key => $res) {
                    $total = 0;
                    $vat_nonvat = 0;
                    $expanded_tax = 0;
                    $account_title =  $res[0]['gl_account_title'];

                    foreach ($res as $data) {
                        $total += (float)$data['withdrawals'];
                        $vat_nonvat += (float)$data['vat_nonvat'];
                        $expanded_tax += (float)$data['expanded_tax'];
                    }

                    $consolidated[] = [
                        'object_code' => $key,
                        'account_title' => $account_title,
                        'total' => $total,
                        'vat_nonvat' => $vat_nonvat,
                        'expanded_tax' => $expanded_tax,
                        'gross_amount' => $total + $vat_nonvat + $expanded_tax
                    ];
                }
            }

            // ob_clean();
            // echo "<pre>";
            // var_dump($consolidated);
            // echo "</pre>";

            // return ob_get_clean();
            // return json_encode($cdr);
            return $this->render('cdr', [
                'dataProvider' => $query,
                'reporting_period' => $reporting_period,
                'province' => $province,
                'consolidated' => $consolidated,
                'book' => $book_name,
                'cdr' => $cdr


            ]);
        }
    }

    public function actionInsertCdr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $province = $_POST['province'];
            $book_name = $_POST['book'];
            $report_type = $_POST['report_type'];
            $query = (new \yii\db\Query())
                ->select('id')
                ->from('cdr')
                ->where('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                ->andWhere('province LIKE :province', ['province' => $province])
                ->andWhere('book_name LIKE :book_name', ['book_name' => $book_name])
                ->andWhere('report_type LIKE :report_type', ['report_type' => $report_type])
                ->one();
            if (!empty($query)) {
                return json_encode(['isSuccess' => false, 'error' => 'na save na ']);
            }
            $cdr = new Cdr();
            $cdr->reporting_period = $reporting_period;
            $cdr->province = $province;
            $cdr->book_name = $book_name;
            $cdr->report_type = $report_type;

            if ($cdr->validate()) {
                if ($cdr->save(false)) {
                    return json_encode(['isSuccess' => true,]);
                }
            } else {
                return json_encode(['isSuccess' => false, 'error' => $cdr->errors]);
            }
        }
    }
    public function actionGetCdr()

    {
        if ($_POST) {
            $id = $_POST['update_id'];

            $cdr  = Cdr::findOne($id);

            $q = Yii::$app->db->createCommand("SELECT

            (CASE  
                WHEN liquidation_entries.new_object_code IS  NOT NULL THEN liquidation_entries.new_object_code
                WHEN  liquidation_entries.new_chart_of_account_id IS  NOT NULL THEN  new_chart.uacs
                ELSE orig_chart.uacs
            END) as  object_code,
            (CASE  
                WHEN liquidation_entries.new_object_code IS  NOT NULL THEN _new_object_code.account_title
                WHEN  liquidation_entries.new_chart_of_account_id IS  NOT NULL THEN  new_chart.general_ledger
                ELSE orig_chart.general_ledger
            END) as  account_title,
            ROUND(IFNULL(SUM(withdrawals),0),2)+
            ROUND(IFNULL(SUM(vat_nonvat),0),2)+
            ROUND(IFNULL(SUM(expanded_tax),0),2)  as debit,
            ROUND(SUM(withdrawals),2) as total_withdrawals,
            ROUND(SUM(vat_nonvat),2) as total_vat_nonvat,
            ROUND(SUM(expanded_tax),2) as total_expanded_tax
            FROM liquidation_entries
            LEFT JOIN chart_of_accounts  as orig_chart ON liquidation_entries.chart_of_account_id= orig_chart.id
            LEFT JOIN chart_of_accounts as new_chart ON liquidation_entries.new_chart_of_account_id= new_chart.id
            LEFT JOIN accounting_codes as _new_object_code ON liquidation_entries.new_object_code= _new_object_code.object_code
            LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
            LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id  = advances_entries.id
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id =  cash_disbursement.id
            WHERE liquidation_entries.reporting_period = :reporting_period
            AND liquidation.province = :province
            AND advances_entries.report_type = :report_type
            GROUP BY object_code
            ")
                ->bindValue(':reporting_period', $cdr->reporting_period)
                ->bindValue(':province', $cdr->province)
                ->bindValue(':report_type', $cdr->report_type)
                ->queryAll();

            $r  = date('F, Y', strtotime($cdr->reporting_period));
            $acc = "Due to BIR - " . strtoupper($cdr->province) . " ($r)";
            $v =  "Due to BIR VAT/NonVat - " . strtoupper($cdr->province) . " ($r)";
            $e =   "Due to BIR Expanded - " . strtoupper($cdr->province) . " ($r)";

            $account = (new \yii\db\Query())
                ->select('*')
                ->from('sub_accounts1')
                ->where('name LIKE :name', ['name' => $acc])
                ->one();
            $vat = (new \yii\db\Query())
                ->select('*')
                ->from('sub_accounts1')
                ->where('name LIKE :name', ['name' => "Due to BIR VAT/NonVat - $cdr->province ($r)"])
                ->one();
            $expanded = (new \yii\db\Query())
                ->select('*')
                ->from('sub_accounts1')
                ->where('name LIKE :name', ['name' => "Due to BIR Expanded - $cdr->province ($r)"])
                ->one();
            $advances = (new \yii\db\Query())
                ->select('*')
                ->from('sub_accounts1')
                ->where('name LIKE :name', ['name' => "$cdr->report_type - $cdr->province%"])
                ->one();
            $c_id = (new \yii\db\Query())
                ->select("id ")
                ->from('chart_of_accounts')
                ->where("uacs =:uacs", ['uacs' => 2020101000])
                ->one();

            if (empty($account)) {

                $account = Yii::$app->memem->createSubAccount1($acc, $c_id['id']);
            }

            if (empty($vat)) {

                $vat = Yii::$app->memem->createSubAccount1($v, $c_id['id']);
            }
            if (empty($expanded)) {

                $expanded = Yii::$app->memem->createSubAccount1($e, $c_id['id']);
            }
            // ob_clean();
            // echo "<pre>";
            // var_dump($account);
            // echo "</pre>";
            // return ob_get_clean();
            if (!empty($cdr)) {
                return json_encode(['result' => $q, 'vat' => $vat, 'expanded' => $expanded, 'account' => $account]);
            }
        }
    }

    public function actionTemp()
    {
        return $this->render('temp');
    }
    public function actionTempImport()
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
            $excel->setActiveSheetIndexByName('qwe');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';

                    $cells[] =   $cell->getValue();
                }
                if (!empty($cells)) {

                    // $dv = trim($cells[1]);


                    // $q = (new \yii\db\Query())
                    //     ->select('id')
                    //     ->from('dv_aucs')
                    //     ->where("dv_number LIKE :dv_number", ['dv_number', "%".$dv])
                    //     ->one();
                    $dv = '%' . trim($cells[1]);
                    $q = Yii::$app->db->createCommand("SELECT id  FROM dv_aucs where dv_number LIKE :dv")
                        ->bindValue(':dv', $dv)
                        ->queryScalar();
                    $w = DvAucs::findOne($q);
                    // Fund 01-2021-06-0956
                    $x = explode('-', $w->dv_number);
                    unset($x[3]);
                    array_push($x, $cells[0]);
                    $y = implode('-', $x);
                    $w->dv_number = $y;
                    // $w->dv_number =$dv_number;
                    // ob_clean();
                    // echo "<pre>";
                    // var_dump();
                    // echo "</pre>";
                    // return ob_get_clean();
                    // die();
                    $data[] = [
                        'id' => $q,
                        'to' => $cells[0],
                        'from' => $cells[1],
                    ];
                }
                // if ($key === 1) {
                //     ob_clean();
                //     echo "<pre>";
                //     var_dump($dv);
                //     echo "</pre>";
                //     return ob_get_clean();
                // }
            }
            $qwe = [];
            foreach ($data as $d) {
                $s = DvAucs::findOne((int)$d['id']);
                $x = explode('-', $w->dv_number);
                unset($x[3]);
                array_push($x, $d['to']);
                $y = implode('-', $x);
                $s->dv_number = $y;
                $qwe[] = $s->dv_number;
                if ($s->save(false)) {
                }
                // var_dump($d['id']);
            }
            $transaction->commit();


            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($qwe);
            echo "</pre>";
            return ob_get_clean();
        }
    }
    public function generateDetailedDv($from_reporting_period, $to_reporting_period, $sql)
    {
        $q = Yii::$app->db->createCommand("
       $sql  detailed_dv_aucs (SELECT 
            dv_aucs.dv_number,
            dv_aucs.reporting_period,
            process_ors.serial_number as obligation_number,
            `transaction`.tracking_number as transaction_tracking_number,
            payee.account_name as payee,
            dv_aucs.particular,
            dv_aucs_entries.amount_disbursed as total_dv,
            dv_aucs_entries.vat_nonvat as total_vat,
            dv_aucs_entries.ewt_goods_services as total_ewt,
            dv_aucs_entries.compensation as total_compensation,
            mfo_pap_code.`code` as mfo_code,
            mfo_pap_code.`name` as mfo_name,
            mfo_pap_code.description as mfo_description,
            mfo_pap_code.id as mfo_id,
            record_allotments.serial_number as allotment_number,
            chart_of_accounts.uacs as allotment_object_code,
            chart_of_accounts.general_ledger as allotment_account_title,
            major_accounts.`name` as allotment_class,
            ors_chart.uacs as obligation_object_code,
            ors_chart.general_ledger as obligation_account_title,
            process_ors_entries.amount as obligation_amount,
            t_obligation.total_obligation,
            ROUND((dv_aucs_entries.amount_disbursed *process_ors_entries.amount)/t_obligation.total_obligation,2) as dv_amount,
            ROUND((dv_aucs_entries.vat_nonvat*process_ors_entries.amount)/t_obligation.total_obligation,2) as dv_vat,
            ROUND((dv_aucs_entries.ewt_goods_services*process_ors_entries.amount)/t_obligation.total_obligation,2)as dv_ewt,
            ROUND((dv_aucs_entries.compensation*process_ors_entries.amount)/t_obligation.total_obligation,2) as dv_compensation,
            cash_disbursement.mode_of_payment,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.ada_number,
            cash_disbursement.issuance_date,
            nature_of_transaction.`name` as nature_transaction_name,
            mrd_classification.`name` as mrd_name,
            dv_aucs.is_cancelled,
            document_recieve.`name` as doc_name,
            jev_preparation.jev_number,
            record_allotment_entries.amount as allotment_recieved,
            record_allotments.id as record_allotment_id,
            books.`name`as book,
            cash_disbursement.is_cancelled as cash_disursement_is_cancelled

            FROM dv_aucs
            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
            LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
            LEFT JOIN process_ors_entries ON process_ors.id = process_ors_entries.process_ors_id
            LEFT JOIN record_allotment_entries ON process_ors_entries.record_allotment_entries_id = record_allotment_entries.id
            LEFT JOIN	record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
            LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id 
            LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
            LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id 
            LEFT JOIN mrd_classification on dv_aucs.mrd_classification_id = mrd_classification.id
            LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id 
            LEFT JOIN jev_preparation ON dv_aucs.dv_number = jev_preparation.dv_number
            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
              LEFT JOIN books ON dv_aucs.book_id = books.id
            LEFT JOIN(SELECT SUM(process_ors_entries.amount) as total_obligation,process_ors_entries.process_ors_id
            FROM process_ors_entries 
            GROUP BY process_ors_entries.process_ors_id) as t_obligation ON process_ors.id = t_obligation.process_ors_id
            LEFT JOIN chart_of_accounts  as ors_chart ON process_ors_entries.chart_of_account_id = ors_chart.id 
            WHERE dv_aucs.is_cancelled = 0 AND dv_aucs.reporting_period >=:from_reporting_period 
            AND dv_aucs.reporting_period <=:to_reporting_period  AND dv_aucs_entries.is_deleted = 0)
       ")
            ->bindValue(":from_reporting_period", $from_reporting_period)
            ->bindValue(":to_reporting_period", $to_reporting_period)
            ->query();
    }
    public function actionDetailedDvAucs()
    {
        $from_reporting_period = !empty($_POST['from_reporting_period']) ? $_POST['from_reporting_period'] : '';
        $to_reporting_period = !empty($_POST['to_reporting_period']) ? $_POST['to_reporting_period'] : '';
        $check_table = intval(Yii::$app->db->createCommand("SELECT COUNT(*)
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
            AND table_name = 'detailed_dv_aucs'")->queryScalar());

        if ($_POST) {
            if (!empty($to_reporting_period) && !empty($from_reporting_period)) {
                $sql = '';
                if ($check_table === 1) {

                    Yii::$app->db->createCommand("TRUNCATE TABLE detailed_dv_aucs")->query();
                    $sql = 'INSERT INTO';
                } else {
                    $sql = 'CREATE TABLE';
                }
                $this->generateDetailedDv($from_reporting_period, $to_reporting_period, $sql);
            }
        }
        $searchModel = '';



        if ($check_table !== 1) {

            $dataProvider = new \yii\data\ArrayDataProvider();
        } else {
            $searchModel = new DetailedDvAucsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }


        return $this->render(
            'detailed_dv_aucs',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
        // }


        return $this->render('detailed_dv_aucs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionConsoDetailedDv()
    {
        $dataProvider = '';
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $q = date('Y', strtotime($reporting_period));
            $year = "$q%";
            $allotment_class = $_POST['allotment_class'];
            $book_id = $_POST['book_id'];
            if (
                empty($reporting_period) &&
                empty($allotment_class) &&
                empty($book_id)

            ) {
                $x = date('Y');
                $year = "$x%";
                $dataProvider = Yii::$app->db->createCommand("CALL conso_dv_all(:year)")
                    ->bindValue(':year', $year)
                    ->queryAll();
            } else {
                $dataProvider = Yii::$app->db->createCommand("CALL conso_dv(:reporting_period,:year,:book_id,:allotment_class)")
                    ->bindValue(':reporting_period', $reporting_period)
                    ->bindValue(':year', $year)
                    ->bindValue(':allotment_class', $allotment_class)
                    ->bindValue(':book_id', $book_id)
                    ->queryAll();
            }

            // return json_encode($allotment_class);
            ArrayHelper::multisort($dataProvider, ['mfo_code'], [SORT_ASC]);

            return json_encode($dataProvider);
        } else {

            return $this->render('conso_dv');
        }
    }
    public function actionDvNumber()
    {

        $query1 = (new \yii\db\Query());
        $query1->select([])
            ->from('jev_accounting_entries')
            ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id=jev_preparation.id')
            ->join('LEFT JOIN', 'chart_of_accounts', 'jev_accounting_entries.chart_of_account_id=chart_of_accounts.id');

        echo "<pre>";
        var_dump($query1->one());
        echo "</pre>";
    }
    public function actionFur()
    {
        $dataProvider = [];
        $conso_fur = [];
        if ($_POST) {
            $province = $_POST['province'];
            $reporting_period = $_POST['reporting_period'];
            $x = explode('-', $reporting_period);
            $x[1] =  '0' . ($x[1] - 1);

            $prev = implode('-', $x);

            $query = Yii::$app->db->createCommand("CALL q(:province,:reporting_period)")
                ->bindValue(':province', $province)
                ->bindValue(':reporting_period', $reporting_period)
                ->queryAll();
            $dataProvider = $query;
            $conso_fur = YIi::$app->db->createCommand('CALL conso_fur(:province,:reporting_period,:prev_r_period)')
                ->bindValue(':province', $province)
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':prev_r_period', $prev)
                ->queryAll();
        }


        return $this->render('fur', [
            'dataProvider' => $dataProvider,
            'consoFur' => $conso_fur
        ]);
    }
    public function actionGetFur()
    {
        $dataProvider = [];
        $conso_fur = [];
        if ($_POST) {
            $province = $_POST['province'];
            $reporting_period = $_POST['reporting_period'];
            $x = explode('-', $reporting_period);
            $x[1] =  '0' . ($x[1] - 1);

            $prev = implode('-', $x);

            $query = Yii::$app->db->createCommand("CALL q(:province,:reporting_period)")
                ->bindValue(':province', $province)
                ->bindValue(':reporting_period', $reporting_period)
                ->queryAll();
            $dataProvider = $query;
            $conso_fur = YIi::$app->db->createCommand('CALL conso_fur(:province,:reporting_period,:prev_r_period)')
                ->bindValue(':province', $province)
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':prev_r_period', $prev)
                ->queryAll();
        }
        return json_encode([
            'fur' => $dataProvider,
            'conso_fur' => $conso_fur
        ]);
    }


    public function actionTaxRemittance()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book = $_POST['book'];
            $province = $_POST['province'];

            $query = Yii::$app->db->createCommand("SELECT 

            dv_number,
            ROUND(SUM(advances_liquidation.vat_nonvat),2) as total_vat,
            ROUND(SUM(advances_liquidation.expanded_tax),2) as total_expanded
             FROM `advances_liquidation`
            
            WHERE 
             (  advances_liquidation.expanded_tax >0
            OR advances_liquidation.vat_nonvat>0)
            AND reporting_period =:reporting_period
            AND province LIKE :province
            AND book_name =:book
            GROUP BY advances_liquidation.dv_number
            ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':book', $book)
                ->bindValue(':province', $province)
                ->queryAll();
            return $this->render('tax_remittance', [
                'dataProvider' => $query
            ]);
        } else {

            return $this->render('tax_remittance');
        }
    }
    public function actionGetAllTransaction()
    {
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('transaction')
            ->all();
        return json_encode($query);
    }
    public function actionTransactionArchive()
    {
        $searchModel = new TransactionArchiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('transaction_archive', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionPoTransmittalPendingAtRo()
    {
        $searchModel = new PoTransmittalsPendingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render(
            'po_transmittal_pending_at_ro',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]
        );
    }
    public function actionRsmi()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book = $_POST['book'];
            $province = $_POST['province'];

            $query = Yii::$app->db->createCommand("SELECT 
					advances_liquidation.gl_object_code,
                    dv_number,
                    ROUND(SUM(advances_liquidation.withdrawals),2) as total_withdrawal
                    FROM `advances_liquidation`
                    WHERE 
                    reporting_period =:reporting_period
                    AND province LIKE :province
                    AND book_name =:book
                    GROUP BY dv_number, advances_liquidation.gl_object_code
                    ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':book', $book)
                ->bindValue(':province', $province)
                // ->queryAll()
            ;
            $query2 = (new \yii\db\Query())
                ->select([
                    "CONCAT(chart_of_accounts.uacs,' ',chart_of_accounts.general_ledger) as account",
                    'query1.*q'

                ])
                ->from('chart_of_accounts')
                ->orderBy('chart_of_accounts.uacs');
            $query2->join('INNER JOIN', "({$query->getRawSql()}) as query1", 'query1.gl_object_code = chart_of_accounts.uacs');
            $res =   $query2->join('INNER JOIN', "liquidation", 'query1.dv_number = liquidation.dv_number')->all();
            // $query3 = (new \yii\db\Query())
            //     ->select(' advances_liquidation.dv_number,
            //     advances_liquidation.check_date,
            //     advances_liquidation.check_number,
            //     advances_liquidation.payee,
            //     q.*')
            //     ->from('advances_liquidation')
            //     ->where('advances_liquidation.reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
            //     ->andWhere('advances_liquidation.province =:province', ['province' => $province])
            //     ->andWhere('advances_liquidation.book_name =:name', ['name' => $book]);
            // $res = $query3->join('INNER JOIN', "({$query2->createCommand()->getRawSql()}) as q", 'advances_liquidation.dv_number = q.dv_number')->all();
            $result = ArrayHelper::index($res, null, 'account');
            // ob_clean();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();.

            return $this->render('rsmi', [
                'dataProvider' => $result
            ]);
        } else {

            return $this->render('rsmi');
        }
    }

    public function actionRod()
    {
        if ($_POST) {

            $province = !empty($_POST['province']) ? $_POST['province'] : '';

            $fund_source = $_POST['fund_source'];
            $params = [];
            $user_province = strtolower(Yii::$app->user->identity->province);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'
            ) {
                $province = $user_province;
            }
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'liquidation_entries.advances_entries_id', $fund_source], $params);
            $query1 = (new \yii\db\Query())
                ->select(["
                        liquidation.check_date,
                        liquidation.dv_number,
                        IFNULL(po_responsibility_center.`name`,'') reponsibility_center_name,
                        IFNULL(po_transaction.payee,liquidation.payee) as payee,
                        liquidation_entries.withdrawals,
                        advances_entries.fund_source
            "])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'liquidation', 'liquidation_entries.liquidation_id = liquidation.id')
                ->join('LEFT JOIN', 'advances_entries', 'liquidation_entries.advances_entries_id = advances_entries.id')
                ->join('LEFT JOIN', 'po_transaction', 'liquidation.po_transaction_id = po_transaction.id')
                ->join('LEFT JOIN', 'po_responsibility_center', 'po_transaction.po_responsibility_center_id = po_responsibility_center.id')
                ->where('liquidation.province = :province', ['province' => $province])
                ->andWhere("$sql", $params)
                ->orderBy('liquidation.check_number DESC')
                ->all();
            //                 SELECT 
            // advances_entries.fund_source,
            // cash_disbursement.check_or_ada_no,
            // cash_disbursement.issuance_date,
            // advances_entries.amount,
            // liquidation_total.total_withdrawals,
            // advances_entries.amount - IFNULL(liquidation_total.total_withdrawals,0) as balance


            // FROM advances_entries
            // LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
            // LEFT JOIN (SELECT SUM(liquidation_entries.withdrawals) as total_withdrawals,
            // liquidation_entries.advances_entries_id
            // FROM liquidation_entries
            // GROUP BY liquidation_entries.advances_entries_id
            // ) as liquidation_total ON advances_entries.id = liquidation_total.advances_entries_id
            $params2 = [];
            $fund_source_sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'advances_entries.id', $fund_source], $params2);
            $fund_source_query = (new \yii\db\Query())
                ->select(["
                 advances_entries.fund_source,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.issuance_date,
                advances_entries.amount,
                liquidation_total.total_withdrawals,
                advances_entries.amount - IFNULL(liquidation_total.total_withdrawals,0) as balance
            "])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'cash_disbursement', 'advances_entries.cash_disbursement_id = cash_disbursement.id')
                ->join('LEFT JOIN', ' (SELECT SUM(liquidation_entries.withdrawals) as total_withdrawals,
            liquidation_entries.advances_entries_id
            FROM liquidation_entries
            GROUP BY liquidation_entries.advances_entries_id
            ) as liquidation_total', 'advances_entries.id = liquidation_total.advances_entries_id')
                ->where("$fund_source_sql", $params)
                ->all();


            // $result = ArrayHelper::index($query1, null, 'fund_source');
            // $fund_source_data = [];
            // foreach ($result as $index => $val) {

            //     $fund_source_data[] = [
            //         'fund_source' => $index,
            //         'total' => round(array_sum(array_column($result[$index], 'withdrawals')), 2)
            //     ];
            // }
            return json_encode([
                'liquidations' => $query1,
                'conso_fund_source' => $fund_source_query,
                'fund_sources' => $fund_source
            ]);
        }
        return $this->render('rod');
    }
    public function actionFund($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('advances_entries.id, advances_entries.fund_source AS text')
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->where(['like', 'advances_entries.fund_source', $q]);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'
            ) {
                $query->andWhere('advances.province = :province', ['province' => $user_province]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        }
        return $out;
    }
    public function actionFundSourceFur()
    {
        if ($_POST) {

            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $fund_source_type = $_POST['fund_source_type'];
            $province = !empty($_POST['province']) ? $_POST['province'] : '';
            $user_province = strtolower(Yii::$app->user->identity->province);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'

            ) {
                $province = $user_province;
            }
            $and = '';
            $params = [];
            $sql = '';
            if ($province !== 'all') {
                $and = 'AND ';
                $sql = Yii::$app->db->getQueryBuilder()->buildCondition('advances.province = :province', $params);
            }
            $query = Yii::$app->db->createCommand("SELECT 
            SUBSTRING_INDEX(advances_entries.reporting_period,'-',1)as budget_year,
            advances_entries.reporting_period,
            advances.province,
            advances_entries.fund_source,
            dv_aucs.particular,
            IFNULL(current_advances.current_total_advances,0) as current_total_advances ,
            IFNULL(current_liquidation.current_total_withdrawals,0) as current_total_withdrawals,
            (
            IFNULL(prev_advances.prev_total_advances,0)-IFNULL(prev_liquidation.prev_total_withdrawals,0)
            ) as begin_balance
            
            FROM advances_entries
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id  = cash_disbursement.id 
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT  JOIN (SELECT liquidation_entries.advances_entries_id,SUM(liquidation_entries.withdrawals) as current_total_withdrawals
            FROM liquidation_entries WHERE liquidation_entries.reporting_period >=:from_reporting_period AND liquidation_entries.reporting_period <=:to_reporting_period
            GROUP BY liquidation_entries.advances_entries_id
             )as current_liquidation ON advances_entries.id  = current_liquidation.advances_entries_id
            LEFT  JOIN (SELECT liquidation_entries.advances_entries_id,SUM(liquidation_entries.withdrawals) as prev_total_withdrawals 
            FROM liquidation_entries WHERE liquidation_entries.reporting_period <:from_reporting_period 
            GROUP BY liquidation_entries.advances_entries_id
             )as prev_liquidation ON advances_entries.id  = prev_liquidation.advances_entries_id
            LEFT  JOIN (SELECT  advances_entries.amount as current_total_advances ,advances_entries.id
             FROM advances_entries WHERE advances_entries.reporting_period >=:from_reporting_period AND advances_entries.reporting_period <=:to_reporting_period
             )as current_advances ON advances_entries.id  = current_advances.id
            LEFT  JOIN (SELECT  advances_entries.amount as prev_total_advances,advances_entries.id 
            FROM advances_entries WHERE advances_entries.reporting_period <:from_reporting_period
             )as prev_advances ON advances_entries.id  = prev_advances.id
            WHERE advances_entries.fund_source_type  = :fund_source_type
            AND advances_entries.reporting_period <=:to_reporting_period
            AND advances_entries.is_deleted NOT IN (1,9)
            $and $sql
            ORDER BY budget_year DESC")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':fund_source_type', $fund_source_type)
                ->bindValue(':province', $province)
                ->queryAll();
            // $query  = (new \yii\db\Query())->select(["
            //     SUBSTRING_INDEX(advances_entries.reporting_period,'-',1) as budget_year,
            //     advances.province,
            //     advances_entries.reporting_period,
            //     advances_entries.fund_source,
            //     IFNULL(beginning_advances.amount,0) - IFNULL(beginning_balance.prev_withdrawals,0)  as begin_balance,
            //     IFNULL(liquidation_total.total_withdrawals,0) as total_withdrawals,
            //     (  IFNULL(beginning_advances.amount,0) +
            //     IFNULL(current_advances.amount,0) )- 
            //      IFNULL(liquidation_total.total_withdrawals,0) as balance,
            //     dv_aucs.particular,
            //     IFNULL(current_advances.amount,0)   as cash_advances_for_the_period
            //     "])
            //     ->from('advances_entries')
            //     ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
            //     ->join('LEFT JOIN', 'cash_disbursement', 'advances_entries.cash_disbursement_id = cash_disbursement.id')
            //     ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
            //     ->join(
            //         'LEFT JOIN',
            //         "(
            //             SELECT 
            //             liquidation_balance_per_advances.advances_entries_id,
            //             SUM(liquidation_balance_per_advances.total_withdrawals) as total_withdrawals
            //             FROM liquidation_balance_per_advances
            //             WHERE
            //             liquidation_balance_per_advances.reporting_period >= :from_reporting_period  
            //             AND
            //          liquidation_balance_per_advances.reporting_period <= :to_reporting_period
            //             GROUP BY liquidation_balance_per_advances.advances_entries_id
            //         ) as liquidation_total",
            //         'advances_entries.id = liquidation_total.advances_entries_id',
            //         [
            //             'from_reporting_period' => $from_reporting_period,
            //             'to_reporting_period' => $to_reporting_period,
            //         ]

            //     )
            //     ->join(
            //         'LEFT JOIN',
            //         "(
            //             SELECT 
            //             liquidation_balance_per_advances.advances_entries_id,
            //             SUM(liquidation_balance_per_advances.total_withdrawals) as prev_withdrawals
            //             FROM liquidation_balance_per_advances
            //             WHERE 
            //             liquidation_balance_per_advances.reporting_period < :from_reporting_period
            //             GROUP BY liquidation_balance_per_advances.advances_entries_id
            //         ) as beginning_balance",
            //         'advances_entries.id = beginning_balance.advances_entries_id',
            //         [
            //             'from_reporting_period' => $from_reporting_period,
            //         ]
            //     )
            //     ->join(
            //         "LEFT JOIN",
            //         "(SELECT advances_entries.id,advances_entries.amount
            //         FROM advances_entries
            //         WHERE
            //         advances_entries.reporting_period < :from_reporting_period
            //         ) as beginning_advances",
            //         "
            //         advances_entries.id = beginning_advances.id
            //         ",
            //         [
            //             'from_reporting_period' => $from_reporting_period
            //         ]
            //     )
            //     ->join(
            //         "LEFT JOIN",
            //         "(SELECT advances_entries.id,advances_entries.amount
            //             FROM advances_entries
            //             WHERE
            //             advances_entries.reporting_period >= :from_reporting_period
            //             AND
            //             advances_entries.reporting_period <= :to_reporting_period
            //             ) as current_advances",
            //         "
            //             advances_entries.id = current_advances.id
            //     ",
            //         [
            //             'from_reporting_period' => $from_reporting_period,
            //             'to_reporting_period' => $to_reporting_period
            //         ]
            //     )
            //     ->where('advances_entries.fund_source_type=:fund_source_type', ['fund_source_type' => $fund_source_type])
            //     ->andWhere('advances_entries.reporting_period <= :to_reporting_period', ['to_reporting_period' => $to_reporting_period])
            //     ->andWhere('advances_entries.is_deleted !=1 ');
            // if (strtolower($province) !== 'all') {
            //     $query->andWhere('advances.province = :province', ['province' => $province]);
            // }
            // $final_query = $query->orderBy('advances_entries.reporting_period')
            //     ->all();
            $result = ArrayHelper::index($query, null, 'budget_year');
            $index_per_province = ArrayHelper::index($query, null, 'province');
            // unset($result['2020']['2020-12']);
            $conso = array();
            foreach (array_unique(array_column($query, 'province')) as $val) {


                $conso[$val] = [
                    'grand_total_withdrawals' => round(array_sum(array_column($index_per_province[$val], 'current_total_withdrawals')), 2),
                    'grand_total_begin_balance' => round(array_sum(array_column($index_per_province[$val], 'begin_balance')), 2),
                    'grand_total_cash_advances_for_the_period' => round(array_sum(array_column($index_per_province[$val], 'current_total_advances')), 2),
                ];
            }
            // echo "<pre>";
            // var_dump($conso);
            // echo "</pre>";
            // $reverse = array_reverse($result);
            // var_dump($reverse);
            return json_encode(['detailed' => $result, 'conso' => $conso]);
        }


        return $this->render('fund-source-fur');
    }
    public function actionSummaryFundSourceFur()
    {

        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $province = !empty($_POST['province']) ? $_POST['province'] : '';
            $division = !empty($_POST['division']) ? $_POST['division'] : '';
            // $from_reporting_period = '2021-02';
            // $to_reporting_period = '2021-06';
            // $province = 'all';
            // $division =  'all';
            $user_province = strtolower(Yii::$app->user->identity->province);
            $user_division = strtolower(Yii::$app->user->identity->division);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'pdi' ||
                $user_province === 'sdn' ||
                $user_province === 'sds'
            ) {
                $province = $user_province;
            }
            if (
                !empty($user_division)

            ) {
                $division = $user_division;
            }
            $q   = new Query();


            $q->select([
                "advances.`province`,
                fund_source_type.`division`,
                fund_source_type.`name` as fund_source_type
                 
            "
            ])
                ->from('advances_entries')
                ->join('LEFT JOIN', "advances", 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', "fund_source_type", 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->andWhere("advances_entries.is_deleted != 1");
            if ($province !== 'all') {

                $q->andWhere("advances.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {

                $q->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $q->andWhere("fund_source_type.division !=''");
            $q->andWhere("fund_source_type.`name` !=''");
            $query = $q->groupBy("advances.province,
            fund_source_type.division,
            fund_source_type.`name` ");


            $current_advances = new Query();
            $current_advances->select([
                "advances.province,
                IFNULL(fund_source_type.division,'') as division,
                IFNULL(fund_source_type.`name`,' ') as fund_source_type,
                SUM(advances_entries.amount)  as current_advances_amount
                "
            ])
                ->from('advances_entries')
                ->join('LEFT JOIN', "advances", 'advances_entries.advances_id=  advances.id')
                ->join('LEFT JOIN', "fund_source_type", 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->where("advances_entries.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andwhere("advances_entries.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period])
                ->andWhere("advances_entries.is_deleted != 1");
            if ($province !== 'all') {
                $current_advances->andWhere("advances.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {

                $current_advances->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $current_advances->groupBy("advances.province,
            fund_source_type.division,
            fund_source_type.`name`");
            $final_query1 = $current_advances->all();

            $prev_advances  = new Query();
            $prev_advances->select([
                "
                advances.province,
                fund_source_type.division,
                fund_source_type.`name` as fund_source_type,
                IFNULL(SUM(advances_entries.amount),0) as prev_amount
                "
            ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type =  fund_source_type.`name`')
                ->where(
                    "advances_entries.reporting_period < :from_reporting_period",
                    ['from_reporting_period' => $from_reporting_period]
                )
                ->andWhere("advances_entries.is_deleted != 1")
                ->groupBy("advances.province,
                        fund_source_type.division,
                        fund_source_type.`name`")
                ->all();

            $current_liquidation = new Query();
            $current_liquidation->select([" 
                           advances.province,
            fund_source_type.division,
            advances_entries.fund_source_type,
            SUM(liquidation_entries.withdrawals) as total_withdrawals"])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'advances_entries', 'liquidation_entries.advances_entries_id = advances_entries.id')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->where("liquidation_entries.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("liquidation_entries.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period])
                ->groupBy("
            advances.province,
            fund_source_type.division,
            advances_entries.fund_source_type
            ")
                ->all();
            $current_advances_query = $current_advances->createCommand()->getRawSql();
            $prev_advances_query = $prev_advances->createCommand()->getRawSql();
            $current_liquidation_query = $current_liquidation->createCommand()->getRawSql();

            $q4 = $query->createCommand()->getRawSql();
            $final_query  = Yii::$app->db->createCommand(
                "SELECT
                    qq1.*,IFNULL(current_liquidationd.total_withdrawals,0) as total_withdrawals,
                    IFNULL(current_advances.current_advances_amount,0) as current_advances_amount ,
                    IFNULL(prev_advances.prev_amount,0) as prev_amount,
                    (IFNULL(current_advances.current_advances_amount,0) + IFNULL(prev_advances.prev_amount,0))
                     - IFNULL(current_liquidationd.total_withdrawals,0) as ending_balance
                    FROM ($q4) as qq1
                    LEFT JOIN($current_advances_query) as current_advances
                    ON (qq1.province = current_advances.province AND qq1.division = current_advances.division AND qq1.`fund_source_type` = current_advances.`fund_source_type`)
                    LEFT JOIN ($prev_advances_query) as prev_advances
                    ON (qq1.province = prev_advances.province AND qq1.division = prev_advances.division AND qq1.`fund_source_type` = prev_advances.`fund_source_type`)
                LEFT JOIN ($current_liquidation_query) as current_liquidationd
                ON (qq1.province = current_liquidationd.province AND qq1.division = current_liquidationd.division AND qq1.`fund_source_type` = current_liquidationd.`fund_source_type`)
            "
            )
                ->queryAll();


            // echo "<pre>";
            // var_dump($final_query);
            // echo "</pre>";






            $result = ArrayHelper::index($final_query, null, [function ($element) {
                return $element['province'];
            }, 'division']);



            return json_encode($result);
        }
        return $this->render('fund-source-type-fur');
    }
    public function actionBudgetYearFur()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $province = !empty($_POST['province']) ? $_POST['province'] : '';
            $division = !empty($_POST['division']) ? $_POST['division'] : '';
            // $from_reporting_period = ";SELECT * FROM users";
            // $to_reporting_period = ";SELECT * FROM users";
            // $province = 'all';
            // $division =  'all';
            $user_province = strtolower(Yii::$app->user->identity->province);
            $user_division = strtolower(Yii::$app->user->identity->division);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'pdi' ||
                $user_province === 'sdn' ||
                $user_province === 'sds'
            ) {
                $province = $user_province;
            }
            if (
                !empty($user_division)

            ) {
                $division = $user_division;
            }



            $budget_fur = new Query();
            $budget_fur->select([
                "SUBSTRING_INDEX(advances_entries.reporting_period,'-',1) as `year`,advances.province,fund_source_type.`division`"
            ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id=  advances.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type=  fund_source_type.`name`')
                ->where('is_deleted NOT IN (1,9)');
            if ($province !== 'all') {
                $budget_fur->andWhere("advances.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {
                $budget_fur->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $budget_fur->groupBy("`year`,advances.province, fund_source_type.division ");



            $current_advances = new Query();
            $current_advances->select([
                "SUBSTRING_INDEX(advances_entries.reporting_period,'-',1) as `year`,
                advances.province,
                fund_source_type.`division`,
                fund_source_type.`name` as fund_type,
                SUM(advances_entries.amount) as total_amount"
            ])
                ->from("advances_entries")
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id=  advances.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->where("advances_entries.reporting_period >=:from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("advances_entries.reporting_period <=:to_reporting_period", ['to_reporting_period' => $to_reporting_period])
                ->andWhere("advances_entries.is_deleted NOT IN (1,9)");
            if ($province !== 'all') {
                $current_advances->andWhere("advances.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {
                $current_advances->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $current_advances->groupBy("`year`,advances.province, fund_source_type.division,fund_source_type.`name` ");

            $prev_advances = new Query();
            $prev_advances->select([
                "SUBSTRING_INDEX(advances_entries.reporting_period,'-',1) as `year`",
                "advances.province",
                "fund_source_type.`division`",
                "fund_source_type.`name` as fund_type",
                "SUM(advances_entries.amount) as total_amount"
            ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id=  advances.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->where(' advances_entries.reporting_period < :from_reporting_period ', ['from_reporting_period' => $from_reporting_period])
                ->andWhere("advances_entries.is_deleted NOT IN(1,9)");
            if ($province !== 'all') {
                $prev_advances->andWhere("advances.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {
                $prev_advances->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $prev_advances->groupBy("`year`,advances.province, fund_source_type.division,fund_source_type.`name` ");

            $current_liquidation  = new Query();

            $current_liquidation->select([
                "substring_index(liquidation_entries.reporting_period,'-',1) as `year`",
                "liquidation.province",
                "fund_source_type.division",
                "fund_source_type.`name` as fund_type",
                "SUM(liquidation_entries.withdrawals) as total_withdrawals"

            ])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'liquidation', 'liquidation_entries.liquidation_id  = liquidation.id')
                ->join('LEFT JOIN', 'advances_entries', 'liquidation_entries.advances_entries_id = advances_entries.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->where("liquidation_entries.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("liquidation_entries.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
            if ($province !== 'all') {
                $current_liquidation->andWhere("liquidation.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {
                $current_liquidation->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $current_liquidation->groupBy("`year`,liquidation.province,fund_source_type.division,fund_source_type.`name`");

            $prev_liquidation  = new Query();
            $prev_liquidation->select([
                "substring_index(liquidation_entries.reporting_period,'-',1) as `year`",
                "liquidation.province",
                "fund_source_type.division",
                "fund_source_type.`name` as fund_type",
                "SUM(liquidation_entries.withdrawals) as total_withdrawals"

            ])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'liquidation', 'liquidation_entries.liquidation_id  = liquidation.id')
                ->join('LEFT JOIN', 'advances_entries', 'liquidation_entries.advances_entries_id = advances_entries.id')
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type = fund_source_type.`name`')
                ->where("liquidation_entries.reporting_period >= :from_reporting_period", ['from_reporting_period' => DateTime::createFromFormat('Y-m', $from_reporting_period)->format('y') . '-01'])
                ->andWhere("liquidation_entries.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
            if ($province !== 'all') {
                $prev_liquidation->andWhere("liquidation.province =:province", ['province' => $province]);
            }
            if ($division !== 'all') {
                $prev_liquidation->andWhere("fund_source_type.division =:division", ['division' => $division]);
            }
            $prev_liquidation->groupBy("`year`,liquidation.province,fund_source_type.division,fund_source_type.`name`");

            $budget_fur_query = $budget_fur->createCommand()->getRawSql();
            $current_advances_query = $current_advances->createCommand()->getRawSql();
            $prev_advances_query = $prev_advances->createCommand()->getRawSql();
            $current_liquidation_query = $current_liquidation->createCommand()->getRawSql();
            $prev_liquidation_query = $prev_liquidation->createCommand()->getRawSql();




            $final_query  = Yii::$app->db->createCommand(
                "SELECT 
              budget_year.*,
              IFNULL(prev_advances.total_amount,0) as beginning_balance,
              IFNULL(current_advances.total_amount,0) as current_advances_amount,
              IFNULL(current_liquidation.total_withdrawals,0) as total_withdrawals,
            ( IFNULL(prev_advances.total_amount,0) +IFNULL(current_advances.total_amount,0)) -  IFNULL(current_liquidation.total_withdrawals,0) as ending_balance

            FROM(
                SELECT
                q1.*,fund_source_type.name as fund_type
                FROM ($budget_fur_query) as q1
                LEFT JOIN fund_source_type ON q1.division = fund_source_type.division
            ) as budget_year
            LEFT JOIN ($current_advances_query) as current_advances 
             ON (budget_year.`year` = current_advances.`year`
              AND budget_year.province = current_advances.province  
              AND budget_year.division = current_advances.division
              AND budget_year.fund_type = current_advances.fund_type
              )
            LEFT JOIN ($prev_advances_query) as prev_advances 
             ON (budget_year.`year` = prev_advances.`year` 
             AND budget_year.province = prev_advances.province  
             AND budget_year.division = prev_advances.division
             AND budget_year.fund_type = prev_advances.fund_type
             )
            LEFT JOIN ($current_liquidation_query) as current_liquidation 
             ON (budget_year.`year` = current_liquidation.`year` 
             AND budget_year.province = current_liquidation.province 
              AND budget_year.division = current_liquidation.division
              AND budget_year.fund_type = current_liquidation.fund_type
              )
            LEFT JOIN ($prev_liquidation_query) as prev_liquidation 
             ON (budget_year.`year` = current_liquidation.`year` 
             AND budget_year.province = current_liquidation.province 
              AND budget_year.division = current_liquidation.division
              AND budget_year.fund_type = current_liquidation.fund_type
              )
              WHERE 
            IFNULL(prev_advances.total_amount,0) >0
            OR
            IFNULL(current_advances.total_amount,0) > 0
            OR
            IFNULL(current_liquidation.total_withdrawals,0)>0
            "
            )
                ->queryAll();
            // return json_encode($final_query);



            $result = ArrayHelper::index($final_query, 'fund_type', [function ($element) {
                return $element['province'];
            }, 'year', 'division']);
            $conso = array();

            foreach ($result as $province => $val1) {
                foreach ($val1  as $year  => $val2) {
                    foreach ($val2 as $division => $val3) {
                        $conso[] = [
                            'province' => $province,
                            'year' => $year,
                            'division' => $division,
                            'beginning_balance' => array_sum(array_column($result[$province][$year][$division], 'beginning_balance')),
                            'current_advances_amount' => array_sum(array_column($result[$province][$year][$division], 'current_advances_amount')),
                            'total_withdrawals' => array_sum(array_column($result[$province][$year][$division], 'total_withdrawals')),
                            'ending_balance' => array_sum(array_column($result[$province][$year][$division], 'ending_balance')),
                        ];
                    }
                }
            }

            $conso_result = ArrayHelper::index($conso, 'division', [function ($element) {
                return $element['province'];
            }, 'year']);
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";


            return json_encode(['detailed' => $result, 'conso' => $conso_result]);
        }
        return $this->render('budget-year-fur');
    }

    public function actionImportOrsEntries()
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
            $excel->setActiveSheetIndexByName('process_ors_entries');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];

            $transaction = \Yii::$app->ryn_db->beginTransaction();
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $cells[] =   $cell->getValue();
                }
                if (!empty($cells)) {

                    $ors_id = $cells[0];
                    $allotment_id =  $cells[1];
                    $chart_id = $cells[2];
                    $amount = $cells[3];
                    $reporting_period = $cells[4];
                    $query = Yii::$app->ryn_db->createCommand("  INSERT INTO `process_ors_entries` 
                    (`process_ors_id`,`record_allotment_entries_id`,`chart_of_account_id`,`amount`,`reporting_period`) 
                    VALUES (:process_ors_id,:record_allotment_entries_id,:chart_of_account_id,:amount,:reporting_period)
                    ")
                        ->bindValue(':process_ors_id', $ors_id)
                        ->bindValue(':record_allotment_entries_id', $allotment_id)
                        ->bindValue(':chart_of_account_id', $chart_id)
                        ->bindValue(':amount', $amount)
                        ->bindValue(':reporting_period', $reporting_period)

                        ->query();


                    $data[] = [

                        'chart_of_account_id' => $chart_id,
                        'process_ors_id' => $ors_id,
                        'amount' => $amount,
                        'record_allotment_entries_id' => $allotment_id,
                        'reporting_period' => $reporting_period,
                    ];
                }
            }

            $column = [
                'chart_of_account_id',
                'process_ors_id',
                'amount',
                'record_allotment_entries_id',
                'reporting_period',

            ];
            // $transaction->commit();
            // $ja = Yii::$app->db->createCommand()->batchInsert('process_ors_entries', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            return ob_get_clean();
        }
        return $this->render('import_ors');
    }
    public function actionSaobs()
    {
        if ($_POST) {

            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $mfo_code = $_POST['mfo_code'];
            $document_recieve = $_POST['document_recieve'];
            $book_id = $_POST['book_id'];

            $current_ors = new Query();
            $current_ors->select([

                "saob_rao.mfo_pap_code_id",
                "saob_rao.document_recieve_id",
                "saob_rao.book_id",
                "saob_rao.chart_of_account_id",
                "SUM(saob_rao.allotment_amount) as total_allotment",
                "SUM(saob_rao.ors_amount) as total_ors"
            ])
                ->from('saob_rao')

                ->where(" saob_rao.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("saob_rao.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period])
                ->andWhere("saob_rao.book_id = :book_id", ['book_id' => $book_id]);
            if (strtolower($mfo_code) !== 'all') {

                $current_ors->andWhere("saob_rao.mfo_pap_code_id = :mfo_code", ['mfo_code' => $mfo_code]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $current_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
            }
            $current_ors->groupBy(
                "saob_rao.mfo_pap_code_id,
                saob_rao.document_recieve_id,
                saob_rao.book_id,
                saob_rao.chart_of_account_id"
            );


            $prev_ors = new Query();
            $prev_ors->select([

                "saob_rao.mfo_pap_code_id",
                "saob_rao.document_recieve_id",
                "saob_rao.book_id",
                "saob_rao.chart_of_account_id",
                "SUM(saob_rao.allotment_amount) as total_allotment",
                "SUM(saob_rao.ors_amount) as total_ors"
            ])
                ->from('saob_rao')

                ->where(" saob_rao.reporting_period < :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("saob_rao.book_id = :book_id", ['book_id' => $book_id]);
            if (strtolower($mfo_code) !== 'all') {

                $prev_ors->andWhere("saob_rao.mfo_pap_code_id = :mfo_code", ['mfo_code' => $mfo_code]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $prev_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
            }
            $prev_ors->groupBy(
                "saob_rao.mfo_pap_code_id,
                saob_rao.document_recieve_id,
                saob_rao.book_id,
                saob_rao.chart_of_account_id"
            );





            $sql_current_ors = $current_ors->createCommand()->getRawSql();
            $sql_prev_ors = $prev_ors->createCommand()->getRawSql();
            $query = Yii::$app->db->createCommand("SELECT
            mfo_pap_code.`name` as mfo_name,
            document_recieve.`name` as document_name,
            major_accounts.`name` as major_name,
            major_accounts.`object_code` as major_object_code,
            
            sub_major_accounts.`name` as sub_major_name,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
            IFNULL(prev.total_ors ,0)as prev_total_ors,
            IFNULL(current.total_ors,0) as current_total_ors,
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) as ors_to_date
            FROM ($sql_current_ors) as current
            LEFT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
            AND current.document_recieve_id = prev.document_recieve_id
            AND current.book_id = prev.book_id
            AND current.chart_of_account_id = prev.chart_of_account_id)
            LEFT JOIN chart_of_accounts ON current.chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
            LEFT JOIN mfo_pap_code ON current.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON current.document_recieve_id = document_recieve.id
            LEFT JOIN books ON current.book_id  = books.id
            WHERE
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0 OR 
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) >0
            UNION
            SELECT
            mfo_pap_code.`name` as mfo_name,
            document_recieve.`name` as document_name,
            major_accounts.`name` as major_name,
            major_accounts.`object_code` as major_object_code,
            
            sub_major_accounts.`name` as sub_major_name,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
            IFNULL(prev.total_ors ,0)as prev_total_ors,
            IFNULL(current.total_ors,0) as current_total_ors,
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) as ors_to_date
            FROM ($sql_current_ors) as current
            RIGHT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
            AND current.document_recieve_id = prev.document_recieve_id
            AND current.book_id = prev.book_id
            AND current.chart_of_account_id = prev.chart_of_account_id)
            LEFT JOIN chart_of_accounts ON prev.chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
            LEFT JOIN mfo_pap_code ON prev.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON prev.document_recieve_id = document_recieve.id
            LEFT JOIN books ON prev.book_id  = books.id
            WHERE
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0 OR 
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) >0
            
            ")->queryAll();







            // IFNULL(allotment_per_ors_uacs.total_allotment,0) as allotment_per_uacs
            // LEFT JOIN ($sql_allotment) as allotment_per_ors_uacs
            // ON (current_prev.mfo_code = allotment_per_ors_uacs.mfo_code 
            // AND current_prev.document_recieve_name = allotment_per_ors_uacs.document_recieve_name 
            // AND current_prev.uacs = allotment_per_ors_uacs.uacs 
            // AND current_prev.ors_object_code = allotment_per_ors_uacs.uacs 



            // $query = Yii::$app->db->createCommand("CALL saob(:from_reporting_period,:to_reporting_period,:document_recieve,:mfo_code)")
            //     ->bindValue(':from_reporting_period', $from_reporting_period)
            //     ->bindValue(':to_reporting_period', $to_reporting_period)
            //     ->bindValue(':document_recieve', $document_recieve)
            //     ->bindValue(':mfo_code', $mfo_code)
            //     ->queryAll();

            $result = ArrayHelper::index($query, 'uacs', [function ($element) {
                return $element['mfo_name'];
            }, 'document_name']);


            $allotment_total = array();
            foreach ($result as $mfo => $val1) {
                foreach ($val1 as $document => $val2) {
                    foreach ($val2 as $uacs => $val3) {
                        $allot = floatval($result[$mfo][$document][$uacs]['allotment']);
                        if ($allot != 0) {

                            $allotment_total[$mfo][$document][$uacs] = $allot;
                        }
                    }
                }
            }

            // foreach ($query as $val) {



            //     if (!empty($allotment_total[$val['mfo_name']][$val['document_name']][$val['uacs']])) {
            //         $val['balance'] = $allotment_total[ $val['mfo_name']][ $val['document_name']][ $val['uacs']] - $val['ors_to_date'];
            //     } else {
            //         $val['balance'] = $allotment_total[ $val['mfo_name']][ $val['document_name']][ $val['major_object_code']] - $val['ors_to_date'];
            //     }
            // }

            $result2 = ArrayHelper::index($query, null, [function ($element) {
                return $element['major_name'];
            }, 'sub_major_name',]);
            $conso_saob = array();
            $sort_by_mfo_document = ArrayHelper::index($query, null, [function ($element) {
                return $element['mfo_name'];
            }, 'document_name']);

            // echo "<pre>";
            // var_dump(array_column($sort_by_mfo_document['GAS']['GARO'], 'prev_total_ors'));
            // echo "</pre>";
            // die();
            foreach ($sort_by_mfo_document as $mfo => $mfo_val) {
                foreach ($mfo_val as $document => $document_val) {
                    $to_date = round(array_sum(array_column($document_val, 'ors_to_date')), 2);






                    $conso_saob[] =
                        [
                            'mfo_name' => $mfo,
                            'document' => $document,
                            'beginning_balance' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document], 'allotment')), 2),
                            'prev' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document], 'prev_total_ors')), 2),
                            'current' => round(array_sum(array_column($document_val, 'current_total_ors')), 2),
                            'to_date' => round(array_sum(array_column($document_val, 'ors_to_date')), 2),
                        ];
                }
            }


            return json_encode(['result' => $result2, 'allotments' => $allotment_total, 'conso_saob' => $conso_saob]);
        }
        return $this->render('saobs');
    }
    public function actionGitPull()
    {

        // exec('git fet');
        echo "<pre>";
        echo  exec("git fetch https://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@github.com/kiotipot1/dti-afms-2.git");
        echo  shell_exec("git fetch https://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@github.com/kiotipot1/dti-afms-2.git");
        echo "</pre>";
        echo "<pre>";
        echo  exec("git pull https://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@github.com/kiotipot1/dti-afms-2.git");
        echo  shell_exec("git pull https://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@github.com/kiotipot1/dti-afms-2.git");
        echo "</pre>";
        echo "<pre>";
        echo  shell_exec("yii migrate --interactive=0");
        echo "</pre>";
    }
    public function actionDivisionFur()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $division = !empty($_POST['division']) ? $_POST['division'] : '';
            $document_recieve = $_POST['document_recieve'];

            if (!empty(Yii::$app->user->identity->division)) {
                $division = Yii::$app->user->identity->division;
            }
            $current_ors = new Query();
            $current_ors->select([

                "saob_rao.division",
                "saob_rao.mfo_pap_code_id",
                "saob_rao.document_recieve_id",
                "saob_rao.major_id",
                "SUM(saob_rao.allotment_amount) as total_allotment",
                "SUM(saob_rao.ors_amount) as total_ors"
            ])
                ->from('saob_rao')

                ->where(" saob_rao.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("saob_rao.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
            if (strtolower($division) !== 'all') {

                $current_ors->andWhere("saob_rao.division = :division", ['division' => $division]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $current_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
            }
            $current_ors->groupBy(
                "saob_rao.division,
                saob_rao.mfo_pap_code_id,
                saob_rao.document_recieve_id,
                saob_rao.major_id"
            );


            $prev_ors = new Query();
            $prev_ors->select([
                "saob_rao.division",
                "saob_rao.mfo_pap_code_id",
                "saob_rao.document_recieve_id",
                "saob_rao.major_id",
                "SUM(saob_rao.allotment_amount) as total_allotment",
                "SUM(saob_rao.ors_amount) as total_ors"
            ])
                ->from('saob_rao')
                ->where(" saob_rao.reporting_period < :from_reporting_period", ['from_reporting_period' => $from_reporting_period]);
            if (strtolower($division) !== 'all') {

                $prev_ors->andWhere("saob_rao.division = :division", ['division' => $division]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $prev_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
            }
            $prev_ors->groupBy(
                "saob_rao.division,
                saob_rao.mfo_pap_code_id,
                saob_rao.document_recieve_id,
                saob_rao.major_id"
            );





            $sql_current_ors = $current_ors->createCommand()->getRawSql();
            $sql_prev_ors = $prev_ors->createCommand()->getRawSql();
            $query = Yii::$app->db->createCommand("SELECT
            current.division,
            mfo_pap_code.`code` as mfo_code,
            mfo_pap_code.`name` as mfo_name,
            mfo_pap_code.`description` as mfo_description,
            document_recieve.`name` as document_name,
            major_accounts.`name` as major_name,
            major_accounts.`object_code` as major_object_code,
    
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
              IFNULL(prev.total_ors ,0)as prev_total_ors,
            IFNULL(current.total_ors,0) as current_total_ors,
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) as ors_to_date,
           ( IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) )-
           ( IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0)) as balance,
           ( IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) )-
            IFNULL(prev.total_ors ,0) 
             as begin_balance,
           (  IFNULL(prev.total_ors ,0) + IFNULL(current.total_ors,0))
                /
            ( IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) ) as utilization
            FROM ($sql_current_ors) as current
            LEFT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
            AND current.document_recieve_id = prev.document_recieve_id
            AND current.major_id = prev.major_id)
     
            LEFT JOIN major_accounts ON current. major_id = major_accounts.id
            LEFT JOIN mfo_pap_code ON current.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON current.document_recieve_id = document_recieve.id
            WHERE

            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) >0
            OR 
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0
   
            ")->queryAll();

            $result = ArrayHelper::index($query, null, [function ($element) {
                return $element['division'];
            }, 'mfo_name', 'document_name']);
            $mfo = Yii::$app->db->createCommand("SELECT * FROM mfo_pap_code")->queryAll();
            $mfo_final = ArrayHelper::index($mfo, null, 'name');
            return json_encode(['result' => $result, 'mfo_pap' => $mfo_final]);
            // 
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // die();

            // $allotment_total = array();
            // foreach ($result as $mfo => $val1) {
            //     foreach ($val1 as $document => $val2) {
            //         foreach ($val2 as $uacs => $val3) {
            //             $allot = floatval($result[$mfo][$document][$uacs]['allotment']);
            //             if ($allot != 0) {

            //                 $allotment_total[$mfo][$document][$uacs] = $allot;
            //             }
            //         }
            //     }
            // }
            // $mfo = Yii::$app->db->createCommand("SELECT * FROM mfo_pap_code")->queryAll();
            // $mfo_final = ArrayHelper::index($mfo, null, 'name');
            // $result2 = ArrayHelper::index($query, null, [function ($element) {
            //     return $element['division'];
            // }, 'mfo_name', 'major_name', 'sub_major_name',]);

            // return json_encode(['result' => $result2, 'allotments' => $allotment_total, 'mfo_pap' => $mfo_final]);
        }
        return $this->render('division_fur');
    }
    public function actionCadadr()
    {
        if (Yii::$app->request->post()) {

            // $from_reporting_period = $_POST['from_reporting_period'];
            // $to_reporting_period = $_POST['to_reporting_period'];
            // $book = $_POST['book'];
            $qry = Yii::$app->db->createCommand("CALL prc_Cadadr('2023-01','2023-06',5)")
                ->queryAll();

            //     $query = Yii::$app->db->createCommand("SELECT * FROM cadadr
            //         WHERE 
            //         reporting_period >= :from_reporting_period
            //         AND reporting_period <= :to_reporting_period
            //         AND book_name = :book
            //         AND is_cancelled =0

            //         UNION ALL
            //         SELECT
            //         cadadr.mode_of_payment,
            //         cadadr.reporting_period,
            //         cadadr.dv_number,
            //         cadadr.dv_date,
            //         cadadr.check_or_ada_no,
            //         cadadr.ada_number,
            //         cadadr.issuance_date,
            //         cadadr.account_name,
            //         cadadr.particular,
            //         cadadr.book_name,
            //         cadadr.nca_recieve,
            //         (CASE
            //         WHEN QUARTER(CONCAT(cadadr.reporting_period,'-01')) = QUARTER(CONCAT(cadadr.cancelled_r_period,'-01')) AND SUBSTRING_INDEX(cadadr.reporting_period,'-',1) = SUBSTRING_INDEX(cadadr.cancelled_r_period,'-',1)
            //         THEN cadadr.check_issued 
            //         ELSE
            //         0
            //         END
            //         ) as check_issued,
            //         (CASE

            //         WHEN QUARTER(CONCAT(cadadr.reporting_period,'-01')) = QUARTER(CONCAT(cadadr.cancelled_r_period,'-01')) AND SUBSTRING_INDEX(cadadr.reporting_period,'-',1) = SUBSTRING_INDEX(cadadr.cancelled_r_period,'-',1)
            //         THEN cadadr.ada_issued 
            //         ELSE
            //        0
            //         END
            //         ) as ada_issued,
            //         cadadr.is_cancelled,
            //         cadadr.cancelled_r_period
            //         FROM cadadr
            //         WHERE 
            //         reporting_period >= :from_reporting_period
            //         AND reporting_period <= :to_reporting_period
            //         AND book_name = :book
            //         AND is_cancelled =1
            //         ORDER BY issuance_date
            //     ")
            //         ->bindValue(':from_reporting_period', $from_reporting_period)
            //         ->bindValue(':to_reporting_period', $to_reporting_period)
            //         ->bindValue(':book', $book)
            //         ->queryAll();
            //     $d = new DateTime($from_reporting_period);
            //     $w = new DateTime('2021-10');
            //     $begin_balance = 0;
            //     $adjustment_begin_balance = 0;
            //     if ($d->format('Y-m') >= $w->format('Y-m') && $book === 'Fund 01') {
            //         $begin_balance = Yii::$app->db->createCommand("SELECT 
            //             IFNULL(SUM(total_nca_recieve) - (SUM(total_check_issued)+SUM(total_ada_issued)),0) as begin_balance
            //            FROM cadadr_balances
            //            WHERE 
            //            cadadr_balances.reporting_period < :from_reporting_period 
            //            and
            //            cadadr_balances.reporting_period >= '2021-10' 
            //            AND cadadr_balances.book_name = :book

            //            ")
            //             ->bindValue(':from_reporting_period', $from_reporting_period)
            //             ->bindValue(':book', $book)
            //             ->queryScalar();
            //         $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
            //             SUM(cash_adjustment.amount) as total_amount
            //             FROM  cash_adjustment
            //             LEFT JOIN books ON cash_adjustment.book_id = books.id
            //             WHERE 
            //             cash_adjustment.reporting_period < :from_reporting_period 
            //             AND
            //             cash_adjustment.reporting_period >= '2021-10' 
            //             AND books.name = :book")
            //             ->bindValue(':from_reporting_period', $from_reporting_period)
            //             ->bindValue(':book', $book)
            //             ->queryScalar();
            //     } else {
            //         $begin_balance = Yii::$app->db->createCommand("SELECT 
            //             IFNULL(SUM(total_nca_recieve) - (SUM(total_check_issued)+SUM(total_ada_issued)),0) as begin_balance
            //            FROM cadadr_balances
            //            WHERE 
            //            cadadr_balances.reporting_period < :from_reporting_period 
            //           AND  cadadr_balances.reporting_period >= '2021-01' 
            //            AND cadadr_balances.book_name = :book
            //            ")
            //             ->bindValue(':from_reporting_period', $from_reporting_period)
            //             ->bindValue(':book', $book)
            //             ->queryScalar();
            //         $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
            //             SUM(cash_adjustment.amount) as total_amount
            //             FROM  cash_adjustment
            //             LEFT JOIN books ON cash_adjustment.book_id = books.id
            //             WHERE 
            //             cash_adjustment.reporting_period < :from_reporting_period 
            //             AND  cash_adjustment.reporting_period >= '2021-01' 
            //             AND books.name = :book")
            //             ->bindValue(':from_reporting_period', $from_reporting_period)
            //             ->bindValue(':book', $book)
            //             ->queryScalar();
            //     }

            //     // return json_encode([$begin_balance, $adjustment_begin_balance]);
            //     // $begin_balance  += $adjustment_begin_balance;
            //     $begin_balance = (float)$begin_balance - (float)$adjustment_begin_balance;
            //     $adjustment = Yii::$app->db->createCommand("SELECT * 
            //    FROM cash_adjustment
            //    LEFT JOIN books ON cash_adjustment.book_id = books.id

            //    WHERE cash_adjustment.reporting_period <= :to_reporting_period
            //    AND cash_adjustment.reporting_period >= :from_reporting_period
            //    AND books.name = :book
            //    ")

            //         ->bindValue(':book', $book)
            //         ->bindValue(':to_reporting_period', $to_reporting_period)
            //         ->bindValue(':from_reporting_period', $from_reporting_period)
            //         ->queryAll();
            //     $cancelled_checks = Yii::$app->db->createCommand("SELECT * FROM cadadr
            //         WHERE 
            //         reporting_period >= :from_reporting_period
            //         AND reporting_period <= :to_reporting_period
            //         AND book_name = :book
            //         AND is_cancelled = 1
            //         ORDER BY issuance_date
            //         ")
            //         ->bindValue(':from_reporting_period', $from_reporting_period)
            //         ->bindValue(':to_reporting_period', $to_reporting_period)
            //         ->bindValue(':book', $book)
            //         ->queryAll();

            return json_encode([
                'results' => $qry,
                // 'begin_balance' => $begin_balance,
                // 'adjustment' => $adjustment,
                // 'cancelled_checks' => $cancelled_checks
            ]);
        }
        return $this->render('cadadr');
    }
    public function actionAnnex3()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['to_reporting_period'];

            $book_id = $_POST['book'];
            $from_reporting_period = date('Y-01', strtotime($to_reporting_period));
            $db = Yii::$app->db;
            // if ($_SERVER['REMOTE_ADDR'] !== '210.1.103.26') {

            //     $db = Yii::$app->cloud_db;
            // }
            $query = $db->createCommand("SELECT
            cash_disbursement.id,
            cash_disbursement.check_or_ada_no as check_number,
            cash_disbursement.issuance_date as check_date,
            dv_aucs.particular,
            CONCAT(accounting_codes.object_code,' - ',accounting_codes.account_title) as account_name,
            IFNULL(totals.advances_amount,0) as advances_amount,
            IFNULL(totals.total_liquidation,0) as total_liquidation,
            IFNULL(totals.advances_amount,0)-
            IFNULL(totals.total_liquidation,0) as unliquidated,
            totals.province,
            totals.advance_type
            FROM
            cash_disbursement 
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id  = dv_aucs.id
            INNER JOIN 
            (
                SELECT
                advances.province,
                report_type.advance_type,
                cash_disbursement.id,
                accounting_codes.coa_object_code,
                SUM(advances_entries.amount)  as advances_amount,
                IFNULL(SUM(current.current_liquidation),0) as current_liquidation,
                SUM(advances_entries.amount)   -  IFNULL(SUM(current.current_liquidation),0) as current_unliquidated,
                IFNULL(SUM(prev.current_liquidation),0) as prev_liquidation,
                SUM(advances_entries.amount)   -  IFNULL(SUM(prev.current_liquidation),0) as prev_unliquidated,
                IFNULL(SUM(current.current_liquidation),0) +  IFNULL(SUM(prev.current_liquidation),0) as total_liquidation
                FROM 
                advances_entries
                LEFT JOIN report_type ON advances_entries.report_type = report_type.name
                LEFT JOIN accounting_codes ON advances_entries.object_code = accounting_codes.object_code
                LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
                LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                LEFT JOIN advances ON advances_entries.advances_id = advances.id
                LEFT JOIN 
               (
            
                    SELECT 
                    liquidation_balance_per_advances.advances_entries_id,
                    SUM(liquidation_balance_per_advances.total_withdrawals) as current_liquidation
                    FROM 
                    liquidation_balance_per_advances 
                    WHERE 
                    liquidation_balance_per_advances.reporting_period >= :from_reporting_period
                    AND liquidation_balance_per_advances.reporting_period <= :to_reporting_period
                    
                    GROUP BY liquidation_balance_per_advances.advances_entries_id
                ) as current ON advances_entries.id = current.advances_entries_id
                LEFT JOIN 
                (
                
                    SELECT 
                    liquidation_balance_per_advances.advances_entries_id,
                    SUM(liquidation_balance_per_advances.total_withdrawals) as current_liquidation
                    FROM 
                    liquidation_balance_per_advances 
                    WHERE 
                    liquidation_balance_per_advances.reporting_period < :from_reporting_period
                    
                    GROUP BY liquidation_balance_per_advances.advances_entries_id
                ) as prev ON advances_entries.id = prev.advances_entries_id
            WHERE 
            
            dv_aucs.reporting_period <= :to_reporting_period
            AND  report_type.advance_type  NOT LIKE 'Others'
            AND advances_entries.is_deleted NOT IN (1,9)
            AND advances_entries.book_id = :book_id
            GROUP BY 
            advances.province,
            report_type.advance_type,
            cash_disbursement.id,
            accounting_codes.coa_object_code) as totals ON cash_disbursement.id = totals.id
            LEFT JOIN accounting_codes ON totals.coa_object_code = accounting_codes.object_code
            WHERE totals.prev_unliquidated >0
            ORDER BY totals.province,cash_disbursement.issuance_date
            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->queryAll();
            $d = new DateTime($to_reporting_period);
            $report = $d->format('F t, Y');
            $result = ArrayHelper::index($query, null, 'advance_type');
            return json_encode([
                'result' => $result,
                'reporting_period' => $report
            ]);
        }
        return $this->render('annex3');
    }
    public function actionAnnexA()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['to_reporting_period'];
            $from_reporting_period = date('Y-01', strtotime($to_reporting_period));
            $query = Yii::$app->db->createCommand("SELECT
            cash_disbursement.id,
            cash_disbursement.check_or_ada_no as check_number,
            cash_disbursement.issuance_date as check_date,
            dv_aucs.particular,
            accounting_codes.account_title,
            accounting_codes.object_code,
            IFNULL(totals.advances_amount,0) as advances_amount,
            IFNULL(totals.total_liquidation,0) as total_liquidation,
            IFNULL(totals.advances_amount,0)-
            IFNULL(totals.total_liquidation,0) as unliquidated,
            totals.province,
            totals.advance_type
            FROM
            cash_disbursement 
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id  = dv_aucs.id
            INNER JOIN 
            (
            SELECT
            advances.province,
            report_type.advance_type,
            cash_disbursement.id,
            accounting_codes.coa_object_code,
            SUM(advances_entries.amount)  as advances_amount,
            IFNULL(SUM(current.current_liquidation),0) as current_liquidation,
            SUM(advances_entries.amount)   -  IFNULL(SUM(current.current_liquidation),0) as current_unliquidated,
            IFNULL(SUM(prev.current_liquidation),0) as prev_liquidation,
            SUM(advances_entries.amount)   -  IFNULL(SUM(prev.current_liquidation),0) as prev_unliquidated,
            IFNULL(SUM(current.current_liquidation),0) +  IFNULL(SUM(prev.current_liquidation),0) as total_liquidation
            FROM 
            advances_entries
            LEFT JOIN report_type ON advances_entries.report_type = report_type.name
            LEFT JOIN accounting_codes ON advances_entries.object_code = accounting_codes.object_code
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
            LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT JOIN 
            (
            
            SELECT 
            liquidation_balance_per_advances.advances_entries_id,
            SUM(liquidation_balance_per_advances.total_withdrawals) as current_liquidation
            FROM 
            liquidation_balance_per_advances 
            WHERE 
             liquidation_balance_per_advances.reporting_period >= :from_reporting_period
             AND liquidation_balance_per_advances.reporting_period <= :to_reporting_period
            
            GROUP BY liquidation_balance_per_advances.advances_entries_id
            ) as current ON advances_entries.id = current.advances_entries_id
            LEFT JOIN 
            (
            
            SELECT 
            liquidation_balance_per_advances.advances_entries_id,
            SUM(liquidation_balance_per_advances.total_withdrawals) as current_liquidation
            FROM 
            liquidation_balance_per_advances 
            WHERE 
             liquidation_balance_per_advances.reporting_period < :from_reporting_period
            
            GROUP BY liquidation_balance_per_advances.advances_entries_id
            ) as prev ON advances_entries.id = prev.advances_entries_id
            WHERE 
            
            dv_aucs.reporting_period <= :to_reporting_period
            AND  report_type.advance_type  NOT LIKE 'Others'
            AND advances_entries.is_deleted !=1
            GROUP BY 
            advances.province,
            report_type.advance_type,
            cash_disbursement.id,
            accounting_codes.coa_object_code) as totals ON cash_disbursement.id = totals.id
            LEFT JOIN accounting_codes ON totals.coa_object_code = accounting_codes.object_code
            WHERE  IFNULL(totals.advances_amount,0)-
            IFNULL(totals.total_liquidation,0)!=0
            ORDER BY totals.province,cash_disbursement.issuance_date
            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->queryAll();
            $d = new DateTime($to_reporting_period);
            $target_date = $d->format('Y-m-t');
            $res = ArrayHelper::index($query, null, [function ($element) {
                return $element['advance_type'];
            }, 'object_code']);
            $result = ArrayHelper::index($query, null, 'advance_type');
            return json_encode([
                'result' => $result,
                'target_date' => $target_date,
                'res' => $res,
                'reporting_period' => $d->format('F t, Y')
            ]);
        }
        return $this->render('annex_a');
    }


    public function actionRaaf()
    {



        if ($_POST) {
            $reporting_period = $_POST['to_reporting_period'];
            $province = $_POST['province'];
            $current_check = new Query();
            // "MAX(liquidation.check_number) as current_max",
            // "MIN(liquidation.check_number) as current_min"
            $current_check->select([
                "liquidation.check_number",
                "liquidation.check_range_id"

            ])

                ->from('liquidation')
                ->where("liquidation.check_number IS NOT NULL")
                ->andWhere("liquidation.check_range_id IS NOT NULL")
                ->andWhere("liquidation.check_number > 0")
                ->andWhere("liquidation.reporting_period = :reporting_period", ['reporting_period' => $reporting_period])
                ->andWhere("liquidation.exclude_in_raaf = 0")
                // ->orWhere("liquidation.cancel_reporting_period = :reporting_period", ['reporting_period' => $reporting_period])
                // ->groupBy("liquidation.check_range_id")
            ;

            $prev_check = new Query();
            $prev_check->select([
                "liquidation.check_number",
                "liquidation.check_range_id"


            ])

                ->from('liquidation')
                ->where("liquidation.check_number IS NOT NULL")
                ->andWhere("liquidation.check_range_id IS NOT NULL")
                ->andWhere("liquidation.check_number > 0")
                ->andWhere("liquidation.reporting_period < :reporting_period", ['reporting_period' => $reporting_period])
                ->andWhere("liquidation.exclude_in_raaf = 0")

                // ->orWhere("liquidation.cancel_reporting_period = :reporting_period", ['reporting_period' => $reporting_period])
                // ->groupBy("liquidation.check_range_id")
            ;
            // $qqq =  $current_check->all();
            // ob_clean();
            // echo "<pre>";
            // var_dump($qqq);
            // echo "</pre>";
            // return ob_get_clean();

            // $prev_check = new Query();
            // $prev_check->select([
            //     "COUNT(liquidation.check_number) as prev_count",
            //     "liquidation.check_range_id",
            // ])
            //     ->from('liquidation')
            //     ->where("liquidation.check_number IS NOT NULL")
            //     ->andWhere("liquidation.check_range_id IS NOT NULL")
            //     ->andWhere("liquidation.check_number > 0")
            //     ->andWhere("liquidation.reporting_period < :reporting_period", ['reporting_period' => $reporting_period])
            //     ->groupBy("liquidation.check_range_id");
            $current_query = $current_check->createCommand()->getRawSql();
            $prev_query = $prev_check->createCommand()->getRawSql();
            $query = Yii::$app->db->createCommand("SELECT
                check_range.`id`,
                check_range.`from`,
                check_range.`to`,
                check_range.province,
                check_range.`to` - check_range.`from` +1 as range_total,
                (check_range.`to` - check_range.`from` +1) -  IFNULL(prev.prev_count,0 ) as q_begin_balance,
                IF (check_range.begin_balance IS NULL ,  (check_range.`to` - check_range.`from`  + 1)-  IFNULL(prev.prev_count,0),check_range.begin_balance -  IFNULL(prev.prev_count,0))
                    as begin_balance,
                IFNULL(prev.prev_count,'') as  prev_count, 
                IFNULL(current.current_count,0) as current_count,
                IFNULL(current.current_max,'') as current_max,
                IFNULL(current.current_min,'') as current_min,
                IFNULL(prev.prev_count,0) +
                IFNULL(current.current_count,0) as total_use,
                (check_range.`to` - check_range.`from` +1) -(IFNULL(prev.prev_count,0) +
                IFNULL(current.current_count,0)) as balance
                FROM 
                check_range
                
                LEFT JOIN  (
                    SELECT 
                    COUNT(q.check_number) as prev_count,
                q.check_range_id
                FROM 
                    ($prev_query) as q
                    GROUP BY q.check_range_id
                    ) as prev ON check_range.id = prev.check_range_id
                LEFT JOIN  (
                    SELECT 
                    COUNT(q.check_number) as current_count,
                MAX(q.check_number) as current_max,
                MIN(q.check_number) as current_min,
                q.check_range_id
                FROM 
                    ($current_query) as q
                    GROUP BY q.check_range_id
                    ) as current ON check_range.id = current.check_range_id
                WHERE 
                check_range.province = :province
                AND check_range.from >0
                ORDER BY  check_range.`from`
                ")
                ->bindValue(':province', $province)
                ->queryAll();
            $duplicates = array();
            $skipped_check_number = array();
            $c = array();
            $lc = array();
            foreach ($query as $val) {
                $range = $val['from'] . ' to ' . $val['to'];
                // if ($val['balance'] < 0) {
                $q = Yii::$app->db->createCommand("SELECT
                        * FROM 
                        (
                        SELECT
                        COUNT(liquidation.check_number) as dup_count,
                        liquidation.check_number
                        FROM liquidation
                        
                        WHERE
                        liquidation.check_range_id =:id
                        AND liquidation.exclude_in_raaf = 0
                        GROUP BY liquidation.check_number 
                        ) as dup
                        WHERE dup.dup_count >1")
                    ->bindValue(':id', $val['id'])
                    ->queryAll();
                if (!empty($q)) {

                    $duplicates[$range] = $q;
                }
                // }
                $current_min  = intval($val['current_min']);
                $current_max  = intval($val['current_max']);
                $skipped_range = intval($val['current_count']) - ($current_max - $current_min + 1);
                if ($skipped_range > 0) {
                    $skipped_check_number[] = $skipped_range;
                    $checks = array();
                    foreach (range($current_min, $current_max) as $number) {
                        $checks[] = $number;
                    }
                    // CONVERT(liquidation.check_number ,UNSIGNED INTEGER) as check_number
                    $q = Yii::$app->db->createCommand("SELECT
                    CAST(liquidation.check_number AS UNSIGNED) as check_number
                    FROM liquidation
                    WHERE
                    liquidation.check_number >= :current_min
                    AND liquidation.check_number <= :current_max
                    AND liquidation.check_range_id = :id
                    GROUP BY liquidation.check_number
                    ORDER BY liquidation.check_number
                     ")
                        ->bindValue(':current_min', $current_min)
                        ->bindValue(':current_max', $current_max)
                        ->bindValue(':id', $val['id'])
                        ->queryAll();
                    foreach (array_column($q, 'check_number') as $qwe) {
                        $liquidation_checks[] = intval($qwe);
                    }


                    $skipped_check_number[$range]  = array_diff($checks, $liquidation_checks);
                    $c = $checks;
                    $lc = $liquidation_checks;
                }
            }
            // ob_clean();
            // echo "<pre>";
            // var_dump($skipped_check_number);
            // echo "</pre>";
            // return ob_get_clean();
            return json_encode([
                'results' => $query,
                'duplicates' => $duplicates,
                'skiped_checks' => $skipped_check_number,
                'c' => $c,
                'lc' => $lc,
                'province' => $province,

            ]);
        }
        return $this->render('raaf');
    }
    public function actionQr()
    {

        return $this->render('qr');
    }
    public function actionCdj()
    {

        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book = $_POST['book'];
            $q  = Yii::$app->db->createCommand("SELECT
                cdr.id,
                cdr.province,
                cdr.serial_number,
                cdr.reporting_period,
                cdr.report_type,
                IFNULL(cdj_data.withdrawals,0) as withdrawals,
                IFNULL(cdj_data.vat_nonvat,0) as vat_nonvat,
                IFNULL(cdj_data.expanded_tax,0) as expanded_tax,
                chart_of_accounts.uacs,
                chart_of_accounts.general_ledger,
                report_type.advance_type
                FROM cdr
                LEFT JOIN report_type ON cdr.report_type = report_type.name
                LEFT JOIN (
                
                SELECT
                liquidation_for_cdj.province,
                liquidation_for_cdj.reporting_period,
                liquidation_for_cdj.report_type,
                liquidation_for_cdj.chart_of_account_id,
                SUM(liquidation_for_cdj.withdrawals) as withdrawals,
                SUM(liquidation_for_cdj.vat_nonvat) as vat_nonvat,
                SUM(liquidation_for_cdj.expanded_tax) as expanded_tax
                
                FROM liquidation_for_cdj
                
                GROUP BY liquidation_for_cdj.province,
                liquidation_for_cdj.reporting_period,
                liquidation_for_cdj.report_type,
                liquidation_for_cdj.chart_of_account_id
                ) as cdj_data ON (cdr.province  =cdj_data.province 
                AND cdr.reporting_period = cdj_data.reporting_period
                 AND cdr.report_type = cdj_data.report_type )
                LEFT JOIN chart_of_accounts ON cdj_data.chart_of_account_id = chart_of_accounts.id
                WHERE cdr.reporting_period = :reporting_period
                AND cdr.serial_number LIKE :book
                ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':book', $book . '%');
            $query = $q->queryAll();

            $q_sql = $q->getRawSql();
            $conso = Yii::$app->db->createCommand("SELECT
                       q.uacs,
                q.general_ledger,
                SUM(q.withdrawals)+SUM(q.vat_nonvat)+SUM(q.expanded_tax)  as debit
                FROM 
                ($q_sql) as q
                GROUP BY q.uacs,
                q.general_ledger
                ")
                ->queryAll();
            $result = ArrayHelper::index($query, null, 'serial_number');
            $d = new DateTime($reporting_period);
            $report = $d->format('F t, Y');
            return json_encode([
                'result' => $result,
                'conso' => $conso,
                'period' => $report,
                'book' => $book
            ]);
        }
        return $this->render('cdj');
    }
    public function actionFurMfo()
    {
        if ($_POST) {

            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $mfo_code = $_POST['mfo_code'];


            $current_ors = new Query();
            $current_ors->select([

                "saob_rao.mfo_pap_code_id",
                "saob_rao.document_recieve_id",
                "saob_rao.book_id",
                "saob_rao.chart_of_account_id",
                "SUM(saob_rao.allotment_amount) as total_allotment",
                "SUM(saob_rao.ors_amount) as total_ors"
            ])
                ->from('saob_rao')

                ->where(" saob_rao.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("saob_rao.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
            if (strtolower($mfo_code) !== 'all') {

                $current_ors->andWhere("saob_rao.mfo_pap_code_id = :mfo_code", ['mfo_code' => $mfo_code]);
            } else  if (strtolower($mfo_code) === 'all' && !empty(Yii::$app->user->identity->division)) {
                $current_ors->andWhere("saob_rao.mfo_pap_code_id IN (SELECT id FROM mfo_pap_code WHERE mfo_pap_code.division = :division) ", ['division' => Yii::$app->user->identity->division]);
            }

            $current_ors->groupBy(
                "saob_rao.mfo_pap_code_id,
                saob_rao.document_recieve_id,
                saob_rao.book_id,
                saob_rao.chart_of_account_id"
            );


            $prev_ors = new Query();
            $prev_ors->select([

                "saob_rao.mfo_pap_code_id",
                "saob_rao.document_recieve_id",
                "saob_rao.book_id",
                "saob_rao.chart_of_account_id",
                "SUM(saob_rao.allotment_amount) as total_allotment",
                "SUM(saob_rao.ors_amount) as total_ors"
            ])
                ->from('saob_rao')

                ->where(" saob_rao.reporting_period < :from_reporting_period", ['from_reporting_period' => $from_reporting_period]);
            if (strtolower($mfo_code) !== 'all') {

                $prev_ors->andWhere("saob_rao.mfo_pap_code_id = :mfo_code", ['mfo_code' => $mfo_code]);
            } else  if (strtolower($mfo_code) === 'all' && !empty(Yii::$app->user->identity->division)) {
                $prev_ors->andWhere("saob_rao.mfo_pap_code_id IN (SELECT id FROM mfo_pap_code WHERE mfo_pap_code.division = :division) ", ['division' => Yii::$app->user->identity->division]);
            }

            $prev_ors->groupBy(
                "saob_rao.mfo_pap_code_id,
                saob_rao.document_recieve_id,
                saob_rao.book_id,
                saob_rao.chart_of_account_id"
            );

            $sql_current_ors = $current_ors->createCommand()->getRawSql();
            $sql_prev_ors = $prev_ors->createCommand()->getRawSql();
            $query = Yii::$app->db->createCommand("SELECT 
            mfo_pap_code.`name` as mfo_name,
            document_recieve.`name` as document_name,
            major_accounts.`name` as major_name,
            major_accounts.`object_code` as major_object_code,
            
            sub_major_accounts.`name` as sub_major_name,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
            IFNULL(prev.total_ors ,0)as prev_total_ors,
            IFNULL(current.total_ors,0) as current_total_ors,
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) as ors_to_date,
            books.name as book_name
            FROM ($sql_current_ors) as current
            LEFT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
            AND current.document_recieve_id = prev.document_recieve_id
            AND current.book_id = prev.book_id
            AND current.chart_of_account_id = prev.chart_of_account_id)
            LEFT JOIN chart_of_accounts ON current.chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
            LEFT JOIN mfo_pap_code ON current.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON current.document_recieve_id = document_recieve.id
            LEFT JOIN books ON current.book_id  = books.id
            WHERE
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0 OR 
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) >0
            UNION
            SELECT
            mfo_pap_code.`name` as mfo_name,
            document_recieve.`name` as document_name,
            major_accounts.`name` as major_name,
            major_accounts.`object_code` as major_object_code,
            
            sub_major_accounts.`name` as sub_major_name,
            chart_of_accounts.uacs,
            chart_of_accounts.general_ledger,
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
            IFNULL(prev.total_ors ,0)as prev_total_ors,
            IFNULL(current.total_ors,0) as current_total_ors,
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) as ors_to_date,
            books.name as book_name
            FROM ($sql_current_ors) as current
            RIGHT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
            AND current.document_recieve_id = prev.document_recieve_id
            AND current.book_id = prev.book_id
            AND current.chart_of_account_id = prev.chart_of_account_id)
            LEFT JOIN chart_of_accounts ON prev.chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
            LEFT JOIN mfo_pap_code ON prev.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON prev.document_recieve_id = document_recieve.id
            LEFT JOIN books ON prev.book_id  = books.id
            WHERE
            IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0 OR 
            IFNULL(prev.total_ors ,0) + 
            IFNULL(current.total_ors,0) >0
            
            ")->queryAll();





            $result = ArrayHelper::index($query, 'uacs', [function ($element) {
                return $element['mfo_name'];
            }, 'document_name', 'book_name']);


            $allotment_total = array();
            foreach ($result as $mfo => $val1) {
                foreach ($val1 as $document => $val2) {
                    foreach ($val2 as $book => $val3) {

                        foreach ($val3 as $uacs => $val4) {
                            $allot = floatval($result[$mfo][$document][$book][$uacs]['allotment']);
                            if ($allot != 0) {

                                $allotment_total[$mfo][$document][$book][$uacs] = $allot;
                            }
                        }
                    }
                }
            }



            $result2 = ArrayHelper::index($query, null, [function ($element) {
                return $element['major_name'];
            }, 'document_name',]);
            $conso_saob = array();
            $sort_by_mfo_document = ArrayHelper::index($query, null, [function ($element) {
                return $element['mfo_name'];
            }, 'document_name', 'book_name']);


            foreach ($sort_by_mfo_document as $mfo => $mfo_val) {
                foreach ($mfo_val as $document => $document_val) {
                    foreach ($document_val as $book => $book_val) {
                        // echo "<pre>";
                        // var_dump( $sort_by_mfo_document[$mfo][$document][$book]);
                        // echo "</pre>";
                        // die();
                        $to_date = round(array_sum(array_column($book_val, 'ors_to_date')), 2);
                        $conso_saob[] =
                            [
                                'mfo_name' => $mfo,
                                'document' => $document,
                                'book' => $book,
                                'beginning_balance' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document][$book], 'allotment')), 2),
                                'prev' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document][$book], 'prev_total_ors')), 2),
                                'current' => round(array_sum(array_column($book_val, 'current_total_ors')), 2),
                                'to_date' => round(array_sum(array_column($book_val, 'ors_to_date')), 2),
                            ];
                    }
                }
            }


            return json_encode(['result' => $result2, 'allotments' => $allotment_total, 'conso_saob' => $conso_saob]);
        }
        return $this->render('fur_mfo');
    }
    public function actionDvCadadr()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $book = $_POST['book'];


            $query = Yii::$app->db->createCommand("SELECT * FROM dv_cadadr
            WHERE 
            reporting_period >= :from_reporting_period
            AND reporting_period <= :to_reporting_period
            AND book_name = :book
            ORDER BY issuance_date
            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':book', $book)
                ->queryAll();
            $d = new DateTime($from_reporting_period);
            $w = new DateTime('2021-10');
            $begin_balance = 0;
            if ($d->format('Y-m') >= $w->format('Y-m')) {
                $begin_balance = Yii::$app->db->createCommand("SELECT 
                    IFNULL(SUM(total_nca_recieve) - (SUM(total_check_issued)+SUM(total_ada_issued)),0) as begin_balance
                   FROM cadadr_balances
                   WHERE 
                   cadadr_balances.reporting_period < :from_reporting_period 
                   and
                   cadadr_balances.reporting_period >= '2021-10' 
                   AND cadadr_balances.book_name = :book
                   ")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
                $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
                    SUM(cash_adjustment.amount) as total_amount
                    FROM  cash_adjustment
                    LEFT JOIN books ON cash_adjustment.book_id = books.id
                    WHERE 
                    cash_adjustment.reporting_period < :from_reporting_period 
                    AND
                    cash_adjustment.reporting_period >= '2021-10' 
                    AND books.name = :book")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
            } else {
                $begin_balance = Yii::$app->db->createCommand("SELECT 
                    IFNULL(SUM(total_nca_recieve) - (SUM(total_check_issued)+SUM(total_ada_issued)),0) as begin_balance
                   FROM cadadr_balances
                   WHERE 
                   cadadr_balances.reporting_period < :from_reporting_period 
                   AND cadadr_balances.book_name = :book
                   ")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
                $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
                    SUM(cash_adjustment.amount) as total_amount
                    FROM  cash_adjustment
                    LEFT JOIN books ON cash_adjustment.book_id = books.id
                    WHERE 
                    cash_adjustment.reporting_period < :from_reporting_period 
                    AND books.name = :book")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
            }



            // $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
            // SUM(cash_adjustment.amount) as total_amount
            // FROM  cash_adjustment
            // LEFT JOIN books ON cash_adjustment.book_id = books.id
            // WHERE 
            // cash_adjustment.reporting_period < :from_reporting_period 
            // AND books.name = :book")
            //     ->bindValue(':from_reporting_period', $from_reporting_period)
            //     ->bindValue(':book', $book)
            //     ->queryScalar();
            $begin_balance  += $adjustment_begin_balance;
            $adjustment = Yii::$app->db->createCommand("SELECT * 
           FROM cash_adjustment
           WHERE reporting_period <= :to_reporting_period
           AND reporting_period >= :from_reporting_period
           ")
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->queryAll();
            // $result2 = ArrayHelper::index($query, null, [function ($element) {
            //     return $element['division'];
            // }, 'mfo_name', 'major_name', 'sub_major_name',]);
            // echo "<pre>";
            // var_dump($query);
            // echo "</pre>";
            // die();

            return json_encode(['results' => $query, 'begin_balance' => $begin_balance, 'adjustment' => $adjustment]);
        }
        return $this->render('dv_cadadr');
    }


    public function actionPass()
    {
        // echo substr(md5(uniqid(mt_rand(), true)), 0, 8);
        // $num = 123456789;
        // if(strlen($num)<4){

        //     $string = substr(str_repeat(0, 4) . $num, -4);
        // }
        // else {
        //     $string = $num;

        // }
        $x = explode(' ', 'Common Electrical Supplies');
        var_dump($x);
        die();
        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `advances_entries` WHERE id = :id)")
            ->bindValue(':id', 4705)
            ->queryScalar();
        // return intval($query);
        $date = date("Y");
        $responsibility_center = (new \yii\db\Query())
            ->select("name")
            ->from('po_responsibility_center')
            ->where("id =:id", ['id' => 4])
            ->one();
        // $province = Yii::$app->user->identity->province;
        $province = 'sds';
        $latest_tracking_no = Yii::$app->db->createCommand(
            "SELECT substring_index(tracking_number,'-',-1)as q
        FROM `po_transaction`
        WHERE tracking_number LIKE :province
         ORDER BY q DESC LIMIT 1"
        )
            ->bindValue(':province', $province . '%')
            ->queryScalar();
        if (!empty($latest_tracking_no)) {
            $last_number = $latest_tracking_no + 1;
        } else {
            $last_number = 1;
        }
        $final_number = '';
        // for ($y = strlen($last_number); $y < 3; $y++) {
        //     $final_number .= 0;
        // }
        if (strlen($last_number) < 4) {

            $final_number = substr(str_repeat(0, 3) . $last_number, -3);
        } else {
            $final_number = $last_number;
        }

        // $final_number .= $last_number;
        $tracking_number = 'SDS' . '-' . trim($responsibility_center['name']) . '-' . $date . '-' . $final_number;
        return  $tracking_number;
    }
    public function actionConsoTrialBalance()
    {
        if ($_POST) {
            $params = [];
            // return json_encode(Yii::$app->db->createCommand("SELECT * FROM jev_preparation WHERE $book_sql",$params)->getRawSql());


            $to_reporting_period = $_POST['reporting_period'];
            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = $r_period_date->format('F Y');
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_id  = $_POST['book_id'];
            $entry_type = strtolower($_POST['entry_type']);
            $book_name = strtoupper($book_id);
            $and = '';
            $sql = '';
            $type = '';

            // return json_encode($entry_type);
            if ($entry_type !== 'post-closing') {
                $and = 'AND ';
                if ($entry_type === 'pre-closing') {
                    $type = 'Non-Closing';
                } else if ($entry_type = 'closing') {
                    $type = 'Closing';
                }
                $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['=', 'jev_preparation.entry_type', $type], $params);
            }
            $book_and = '';
            $book_sql1 = '';
            $book_sql2 = '';
            $book_sql3 = '';
            $book_params = [];
            if ($book_id !== 'all') {
                $book_and = 'AND ';
                $book_sql1 = Yii::$app->db->getQueryBuilder()->buildCondition("EXISTS (SELECT id FROM books WHERE books.`type` =:book_id AND jev_preparation.book_id = books.id)", $book_params);
                $book_sql2 = Yii::$app->db->getQueryBuilder()->buildCondition("EXISTS (SELECT id FROM books WHERE books.`type` =:book_id AND jev_beginning_balance.book_id = books.id)", $book_params);

                // EXISTS (SELECT id FROM books WHERE books.`name` IN ('Fund 01','Rapid LP') AND jev_beginning_balance.book_id = books.id)
            }

            $query = Yii::$app->db->createCommand("SELECT 
            chart_of_accounts.uacs as object_code,
            chart_of_accounts.general_ledger as account_title,
            chart_of_accounts.normal_balance,
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) as total_debit_credit,
            begin_balance.total_beginning_balance as begin_balance
             FROM (
            SELECT
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as obj_code
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.reporting_period <=:to_reporting_period
             $book_and $book_sql1
            GROUP BY obj_code
            ) as jev_object_codes
            
            LEFT JOIN (
            SELECT

            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit,
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as chart
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.reporting_period >=:from_reporting_period
            AND jev_preparation.reporting_period <=:to_reporting_period
            $book_and $book_sql1
            $and $sql
            GROUP BY chart) as accounting_entries ON jev_object_codes.obj_code = accounting_entries.chart
            LEFT JOIN (SELECT 

                accounting_codes.object_code,
                (CASE
                    WHEN accounting_codes.normal_balance = 'Debit' THEN SUM(jev_beginning_balance_item.debit)  - SUM(jev_beginning_balance_item.credit)
                    ELSE SUM(jev_beginning_balance_item.credit) - SUM(jev_beginning_balance_item.debit)
                END) as total_beginning_balance
                FROM jev_beginning_balance_item 
              LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
              LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
              LEFT JOIN books ON jev_beginning_balance.book_id = books.id
              WHERE 
                    jev_beginning_balance.`year` = :_year
                    $book_and $book_sql2
            
            GROUP BY accounting_codes.object_code
            ) as begin_balance  ON jev_object_codes.obj_code = begin_balance.object_code
            LEFT JOIN chart_of_accounts ON jev_object_codes.obj_code = chart_of_accounts.uacs
            
            WHERE 
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) !=0
            ORDER BY chart_of_accounts.uacs  ASC
            ", $params)
                ->bindValue(':_year', $year)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->queryAll();;
            // return json_encode($query->getRawSql());

            return json_encode(['result' => $query, 'month' => $month, 'book_name' => $book_name]);
        }

        return $this->render('conso_trial_balance');
    }
    public function actionConsoSubTrialBalance()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = $r_period_date->format('F Y');
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_id  = $_POST['book_id'];
            $book_and = '';
            $book_sql1 = '';
            $book_sql2 = '';
            $book_sql3 = '';
            $book_params = [];
            // return json_encode($book_id);
            if ($book_id !== 'all') {
                $book_and = 'AND ';
                $book_sql1 = Yii::$app->db->getQueryBuilder()->buildCondition("EXISTS (SELECT id FROM books WHERE books.`type` =:book_id AND jev_preparation.book_id = books.id)", $book_params);
                $book_sql2 = Yii::$app->db->getQueryBuilder()->buildCondition("EXISTS (SELECT id FROM books WHERE books.`type` =:book_id AND jev_beginning_balance.book_id = books.id)", $book_params);

                // EXISTS (SELECT id FROM books WHERE books.`name` IN ('Fund 01','Rapid LP') AND jev_beginning_balance.book_id = books.id)
            }
            $query = Yii::$app->db->createCommand("SELECT 
            accounting_codes.object_code,
            accounting_codes.account_title as account_title,
            accounting_codes.normal_balance,
            (CASE
            WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0)
            ELSE IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0)
            END) as total_debit_credit,
            beginning_balance.total_beginning_balance as begin_balance,
			(CASE
            WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0) +(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) as balance
            
            FROM (
            SELECT
            jev_accounting_entries.object_code
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.reporting_period <= :to_reporting_period
             $book_and $book_sql1
            GROUP BY jev_accounting_entries.object_code
            ) as jev_object_codes
            LEFT JOIN (SELECT
            
            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit,
            jev_accounting_entries.object_code 
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
          jev_preparation.reporting_period >=  :from_reporting_period
            AND jev_preparation.reporting_period <=  :to_reporting_period
            $book_and $book_sql1
            GROUP BY jev_accounting_entries.object_code) as accounting_entries ON jev_object_codes.object_code = accounting_entries.object_code
            LEFT JOIN (SELECT 
                accounting_codes.object_code,
                (CASE
                    WHEN accounting_codes.normal_balance = 'Debit' THEN jev_beginning_balance_item.debit  - jev_beginning_balance_item.credit
                    ELSE jev_beginning_balance_item.credit - jev_beginning_balance_item.debit
                END) as total_beginning_balance
                FROM jev_beginning_balance_item 
              LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
              LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
              LEFT JOIN books ON jev_beginning_balance.book_id = books.id
              WHERE 
                    jev_beginning_balance.`year` = :_year
                    $book_and $book_sql2
						GROUP BY  accounting_codes.object_code
						) as beginning_balance ON jev_object_codes.object_code = beginning_balance.object_code
            LEFT JOIN accounting_codes ON jev_object_codes.object_code = accounting_codes.object_code
            WHERE (CASE
            WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0) +(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) !=0
            ORDER BY jev_object_codes.object_code
            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->bindValue(':_year', $year)
                ->queryAll();
            return json_encode(['query' => $query, 'month' => $month]);
        }

        return $this->render('conso_sub_trial_balance');
    }
    public function actionTrialBalance()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];

            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = date('F Y', strtotime($to_reporting_period));
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_id  = $_POST['book_id'];
            $entry_type = strtolower($_POST['entry_type']);
            $book_name = Books::findOne($book_id)->name;
            $and = '';
            $sql = '';
            $type = '';
            $params = [];
            // return json_encode($entry_type);
            if ($entry_type !== 'post-closing') {
                $and = 'AND ';
                if ($entry_type === 'pre-closing') {
                    $type = 'Non-Closing';
                } else if ($entry_type = 'closing') {
                    $type = 'Closing';
                }
                $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['=', 'jev_preparation.entry_type', $type], $params);
            }



            $query = Yii::$app->db->createCommand("SELECT 
            chart_of_accounts.uacs as object_code,
            chart_of_accounts.general_ledger as account_title,
            chart_of_accounts.normal_balance,
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) as total_debit_credit,
            begin_balance.total_beginning_balance as begin_balance

            
             FROM (
            
            SELECT
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as obj_code
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.book_id = :book_id

            AND jev_preparation.reporting_period <=:to_reporting_period
            GROUP BY obj_code
            UNION
            SELECT 
                SUBSTRING_INDEX(jev_beginning_balance_item.object_code,'_',1) as object_code
                FROM jev_beginning_balance
                LEFT JOIN jev_beginning_balance_item ON jev_beginning_balance.id = jev_beginning_balance_item.jev_beginning_balance_id
            WHERE  jev_beginning_balance.book_id=:book_id
            GROUP BY object_code
            ) as jev_object_codes
            
            LEFT JOIN (
            SELECT
            
            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit,
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as chart
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.book_id = :book_id
            AND jev_preparation.reporting_period >=:from_reporting_period
            AND jev_preparation.reporting_period <=:to_reporting_period
            $and $sql
            GROUP BY chart)
             as accounting_entries ON jev_object_codes.obj_code = accounting_entries.chart
            LEFT JOIN (SELECT 
                    b_balance.object_code,
                    SUM(b_balance.total_beginning_balance) as total_beginning_balance
                    FROM (
                    SELECT 
                    SUBSTRING_INDEX(accounting_codes.object_code,'_',1) as object_code,
                    (CASE
                    WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(jev_beginning_balance_item.debit,0)  - IFNULL(jev_beginning_balance_item.credit,0)
                    ELSE IFNULL(jev_beginning_balance_item.credit,0) - IFNULL(jev_beginning_balance_item.debit,0)
                    END) as total_beginning_balance
                    FROM jev_beginning_balance_item 
                    LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
                    LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
                    LEFT JOIN books ON jev_beginning_balance.book_id = books.id
                    WHERE 
                    jev_beginning_balance.`year` = :_year
                    AND jev_beginning_balance.book_id = :book_id
                    ) b_balance
                    GROUP BY b_balance.object_code

            
            
            ) as begin_balance  ON jev_object_codes.obj_code = begin_balance.object_code
            LEFT JOIN chart_of_accounts ON jev_object_codes.obj_code = chart_of_accounts.uacs
            
            WHERE 
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) !=0
            ORDER BY chart_of_accounts.uacs  ASC
            ", $params)
                ->bindValue(':_year', $year)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->queryAll();;
            // return json_encode($query->getRawSql());

            return json_encode(['result' => $query, 'month' => $month, 'book_name' => $book_name]);
        }

        return $this->render('trial_balance');
    }

    public function actionDetailedFinancialPosition()
    {
        if ($_POST) {

            $to_reporting_period = $_POST['reporting_period'];
            $period = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $current_year = $period->format('Y');
            $from_reporting_period  = $period->format('Y') . '-01';
            $last_year_from = $period->format('Y') - 1 . '-01';
            $last_year_to = $period->format('Y') - 1 . '-12';
            $last_year = $period->format('Y') - 1;
            $book_id = $_POST['book_id'];


            $query = Yii::$app->db->createCommand("SELECT 
            chart_of_accounts.account_group,
            major_accounts.`name` as major_name,
            chart_of_accounts.general_ledger,
            chart_of_accounts.current_noncurrent,
            sub_major_accounts.name as sub_major_name,
            IFNULL(current.balance,0) as current_balance,
            IFNULL(last_year.balance,0)as last_year_balance
            FROM 

            (SELECT chart_of_accounts.id
            FROM jev_accounting_entries
            INNER JOIN chart_of_accounts ON SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) = chart_of_accounts.uacs
            GROUP BY chart_of_accounts.id
            ) as jev_chart_of_accounts


            LEFT JOIN 
            (SELECT chart_of_accounts.id,
                (
                CASE
                WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+ SUM(jev_accounting_entries.debit)-SUM(jev_accounting_entries.credit)
                ELSE IFNULL(begin_balance.total_beginning_balance,0)+ SUM(jev_accounting_entries.credit)-SUM(jev_accounting_entries.debit)
                END
                ) as balance
            FROM 
            jev_preparation
            INNER JOIN jev_accounting_entries ON jev_preparation.id = jev_accounting_entries.jev_preparation_id
            INNER JOIN chart_of_accounts ON SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1)  = chart_of_accounts.uacs
            LEFT JOIN (SELECT 
                b_balance.object_code,
                SUM(b_balance.total_beginning_balance) as total_beginning_balance
                FROM (
                SELECT 
                SUBSTRING_INDEX(accounting_codes.object_code,'_',1) as object_code,
                (CASE
                WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(jev_beginning_balance_item.debit,0)  - IFNULL(jev_beginning_balance_item.credit,0)
                ELSE IFNULL(jev_beginning_balance_item.credit,0) - IFNULL(jev_beginning_balance_item.debit,0)
                END) as total_beginning_balance
                FROM jev_beginning_balance_item 
                LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
                LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
                LEFT JOIN books ON jev_beginning_balance.book_id = books.id
                WHERE 
                
                jev_beginning_balance.`year` = :current_year
                AND jev_beginning_balance.book_id = :book_id
                ) b_balance
                GROUP BY b_balance.object_code

        
        
        ) as begin_balance  ON SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) = begin_balance.object_code
            WHERE 
            jev_preparation.reporting_period >=:from_reporting_period
            AND jev_preparation.book_id = :book_id
            AND jev_preparation.reporting_period <=:to_reporting_period
            AND chart_of_accounts.account_group IN ('Assets','Liabilities','Equity')
            GROUP BY  chart_of_accounts.id
            
            ) as current ON jev_chart_of_accounts.id = current.id
            LEFT JOIN (
                SELECT chart_of_accounts.id,
                (
                CASE
                WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+ SUM(jev_accounting_entries.debit)-SUM(jev_accounting_entries.credit)
                ELSE IFNULL(begin_balance.total_beginning_balance,0)+ SUM(jev_accounting_entries.credit)-SUM(jev_accounting_entries.debit)
                END
                ) as balance
            FROM 
            jev_preparation
            INNER JOIN jev_accounting_entries ON jev_preparation.id = jev_accounting_entries.jev_preparation_id
            INNER JOIN chart_of_accounts ON SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1)  = chart_of_accounts.uacs
            LEFT JOIN (SELECT 
                b_balance.object_code,
                SUM(b_balance.total_beginning_balance) as total_beginning_balance
                FROM (
                SELECT 
                SUBSTRING_INDEX(accounting_codes.object_code,'_',1) as object_code,
                (CASE
                WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(jev_beginning_balance_item.debit,0)  - IFNULL(jev_beginning_balance_item.credit,0)
                ELSE IFNULL(jev_beginning_balance_item.credit,0) - IFNULL(jev_beginning_balance_item.debit,0)
                END) as total_beginning_balance
                FROM jev_beginning_balance_item 
                LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
                LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
                LEFT JOIN books ON jev_beginning_balance.book_id = books.id
                WHERE 
                jev_beginning_balance.`year` = :last_year
                AND jev_beginning_balance.book_id = :book_id
                ) b_balance

                GROUP BY b_balance.object_code
        
        ) as begin_balance  ON SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) = begin_balance.object_code
            WHERE 
            jev_preparation.reporting_period >=:last_year_from
            AND jev_preparation.book_id = :book_id
            AND jev_preparation.reporting_period <=:last_year_to
            AND chart_of_accounts.account_group IN ('Assets','Liabilities','Equity')
            GROUP BY  chart_of_accounts.id
            
            ) as last_year ON jev_chart_of_accounts.id = last_year.id
            INNER JOIN chart_of_accounts ON jev_chart_of_accounts.id = chart_of_accounts.id
            INNER JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            INNER JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
            WHERE 
            chart_of_accounts.account_group IN ('Assets','Liabilities','Equity')
            AND (
                IFNULL(current.balance,0) !=0
                OR
                   IFNULL(last_year.balance,0)!=0)
            ORDER BY chart_of_accounts.account_group,
            major_accounts.`name`")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':last_year_from', $last_year_from)
                ->bindValue(':last_year_to', $last_year_to)
                ->bindValue(':book_id', $book_id)
                ->bindValue(':current_year', $current_year)
                ->bindValue(':last_year', $last_year)
                ->queryAll();


            $result = ArrayHelper::index($query, null, [function ($element) {
                return $element['account_group'];
            }, 'current_noncurrent', 'major_name', 'sub_major_name']);
            return json_encode(
                [
                    'result' => $result,
                    'reporting_period' => $period->format('F Y'),
                    'current_year' => $period->format('Y'),
                    'last_year' => $period->format('Y') - 1

                ]
            );
        }
        return $this->render('detailed_financial_position');
    }
    public function actionWithholdingAndRemittanceSummary()
    {

        $searchModel = new WithholdingAndRemittanceSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('withholding_and_remittance_summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionTransactionTracking()
    {

        $searchModel = new TransactionTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('transaction_tracking', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionRandomString()
    {
        $n = 8;
        echo $this->getName($n);
    }
    function getName($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }


    public function actionDvTimeMonitoring()
    {
        if (Yii::$app->request->isPost) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $query = Yii::$app->db->createCommand("SELECT 
            dv_aucs.dv_number,
            payee.account_name as payee,
            IFNULL(DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d'),'') as dv_in,
            IFNULL(DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d'),'') as dv_out,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.ada_number,
            cash_disbursement.issuance_date,
            cash_disbursement.begin_time as cash_in,
            cash_disbursement.out_time as cash_out,
            IFNULL(dv_entry_amount.dv_amount,0) as dv_amount,

            dv_aucs.is_cancelled,
            cash_disbursement.is_cancelled

            FROM
            dv_aucs
            INNER JOIN payee ON dv_aucs.payee_id = payee.id
            INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
            LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,
            SUM(dv_aucs_entries.amount_disbursed)as dv_amount 
            FROM dv_aucs_entries
            GROUP BY 
            dv_aucs_entries.dv_aucs_id) AS dv_entry_amount ON dv_aucs.id = dv_entry_amount.dv_aucs_id

            WHERE

            dv_aucs.is_cancelled !=1
  

            AND 
            cash_disbursement.is_cancelled !=1
            AND dv_aucs.reporting_period >= :from_reporting_period
            AND dv_aucs.reporting_period <= :to_reporting_period
            ORDER BY cash_disbursement.issuance_date

            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->queryAll();
            $static_holidays = [
                '01-01',
                '04-09',
                '05-01',
                '08-28',
                '11-01',
                '11-30',
                '12-08',
                '12-25',
                '12-30',
            ];
            $holidays = [];
            $from_reporting_period_format = DateTime::createFromFormat('Y-m', $from_reporting_period);
            $to_reporting_period_format = DateTime::createFromFormat('Y-m', $from_reporting_period);
            foreach ($static_holidays as $val) {
                $holidays[] = $from_reporting_period_format->format('Y') . '-' . $val;
            }
            foreach ($static_holidays as $val) {
                $holidays[] = $to_reporting_period_format->format('Y') . '-' . $val;
            }
            // var_dump(array_unique($holidays));
            // die();

            $holidays_query = Yii::$app->db->createCommand("SELECT holidays.date FROM holidays
            WHERE holidays.date >= :from_year
            AND holidays.date <= :to_year
            ")
                ->bindValue(':from_year', $from_reporting_period_format->format('Y') . '-01' . '-01')
                ->bindValue(':to_year', $to_reporting_period_format->format('Y') . '-12' . '-31')
                ->queryAll();
            // var_dump(array_merge(array_unique($holidays), $holidays_query));
            // var_dump($from_reporting_period . '-01');
            // var_dump($to_reporting_period . '-01');
            // var_dump($holidays_query->queryAll());
            // die();
            return json_encode(['data' => $query, 'holidays' => array_merge(array_unique($holidays), array_column($holidays_query, 'date'))]);
        }
        // echo $this->getWorkdays('2022-05-23', '2022-05-24');

        return $this->render('dv_time_monitoring');
    }
    public function actionDvTimeMonitoringSummary()
    {

        if ($_POST) {
            $year = $_POST['year'];
            $query  = Yii::$app->db->createCommand("SELECT 
            dv_aucs.reporting_period,
            COUNT(dv_aucs.id) as total_dv,
            SUM(IF(IF((5 * (DATEDIFF(DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d'), DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d')) DIV 7) 
            + MID('0123444401233334012222340111123400012345001234550', 7 * WEEKDAY(DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d')) 
            + WEEKDAY(DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d')) + 1, 1))  +0=0,1,(5 * (DATEDIFF(DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d'), DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d')) DIV 7) 
            + MID('0123444401233334012222340111123400012345001234550', 7 * WEEKDAY(DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d')) 
            + WEEKDAY(DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d')) + 1, 1)) +0) - (SELECT COUNT(hol_days.date)
            FROM 
            (SELECT 
            holidays.date
            FROM holidays
            UNION 
            VALUES
            
             ('2022-01-01'),
             ('2022-04-09'),
              ('2022-05-01'),
             ('2022-08-28'),
              ('2022-11-30'),
               ('2022-12-08'),
                ('2022-12-25'),
                 ('2022-12-30')
            ) hol_days
            
            WHERE 
            hol_days.date >=DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d')
            AND hol_days.date <=DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d')
            AND
            DAYOFWEEK(hol_days.date) != 7
            AND DAYOFWEEK(hol_days.date) != 1
            )<=3,1,0)) as dv_within 
            
                        FROM
                        dv_aucs
                        INNER JOIN payee ON dv_aucs.payee_id = payee.id
                        INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
                        LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,
                        SUM(dv_aucs_entries.amount_disbursed)as dv_amount 
                        FROM dv_aucs_entries
                        GROUP BY 
                        dv_aucs_entries.dv_aucs_id) AS dv_entry_amount ON dv_aucs.id = dv_entry_amount.dv_aucs_id
            
                        WHERE
            
                        dv_aucs.is_cancelled !=1
                        AND 
                        cash_disbursement.is_cancelled !=1
                        AND dv_aucs.reporting_period LIKE :_year
                      
            GROUP BY dv_aucs.reporting_period")
                ->bindValue(':_year', $year . '%')
                ->queryAll();
            return json_encode($query);
        }
        return $this->render('dv_time_monitoring_summary');
    }

    public function dvTimeMonitoringQuery($from_reporting_period, $to_reporting_period)
    {
        if (empty($from_reporting_period || empty($to_reporting_period))) {
            return;
        }
        $query = Yii::$app->db->createCommand("SELECT 
        dv_aucs.dv_number,
        payee.account_name as payee,
        IFNULL(DATE_FORMAT(dv_aucs.in_timestamp,'%Y-%m-%d'),'') as dv_in,
        IFNULL(DATE_FORMAT(dv_aucs.out_timestamp,'%Y-%m-%d'),'') as dv_out,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.issuance_date,
        cash_disbursement.begin_time as cash_in,
        cash_disbursement.out_time as cash_out,
        IFNULL(dv_entry_amount.dv_amount,0) as dv_amount,

        dv_aucs.is_cancelled,
        cash_disbursement.is_cancelled

        FROM
        dv_aucs
        INNER JOIN payee ON dv_aucs.payee_id = payee.id
        INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
        LEFT JOIN (SELECT dv_aucs_entries.dv_aucs_id,
        SUM(dv_aucs_entries.amount_disbursed)as dv_amount 
        FROM dv_aucs_entries
        GROUP BY 
        dv_aucs_entries.dv_aucs_id) AS dv_entry_amount ON dv_aucs.id = dv_entry_amount.dv_aucs_id

        WHERE

        dv_aucs.is_cancelled !=1
        AND 
        cash_disbursement.is_cancelled !=1
        AND dv_aucs.reporting_period >= :from_reporting_period
        AND dv_aucs.reporting_period <= :to_reporting_period
        ORDER BY cash_disbursement.issuance_date

        ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->queryAll();
        return $query;
    }
    public function actionDvTimeMonitoringExport()
    {
        // return YIi::$app->memem->getWorkdays('2022-06-17', '2022-06-20');
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $from_reporting_period_format = DateTime::createFromFormat('Y-m', $from_reporting_period);
            $to_reporting_period_format = DateTime::createFromFormat('Y-m', $from_reporting_period);
            // $holidays_query = Yii::$app->db->createCommand("SELECT holidays.date FROM holidays
            // WHERE holidays.date >= :from_year
            // AND holidays.date <= :to_year
            // ")
            //     ->bindValue(':from_year', $from_reporting_period_format->format('Y') . '-01' . '-01')
            //     ->bindValue(':to_year', $to_reporting_period_format->format('Y') . '-12' . '-31')
            //     ->queryAll();
            $query = $this->dvTimeMonitoringQuery($from_reporting_period, $to_reporting_period);
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->setAutoFilter('A1:F1');
            $sheet->setCellValue('A1', "Payee");
            $sheet->setCellValue('B1', "DV Number");
            $sheet->setCellValue('C1', "Accounting DV IN");
            $sheet->setCellValue('D1', "Accounting DV Out");
            $sheet->setCellValue('E1', "Cash DV IN");
            $sheet->setCellValue('F1', 'Cash DV Out');

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
            foreach ($query as $val) {

                $payee = $val['payee'];
                $dv_number = $val['dv_number'];
                $accounting_in = $val['dv_in'];
                $accounting_out = $val['dv_out'];
                $check_or_ada_no = $val['check_or_ada_no'];
                $ada_number = $val['ada_number'];
                $issuance_date = $val['issuance_date'];
                $cash_in = $val['cash_in'];
                $cash_out = $val['cash_out'];

                $sheet->setCellValue(
                    [
                        1,
                        $row
                    ],
                    $payee
                );

                $sheet->setCellValue(
                    [
                        2,
                        $row
                    ],
                    $dv_number
                );
                $sheet->setCellValue(
                    [
                        3,
                        $row
                    ],
                    $accounting_in
                );
                $sheet->setCellValue(
                    [
                        4,
                        $row
                    ],
                    $accounting_out
                );
                $sheet->setCellValue(
                    [
                        5,
                        $row
                    ],
                    $issuance_date . ' ' . $cash_in
                );
                $sheet->setCellValue(
                    [
                        6,
                        $row
                    ],
                    $issuance_date . ' ' . $cash_out
                );

                $row++;
            }

            date_default_timezone_set('Asia/Manila');
            // return date('l jS \of F Y h:i:s A');
            $id = date('Y-m-d h A');
            $file_name = "jev_$id.xlsx";

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            $file =  "transaction\jev_$id.xlsx";
            $file2 = Url::base() . '/' . "transaction/jev_$id.xlsx";

            $writer->save($file);
            // return ob_get_clean();
            header('Content-Type: application/vnd.ms-excel');
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Pragma: public'); // HTTP/1.0
            return json_encode($file2);
            echo "<script>window.open('$file2','_self')</script>";
            exit();
        }
    }

    function getWorkdays($date1, $date2, $workSat = FALSE, $patron = NULL)
    {
        if (!defined('SATURDAY')) define('SATURDAY', 6);
        if (!defined('SUNDAY')) define('SUNDAY', 0);

        // Array of all public festivities
        $publicHolidays = array('01-01', '12-30', '12-25', '04-09', '');
        $publicHolidays[] = array_column(Yii::$app->db->createCommand("SELECT DATE_FORMAT(holidays.date,'%m-%d') as _date FROM holidays")->queryAll(), '_date');
        var_dump($publicHolidays);
        // var_dump($publicHolidays2);
        // // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }

        /*
             * Array of all Easter Mondays in the given interval
             */
        $yearStart = date('Y', strtotime($date1));
        $yearEnd   = date('Y', strtotime($date2));

        for ($i = $yearStart; $i <= $yearEnd; $i++) {
            $easter = date('Y-m-d', easter_date($i));
            list($y, $m, $g) = explode("-", $easter);
            $monday = mktime(0, 0, 0, date($m), date($g) + 1, date($y));
            $easterMondays[] = $monday;
        }

        $start = strtotime($date1);
        $end   = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if (
                $day != SUNDAY &&
                !in_array($mmgg, $publicHolidays) &&
                !in_array($i, $easterMondays) &&
                !($day == SATURDAY && $workSat == FALSE)
            ) {
                $workdays++;
            }
        }



        return intval($workdays - 1);
    }
    public function actionPrReport()
    {
        if ($_POST) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];

            $total_pr_query = Yii::$app->db->createCommand("SELECT 
            pr_purchase_request.pr_number,
            pr_purchase_request.date,
            pr_purchase_request.purpose,
            books.`name` as book_name,
            req_by.employee_name as requested_by,
            app_by.employee_name as approved_by
            FROM pr_purchase_request
            LEFT JOIN books ON pr_purchase_request.book_id = books.id
            LEFT JOIN employee_search_view as req_by ON pr_purchase_request.requested_by_id = req_by.employee_id
            LEFT JOIN employee_search_view  as app_by ON pr_purchase_request.approved_by_id = app_by.employee_id
            LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id  = pr_project_procurement.id
            
            WHERE 
            pr_purchase_request.created_at >=:from_date
            AND pr_purchase_request.created_at <=:to_date")
                ->bindValue(':from_date', $from_date)
                ->bindValue(':to_date', $to_date)
                ->queryAll();
            return json_encode($total_pr_query);
        }
        return $this->render('pr_report');
    }
    public function actionProvinceAdequacy()
    {
        if ($_POST) {

            $province = $_POST['province'];
            $to_reporting_period = $_POST['reporting_period'];
            $book = $_POST['book'];
            $from_reporting_period = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('Y') . '-01';
            $db = Yii::$app->db;

            if ($_SERVER['REMOTE_ADDR'] !== '210.1.103.26') {
                $db = Yii::$app->cloud_db;
            }
            $query = $db->createCommand("SELECT 
            liquidation_target.fund_source_type,
            IFNULL(liquidation_target.total_amount,0) as target_liquidation,
            IFNULL(total_liquidation.total_liquidation,0) as actual_liquidation,
            IFNULL(total_liquidation.total_liquidation,0) - IFNULL(liquidation_target.total_amount,0) as variance,
            IFNULL(total_advances.total_advances,0) as total_advances,
            IFNULL(begin_balance.begin_balance,0) as begin_balance,
            (IFNULL(begin_balance.begin_balance,0)+IFNULL(total_advances.total_advances,0)) - IFNULL(total_liquidation.total_liquidation,0) as balance
            
            FROM (SELECT 
            monthly_liquidation_program.province,
            monthly_liquidation_program.fund_source_type,
            SUM(monthly_liquidation_program.amount) as total_amount
            FROM 
            monthly_liquidation_program
            WHERE 
            monthly_liquidation_program.reporting_period >=:from_reporting_period
            AND monthly_liquidation_program.reporting_period <=:to_reporting_period
            AND monthly_liquidation_program.book_id = :book
            AND monthly_liquidation_program.province = :province
            GROUP BY
            monthly_liquidation_program.province,
            monthly_liquidation_program.fund_source_type
            
            ) as liquidation_target
            LEFT JOIN (SELECT 
            liquidation.province,
            advances_entries.fund_source_type,
            
            SUM(liquidation_entries.withdrawals) as total_liquidation
            FROM advances_entries
            LEFT JOIN liquidation_entries ON advances_entries.id = liquidation_entries.advances_entries_id
            LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
            
            WHERE liquidation_entries.reporting_period >=:from_reporting_period
            AND liquidation_entries.reporting_period <=:to_reporting_period
            AND advances_entries.book_id =:book
            AND liquidation.province = :province
            AND advances_entries.is_deleted NOT IN (1,9)
            GROUP BY
            liquidation.province,
            advances_entries.fund_source_type
            ) total_liquidation ON liquidation_target.province = total_liquidation.province
            AND  liquidation_target.fund_source_type = total_liquidation.fund_source_type
            LEFT JOIN (SELECT 
            advances.province,
            advances_entries.fund_source_type,
            
            SUM(advances_entries.amount) as total_advances
            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id  = advances.id
            
            WHERE advances_entries.reporting_period >=:from_reporting_period
            AND advances_entries.reporting_period <=:to_reporting_period
            AND advances_entries.book_id =:book
            AND advances.province = :province
            AND advances_entries.is_deleted NOT IN (1,9)
            GROUP BY
            advances.province,
            advances_entries.fund_source_type
            ) total_advances ON liquidation_target.province = total_advances.province
            AND  liquidation_target.fund_source_type = total_advances.fund_source_type
            
            LEFT JOIN (
            
            
            
            SELECT
            begin_total_advances.fund_source_type,
            begin_total_advances.total_advances - IFNULL(begin_total_liquidation.total_liquidation,0) as begin_balance
            
            FROM
            (SELECT advances_entries.fund_source_type,
            SUM(advances_entries.amount) as total_advances
            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            
            WHERE advances_entries.reporting_period <:from_reporting_period
            AND advances.province = :province
            AND advances_entries.book_id =:book
            AND advances_entries.is_deleted NOT IN (1,9)
            GROUP BY advances_entries.fund_source_type
            ) as begin_total_advances
            LEFT JOIN 
            (
            SELECT advances_entries.fund_source_type,
            SUM(liquidation_entries.withdrawals) as total_liquidation
            FROM advances_entries
            LEFT JOIN advances ON advances_entries.advances_id = advances.id
            LEFT JOIN liquidation_entries ON advances_entries.id = liquidation_entries.advances_entries_id
            WHERE liquidation_entries.reporting_period <:from_reporting_period
            AND advances.province = :province
            AND advances_entries.book_id =:book
            AND advances_entries.is_deleted NOT IN (1,9)
            GROUP BY advances_entries.fund_source_type
            ) begin_total_liquidation ON begin_total_advances.fund_source_type = begin_total_liquidation.fund_source_type
            ) as begin_balance ON liquidation_target.fund_source_type  = begin_balance.fund_source_type
            
            
            ")
                ->bindValue(':province', $province)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':book', $book)
                ->queryAll();
            return json_encode($query);
        }
        return $this->render('province_adequacy');
    }


    public function actionLiquidationReportAnnex()
    {

        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book_id = $_POST['book_id'];
            $query = Yii::$app->db->createCommand("SELECT 
                cash_disbursement.id as cash_id,
                    payee.account_name as payee,
                    cash_disbursement.check_or_ada_no as check_number,
                    cash_disbursement.ada_number,
                    CONCAT(dv_aucs.dv_number,'-',dv_aucs.particular) as particular,
                    cash_disbursement.issuance_date,
                    total_dv_amount.total_disbursed,
                IFNULL(liquidation_report_items_total.total_items_amount,0) + IFNULL(liquidation_report_refunds_total.total_refund,0) as liquidated_amount,
            
                total_dv_amount.total_disbursed - (	IFNULL(liquidation_report_items_total.total_items_amount,0) + IFNULL(liquidation_report_refunds_total.total_refund,0)) as balance
                    FROM cash_disbursement
                    LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                    LEFT JOIN payee ON dv_aucs.payee_id = payee.id
                    LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,
                    dv_aucs_entries.dv_aucs_id 
                    FROM dv_aucs_entries GROUP BY 
                    dv_aucs_entries.dv_aucs_id
                    ) as total_dv_amount ON dv_aucs.id = total_dv_amount.dv_aucs_id
                            LEFT JOIN (SELECT 
                            ro_liquidation_report_items.fk_cash_disbursement_id,
                            SUM(ro_liquidation_report_items.amount) as total_items_amount
                            FROM ro_liquidation_report_items
            WHERE ro_liquidation_report_items.is_deleted !=1
                            GROUP BY ro_liquidation_report_items.fk_cash_disbursement_id)
             as liquidation_report_items_total ON cash_disbursement.id = liquidation_report_items_total.fk_cash_disbursement_id
                    LEFT JOIN (
                SELECT ro_liquidation_report_refunds.fk_cash_disbursement_id,
            SUM(ro_liquidation_report_refunds.amount) as total_refund
            FROm ro_liquidation_report_refunds
            WHERE ro_liquidation_report_refunds.is_deleted !=1
            GROUP BY ro_liquidation_report_refunds.fk_cash_disbursement_id
                    ) as liquidation_report_refunds_total ON cash_disbursement.id = liquidation_report_refunds_total.fk_cash_disbursement_id
            
            LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id
            WHERE
                    cash_disbursement.is_cancelled =0
                    AND cash_disbursement.reporting_period LIKE'2022%' 
                    AND cash_disbursement.reporting_period = :reporting_period
                    AND cash_disbursement.book_id = :book_id
                AND nature_of_transaction.`name` = 'CA to Employees' ")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':book_id', $book_id)
                ->queryAll();
            return json_encode($query);
        }

        return $this->render('liquidation_report_annex');
    }
    public function actionProcurementSummary()
    {
        $searchModel = new ProcurementSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('procurement_summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionDvTransmittalSummary()
    {
        if (Yii::$app->request->isPost) {
            $year = !empty(Yii::$app->getRequest()->getBodyParams()['year']) ? Yii::$app->getRequest()->getBodyParams()['year'] : date('Y');
            $query = Yii::$app->db->createCommand("SELECT 
            total_dv.*,
            IFNULL(dv_at_ro.dv_count_at_ro,0) as dv_count_at_ro ,
            IFNULL(dv_at_coa.dv_count_at_coa,0) as dv_count_at_coa
            FROM 
            (SELECT 
                cash.reporting_period,
                COUNT(cash.id) as total_dv
                FROM cash_disbursement as cash
                WHERE
                NOT EXISTS (SELECT * FROM cash_disbursement WHERE cash_disbursement.check_or_ada_no = cash.check_or_ada_no AND cash_disbursement.dv_aucs_id = cash.dv_aucs_id AND cash_disbursement.is_cancelled = 1)
                AND cash.is_cancelled = 0 
                GROUP BY
                cash.reporting_period) as total_dv
            LEFT JOIN 
            (
                SELECT
                all_good_cash.reporting_period,
                COUNT(all_good_cash.id) as dv_count_at_ro
                FROM 
                (SELECT 
                cash.id,
                cash.reporting_period
                FROM cash_disbursement as cash
                WHERE

                NOT EXISTS (SELECT * FROM cash_disbursement WHERE cash_disbursement.check_or_ada_no = cash.check_or_ada_no AND cash_disbursement.dv_aucs_id = cash.dv_aucs_id AND cash_disbursement.is_cancelled = 1)
                
                AND cash.is_cancelled = 0 ) as all_good_cash
                WHERE 

                NOT EXISTS (SELECT cash_disbursement_id FROM transmittal_entries WHERE transmittal_entries.cash_disbursement_id  =  all_good_cash.id)
                GROUP BY 
                all_good_cash.reporting_period
            )  as dv_at_ro ON total_dv.reporting_period   = dv_at_ro.reporting_period
            
            LEFT JOIN 
            (SELECT
            all_good_cash.reporting_period,
            COUNT(all_good_cash.id) as dv_count_at_coa
            FROM 
            (SELECT 
            cash.id,
            cash.reporting_period
            FROM cash_disbursement as cash
            WHERE

            NOT EXISTS (SELECT * FROM cash_disbursement WHERE cash_disbursement.check_or_ada_no = cash.check_or_ada_no AND cash_disbursement.dv_aucs_id = cash.dv_aucs_id AND cash_disbursement.is_cancelled = 1)
            
            AND cash.is_cancelled = 0 ) as all_good_cash
            INNER JOIN  (SELECT cash_disbursement_id FROM transmittal_entries GROUP BY cash_disbursement_id)as transmitted ON all_good_cash.id = transmitted.cash_disbursement_id
            GROUP BY 
            all_good_cash.reporting_period) as dv_at_coa ON   total_dv.reporting_period = dv_at_coa.reporting_period
            WHERE total_dv.reporting_period LIKE :_year
            ")

                ->bindValue(':_year', $year . '%')
                ->queryAll();
            return json_encode($query);
        }
    }


    public function actionDetailedTransmittalSummary($reporting_period = '')
    {

        $not_transmitted_query = Yii::$app->db->createCommand("SELECT 
        all_good_cash.check_or_ada_no,
        all_good_cash.issuance_date,
        dv_aucs.dv_number,
        dv_aucs.particular,
        dv_amount.amount_disbursed
         FROM 
        (SELECT 
        cash.id,
        cash.dv_aucs_id,
        cash.check_or_ada_no,
        cash.issuance_date,
        cash.reporting_period
        FROM cash_disbursement as cash
        WHERE

        NOT EXISTS (SELECT * FROM cash_disbursement 
        WHERE cash_disbursement.check_or_ada_no = cash.check_or_ada_no
        AND cash_disbursement.dv_aucs_id = cash.dv_aucs_id
        AND cash_disbursement.is_cancelled = 1)
        AND cash.is_cancelled = 0 ) as all_good_cash
         INNER JOIN dv_aucs on all_good_cash.dv_aucs_id = dv_aucs.id
         LEFT JOIN (SELECT SUM(dv_aucs_entries.amount_disbursed) 
                as amount_disbursed,dv_aucs_entries.dv_aucs_id
                 FROM dv_aucs_entries 
                 WHERE dv_aucs_entries.is_deleted = 0
                 GROUP BY dv_aucs_entries.dv_aucs_id) as dv_amount ON dv_aucs.id  = dv_amount.dv_aucs_id
                WHERE 
            NOT EXISTS 
        (SELECT transmittal_entries.cash_disbursement_id
        FROM transmittal_entries 
        WHERE transmittal_entries.cash_disbursement_id=
        all_good_cash.id 
        GROUP BY transmittal_entries.cash_disbursement_id) 
        AND all_good_cash.reporting_period = :reporting_period
        ORDER BY all_good_cash.check_or_ada_no")
            ->bindValue(':reporting_period', $reporting_period)
            ->queryAll();
        $transmitted_query = Yii::$app->db->createCommand("SELECT 
        cash_disbursement.check_or_ada_no,
        cash_disbursement.issuance_date,
        dv_aucs.dv_number,
        dv_aucs.particular
         FROM 
        cash_disbursement 
        INNER JOIN dv_aucs on cash_disbursement.dv_aucs_id = dv_aucs.id
        WHERE 
        cash_disbursement.is_cancelled !=1
        AND EXISTS (SELECT transmittal_entries.cash_disbursement_id FROM transmittal_entries WHERE transmittal_entries.cash_disbursement_id=  cash_disbursement.id GROUP BY transmittal_entries.cash_disbursement_id) 
        ORDER BY cash_disbursement.check_or_ada_no")
            ->bindValue(':reporting_period', $reporting_period)
            ->queryAll();
        return $this->render('detailed_transmittal_summary', [
            'not_transmitted' => $not_transmitted_query,
            'transmitted' => $transmitted_query,
        ]);
    }
    public function actionPoTransmittalSummary()
    {
        if (YIi::$app->request->isPost) {
            $year = $_POST['year'];
            $reporting_periods = YIi::$app->db->createCommand(" SELECT
            liquidation.reporting_period
            FROM liquidation
            WHERE liquidation.reporting_period LIKE :_year
            GROUP  BY liquidation.reporting_period
            ORDER BY  liquidation.reporting_period ASC")
                ->bindValue(':_year', $year . '%')
                ->queryAll();
            $query = Yii::$app->db->createCommand("SELECT 
                liq_periods.*,
                dv_at_po.dv_at_po_count,
                dv_at_ro.dv_at_ro_count,
                dv_at_coa.dv_at_coa_count
                FROM 
                (SELECT 
                
                liquidation.province,
                liquidation.reporting_period
                FROM liquidation
                GROUP BY
                liquidation.province,
                liquidation.reporting_period
                ) as liq_periods
                LEFT JOIN 
                (SELECT 
                liquidation.province,
                liquidation.reporting_period,
                COUNT(liquidation.id) as dv_at_po_count
                FROM 
                liquidation
                LEFT JOIN (SELECT liquidation_entries.liquidation_id,SUM(liquidation_entries.withdrawals) as total_withdrawals FROM liquidation_entries GROUP BY liquidation_entries.liquidation_id) as liq_entries ON liquidation.id = liq_entries.liquidation_id
                WHERE
                NOT EXISTS (SELECT 
                po_transmittal_entries.liquidation_id
                FROM po_transmittal_entries
                LEFT JOIN po_transmittal ON po_transmittal_entries.po_transmittal_number = po_transmittal.transmittal_number
                WHERE
                po_transmittal.`status` IN ('at_ro','at_coa')
                AND
                po_transmittal_entries.`status` IS NULL
                AND
                po_transmittal_entries.liquidation_id = liquidation.id
                )
                AND liq_entries.total_withdrawals >0
                GROUP BY 
                liquidation.province,
                liquidation.reporting_period) dv_at_po ON liq_periods.province = dv_at_po.province AND liq_periods.reporting_period = dv_at_po.reporting_period
                
                LEFT JOIN 
                (SELECT 
                liquidation.province,
                liquidation.reporting_period,
                COUNT(liquidation.id) as dv_at_ro_count
                FROM 
                po_transmittal
                LEFT JOIN `po_transmittal_entries` ON po_transmittal.transmittal_number = po_transmittal_entries.po_transmittal_number
                LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id = liquidation.id
                LEFT JOIN (SELECT liquidation_entries.liquidation_id,SUM(liquidation_entries.withdrawals) as total_withdrawals FROM liquidation_entries GROUP BY liquidation_entries.liquidation_id) as liq_entries ON liquidation.id = liq_entries.liquidation_id 
                WHERE  po_transmittal.status = 'at_ro'
                AND po_transmittal_entries.status IS NULL  
                AND liq_entries.total_withdrawals >0
                GROUP BY liquidation.province,liquidation.reporting_period) dv_at_ro ON liq_periods.province = dv_at_ro.province AND liq_periods.reporting_period = dv_at_ro.reporting_period
                LEFT JOIN 
                (SELECT 
                liquidation.province,
                liquidation.reporting_period,
                COUNT(liquidation.id) as dv_at_coa_count
                FROM 
                po_transmittal
                LEFT JOIN `po_transmittal_entries` ON po_transmittal.transmittal_number = po_transmittal_entries.po_transmittal_number
                LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id = liquidation.id
                LEFT JOIN (SELECT liquidation_entries.liquidation_id,SUM(liquidation_entries.withdrawals) as total_withdrawals 
                    FROM liquidation_entries GROUP BY liquidation_entries.liquidation_id) as liq_entries ON liquidation.id = liq_entries.liquidation_id 
                WHERE  po_transmittal.status = 'at_coa'
                AND po_transmittal_entries.status IS NULL  
                AND liq_entries.total_withdrawals >0
                GROUP BY liquidation.province,liquidation.reporting_period) dv_at_coa ON liq_periods.province = dv_at_coa.province AND liq_periods.reporting_period = dv_at_coa.reporting_period
                WHERE liq_periods.reporting_period LIKE :_year
            ")
                ->bindValue(':_year', $year . '%')
                ->queryAll();
            $result = ArrayHelper::index($query, null, 'province');
            return json_encode(['data' => $result, 'reporting_periods' => $reporting_periods]);
        }


        return $this->render('po_transmittal_summary');
    }
    public function actionPrSummary()
    {

        $searchModel = new PrSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('pr_summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionProcSummary()
    {

        if (Yii::$app->request->isPost) {


            $query  = (new \yii\db\Query())
                ->select([
                    'pr_purchase_request.pr_number',
                    'pr_purchase_request.purpose',
                    'pr_purchase_request.date as pr_date',
                    'pr_rfq.rfq_number',
                    'pr_aoq.aoq_number',
                    'pr_purchase_order_item.serial_number as po_number',
                    'po_payee.payee',
                    'po_payee.total_cost'
                ])
                ->from('pr_purchase_request')
                ->join('LEFT JOIN', 'pr_rfq', 'pr_purchase_request.id = pr_rfq.pr_purchase_request_id')
                ->join('LEFT JOIN', 'pr_aoq', 'pr_rfq.id = pr_aoq.pr_rfq_id')
                ->join('LEFT JOIN', 'pr_purchase_order', 'pr_aoq.id = pr_purchase_order.fk_pr_aoq_id')
                ->join('LEFT JOIN', 'pr_purchase_order_item', 'pr_purchase_order.id = pr_purchase_order_item.fk_pr_purchase_order_id')
                ->join('LEFT JOIN', "(SELECT pr_purchase_order_item.fk_pr_purchase_order_id,
                                pr_purchase_order_items_aoq_items.fk_purchase_order_item_id,
                                payee.account_name as payee,
                                SUM(pr_aoq_entries.amount * pr_purchase_request_item.quantity) as total_cost
                                FROM pr_purchase_order_items_aoq_items
                                LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                                LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
                                GROUP BY
                                pr_purchase_order_items_aoq_items.fk_purchase_order_item_id,
                                payee.account_name) as po_payee", "pr_purchase_order_item.id = po_payee.fk_purchase_order_item_id");

            if (!empty($_POST['po_id'])) {
                $query->andWhere('pr_purchase_order_item.id = :id', ['id' => $_POST['po_id']]);
            }
            if (!empty($_POST['rfq_id'])) {
                $query->andWhere('pr_rfq.id = :id', ['id' => $_POST['rfq_id']]);
            }
            if (!empty($_POST['aoq_id'])) {
                $query->andWhere('pr_aoq.id = :id', ['id' => $_POST['aoq_id']]);
            }
            if (!empty($_POST['pr_id'])) {
                $query->andWhere('pr_purchase_request.id = :id', ['id' => $_POST['pr_id']]);
            }
            if (!empty($_POST['pr_from']) && !empty($_POST['pr_to'])) {
                $query->andWhere('pr_purchase_request.date >= :pr_from', ['pr_from' => $_POST['pr_from']]);
                $query->andWhere('pr_purchase_request.date <= :pr_to', ['pr_to' => $_POST['pr_to']]);
            }
            if (!empty($_POST['rfq_from']) && !empty($_POST['rfq_to'])) {

                $query->andWhere('pr_rfq.created_at >= :rfq_from', ['rfq_from' => $_POST['rfq_from']]);
                $query->andWhere('pr_rfq.created_at <= :rfq_to', ['rfq_to' => $_POST['rfq_to']]);
            }
            if (!empty($_POST['aoq_from']) && !empty($_POST['aoq_to'])) {
                $query->andWhere('pr_aoq.created_at >= :aoq_from', ['aoq_from' => $_POST['aoq_from']]);
                $query->andWhere('pr_aoq.created_at <= :aoq_to', ['aoq_to' => $_POST['aoq_to']]);
            }
            if (!empty($_POST['po_from']) && !empty($_POST['po_to'])) {
                $query->andWhere('pr_purchase_order.created_at >= :po_from', ['po_from' => $_POST['po_from']]);
                $query->andWhere('pr_purchase_order.created_at <= :po_to', ['po_to' => $_POST['po_to']]);
            }
            // $q =  $query->andWhere('pr_purchase_request.id = :id', ['id' => 99768695600971827])->all();

            $q = $query->all();
            $result = ArrayHelper::index($q, 'po_number', [function ($element) {
                return $element['pr_number'] . '[/]' . $element['purpose'] . '[/]' . $element['pr_date'];
            }, 'rfq_number', 'aoq_number']);
            return json_encode($result);
        }
        return $this->render('proc_summary');
    }

    public function actionExportPropertyDatabase()
    {
        // return YIi::$app->memem->getWorkdays('2022-06-17', '2022-06-20');
        if (Yii::$app->request->post()) {

            $reporting_period = Yii::$app->request->post('reporting_period');
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $office =   $user_data->office->office_name;
            }
            // $query = Yii::$app->db->createCommand("SELECT * FROM detailed_property_database")->queryAll();

            $q  = new Query();
            $q->select("*")
                ->from('detailed_property_database')
                ->andWhere("DATE_FORMAT(detailed_property_database.date_acquired,'%Y-%m') <=:reporting_period", ['reporting_period' => $reporting_period]);

            if (!Yii::$app->user->can('super-user')) {
                $q->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
            }
            $query = $q->all();
            // $items  = Yii::$app->db->createCommand("SELECT * FROM detailed_other_property_details")->queryAll();
            $i  = new Query();
            $i->select("*")
                ->from('detailed_other_property_details');
            if (!Yii::$app->user->can('super-user')) {
                $i->andWhere('fk_office_id = :office_id', ['office_id' => $user_data->office->id]);
            }
            $items = $i->all();
            // $result = ArrayHelper::index($items,  'book_name', [function ($element) {
            //     return $element['property_number'];
            // },]);
            $books = YIi::$app->db->createCommand("SELECT books.`name`as book_name  FROM books ORDER BY id ")->queryAll();
            $result = ArrayHelper::index($items, 'book_name', 'property_number');

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();


            // // header
            $headers = [
                "Property Card No.",
                "PTR No.",
                "PTR Date",
                "Transfer Type",
                "Reason for Transfer",
                "Derecognition No.",
                "Derecognition Date",
                "IIRUP No.",
                "RLSDDP No.",
                "Last Month of Depreciation (from derecognition)",
                "JEV No.",
                "Property No.",
                "SSF/Non-SSF",
                "SSF SP No.",
                "Date Acquired",
                "Article",
                "Description",
                "Property Serial No.",
                "Quantity",
                "Unit of Measure",
                "Total Acquisition",
                "Accumulated Depreciation",
                "Book Value",
                "PAR No.",
                "Office",
                "Location",
                "Received by",
                "Actual User",
                "Issued By",
                "Current/Not Current User",
                "Serviceable/Unserviceable",
                "GL Asset Account",
                "GL Asset Account Title",
                "Contra Asset Sub Account",
                "Contra Asset Sub Account Title",
                "Contra Asset Sub Account Title",
                "Depreciation Sub Account ",
                "Depreciation Sub Account Title",

            ];
            foreach ($headers as $key => $head) {
                $sheet->setCellValue([$key + 1, 2], $head);
            }
            // $sheet->setCellValue('A2', "Property Card No.");
            // $sheet->setCellValue('B2', "PTR No.");
            // $sheet->setCellValue('C2', "PTR Date");
            // $sheet->setCellValue('D2', "Transfer Type");
            // $sheet->setCellValue('E2', "Reason for Transfer");
            // $sheet->setCellValue('F2', "Derecognition No.");
            // $sheet->setCellValue('G2', "Derecognition Date");
            // $sheet->setCellValue('H2', "IIRUP No.");
            // $sheet->setCellValue('I2', "RLSDDP No.");
            // $sheet->setCellValue('J2', "Last Month of Depreciation (from derecognition)");
            // $sheet->setCellValue('K2', "JEV No.");
            // $sheet->setCellValue('L2', "Property No.");
            // $sheet->setCellValue('M2', "SSF/Non-SSF");
            // $sheet->setCellValue('N2', "SSF SP No.");
            // $sheet->setCellValue('O2', "Date Acquired");
            // $sheet->setCellValue('P2', "Article");
            // $sheet->setCellValue('Q2', "Description");
            // $sheet->setCellValue('R2', "Property Serial No.");
            // $sheet->setCellValue('S2', "Quantity");
            // $sheet->setCellValue('T2', "Unit of Measure");
            // $sheet->setCellValue('U2', "Total Acquisition");
            // $sheet->setCellValue('V2', "Accumulated Depreciation");
            // $sheet->setCellValue('W2', "Book Value");
            // $sheet->setCellValue('X2', "PAR No.");
            // $sheet->setCellValue('Y2', "Office");
            // $sheet->setCellValue('Z2', "Location");
            // $sheet->setCellValue('AA2', "Received by");
            // $sheet->setCellValue('AB2', "Actual User");
            // $sheet->setCellValue('AC2', "Issued By");
            // $sheet->setCellValue('AD2', "Current/Not Current User");
            // $sheet->setCellValue('AE2', "Serviceable/Unserviceable");
            // $sheet->setCellValue('AF2', "GL Asset Account");
            // $sheet->setCellValue('AG2', "GL Asset Account Title");
            // $sheet->setCellValue('AH2', "Contra Asset Sub Account");
            // $sheet->setCellValue('AI2', "Contra Asset Sub Account Title");
            // $sheet->setCellValue('AJ2', "Contra Asset Sub Account Title");
            // $sheet->setCellValue('AK2', "Depreciation Sub Account ");
            // $sheet->setCellValue('AL2', "Depreciation Sub Account Title");
            // $sheet->setCellValue('AM2', "Fund 01");
            // $sheet->setCellValue('AN2', "RAPID SGA");
            // $sheet->setCellValue('AO2', "RAPID LP");
            // $sheet->setCellValue('AP2', "Fund 158");
            // $sheet->setCellValue('AQ2', "RAPID GOP");
            $bookCol = 39;
            // Book Value/Salvage Value/Months Depreciated
            for ($x = 0; $x < 3; $x++) {
                if ($x === 0) {
                    $sheet->setCellValue([$bookCol, 1], 'Book Value');
                } else if ($x === 1) {

                    $sheet->setCellValue([$bookCol, 1], 'Salvage Value');
                }
                if ($x === 2) {

                    $sheet->setCellValue([$bookCol, 1], 'DEPRECIABLE AMOUNT');
                }
                foreach ($books as $book) {
                    $sheet->setCellValue([$bookCol, 2], $book['book_name']);
                    $bookCol++;
                }
            }
            $sheet->setCellValue([$bookCol, 2], 'Estimated Useful Life in months');
            $bookCol++;


            $sheet->setCellValue([$bookCol, 2], '1st month of Depreciation');
            $bookCol++;

            $sheet->setCellValue([$bookCol, 2], '2nd to the last month of Depreciation');
            $bookCol++;

            $sheet->setCellValue([$bookCol, 1], 'Amount of Monthly Depreciation for 1st to 2nd to the last month');
            foreach ($books as $book) {
                $sheet->setCellValue([$bookCol, 2], $book['book_name']);
                $bookCol++;
            }
            $sheet->setCellValue([$bookCol, 2], 'Last Month of Depreciation');
            $bookCol++;
            $sheet->setCellValue([$bookCol, 1], 'Amount of Monthly Depreciation for the last mon');
            foreach ($books as $book) {
                $sheet->setCellValue([$bookCol, 2], $book['book_name']);
                $bookCol++;
            }
            $sheet->setCellValue([$bookCol, 1], 'Months Depreciated');
            $bookCol++;
            $sheet->setCellValue([$bookCol, 1], 'Accumulated Depreciation');
            foreach ($books as $book) {
                $sheet->setCellValue([$bookCol, 2], $book['book_name']);
                $bookCol++;
            }
            $sheet->setCellValue([$bookCol, 1], 'Book Value Balance');
            foreach ($books as $book) {
                $sheet->setCellValue([$bookCol, 2], $book['book_name']);
                $bookCol++;
            }

            $sheet->setAutoFilter('A2:AZ2');


            $x = 7;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );


            $row = 3;
            foreach ($query as $val) {

                // echo json_encode(
                //     $result[$val['property_number']]['Rapid LP']['book_val']
                // );
                // // echo json_encode($result);
                // die();
                $sheet->setCellValue(
                    [1, $row],
                    $val['pc_num']
                );
                $sheet->setCellValue(
                    [2, $row],
                    $val['ptr_number']
                );
                $sheet->setCellValue(
                    [3, $row],
                    $val['ptr_date']
                );
                $sheet->setCellValue(
                    [4, $row],
                    ''
                );
                $sheet->setCellValue(
                    [5, $row],
                    ''
                );
                $sheet->setCellValue(
                    [6, $row],
                    $val['derecognition_num']
                );
                $sheet->setCellValue(
                    [7, $row],
                    $val['derecognition_date']
                );
                $sheet->setCellValue(
                    [12, $row],
                    $val['property_number']
                );
                $sheet->setCellValue(
                    [15, $row],
                    $val['date_acquired']
                );
                $sheet->setCellValue(
                    [16, $row],
                    $val['article']
                );
                $sheet->setCellValue(
                    [17, $row],
                    $val['description']
                );
                $sheet->setCellValue(
                    [18, $row],
                    $val['serial_number']
                );
                $sheet->setCellValue(
                    [19, $row],
                    1
                );


                $sheet->setCellValue(
                    [20, $row],
                    $val['unit_of_measure']
                );
                $sheet->setCellValue(
                    [21, $row],
                    $val['acquisition_amount']
                );
                $sheet->setCellValue(
                    [23, $row],
                    $val['acquisition_amount']
                );
                $sheet->setCellValue(
                    [24, $row],
                    $val['par_number']
                );
                $sheet->setCellValue(
                    [25, $row],
                    $val['office_name']
                );
                $sheet->setCellValue(
                    [26, $row],
                    $val['location']
                );
                $sheet->setCellValue(
                    [27, $row],
                    $val['rcv_by']
                );
                $sheet->setCellValue(
                    [28, $row],
                    $val['act_usr']
                );
                $sheet->setCellValue(
                    [29, $row],
                    $val['isd_by']
                );
                $sheet->setCellValue(
                    [30, $row],
                    $val['isCrntUsr']
                );
                $sheet->setCellValue(
                    [31, $row],
                    $val['isUnserviceable']
                );
                if (strtolower($val['isCrntUsr']) === 'current user') {
                    $bookValCol = 39;
                    $property_num = $val['property_number'];
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);
                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            !empty($result[$property_num][$book_name]['book_val']) ? $result[$property_num][$book_name]['book_val'] : ''
                        );
                        $bookValCol++;
                    }
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);

                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            !empty($result[$property_num][$book_name]['salvage_value']) ? $result[$property_num][$book_name]['salvage_value'] : ''
                        );
                        $bookValCol++;
                    }
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);

                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            !empty($result[$property_num][$book_name]['depreciable_amount']) ? $result[$property_num][$book_name]['depreciable_amount'] : ''
                        );
                        $bookValCol++;
                    }
                    $sheet->setCellValue(
                        [$bookValCol, $row],
                        !empty($val['useful_life']) ? $val['useful_life'] : ''
                    );
                    $bookValCol++;
                    $sheet->setCellValue(
                        [$bookValCol, $row],
                        !empty($val['strt_mnth']) ? $val['strt_mnth'] : ''
                    );
                    $bookValCol++;
                    $sheet->setCellValue(
                        [$bookValCol, $row],
                        !empty($val['sec_lst_mth']) ? $val['sec_lst_mth'] : ''
                    );
                    $bookValCol++;
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);

                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            !empty($result[$property_num][$book_name]['mnthly_depreciation']) ? $result[$property_num][$book_name]['mnthly_depreciation'] : ''
                        );

                        $bookValCol++;
                    }

                    $sheet->setCellValue(
                        [$bookValCol, $row],
                        !empty($val['lst_mth']) ? $val['lst_mth'] : ''
                    );
                    $bookValCol++;
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);

                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            !empty($result[$property_num][$book_name]['lstmnthdep']) ? $result[$property_num][$book_name]['lstmnthdep'] : ''
                        );
                        $bookValCol++;
                    }

                    $date2 = new DateTime($val['strt_mnth'] . -'30');
                    $date1 = new DateTime($reporting_period . '-30');
                    $interval = $date1->diff($date2);
                    $months_diff = ($interval->y * 12) + $interval->m;
                    $months_depreciated = $months_diff >= $val['useful_life'] ? $val['useful_life'] : $months_diff;
                    $sheet->setCellValue(
                        [$bookValCol, $row],
                        $months_depreciated
                    );
                    $bookValCol++;


                    // accu_depreciation
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);

                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            // !empty($result[$property_num][$book_name]['accu_depreciation']) ? $result[$property_num][$book_name]['accu_depreciation'] : ''
                            !empty($result[$property_num][$book_name]['mnthly_depreciation']) ? $months_depreciated * $result[$property_num][$book_name]['mnthly_depreciation'] : ''
                        );
                        $bookValCol++;
                    }
                    foreach ($books as $book) {
                        $book_name = strtolower($book['book_name']);
                        $depreiated_amt = !empty($result[$property_num][$book_name]['mnthly_depreciation']) ? $months_depreciated * $result[$property_num][$book_name]['mnthly_depreciation'] : 0;
                        $book_val = !empty($result[$property_num][$book_name]['book_val']) ? $result[$property_num][$book_name]['book_val'] : 0;
                        $sheet->setCellValue(
                            [$bookValCol, $row],
                            $book_val - $depreiated_amt
                        );
                        $bookValCol++;
                    }
                }

                $row++;
            }

            date_default_timezone_set('Asia/Manila');
            // return date('l jS \of F Y h:i:s A');
            $date = date('Y-m-d h-s A');
            $file_name = "property_database_$reporting_period.xlsx";



            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            $fileSaveLoc =  "exports\\" . $file_name;
            // $fileDwnldLoc = Url::base() . '/' . "exports//" . $file_name;
            $path = Yii::getAlias('@webroot') . '/exports';
            $file = $path . "/$file_name";
            $writer->save($file);
            // return ob_get_clean();
            header('Content-Type: application/vnd.ms-excel');
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Pragma: public'); // HTTP/1.0
            echo  json_encode($fileSaveLoc);
            // unlink($fileSaveLoc)
            // echo "<script>window.open('$fileDwnldLoc','_self')</script>";
            exit();
        }
    }


    // yii2 upload
    public function actionFile()
    {

        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            // // $q = $_FILES['file'];
            // if (isset($_FILES['file'])) {
            //     $file = $_FILES;
            //     $file = \yii\web\UploadedFile::getInstanceByName('file');
            //     $model->file = $file;
            //     if ($model->validate()) {
            //         if ($model->upload('uploads')) {
            //             return json_encode('success');
            //         } else {
            //             return var_dump($model->errors);
            //         }
            //     } else {
            //         return var_dump($model->errors);
            //     }
            //     // if ($model->upload()) {
            //     //     // file is uploaded successfully
            //     //     return 'succes';
            //     // }
            // }
            if (!empty($_POST)) {
                $name = $_FILES["file"]["name"];


                $id = uniqid();
                $file = "jev/{$id}_{$name}";;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                } else {
                    return "ERROR 2: MOVING FILES FAILED.";
                    die();
                }

                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $excel = $reader->load($file);
                // $excel->setActiveSheetIndexByName('Conso-For upload');
                $worksheet = $excel->getActiveSheet();
                $reader->setReadDataOnly(FALSE);
                $transaction = Yii::$app->db->beginTransaction();
                foreach ($worksheet->getRowIterator(3) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    $y = 0;
                    foreach ($cellIterator as $x => $cell) {
                        $cells[] = $cell->getValue();
                    }
                    if (!empty($cells)) {
                        $uacs = $cells[0];
                        $ppe_type = $cells[1];
                        $depreciation = $cells[2];
                        $impairment = $cells[3];
                        $depreciation_id = null;
                        $impairment_id = null;
                        $ppe_type_id = null;
                        if (!empty($depreciation)) {
                            $depreciation_id = Yii::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :depreciation")
                                ->bindValue(':depreciation', $depreciation)->queryScalar();
                        }
                        if (!empty($impairment)) {
                            $impairment_id = Yii::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :impairment")
                                ->bindValue(':impairment', $impairment)->queryScalar();
                        }
                        if (!empty($ppe_type)) {
                            $ppe_type_id = Yii::$app->db->createCommand("SELECT id FROM ppe_useful_life WHERE `name` = :ppe_type")
                                ->bindValue(':ppe_type', $ppe_type)->queryScalar();
                        }
                        $chart_of_account = ChartOfAccounts::find()->where('uacs = :uacs', ['uacs' => $uacs])->one();

                        if (!empty($depreciation_id)) {
                            $chart_of_account->fk_depreciation_id = $depreciation_id;
                        }
                        if (!empty($impairment_id)) {
                            $chart_of_account->fk_impairment_id = $impairment_id;
                        }
                        $chart_of_account->fk_ppe_useful_life_id = $ppe_type_id;
                        if ($chart_of_account->save(false)) {
                        }
                    }
                }
                $transaction->commit();
                // JEV ACCOUNTING ENTRIES COLUMNS
                $column = [
                    'jev_preparation_id',
                    // 'chart_of_account_id',
                    'debit',
                    'credit',
                    'current_noncurrent',
                    'closing_nonclosing',
                    'cashflow_id',
                    'net_asset_equity_id',
                    'object_code',
                    'lvl',

                ];



                // try {

                //     // Yii::$app->db->createCommand()->batchInsert('jev_accounting_entries', $column, $jev_entries)->execute();
                //     $transaction->commit();
                // } catch (ErrorException $error) {
                //     $transaction->rollback();
                // }
                // ob_clean();
                echo '<pre>';
                var_dump("Success");
                echo '</pre>';
            }
        }
        return $this->render('file_upload', ['model' => $model]);
    }
    public function actionMail()
    {
        try {
            Yii::$app->mailer->compose()
                ->setFrom(['norman.notorious@gmail.com' => 'norman'])
                ->setTo('normanbutalon@gmail.com')
                ->setSubject('subject')
                ->setTextBody('yes na send ra jud')
                ->send();

            echo 'Email sent successfully!';
        } catch (ErrorException $e) {
            echo 'Error sending email: ' . $e->getMessage();
        }
        // $host = 'localhost';
        // $username = 'root';
        // $password = '';
        // $dbname = 'afms';

        // // Define your SQL query
        // $query = "SELECT * FROM books";

        // // Define the filename for the CSV file
        // $filename = 'data.csv';

        // // Construct the command to execute
        // $command = "mysql -h $host -u $username -p $password -D $dbname -e \"$query\" | tr '\\t' ',' > $filename";
        // echo $command;
        // // Execute the command
        // exec($command);
    }
    public function actionUploadProperty()
    {

        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            if (!empty($_POST)) {
                $name = $_FILES["file"]["name"];
                $id = uniqid();
                $file = "jev/{$id}_{$name}";;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
                } else {
                    return "ERROR 2: MOVING FILES FAILED.";
                    die();
                }

                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $excel = $reader->load($file);
                $excel->setActiveSheetIndexByName('Sheet1');
                $worksheet = $excel->getActiveSheet();
                $reader->setReadDataOnly(FALSE);

                try {
                    $transaction = Yii::$app->db->beginTransaction();
                    foreach ($worksheet->getRowIterator(6) as $key => $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                        $cells = [];
                        $y = 0;
                        $q = 0;
                        foreach ($cellIterator as $x => $cell) {

                            if ($q == 4) {
                                $cells[] = $cell->getFormattedValue();
                            } else {
                                $cells[] = $cell->getValue();
                            }
                            $q++;
                        }
                        $pc_num = $cells[0];
                        $property_num = $cells[1];
                        $is_ssf = strtolower(trim($cells[2])) == 'non-ssf' ? 0 : 1;
                        $date_acq = !empty($cells[4]) ? DateTime::createFromFormat('m/d/Y', $cells[4])->format('Y-m-d') : null;
                        $article = $cells[5];
                        $description = $cells[6];
                        $serial_num = $cells[7];
                        $unit_of_measure = $cells[9];
                        $amount = $cells[10];
                        $par_num = $cells[11];
                        $office = $cells[12];
                        $location = $cells[13];
                        $rcv_by = $cells[15];
                        $act_user = $cells[17];
                        $isd_by = $cells[18];
                        $object_code = $cells[22];
                        $fund01 = $cells[26];
                        $rapidSga = $cells[27];
                        $rapidLp = $cells[28];
                        $fund158 = $cells[29];
                        $rapidGop = $cells[30];
                        $is_unserviceable = strtolower(trim($cells[21])) == 'unserviceable' ? 1 : 0;
                        $office_id = null;
                        $unit_of_measure_id = null;
                        $issued_by_id = null;
                        $rcv_by_id = null;
                        $act_user_id = null;
                        $location_id = null;
                        if (!empty($office)) {
                            $office_id = YIi::$app->db->createCommand("SELECT id 
                            FROM office
                            WHERE office.office_name = :office")
                                ->bindValue(':office', $office)
                                ->queryScalar();
                            if (empty($office_id)) {
                                throw new ErrorException("Office  $office does not exists on line $key");
                            }
                        }
                        if (!empty($unit_of_measure)) {
                            $unit_of_measure_id = YIi::$app->db->createCommand("SELECT id 
                            FROM unit_of_measure
                            WHERE unit_of_measure.unit_of_measure = :unit_of_measure")
                                ->bindValue(':unit_of_measure', $unit_of_measure)
                                ->queryScalar();
                            if (empty($unit_of_measure_id)) {
                                throw new ErrorException("unit_of_measure  $unit_of_measure does not exists on line $key");
                            }
                        }
                        if (!empty($isd_by)) {
                            $issued_by_id = YIi::$app->db->createCommand("SELECT employee_id 
                            FROM employee_search_view
                            WHERE employee_search_view.employee_name LIKE :isd_by")
                                ->bindValue(':isd_by', '%' . $isd_by . '%')
                                ->queryScalar();
                            if (empty($issued_by_id)) {
                                throw new ErrorException("isd_by  $isd_by does not exists on line $key");
                            }
                        }
                        if (!empty($rcv_by)) {
                            $rcv_by_id = YIi::$app->db->createCommand("SELECT employee_id 
                            FROM employee_search_view
                            WHERE employee_search_view.employee_name LIKE :rcv_by")
                                ->bindValue(':rcv_by', '%' . $rcv_by . '%')
                                ->queryScalar();
                            if (empty($rcv_by_id)) {
                                throw new ErrorException("rcv_by  $rcv_by does not exists on line $key");
                            }
                        }
                        if (!empty($act_user)) {
                            $act_user_id = YIi::$app->db->createCommand("SELECT employee_id 
                            FROM employee_search_view
                            WHERE employee_search_view.employee_name LIKE :act_user")
                                ->bindValue(':act_user', '%' . $act_user . '%')
                                ->queryScalar();
                            if (empty($act_user_id)) {
                                throw new ErrorException("act_user  $act_user does not exists on line $key");
                            }
                        }
                        if (!empty($location)) {
                            $location_id = YIi::$app->db->createCommand("SELECT id 
                            FROM `location`
                            WHERE `location`.`location` = :_location")
                                ->bindValue(':_location', $location)
                                ->queryScalar();
                            if (empty($location_id)) {
                                throw new ErrorException("location  $location does not exists on line $key");
                            }
                        }

                        $property  = new Property();
                        $property->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                        $property->property_number = $property_num;
                        $property->fk_office_id = $office_id;
                        $property->date = $date_acq;
                        $property->unit_of_measure_id = $unit_of_measure_id;
                        $property->serial_number = !empty($serial_num) ? $serial_num : 'N/A';
                        $property->employee_id = $issued_by_id;
                        $property->article = $article;
                        $property->description = $description;
                        $property->acquisition_amount = $amount;
                        $property->is_ssf = $is_ssf;
                        $property->fk_ssf_sp_num_id = null;
                        $property->fk_property_article_id = null;
                        if (!$property->validate()) {
                            throw new ErrorException(json_encode($property->errors));
                        }
                        if (!$property->save(false)) {
                            throw new ErrorException("property save failed");
                        }

                        $otherPropertyDetailId = $this->createOtherPropertyDetails($property->id, $object_code,   $property->property_number);
                        if (!empty($fund01)) {
                            $this->createOtherPropertyDetailsItems($otherPropertyDetailId, 5, $fund01);
                        }
                        if (!empty($rapidSga)) {
                            $this->createOtherPropertyDetailsItems($otherPropertyDetailId, 9, $rapidSga);
                        }
                        if (!empty($rapidLp)) {
                            $this->createOtherPropertyDetailsItems($otherPropertyDetailId, 8, $rapidLp);
                        }
                        if (!empty($fund158)) {
                            $this->createOtherPropertyDetailsItems($otherPropertyDetailId, 6, $fund158);
                        }
                        if (!empty($rapidGop)) {
                            $this->createOtherPropertyDetailsItems($otherPropertyDetailId, 13, $rapidGop);
                        }





                        $par = new Par();
                        $par->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                        $par->par_number = $par_num;
                        $par->fk_office_id  = $office_id;
                        $par->date  = date('Y-m-d');
                        $par->fk_received_by  = $rcv_by_id;
                        $par->fk_actual_user  = $act_user_id;
                        $par->fk_property_id  = $property->id;
                        $par->fk_issued_by_id  = $issued_by_id;
                        $par->fk_location_id  = $location_id;
                        $par->is_unserviceable  = $is_unserviceable;
                        $par->_year  = 2022;
                        if (!$par->validate()) {
                            throw new ErrorException(json_encode($par->errors));
                        }
                        if (!$par->save(false)) {
                            throw new ErrorException('par save failed');
                        }
                        $pc = new PropertyCard();
                        $pc->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                        $pc->serial_number = $pc_num;
                        $pc->fk_par_id = $par->id;
                        if (!$pc->validate()) {
                            throw new ErrorException(json_encode($pc->errors));
                        }
                        if (!$pc->save(false)) {
                            throw new ErrorException('PC save failed');
                        }
                    }
                    $transaction->commit();
                } catch (ErrorException $e) {
                    $transaction->rollBack();
                    return $e->getMessage();
                }


                echo '<pre>';
                var_dump("Success");
                echo '</pre>';
            }
        }
        return $this->render('file_upload', ['model' => $model]);
    }
    private function createOtherPropertyDetails($property_id, $object_code, $property_number)
    {

        try {
            $chart_id = YIi::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :object_code")
                ->bindValue(':object_code', $object_code)
                ->queryScalar();
            $model  = new OtherPropertyDetails();
            $model->id = MyHelper::getUuid();
            $model->fk_property_id = $property_id;
            $model->fk_chart_of_account_id = $chart_id;
            $model->salvage_value_prcnt = 5;
            $model->useful_life = 60;
            $createSubAccount = MyHelper::createPropertySubAccount($model->fk_chart_of_account_id, $property_number);
            if ($createSubAccount['isSuccess'] === true) {
                $model->fk_sub_account1_id = $createSubAccount['id'];
            }
            // $create_depreciation_sub_account = MyHelper::createPropertySubAccount($model->fk_chart_of_account_id, $model->property->property_number);
            $decpreciation_account_id = YIi::$app->db->createCommand("SELECT fk_depreciation_id FROM chart_of_accounts WHERE chart_of_accounts.id = :id")
                ->bindValue(':id', $chart_id)
                ->queryScalar();
            // echo $decpreciation_account_id;
            // die();
            $create_depreciation_sub_account = MyHelper::createPropertySubAccount($decpreciation_account_id, $model->property->property_number);
            if ($create_depreciation_sub_account['isSuccess'] === true) {
                $model->fk_depreciation_sub_account1_id = $create_depreciation_sub_account['id'];
            }
            if (!$model->validate()) {
                throw new ErrorException(json_encode($model->errors));
            }
            if (!$model->save(false)) {
                throw new ErrorException("Model Save Failed");
            }
            return $model->id;
        } catch (ErrorException $e) {
            return json_encode(['error_message' => $e->getMessage()]);
        }
    }
    private  function createOtherPropertyDetailsItems($modelid, $bookId, $amt)
    {

        $item = new OtherPropertyDetailItems();
        $item->fk_other_property_details_id = $modelid;
        $item->book_id = $bookId;
        $item->amount =  $amt;
        if (!$item->validate()) {
            throw new ErrorException($amt . 'yawa');
        }
        if (!$item->save(false)) {
            throw new ErrorException('Item Save Failed');
        }
    }
    private function query($uacs, $emp_id = null)
    {

        $qry = new Query();
        $qry->select([
            'detailed_property_database.rcv_by',
            'detailed_property_database.article',
            'detailed_property_database.description',
            'detailed_property_database.property_number',
            'detailed_property_database.unit_of_measure',
            'detailed_property_database.acquisition_amount',
            '1 as qty',
            'detailed_property_database.uacs',
            'detailed_property_database.general_ledger'

        ])
            ->from('detailed_property_database')
            ->andWhere(" detailed_property_database.isUnserviceable = 'serviceable'")
            ->andWhere("detailed_property_database.is_current_user = 1")
            ->andWhere("detailed_property_database.derecognition_num IS NULL")
            ->andWhere("detailed_property_database.uacs = :uacs", ['uacs' => $uacs]);
        if (!empty($emp_id)) {
            $qry->andWhere("detailed_property_database.rcv_by_id = :emp_id", ['emp_id' => $emp_id]);
        }
        $res  = $qry->all();
        return $res;
    }
    public function actionPpelc()
    {
        if (Yii::$app->request->post()) {
            $book_id = !empty(Yii::$app->request->post('book_id')) ? Yii::$app->request->post('book_id') : null;
            $employee_id = !empty(Yii::$app->request->post('employee_id')) ? Yii::$app->request->post('employee_id') : null;
            $office_id = !empty(Yii::$app->request->post('office_id')) ? Yii::$app->request->post('office_id') : null;
            $reporting_period = !empty(Yii::$app->request->post('reporting_period')) ? Yii::$app->request->post('reporting_period') : null;
            $uacs_id = !empty(Yii::$app->request->post('uacs')) ? Yii::$app->request->post('uacs') : null;



            return json_encode($this->ppelcQuery(
                $book_id,
                $employee_id,
                $office_id,
                $reporting_period,
                $uacs_id
            ));
        }
        return $this->render('ppelc');
    }
    private function ppelcQuery(
        $book_id,
        $employee_id = null,
        $office_id = null,
        $reporting_period,
        $uacs_id
    ) {
        $uacs  = Yii::$app->db->createCommand("SELECT uacs FROM chart_of_accounts WHERE id = :id")->bindValue(':id', $uacs_id)->queryScalar();
        $book_name = Books::findOne($book_id)->name;
        $qry = new Query();
        $qry->select([
            'detailed_property_database.pc_num',
            'detailed_property_database.uacs',
            'detailed_property_database.general_ledger',
            'detailed_other_property_details.book_name',
            'detailed_property_database.date_acquired',
            'detailed_property_database.acquisition_amount',
            'detailed_other_property_details.book_val',
            'detailed_other_property_details.mnthly_depreciation',
            'detailed_property_database.useful_life',
            'detailed_property_database.strt_mnth',
            new Expression(':reporting_period as reporting_period', ['reporting_period' => $reporting_period]),
            new Expression('(CASE 
            WHEN TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period) > detailed_property_database.useful_life THEN  detailed_property_database.useful_life
            ELSE TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period)
            END) as diff', ['r_period' => "$reporting_period-01"]),
            new Expression(
                '(CASE 
            WHEN TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period) > detailed_property_database.useful_life THEN  detailed_property_database.useful_life
            ELSE TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period)
            END) * detailed_other_property_details.mnthly_depreciation as depreciated_amt',


                ['r_period' => "$reporting_period-01"]
            ),
            new Expression('detailed_other_property_details.book_val-
            (CASE 
            WHEN TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period) > detailed_property_database.useful_life THEN  detailed_property_database.useful_life
            ELSE TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period)
            END) * detailed_other_property_details.mnthly_depreciation
            as book_bal', ['r_period' => "$reporting_period-01"]),

        ])
            ->from('detailed_property_database')
            ->join('JOIN', 'detailed_other_property_details', 'detailed_property_database.property_id = detailed_other_property_details.property_id')
            ->andWhere("detailed_property_database.is_current_user = 1")
            ->andWhere("detailed_property_database.isUnserviceable = 'serviceable'")
            ->andWhere("detailed_property_database.strt_mnth <=:reporting_period", ['reporting_period' => $reporting_period])
            ->andWhere("detailed_property_database.uacs =:uacs", ['uacs' => $uacs])
            ->andWhere("detailed_other_property_details.book_name =:book_name", ['book_name' => $book_name])
            ->andWhere("DATE_FORMAT(detailed_property_database.derecognition_date,'%Y-%m') >= :reporting_period 
        OR detailed_property_database.derecognition_num IS NULL", ['reporting_period' => $reporting_period]);

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
        }
        if (!empty($office_id)) {
            $offce_name = Office::findOne($office_id)->office_name;
            $qry->andWhere("detailed_property_database.office_name = :offce_name", ['offce_name' => $offce_name]);
        }
        if (!empty($employee_id)) {
            $qry->andWhere("detailed_property_database.rcv_by_id = :emp_id", ['emp_id' => $employee_id]);
        }
        $qry->orderBy("detailed_property_database.date_acquired");
        // echo $qry->createCommand()->getRawSql();
        // die();
        return $qry->all();
    }
    public function actionUserProperties()
    {
        if (Yii::$app->request->post()) {


            $act_usr_id  = !empty(MyHelper::post('act_usr_id')) ? MyHelper::post('act_usr_id') : null;
            $actbl_ofr  = !empty(MyHelper::post('actbl_ofr')) ? MyHelper::post('actbl_ofr') : null;
            $office  = !empty(MyHelper::post('office')) ? MyHelper::post('office') : null;
            // if (empty($act_usr_id) && empty($actbl_ofr)) {
            //     return json_encode('');
            // }
            $qry = new Query();
            $qry->select([
                "property.property_number",
                "location.location",
                'par.par_number',
                new Expression('IFNULL(property_articles.article_name,property.article) as article_name'),
                'property.description',
                'property.serial_number',
                new Expression('property.date as date_acquired'),
                'property.acquisition_amount',
                new Expression(" (CASE 
                    WHEN par.is_unserviceable = 1 THEN 'UnSeviceable'
                    ELSE 'Serviceable'
                END ) as isServiceable"),
                new Expression('IFNULL(act_usr.employee_name,"") as actual_user'),
                new Expression('rcv_by.employee_name as actble_ofr'),
                new Expression('property_card.serial_number as pc_num')
            ])
                ->from('property')
                ->join('JOIN', 'par', 'property.id = par.fk_property_id')
                ->join('LEFT JOIN', 'property_card', 'par.id = property_card.fk_par_id')
                ->join('LEFT JOIN', 'property_articles', 'property.fk_property_article_id = property_articles.id')
                ->join('LEFT JOIN', 'employee_search_view as act_usr', 'par.fk_actual_user = act_usr.employee_id')
                ->join('LEFT JOIN', 'employee_search_view as rcv_by', 'par.fk_received_by = rcv_by.employee_id')
                ->join('LEFT JOIN',  'derecognition', 'property.id = derecognition.fk_property_id')
                ->join('LEFT JOIN',  'location', 'par.fk_location_id = location.id')

                ->andWhere('par.is_current_user = 1')
                ->andWhere('derecognition.id IS NULL');
            if (!empty($act_usr_id)) {
                $qry->andWhere("par.fk_actual_user = :act_usr_id", ['act_usr_id' => $act_usr_id]);
            }
            if (!empty($actbl_ofr)) {
                $qry->andWhere("par.fk_received_by= :actbl_ofr", ['actbl_ofr' => $actbl_ofr]);
            }
            if (!empty($office)) {
                $qry->andWhere("property.fk_office_id= :office", ['office' => $office]);
            }
            $qry->orderBy("location.id");
            return json_encode($result = ArrayHelper::index($qry->all(), null, 'actble_ofr'));
        }
        return $this->render('user_properties');
    }
    public function actionGeneratePar()
    {
        if (Yii::$app->request->post()) {
            $office_id = MyHelper::post('office_id');
            $qry  = Yii::$app->db->createCommand("SELECT 
                office.office_name,
                par.par_number,
                IFNULL(property_articles.article_name,property.article) as article,
                property.description,
                property.property_number,
                property.date,
                property.acquisition_amount,
                rcv_by.employee_name as rcv_by_nme,
                rcv_by.position as rcv_by_pos,
                isd_by.employee_name as isd_by_nme,
                isd_by.position as isd_by_pos,
                act_usr.employee_name as act_usr_nme,
                act_usr.position as act_usr_pos,
                unit_of_measure.unit_of_measure
                FROM par
                JOIN property ON par.fk_property_id = property.id
                LEFT JOIN employee_search_view as rcv_by ON par.fk_received_by = rcv_by.employee_id
                LEFT JOIN employee_search_view as isd_by ON par.fk_issued_by_id = isd_by.employee_id
                LEFT JOIN employee_search_view as act_usr ON par.fk_actual_user  = act_usr.employee_id
                LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
                LEFT JOIN office ON par.fk_office_id = office.id
                LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
                
                WHERE office.id = :office_id")
                ->bindValue(':office_id', $office_id)
                ->queryAll();
            return json_encode($qry);
        }
        return $this->render('generate_par');
    }
    public function actionRao()
    {
        if (Yii::$app->request->post()) {

            $year = Yii::$app->request->post('year');


            $qry  = Yii::$app->db->createCommand("CALL rao(:yr)")->bindValue(':yr', $year)->queryAll();


            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();


            // // header
            $headers = [
                "Budget Year",
                "Office Name",
                "Division",
                "Allotment No.",
                "MFO/PAP",
                "Fund Source",
                "Book",
                "Object Code",
                "Account Title",
                "PR No.",
                "PR Date",
                "PR Purpose",
                "Transaction Tracking No.",
                "Transaction Date",
                "Transaction Particular",
                "Payee",
                "ORS No.",
                "Allotment Amount",
                "PR Amount",
                "Transaction Amount",
                "ORS Amount",


            ];
            $cellVal = [
                'budget_year',
                'office_name',
                'division',
                'allotmentNumber',
                'mfo_name',
                'fund_source_name',
                'book_name',
                'uacs',
                'account_title',
                'pr_number',
                'prDate',
                'prPurpose',
                'transaction_num',
                'txnDate',
                'txnParticular',
                'txnPayee',
                'orsNum',
                'allotAmt',
                'prAmt',
                'txnAmt',
                'orsAmt',

            ];
            foreach ($headers as $key => $head) {
                $sheet->setCellValue([$key + 1, 2], $head);
            }




            $x = 7;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );


            $row = 3;
            foreach ($qry as $itm) {
                foreach ($cellVal as $col => $cell) {
                    $sheet->setCellValue(
                        [$col + 1, $row],
                        $itm[$cell] ?? ''
                    );
                }
                $row++;
            }

            date_default_timezone_set('Asia/Manila');
            $date = date('Y-m-d h-s A');
            $file_name = "rao_$year.xlsx";
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
            $fileSaveLoc =  "exports\\" . $file_name;
            $path = Yii::getAlias('@webroot') . '/exports';
            $file = $path . "/$file_name";
            $writer->save($file);
            header('Content-Type: application/vnd.ms-excel');
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            header('Content-Transfer-Encoding: binary');
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Pragma: public'); // HTTP/1.0
            echo  json_encode($fileSaveLoc);
            // unlink($fileSaveLoc)
            // echo "<script>window.open('$fileDwnldLoc','_self')</script>";
            exit();
        }
        return $this->render('rao');
    }
}

// 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
// https://github.com/kiotipot1/dti-afms-2.git.
// git pull https://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@github.com/kiotipot1/dti-afms-2.git
