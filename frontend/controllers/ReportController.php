<?php

namespace frontend\controllers;

use app\models\AdvancesLiquidationSearch;
use app\models\Cdr;
use app\models\ChartOfAccounts;

use app\models\DetailedDvAucsSearch;
use app\models\DvAucs;

use app\models\PoTransmittalsPendingSearch;

use app\models\TransactionArchiveSearch;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

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
                    'saobs'

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
                            'saobs'

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
                            'budget-year-fur'


                        ],
                        'allow' => true,
                        'roles' => ['@']
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
            ROUND(SUM(withdrawals),2)+ROUND(SUM(vat_nonvat),2)+ROUND(SUM(expanded_tax),2) as debit,
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
                IFNULL(beginning_advances.amount,0)   as begin_balance,
                IFNULL(liquidation_total.total_withdrawals,0) as total_withdrawals,
                (  IFNULL(beginning_advances.amount,0) + IFNULL(current_advances.amount,0) )-  IFNULL(liquidation_total.total_withdrawals,0) as balance,
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
                ->andWhere('advances_entries.reporting_period <= :to_reporting_period', ['to_reporting_period' => $to_reporting_period]);
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
                FROM advances_entries
            "
            ])
                ->join('LEFT JOIN', "advances", 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', "fund_source_type", 'advances_entries.fund_source_type = fund_source_type.`name`');
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
                ->join('LEFT JOIN', 'fund_source_type', 'advances_entries.fund_source_type=  fund_source_type.`name`');
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
                "process_ors_entries.record_allotment_entries_id",
                "chart_of_accounts.uacs",
                "SUM(process_ors_entries.amount) as total_current_ors"

            ])
                ->from('process_ors_entries')
                ->join('LEFT JOIN', 'chart_of_accounts', 'process_ors_entries.chart_of_account_id = chart_of_accounts.id')
                ->join('LEFT JOIN', 'major_accounts', 'chart_of_accounts.major_account_id = major_accounts.id')
                ->join('LEFT JOIN', 'record_allotments_view', 'process_ors_entries.record_allotment_entries_id = record_allotments_view.entry_id')
                ->join('LEFT JOIN', 'process_ors', 'process_ors_entries.process_ors_id = process_ors.id')
                ->where(" process_ors_entries.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("process_ors_entries.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period])
                ->andWhere("process_ors.book_id = :book_id", ['book_id' => $book_id]);
            if (strtolower($mfo_code) !== 'all') {

                $current_ors->andWhere("record_allotments_view.mfo_code = :mfo_code", ['mfo_code' => $mfo_code]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $current_ors->andWhere("record_allotments_view.document_recieve = :document", ['document' => $document_recieve]);
            }
            $current_ors->groupBy("process_ors_entries.record_allotment_entries_id,
            chart_of_accounts.uacs");

            $prev_ors = new Query();
            $prev_ors->select([
                "process_ors_entries.record_allotment_entries_id",
                "chart_of_accounts.uacs",
                "SUM(process_ors_entries.amount) as total_prev_ors"

            ])
                ->from('process_ors_entries')
                ->join('LEFT JOIN', 'chart_of_accounts', 'process_ors_entries.chart_of_account_id = chart_of_accounts.id')
                ->join('LEFT JOIN', 'major_accounts', 'chart_of_accounts.major_account_id = major_accounts.id')
                ->join('LEFT JOIN', 'record_allotments_view', 'process_ors_entries.record_allotment_entries_id = record_allotments_view.entry_id')
                ->join('LEFT JOIN', 'process_ors', 'process_ors_entries.process_ors_id = process_ors.id')
                ->where(" process_ors_entries.reporting_period < :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
                ->andWhere("process_ors.book_id = :book_id", ['book_id' => $book_id]);

            if (strtolower($mfo_code) !== 'all') {

                $prev_ors->andWhere("record_allotments_view.mfo_code = :mfo_code", ['mfo_code' => $mfo_code]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $prev_ors->andWhere("record_allotments_view.document_recieve = :document", ['document' => $document_recieve]);
            }
            $prev_ors->groupBy("process_ors_entries.record_allotment_entries_id,
            chart_of_accounts.uacs");
            $allotment = new Query();
            $allotment->select([
                "mfo_pap_code.`code` as mfo_code",
                "document_recieve.`name` as document_recieve_name",
                "chart_of_accounts.uacs",
                "SUM(record_allotment_entries.amount) as total_allotment"
            ])
                ->from("record_allotment_entries")
                ->join('LEFT JOIN', 'record_allotments', 'record_allotment_entries.record_allotment_id = record_allotments.id')
                ->join('LEFT JOIN', 'document_recieve', 'record_allotments.document_recieve_id = document_recieve.id')
                ->join('LEFT JOIN', 'mfo_pap_code', 'record_allotments.mfo_pap_code_id = mfo_pap_code.id')
                ->join('LEFT JOIN', 'chart_of_accounts', 'record_allotment_entries.chart_of_account_id = chart_of_accounts.id')
                ->where("record_allotments.book_id = :book_id", ['book_id' => $book_id]);


            if (strtolower($mfo_code) !== 'all') {

                $allotment->andWhere("mfo_pap_code.`code` = :mfo_code", ['mfo_code' => $mfo_code]);
            }
            if (strtolower($document_recieve) !== 'all') {

                $allotment->andWhere("document_recieve.name = :document", ['document' => $document_recieve]);
            }
            $allotment->groupBy('mfo_pap_code.`code`,
                document_recieve.`name`,
                chart_of_accounts.uacs');

            // 103,075	103,075	-85,596.64	5.9	OO 4.1 (FTL)	GARO	5020000000



            $sql_current_ors = $current_ors->createCommand()->getRawSql();
            $sql_prev_ors = $prev_ors->createCommand()->getRawSql();
            $sql_allotment = $allotment->createCommand()->getRawSql();
            $query = Yii::$app->db->createCommand("SELECT

            current_prev.mfo_code,
            current_prev.document_recieve_name,
            current_prev.uacs,
            allotment_chart.general_ledger as allotment_account_title,
            major_accounts.object_code as major_object_code,
            major_accounts.`name` as major_name,
            sub_major_accounts.object_code as sub_major_object_code,
            sub_major_accounts.`name` as sub_major_name,
            chart_of_accounts.uacs as ors_object_code,
            chart_of_accounts.general_ledger,
            CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as account_title,
            IFNULL(current_prev.total_ors,0) as current_total,
            IFNULL(current_prev.total_prev_ors,0) as prev_total,
            IFNULL(current_prev.total_ors,0)+
            IFNULL(current_prev.total_prev_ors,0) as ors_to_date,
            allotment.total_allotment
            FROM (
            SELECT 

            mfo_pap_code.`code` as mfo_code,
            document_recieve.`name` as document_recieve_name ,
            chart_of_accounts.uacs,
            ors.uacs as ors_object_code,
            
            SUM(ors.total_current_ors) as total_ors,
            SUM(ors.total_prev_ors) as total_prev_ors
            
            FROM record_allotment_entries
            LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
            LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
            LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
            LEFT JOIN (
            SELECT
                current_ors.record_allotment_entries_id,
                current_ors.uacs,
                current_ors.total_current_ors,
                prev_ors.total_prev_ors 
             FROM 
            ($sql_current_ors) as current_ors  
            LEFT JOIN ($sql_prev_ors) as prev_ors   
            ON (current_ors.record_allotment_entries_id = prev_ors.record_allotment_entries_id AND current_ors.uacs = prev_ors.uacs)
            UNION ALL 
            SELECT
                prev_ors.record_allotment_entries_id,
                prev_ors.uacs,
                current_ors.total_current_ors,
                prev_ors.total_prev_ors 
             FROM 
            ($sql_current_ors) as current_ors  
            RIGHT JOIN ($sql_prev_ors) as prev_ors   
            ON (current_ors.record_allotment_entries_id = prev_ors.record_allotment_entries_id AND current_ors.uacs = prev_ors.uacs)
            WHERE IFNULL(current_ors.total_current_ors,0) <=0 
            ) as ors
            ON record_allotment_entries.id = ors.record_allotment_entries_id
            GROUP BY
            mfo_pap_code.`code`,
            document_recieve.`name` ,
            chart_of_accounts.uacs,
            ors.uacs
            ) as current_prev
            RIGHT  JOIN ($sql_allotment) as allotment
            ON (
                 current_prev.mfo_code = allotment.mfo_code
            AND  current_prev.document_recieve_name =allotment.document_recieve_name 
            AND  current_prev.uacs =allotment.uacs
            )
     
            LEFT JOIN chart_of_accounts ON IFNULL(current_prev.ors_object_code,current_prev.uacs) = chart_of_accounts.uacs
            LEFT JOIN chart_of_accounts  as allotment_chart ON  current_prev.uacs = allotment_chart.uacs
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
   
            ")->queryAll();

            // $uacs_sort = ArrayHelper::index($query, null, 'ors_object_code');
            //  echo "<pre>";
            //     var_dump($query);
            //     echo "</pre>";
            //     die();

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
                return $element['mfo_code'];
            }, 'document_recieve_name']);

            $uacs_sort = ArrayHelper::index($query, 'ors_object_code', [function ($element) {
                return $element['mfo_code'];
            }, 'document_recieve_name']);


            $mfo = Yii::$app->db->createCommand("SELECT code,`name` FROM mfo_pap_code")->queryAll();
            $mfo_sort = ArrayHelper::index(
                $mfo,
                null,
                'code'
            );



            $allotment_total = array();
            foreach ($result as $mfo => $val1) {
                foreach ($val1 as $document => $val2) {
                    foreach ($val2 as $uacs => $val3) {
                        $allot = floatval($result[$mfo][$document][$uacs]['total_allotment']);
                        $allotment_total[$mfo][$document][$uacs] = $allot;

                        if (empty($uacs_sort[$mfo][$document][$uacs])) {

                            $chart_majors = Yii::$app->db->createCommand("SELECT 
                            major_accounts.object_code as major_object_code,
                            major_accounts.`name` as major_name,
                            sub_major_accounts.object_code as sub_major_object_code,
                            sub_major_accounts.`name` as sub_major_name
                            FROM chart_of_accounts
                            INNER  JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
                            INNER JOIN sub_major_accounts ON chart_of_accounts.sub_major_account=  sub_major_accounts.id
                            WHERE
                            chart_of_accounts.uacs = :uacs ")
                                ->bindValue('uacs', $uacs)
                                ->queryOne();
                            // var_dump($mfo_sort[$mfo][0]['name']);
                            // die();
                            $arr =  [
                                'mfo_code' => $mfo,

                                'document_recieve_name' => $document,
                                'uacs' => $uacs,
                                'allotment_account_title' => $result[$mfo][$document][$uacs]['allotment_account_title'],
                                'major_object_code' => $chart_majors['major_object_code'],
                                'major_name' => $chart_majors['major_name'],
                                'sub_major_object_code' => $chart_majors['sub_major_object_code'],
                                'sub_major_name' => $chart_majors['sub_major_name'],
                                'ors_object_code' => $uacs,
                                'general_ledger' => $result[$mfo][$document][$uacs]['allotment_account_title'],
                                'account_title' => $uacs . '-' . $result[$mfo][$document][$uacs]['allotment_account_title'],
                                'current_total' => 0,
                                'prev_total' => 0,
                                'ors_to_date' => 0,
                                'total_allotment' => $result[$mfo][$document][$uacs]['total_allotment']
                            ];
                            array_push($query, $arr);
                        }
                    }
                }
            }
            // echo "<pre>";
            // // var_dump(array_key_exists(5010403001,$allotment_total[100000100001000]['GARO']));
            // var_dump($allotment_total);

            // echo "</pre>";
            // die();
            // $uacs_in_query = in_array($uacs, array_column($query, 'ors_object_code'));

            $arr = $allotment_total;
            foreach ($query as $index => $val) {
                $mfo = $val['mfo_code'];
                $document_recieve = $val['document_recieve_name'];
                $ors_object_code = $val['ors_object_code'];
                $allotment_uacs =  $val['uacs'];
                $exist = array_key_exists($allotment_uacs, $allotment_total[$mfo][$document_recieve]);

                $query[$index]['mfo_name'] = $mfo_sort[$mfo][0]['name'];
                if ($allotment_uacs === $ors_object_code) {
                    $begin_balance = $allotment_total[$mfo][$document_recieve][$ors_object_code];
                    $query[$index]['beginning_balance'] = $begin_balance;
                    $balance  = $begin_balance - $val['ors_to_date'];
                    $query[$index]['balance'] = $balance;
                } else {
                    $query[$index]['beginning_balance'] = 0;
                    $bal  = $allotment_total[$mfo][$document_recieve][$allotment_uacs] - $val['ors_to_date'];
                    $query[$index]['balance'] = $bal;
                    // $arr[$mfo][$document_recieve][$allotment_uacs] = $bal;
                }
            }

            // die();


            $result2 = ArrayHelper::index($query, null, [function ($element) {
                return $element['major_name'];
            }, 'sub_major_name',]);
            $conso_saob = array();
            $sort_by_mfo_document = ArrayHelper::index($query, null, [function ($element) {
                return $element['mfo_name'];
            }, 'document_recieve_name']);
            foreach ($sort_by_mfo_document as $mfo => $mfo_val) {
                foreach ($mfo_val as $document => $document_val) {
                    $to_date = round(array_sum(array_column($document_val, 'ors_to_date')), 2);
                    if ($to_date > 0) {

                        $conso_saob[] =
                            [
                                'mfo_name' => $mfo,
                                'document' => $document,
                                'beginning_balance' => round(array_sum(array_column($document_val, 'beginning_balance')), 2),
                                'prev' => round(array_sum(array_column($document_val, 'prev_total')), 2),
                                'current' => round(array_sum(array_column($document_val, 'current_total')), 2),
                                'to_date' => round(array_sum(array_column($document_val, 'ors_to_date')), 2),
                            ];
                    }
                }
            }

            // ArrayHelper::multisort($query, ['ors_object_code',], [SORT_ASC]);
            //   echo "<pre>";
            //                 var_dump($allotment_total);
            //                 echo "</pre>";
            //                 die();
            return json_encode(['result' => $result2, 'major_allotments' => $allotment_total, 'conso_saob' => $conso_saob]);
        }
        return $this->render('saobs');
    }
    public function actionGitPull()
    {
        echo "<pre>";
        echo  shell_exec("git pull https://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@github.com/kiotipot1/dti-afms-2.git");
        echo "</pre>";

        echo "<pre>";
        echo  shell_exec("yii migrate --interactive=0");
        echo "</pre>";
    }
}

// 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
// https://github.com/kiotipot1/dti-afms-2.git.
// git pull https://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@github.com/kiotipot1/dti-afms-2.git
