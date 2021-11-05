<?php

namespace frontend\controllers;

use app\models\AdvancesLiquidationSearch;
use app\models\Cdr;
use app\models\ChartOfAccounts;

use app\models\DetailedDvAucsSearch;
use app\models\DvAucs;

use app\models\PoTransmittalsPendingSearch;
use app\models\RaoSearch;
use app\models\TransactionArchiveSearch;
use Da\QrCode\QrCode;
use DateTime;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ReportController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
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
                    'git-pull',
                    'cadadr',
                    'annex3',
                    'annex-A',
                    'raaf',

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


                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [
                            'division-fur',
                            'saobs',

                        ],
                        'allow' => true,
                        'roles' => ['department-offices', 'super-user']
                    ],

                    [
                        'actions' => [
                            'cibr',
                            'cdr',
                            'advances-liquidation',

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
    public function actionUnpaidObligation()
    {
        return $this->render('unpaid_obligation');
    }
    public function actionRao()
    {
        $searchModel = new RaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('rao_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSaob()
    {
        if ($_POST) {
            $reporting_period_this_month =  $_POST['reporting_period'];

            $reporting_period = date_create($_POST['reporting_period']);
            date_sub($reporting_period, date_interval_create_from_date_string("1 month"));
            $reporting_period_last_month = date_format($reporting_period, "Y-m");



            $q = Yii::$app->db->createCommand("SELECT ors.*,
            record_allotment_entries.amount,
            major_accounts.name as major_account,
            chart_of_accounts.uacs,
            chart_of_accounts.major_account_id,
            chart_of_accounts.general_ledger from record_allotment_entries,chart_of_accounts,major_accounts,
            (
           SELECT SUM(raoud_entries.amount) as total_obligation,raouds.record_allotment_entries_id,
                process_ors.reporting_period
                from raouds,raoud_entries,process_ors
                where raouds.id = raoud_entries.raoud_id
                
                AND raouds.process_ors_id = process_ors.id
                AND process_ors.reporting_period IN (:this_month,:last_month)
                GROUP BY raouds.record_allotment_entries_id,process_ors.reporting_period

                ORDER BY raouds.record_allotment_entries_id
        
        ) as ors
        where record_allotment_entries.id = ors.record_allotment_entries_id
        AND chart_of_accounts.major_account_id = major_accounts.id
        AND record_allotment_entries.chart_of_account_id =chart_of_accounts.id
        
        ORDER BY record_allotment_entries.id")
                ->bindValue(':this_month', $reporting_period_this_month)
                ->bindValue(':last_month', $reporting_period_last_month)
                ->queryAll();

            $result = ArrayHelper::index($q, null, 'record_allotment_entries_id');
            $qwer = [];

            foreach ($q as $val) {
                $id = $val['record_allotment_entries_id'];
                $x = array_key_exists($val['record_allotment_entries_id'], $qwer);
                if (!$x) {
                    $qwer[$val['record_allotment_entries_id']] = [
                        'major_account' => $val['major_account'],
                        'object_code' => $val['uacs'],
                        'general_ledger' => $val['general_ledger'],
                        'allotment_amount' => $val['amount'],
                    ];
                }

                $qwer[$id][$val['reporting_period']] = $val['total_obligation'];
            }

            $result = ArrayHelper::index($qwer, null, [function ($element) {
                return $element['major_account'];
            },]);
            // ob_clean();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();
            return $this->render('saob', [
                'query' => $result,
                'reporting_period_this_month' => $reporting_period_this_month,
                'reporting_period_last_month' => $reporting_period_last_month
            ]);
        } else {

            // ob_clean();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            // return ob_get_clean();
            return $this->render('saob', [
                'query' => ''
            ]);
        }
    }


    public function actionGetCash()
    {
        $total_cash_disbursed = Yii::$app->db->createCommand("SELECT books.`name`,
         SUM(dv_aucs_entries.amount_disbursed)as total_disbursed 
        FROM cash_disbursement,dv_aucs,dv_aucs_entries,books
        WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
        AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
        AND cash_disbursement.book_id = books.id
        GROUP BY cash_disbursement.book_id")->queryAll();
        // $cash_recieved = Yii::$app->db->createCommand("SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved")->queryOne();
        $date = '2021-01-05';
        $query = (new \yii\db\Query())
            ->select([
                'SUM(dv_aucs_entries.amount_disbursed) as total_disbursed',
                "(SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved) as total_cash_recieved",
                "( (SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved) - SUM(dv_aucs_entries.amount_disbursed)) as cash_balance"
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
            chart_of_accounts.uacs as gl_object_code,
            ROUND(IFNULL(SUM(withdrawals),0),2)+
            ROUND(IFNULL(SUM(vat_nonvat),0),2)+
            ROUND(IFNULL(SUM(expanded_tax),0),2)  as debit,
            ROUND(SUM(withdrawals),2) as total_withdrawals,
            ROUND(SUM(vat_nonvat),2) as total_vat_nonvat,
            ROUND(SUM(expanded_tax),2) as total_expanded_tax
            FROM liquidation_entries
            LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id= chart_of_accounts.id
            LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
            LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id  = advances_entries.id
            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id =  cash_disbursement.id
            WHERE liquidation_entries.reporting_period = :reporting_period
            AND liquidation.province = :province
            AND advances_entries.report_type = :report_type
            GROUP BY chart_of_accounts.uacs
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

            // if (empty($account)) {

            //     $account = Yii::$app->memem->createSubAccount1($acc, $c_id['id']);
            // }

            // if (empty($vat)) {

            //     $vat = Yii::$app->memem->createSubAccount1($v, $c_id['id']);
            // }
            // if (empty($expanded)) {

            //     $expanded = Yii::$app->memem->createSubAccount1($e, $c_id['id']);
            // }
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
    public function actionDetailedDvAucs()
    {
        $searchModel = new DetailedDvAucsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
            $query  = (new \yii\db\Query())->select(["
                SUBSTRING_INDEX(advances_entries.reporting_period,'-',1) as budget_year,
                advances.province,
                advances_entries.reporting_period,
                advances_entries.fund_source,
                IFNULL(beginning_advances.amount,0) - IFNULL(beginning_balance.prev_withdrawals,0)  as begin_balance,
                IFNULL(liquidation_total.total_withdrawals,0) as total_withdrawals,
                (  IFNULL(beginning_advances.amount,0) +
                IFNULL(current_advances.amount,0) )- 
                 IFNULL(liquidation_total.total_withdrawals,0) as balance,
                dv_aucs.particular,
                IFNULL(current_advances.amount,0)   as cash_advances_for_the_period
                "])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', 'cash_disbursement', 'advances_entries.cash_disbursement_id = cash_disbursement.id')
                ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
                ->join(
                    'LEFT JOIN',
                    "(
                        SELECT 
                        liquidation_balance_per_advances.advances_entries_id,
                        SUM(liquidation_balance_per_advances.total_withdrawals) as total_withdrawals
                        FROM liquidation_balance_per_advances
                        WHERE
                        liquidation_balance_per_advances.reporting_period >= :from_reporting_period  
                        AND
                     liquidation_balance_per_advances.reporting_period <= :to_reporting_period
                        GROUP BY liquidation_balance_per_advances.advances_entries_id
                    ) as liquidation_total",
                    'advances_entries.id = liquidation_total.advances_entries_id',
                    [
                        'from_reporting_period' => $from_reporting_period,
                        'to_reporting_period' => $to_reporting_period,
                    ]

                )
                ->join(
                    'LEFT JOIN',
                    "(
                        SELECT 
                        liquidation_balance_per_advances.advances_entries_id,
                        SUM(liquidation_balance_per_advances.total_withdrawals) as prev_withdrawals
                        FROM liquidation_balance_per_advances
                        WHERE 
                        liquidation_balance_per_advances.reporting_period < :from_reporting_period
                        GROUP BY liquidation_balance_per_advances.advances_entries_id
                    ) as beginning_balance",
                    'advances_entries.id = beginning_balance.advances_entries_id',
                    [
                        'from_reporting_period' => $from_reporting_period,
                    ]
                )
                ->join(
                    "LEFT JOIN",
                    "(SELECT advances_entries.id,advances_entries.amount
                    FROM advances_entries
                    WHERE
                    advances_entries.reporting_period < :from_reporting_period
                    ) as beginning_advances",
                    "
                    advances_entries.id = beginning_advances.id
                    ",
                    [
                        'from_reporting_period' => $from_reporting_period
                    ]
                )
                ->join(
                    "LEFT JOIN",
                    "(SELECT advances_entries.id,advances_entries.amount
                        FROM advances_entries
                        WHERE
                        advances_entries.reporting_period >= :from_reporting_period
                        AND
                        advances_entries.reporting_period <= :to_reporting_period
                        ) as current_advances",
                    "
                        advances_entries.id = current_advances.id
                ",
                    [
                        'from_reporting_period' => $from_reporting_period,
                        'to_reporting_period' => $to_reporting_period
                    ]
                )
                ->where('advances_entries.fund_source_type=:fund_source_type', ['fund_source_type' => $fund_source_type])
                ->andWhere('advances_entries.reporting_period <= :to_reporting_period', ['to_reporting_period' => $to_reporting_period])
                ->andWhere('advances_entries.is_deleted !=1 ');
            if (strtolower($province) !== 'all') {
                $query->andWhere('advances.province = :province', ['province' => $province]);
            }
            $final_query = $query->orderBy('advances_entries.reporting_period')
                ->all();
            $result = ArrayHelper::index($final_query, null, [function ($element) {
                return $element['budget_year'];
            }, 'reporting_period']);
            $index_per_province = ArrayHelper::index($final_query, null, 'province');
            // unset($result['2020']['2020-12']);
            $conso = array();
            foreach (array_unique(array_column($final_query, 'province')) as $val) {

                $conso[$val] = [
                    'grand_total_withdrawals' => round(array_sum(array_column($index_per_province[$val], 'total_withdrawals')), 2),
                    'grand_total_begin_balance' => round(array_sum(array_column($index_per_province[$val], 'begin_balance')), 2),
                    'grand_total_cash_advances_for_the_period' => round(array_sum(array_column($index_per_province[$val], 'cash_advances_for_the_period')), 2),
                    'grand_total_balance' => round(array_sum(array_column($index_per_province[$val], 'balance')), 2)
                ];
            }
            // echo "<pre>";
            // var_dump($conso);
            // echo "</pre>";

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
                ->andwhere("advances_entries.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
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
                ->where('is_deleted != 1');
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
                ->andWhere("advances_entries.reporting_period <=:to_reporting_period", ['to_reporting_period' => $to_reporting_period]);
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
                ->where(' advances_entries.reporting_period < :from_reporting_period ', ['from_reporting_period' => $from_reporting_period]);
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

            $budget_fur_query = $budget_fur->createCommand()->getRawSql();
            $current_advances_query = $current_advances->createCommand()->getRawSql();
            $prev_advances_query = $prev_advances->createCommand()->getRawSql();
            $current_liquidation_query = $current_liquidation->createCommand()->getRawSql();




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

        echo "<pre>";
        echo  shell_exec("git fetch https://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@github.com/kiotipot1/dti-afms-2.git ");
        echo "</pre>";
        echo "<pre>";
        echo  shell_exec("git pull https://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@github.com/kiotipot1/dti-afms-2.git");
        echo "</pre>";

        echo "<pre>";
        echo  shell_exec("yii migrate --interactive=0");
        echo "</pre>";
        echo "<pre>";
        echo  "qweqweq";
        echo "</pre>";
    }
    public function actionDivisionFur()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $division = !empty($_POST['division']) ? $_POST['division'] : '';
            $document_recieve = $_POST['document_recieve'];

            if (Yii::$app->user->can('department-offices')) {
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
            echo "<pre>";
            var_dump($result);
            echo "</pre>";
            die();

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
            $mfo = Yii::$app->db->createCommand("SELECT * FROM mfo_pap_code")->queryAll();
            $mfo_final = ArrayHelper::index($mfo, null, 'name');
            $result2 = ArrayHelper::index($query, null, [function ($element) {
                return $element['division'];
            }, 'mfo_name', 'major_name', 'sub_major_name',]);

            return json_encode(['result' => $result2, 'allotments' => $allotment_total, 'mfo_pap' => $mfo_final]);
        }
        return $this->render('division_fur');
    }
    public function actionCadadr()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $book = $_POST['book'];


            $query = Yii::$app->db->createCommand("SELECT * FROM cadadr
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
        return $this->render('cadadr');
    }
    public function actionAnnex3()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['to_reporting_period'];
            $from_reporting_period = date('Y-01', strtotime($to_reporting_period));
            $query = Yii::$app->db->createCommand("SELECT
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
            AND advances_entries.is_deleted !=1
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

    public function actionX()
    {
        $text = "Name: Jhon Doe";
        $text .= "'\n'Adress: Butuan";
        $text .= "'\n'Work: Programmer";
        $path = 'images';


        $qrCode = (new QrCode($text))
            ->setSize(100);
        header('Content-Type: ' . $qrCode->getContentType());
        $base_path =  \Yii::getAlias('@webroot');
        $qrCode->writeFile($base_path . '/images/code2.png');
        // writer defaults to PNG when none is specified

        // display directly to the browser 
        // echo '<img src="' . $qrCode->writeDataUri() . '">';
        echo $qrCode->writeString();
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
                'book'=>$book
            ]);
        }
        return $this->render('cdj');
    }
}

// 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
// https://github.com/kiotipot1/dti-afms-2.git.
// git pull https://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@github.com/kiotipot1/dti-afms-2.git
