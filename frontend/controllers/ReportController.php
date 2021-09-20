<?php

namespace frontend\controllers;

use app\models\AdvancesLiquidation;
use app\models\AdvancesLiquidationSearch;
use app\models\AdvancesViewSearch;
use app\models\Books;
use app\models\Cdr;
use app\models\ChartOfAccounts;
use app\models\ConsoDetailedDv;
use app\models\ConsoDetailedDvSearch;
use app\models\DetailedDvAucs;
use app\models\DetailedDvAucsSearch;
use app\models\DvAucs;
use app\models\JevAccountingEntries;
use app\models\Liquidation;
use app\models\PoTransmittalsPendingSearch;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use app\models\Transaction;
use app\models\TransactionArchiveSearch;
use kartik\grid\GridView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
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
                    'summary-fund-source-fur'

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
                            'summary-fund-source-fur'


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

    // public function actionExportJev()
    // {

    //     // if ($_POST) {
    //     //     $reporting_period = date('Y',strtotime($_POST['reporting_period']));
    //     $query = JevAccountingEntries::find()
    //         ->joinWith('jevPreparation')
    //         ->where('jev_preparation.reporting_period >=:reporting_period', ['reporting_period' => '2021'])
    //         ->all();
    //     $q1 = (new \yii\db\Query())
    //         ->select([
    //             'SUM(jev_accounting_entries.debit) as total_debit',
    //             'SUM(jev_accounting_entries.credit) as total_credit',
    //             'jev_accounting_entries.object_code',
    //             'jev_accounting_entries.lvl',
    //         ])
    //         ->from('jev_accounting_entries')
    //         ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id = jev_preparation.id')
    //         ->where("jev_preparation.reporting_period <:reporting_period", ['reporting_period' => '2021'])
    //         ->groupBy("jev_accounting_entries.object_code")
    //         ->all();

    //     $sub1 = (new \yii\db\Query())
    //         ->select('*')
    //         ->from('sub_accounts1')
    //         ->all();

    //     $sub2 = (new \yii\db\Query())
    //         ->select('*')
    //         ->from('sub_accounts2')
    //         ->all();
    //     $chart = (new \yii\db\Query())
    //         ->select('*')
    //         ->from('chart_of_accounts')
    //         ->all();

    //     // $rrr = array_search("1030501000_00052", array_column($sub1, 'object_code'));
    //     // ob_clean();
    //     // echo "<pre>";
    //     // var_dump($q1);
    //     // echo "</pre>";
    //     // return ob_get_clean();







    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();
    //     // header
    //     $sheet->setAutoFilter('A1:N1');
    //     $sheet->setCellValue('A1', "JEV Number");
    //     $sheet->setCellValue('B1', "DV Number");
    //     $sheet->setCellValue('C1', "Check/ADA Number");
    //     $sheet->setCellValue('D1', "Payee");
    //     $sheet->setCellValue('E1', "UACS");
    //     $sheet->setCellValue('F1', "General Ledger");
    //     $sheet->setCellValue('G1', 'Entry Object Code');
    //     $sheet->setCellValue('H1', 'Entry Account Title');
    //     $sheet->setCellValue('I1', 'Reporting Period');
    //     $sheet->setCellValue('J1', 'Date');
    //     $sheet->setCellValue('K1', 'Particular');
    //     $sheet->setCellValue('L1', 'Debit');
    //     $sheet->setCellValue('M1', 'Credit');
    //     $sheet->setCellValue('N1', 'Reference');

    //     // BEGINNING BALANCE
    //     $sheet->setCellValue('K2', 'Beginning Balance');
    //     // $sheet->setCellValue('L2', $q1['total_debit']);
    //     // $sheet->setCellValue('M2', $q1['total_credit']);
    //     $x = 7;
    //     $styleArray = array(
    //         'borders' => array(
    //             'allBorders' => array(
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
    //                 'color' => array('argb' => 'FFFF0000'),
    //             ),
    //         ),
    //     );


    //     $row = 2;
    //     foreach ($q1 as $x) {
    //         $general_ledger = '';
    //         $object_code = '';
    //         if (intval($x['lvl']) === 1) {
    //             $index = array_search($x['object_code'], array_column($chart, 'uacs'));
    //             // $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $x['object_code']])->one();
    //             $general_ledger = $chart[$index]['general_ledger'];
    //             $object_code =  $chart[$index]['uacs'];
    //         } else if (intval($x['lvl']) === 2) {
    //             $index = array_search($x['object_code'], array_column($sub1, 'object_code'));
    //             // $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $x['object_code']])->one();
    //             $general_ledger = $sub1[$index]['name'];
    //             $object_code =  $sub1[$index]['object_code'];
    //         } else if (intval($x['lvl']) === 3) {
    //             $index = array_search($x['object_code'], array_column($sub2, 'object_code'));
    //             // $q = SubAccounts2::find()->where("object_code =:object_code", ['object_code' => $x['object_code']])->one();
    //             $general_ledger =  $sub2[$index]['name'];
    //             $object_code =  $sub2[$index]['object_code'];
    //         }
    //         $sheet->setCellValueByColumnAndRow(
    //             7,
    //             $row,
    //             $object_code
    //         );
    //         //ENTRY ACCOUNT TITLE
    //         $sheet->setCellValueByColumnAndRow(
    //             8,
    //             $row,
    //             $general_ledger
    //         );
    //         $sheet->setCellValueByColumnAndRow(
    //             11,
    //             $row,
    //             'Beginning Balance'
    //         );
    //         //DEBIT
    //         $sheet->setCellValueByColumnAndRow(
    //             12,
    //             $row,
    //             !empty($x['total_debit']) ? $x['total_debit'] : ''
    //         );
    //         //CREDIT
    //         $sheet->setCellValueByColumnAndRow(
    //             13,
    //             $row,
    //             !empty($x['total_credit']) ? $x['total_credit'] : ''
    //         );
    //         $row++;
    //     }
    //     foreach ($query  as  $val) {

    //         // jev_number
    //         $sheet->setCellValueByColumnAndRow(1, $row,  !empty($val->jevPreparation->jev_number) ? $val->jevPreparation->jev_number : '');
    //         // dv number
    //         $sheet->setCellValueByColumnAndRow(2, $row,  !empty($val->jevPreparation->dv_number) ? $val->jevPreparation->dv_number : '');
    //         // check ada number
    //         $sheet->setCellValueByColumnAndRow(3, $row,  !empty($val->jevPreparation->check_ada_number) ? $val->jevPreparation->check_ada_number : '');
    //         //payee
    //         $sheet->setCellValueByColumnAndRow(
    //             4,
    //             $row,
    //             !empty($val->jevPreparation->payee_id) ? $val->jevPreparation->payee->account_name : ''
    //         );
    //         //UACS
    //         $sheet->setCellValueByColumnAndRow(
    //             5,
    //             $row,
    //             !empty($val->chart_of_account_id) ? $val->chartOfAccount->uacs : ''
    //         );
    //         //GENERAL LEDGER
    //         $sheet->setCellValueByColumnAndRow(
    //             6,
    //             $row,
    //             !empty($val->chart_of_account_id) ? $val->chartOfAccount->general_ledger : ''
    //         );
    //         $general_ledger = '';
    //         $object_code = '';
    //         if ($val->lvl === 1) {
    //             $general_ledger = $val->chartOfAccount->general_ledger;
    //             $object_code = $val->chartOfAccount->uacs;
    //         } else if ($val->lvl === 2) {
    //             // $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $val->object_code])->one();
    //             // $general_ledger = $q->name;
    //             // $object_code = $q->object_code;

    //             $eee = array_search($val->object_code, array_column($sub1, 'object_code'));
    //             $general_ledger = $sub1[$eee]['name'];
    //             $object_code = $sub1[$eee]['object_code'];
    //             // ob_clean();
    //             // echo "<pre>";
    //             // var_dump($sub1[$eee]['object_code']);
    //             // echo "</pre>";
    //             // return ob_get_clean();
    //         } else if ($val->lvl === 3) {
    //             // $q = SubAccounts2::find()->where("object_code =:object_code", ['object_code' => $val->object_code])->one();
    //             // $general_ledger = $q->name;
    //             // $object_code = $q->object_code;
    //             $y = array_search($val->object_code, array_column($sub2, 'object_code'));
    //             $general_ledger = $sub2[$y]['name'];
    //             $object_code = $sub2[$y]['object_code'];
    //         }
    //         //ENTRY OBJECT CODE

    //         $sheet->setCellValueByColumnAndRow(
    //             7,
    //             $row,
    //             $object_code
    //         );
    //         //ENTRY ACCOUNT TITLE
    //         $sheet->setCellValueByColumnAndRow(
    //             8,
    //             $row,
    //             $general_ledger
    //         );
    //         //REPORTING PERIOD
    //         $sheet->setCellValueByColumnAndRow(
    //             9,
    //             $row,
    //             !empty($val->jevPreparation->reporting_period) ? $val->jevPreparation->reporting_period : ''
    //         );
    //         //DATE
    //         $sheet->setCellValueByColumnAndRow(
    //             10,
    //             $row,
    //             !empty($val->jevPreparation->date) ? $val->jevPreparation->date : ''
    //         );
    //         //PARTICULAR
    //         $sheet->setCellValueByColumnAndRow(
    //             11,
    //             $row,
    //             !empty($val->jevPreparation->explaination) ? $val->jevPreparation->explaination : ''
    //         );
    //         //DEBIT
    //         $sheet->setCellValueByColumnAndRow(
    //             12,
    //             $row,
    //             !empty($val->debit) ? $val->debit : ''
    //         );
    //         //CREDIT
    //         $sheet->setCellValueByColumnAndRow(
    //             13,
    //             $row,
    //             !empty($val->credit) ? $val->credit : ''
    //         );
    //         //REFERENCE
    //         $sheet->setCellValueByColumnAndRow(
    //             14,
    //             $row,
    //             !empty($val->jevPreparation->ref_number) ? $val->jevPreparation->ref_number : ''
    //         );

    //         $row++;
    //     }

    //     $id = uniqid();
    //     $file_name = "ckdj_excel_$id.xlsx";
    //     // header('Content-Type: application/vnd.ms-excel');
    //     // header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
    //     // header('Content-Transfer-Encoding: binary');
    //     // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //     // header('Pragma: public'); // HTTP/1.0
    //     // echo readfile($file);
    //     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    //     $file = "transaction\ckdj_excel_$id.xlsx";
    //     $file2 = "transaction/ckdj_excel_$id.xlsx";

    //     $writer->save($file);
    //     // return ob_get_clean();
    //     header('Content-Description: File Transfer');
    //     header('Content-Type: application/octet-stream');
    //     header('Content-Disposition: attachment; filename="' . basename($file2) . '"');
    //     header('Expires: 0');
    //     header('Cache-Control: must-revalidate');
    //     header('Pragma: public');
    //     header('Content-Length: ' . filesize($file2));
    //     flush(); // Flush system output buffer
    //     readfile($file2);
    //     // flush();
    //     // ob_clean();
    //     // flush();

    //     // // echo "<script> window.location.href = '$file';</script>";
    //     // echo "<script>window.open('$file2','_self')</script>";

    //     //    echo readfile("../../frontend/web/transaction/" . $file_name);
    //     exit();
    //     // return json_encode(['res' => "transaction\ckdj_excel_$id.xlsx"]);
    //     // return json_encode($file);
    //     // exit;
    //     // }
    // }

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
            AND cash_disbursement.book_id = :book_name
            AND liquidation.province = :province
            AND advances_entries.advances_type = :report_type
            GROUP BY chart_of_accounts.uacs
            ")
                ->bindValue(':reporting_period', $cdr->reporting_period)
                ->bindValue(':book_name', $cdr->book_name)
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
    // public function actionC()
    // {
    //     $params = [];
    //     $query1 = Yii::$app->db->createCommand("SELECT object_code FROM jev_accounting_entries WHERE jev_preparation_id = 4480226")->queryAll();
    //     $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'object_code', $query1], $params);

    //     $query2 = (new \yii\db\Query())
    //         ->select('*')
    //         ->from('accounting_codes')
    //         ->where('is_active =1 AND coa_is_active = 1 AND sub_account_is_active = 1')
    //         ->orWhere("$sql", $params)
    //         ->orderBy('sub_account_is_active')
    //         ->all();
    //     ob_clean();
    //     echo '<pre>';
    //     var_dump($query2);
    //     echo '<\pre>';
    //     return ob_get_clean();
    // }
    // public function actionQqq()
    // {
    //     $q1 = Yii::$app->cloud_db->createCommand("SELECT * FROM chart_of_accounts")->queryAll();
    //     $q2 = Yii::$app->db->createCommand("SELECT * FROM chart_of_accounts")->queryAll();

    //     $pageWithNoChildren = array_map(
    //         'unserialize',
    //         array_diff(array_map('serialize', $q2), array_map('serialize', $q1))
    //     );
    //     echo "<pre>";
    //     var_dump($pageWithNoChildren);
    //     echo "</pre>";
    //     return ob_get_clean();
    //     // echo shell_exec('git pull git@github.com:kiotipot1/dti-afms-2.git');
    //     // die
    // }
    // public function actionImportQ()
    // {
    //     if (!empty($_POST)) {
    //         // $chart_id = $_POST['chart_id'];
    //         $name = $_FILES["file"]["name"];
    //         // var_dump($_FILES['file']);
    //         // die();
    //         $id = uniqid();
    //         $file = "transaction/{$id}_{$name}";
    //         if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
    //         } else {
    //             return "ERROR 2: MOVING FILES FAILED.";
    //             die();
    //         }
    //         $arr = array(
    //             2351379,
    //             2351380,
    //             2351381,
    //             2351382,
    //             2351383,
    //             2351384,
    //             2351385,
    //             2351386,
    //             2351387,
    //             2351388,
    //             2351389,
    //             2351390,
    //             2351391,
    //             2351392,
    //             2351393,
    //             2351394,
    //             2351395,
    //             2351396,
    //             2351397,
    //             2351398,
    //             2351399,
    //             2351400,
    //             2351401,
    //             2351402,
    //             2351403,
    //             2351404,
    //             2351405,
    //             2351406,
    //             2351407,
    //             2351408,
    //             2351409,
    //             2351410,
    //             2351411,
    //             2351412,
    //             2351413,
    //             2351414,
    //             2351415,
    //             2351416,
    //             2351417,
    //             2351418,
    //             2351419,
    //             2351420,
    //             2351421,
    //             2351422,
    //             2351423,
    //             2351424,
    //             2351425,
    //             2351426,
    //             2351427,
    //             2351428,
    //             2351429,
    //             2351430,
    //             2351431,
    //             2351432,
    //             2351433,
    //             2351434,
    //             2351435,
    //             2351436,
    //             2351437,
    //             2351438,
    //             2351439,
    //             2351440,
    //             2351441,
    //             2351443,
    //             2351444,
    //             2351445,
    //             2351446,
    //             2351447,
    //             2351448,
    //             2351449,
    //             2351450,
    //             2351451,
    //             2351452,
    //             2351453,
    //             2351454,
    //             2351455,
    //             2351456,
    //             2351457,
    //             2351458,
    //             2351459,
    //             2351460,
    //             2351461,
    //             2351462,
    //             2351463,
    //             2351464,
    //             2351465,
    //             2351466,
    //             2351467,
    //             2351468,
    //             2351469,
    //             2351470,
    //             2351471,
    //             2351472,
    //             2351473,
    //             2351474,
    //             2351475,
    //             2351476,
    //             2351477,
    //             2351478,
    //             2351479,
    //             2351480,
    //             2351481,
    //             2351482,
    //             2351483,
    //             2351484,
    //             2351485,
    //             2351486,
    //             2351487,
    //             2351488,
    //             2351489,
    //             2351490,
    //             2351491,
    //             2351492,
    //             2351493,
    //             2351494,
    //             2351495,
    //             2351496,
    //             2351497,
    //             2351498,
    //             2351499,
    //             2351500,
    //             2408401,
    //             2408402,
    //             2408403,
    //             2408404,
    //             2408405,
    //             2408406,
    //             2408407,
    //             2408408,
    //             2408409,
    //             2408410,
    //             2408411,
    //             2408412,
    //             2408413,
    //             2408414,
    //             2408415,
    //             2408416,
    //             2408417,
    //             2408418,
    //             2408420,
    //             2408421,
    //             2408422,
    //             2408423,
    //             2408424,
    //             2408425,
    //             2408426,
    //             2408427,
    //             2408428,
    //             2408429,
    //             2408430,
    //             2408431,
    //             2408432,
    //             2408433,
    //             2408434,
    //             2408435,
    //             2408436,
    //             2408437,
    //             2408438,
    //             2408439,
    //             2408440,
    //             2408441,
    //             2408442,
    //             2408443,
    //             2408444,
    //             2408445,
    //             2408446,
    //             2408447,
    //             2408448,
    //             2408449,
    //             2408450,
    //             2408451,
    //             2408452,
    //             2408453,
    //             2408454,
    //             2408455,
    //             2408456,
    //             2408457,
    //             2408458,
    //             2408459,
    //             2408460,
    //             2408461,
    //             2408462,
    //             2408463,
    //             2408464,
    //             2408465,
    //             2408466,
    //             2408467,
    //             2408468,
    //             2408469,
    //             2408470,
    //             2408471,
    //             2408472,
    //             2408473,
    //             2408474,
    //             2408475,
    //             2408476,
    //             2408477,
    //             2408478,
    //             2408479,
    //             2408480,
    //             2408481,
    //             2408482,
    //             2408483,
    //             2408484,
    //             2408485,
    //             2408486,
    //             2408487,
    //             2408488,
    //             2408489,
    //             2408490,
    //             2408491,
    //             2408492,
    //             2408493,
    //             2408494,
    //             2408495,
    //             2408496,
    //             2408497,
    //             2351369,
    //             2408498,
    //             2408499,
    //             2408500,
    //             2408501,
    //             2408502,
    //             2408503,
    //             2408504,
    //             2408505,
    //             2408506,
    //             2408507,
    //             2408508,
    //             2408509,
    //             2408510,
    //             2408511,
    //             2408512,
    //             2408513,
    //             2408514,
    //             2408515,
    //             2408516,
    //             2408517,
    //             2408518,
    //             2408519,
    //             2408520,
    //             2408521,
    //             2408522,
    //             2408545,
    //             2408547,
    //             2408549,
    //             2408550,
    //             2408569,
    //             2408572,
    //             2408573,
    //             2408574,
    //             2408575,
    //             2408576,
    //             2408577,
    //             2408578,
    //             2408579,
    //             2408580,
    //             2408581,
    //             2408582,
    //             2408583,
    //             2408584,
    //             2408585,
    //             2408586,
    //             2408587,
    //             2408588,
    //             2408589,
    //             2408590,
    //             2408591,
    //             2408592,
    //             2408593,
    //             2408594,
    //             2408595,
    //             2408596,
    //             2408597,
    //             2408598,
    //             2408599,
    //             2408616,
    //             2408617,
    //             2408627,
    //             2408628,
    //             2408629,
    //             2408630,
    //             2408631,
    //             2408632,
    //             2408633,
    //             2408634,
    //             2408641,
    //             2408644,
    //             2408645,
    //             2408648,
    //             2408665,
    //             2408676,
    //             2408677,
    //             2408678,
    //             2408693,
    //             2408694,
    //             2408695,
    //             2408703,
    //             2408724,
    //             2408737,
    //             2408738,
    //             2408739,
    //             2408746,
    //             2408747,
    //             2408751,
    //             2408757,
    //             2408766,
    //             2408767,
    //             2408771,
    //             2408772,
    //             2408780,
    //             2408785,
    //             2408804,
    //             2408831,
    //             2408833,
    //             2408838,
    //             2408840,
    //             2408854,
    //             2408864,
    //             2408895,
    //             2408897,
    //             2408899,
    //             2408905,
    //             2408909,
    //             2408931,
    //             2408932,
    //             2408947,
    //             2408953,
    //             2408955,
    //             2408984,
    //             2408989,
    //             2408997,
    //             2409008,
    //             2409012,
    //             2409013,
    //             2409018,
    //             2409019,
    //             2409080,
    //             2409081,
    //             2409083,
    //             2409084,
    //             2409092,
    //             2409096,
    //             2409098,
    //             2409100,
    //             2409102,
    //             2409127,
    //             2409135,
    //             2409167,
    //             2409194,
    //             2409196,
    //             2409198,
    //             2409214,
    //             2409220,
    //             2409222,
    //             2409226,
    //             2409227,
    //             2409228,
    //             2409238,
    //             2409245,
    //             2409254,
    //         );
    //         $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
    //         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    //         $excel = $reader->load($file);
    //         $excel->setActiveSheetIndexByName('Liquidation');
    //         $worksheet = $excel->getActiveSheet();
    //         // print_r($excel->getSheetNames());

    //         $data = [];
    //         // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

    //         $latest_tracking_no = (new \yii\db\Query())
    //             ->select('tracking_number')
    //             ->from('transaction')
    //             ->orderBy('id DESC')->one();
    //         if ($latest_tracking_no) {
    //             $x = explode('-', $latest_tracking_no['tracking_number']);
    //             $last_number = $x[2] + 1;
    //         } else {
    //             $last_number = 1;
    //         }
    //         // 
    //         $qwe = 1;
    //         $advances_id = [];

    //         $transaction = Yii::$app->db->beginTransaction();
    //         foreach ($worksheet->getRowIterator(2) as $key => $row) {
    //             $cellIterator = $row->getCellIterator();
    //             $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
    //             $cells = [];
    //             $y = 0;
    //             foreach ($cellIterator as $x => $cell) {
    //                 $q = '';
    //                 if ($y === 1) {
    //                     $cells[] = $cell->getFormattedValue();
    //                 } else {
    //                     $cells[] =   $cell->getValue();
    //                 }
    //                 $y++;
    //             }
    //             if (!empty($cells)) {

    //                 $province = $cells[0];
    //                 $check_date = date("Y-m-d", strtotime($cells[1]));
    //                 $check_number = trim($cells[2]);

    //                 $is_cancel =  $cells[3];
    //                 $dv_number = $cells[4];
    //                 $reporting_period = date("Y-m", strtotime($cells[5]));
    //                 $fund_source = trim($cells[6]);
    //                 $payee = trim($cells[7]);
    //                 $particular = trim($cells[8]);
    //                 $object_code = trim($cells[9]);
    //                 // $res_center = trim($cells[8]);
    //                 $withdrawal = trim($cells[12]);
    //                 $vat = trim($cells[13]);
    //                 $expanded = trim($cells[14]);
    //                 $advances_entries_id = null;
    //                 $chart_id = (new \yii\db\Query())
    //                     ->select("id")
    //                     ->from('chart_of_accounts')
    //                     ->where("chart_of_accounts.uacs =:uacs", ['uacs' => $object_code])
    //                     ->one();
    //                 $c_id = null;
    //                 if (!empty($chart_id)) {
    //                     $c_id = $chart_id['id'];
    //                 }
    //                 // $payee_id = (new \yii\db\Query())
    //                 //     ->select('id')
    //                 //     ->from('payee')
    //                 //     ->where("payee.account_name LIKE :account_name", ['account_name' => $payee])
    //                 //     ->one();
    //                 if (strtolower($is_cancel) === 'good') {
    //                     $advances_entries_id = (new \yii\db\Query())
    //                         ->select("id")
    //                         ->from("advances_entries")
    //                         ->where("advances_entries.fund_source LIKE :fund_source", ['fund_source' => $fund_source])
    //                         ->one();
    //                     if (empty($advances_entries_id)) {
    //                         ob_clean();
    //                         echo "<pre>";
    //                         var_dump($key . " yawa" . $fund_source);
    //                         echo "</pre>";
    //                         return ob_get_clean();
    //                     }
    //                 } else {
    //                     $advances_entries_id['id'] = null;
    //                 }


    //                 $liq_id = (new \yii\db\Query())
    //                     ->select('id')
    //                     ->from('liquidation')
    //                     ->where('liquidation.check_number =:check_number', ['check_number' => $check_number])
    //                     ->one();


    //                 $liquidation_id = null;
    //                 if (empty($liq_id)) {
    //                     $liquidation = new Liquidation();
    //                     $liquidation->province = $province;
    //                     $liquidation->check_date = $check_date;
    //                     $liquidation->check_number = $check_number;
    //                     $liquidation->particular = $particular;
    //                     $liquidation->is_cancelled = strtolower($is_cancel) === 'good' ? 0 : 1;
    //                     $liquidation->payee = $payee;
    //                     $liquidation->dv_number = $dv_number;
    //                     $liquidation->reporting_period = $reporting_period;
    //                     // $liquidation->advances_entries_id = $advances_entries_id['id'];
    //                     // $liquidation->chart_of_account_id = $advances_entries_id['id'];
    //                     // $liquidation->responsibility_center_id = $res_center;
    //                     if ($liquidation->save(false)) {
    //                         $liquidation_id = $liquidation->id;
    //                     }
    //                 } else {
    //                     $liquidation_id = $liq_id['id'];
    //                 }
    //                 $data[] = [
    //                     'liquidation_id' => $liquidation_id,
    //                     'chart_of_account_id' => $c_id,
    //                     'withdrawals' => $withdrawal,
    //                     'vat_nonvat' => $vat,
    //                     'expanded_tax' => $expanded,
    //                     'reporting_period' => $reporting_period,
    //                     'advances_entries_id' => $advances_entries_id['id']

    //                 ];
    //             }
    //         }

    //         $column = [
    //             'liquidation_id',
    //             'chart_of_account_id',
    //             'withdrawals',
    //             'vat_nonvat',
    //             'expanded_tax',
    //             'reporting_period',
    //             'advances_entries_id'
    //         ];
    //         $ja = Yii::$app->db->createCommand()->batchInsert('liquidation_entries', $column, $data)->execute();

    //         // return $this->redirect(['index']);
    //         // return json_encode(['isSuccess' => true]);
    //         $transaction->commit();
    //         ob_clean();
    //         echo "<pre>";
    //         var_dump('success');
    //         echo "</pre>";
    //         return ob_get_clean();
    //     }
    // }
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
            $current_advances = new Query();
            $current_advances->select([
                "
                advances.province,
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
            $q1 = $current_advances->createCommand()->getRawSql();
            $q2 = $prev_advances->createCommand()->getRawSql();
            $q3 = $current_liquidation->createCommand()->getRawSql();
            $final_query  = Yii::$app->db->createCommand(
                "SELECT w.*,IFNULL(e.total_withdrawals,0) as total_withdrawals,
                (IFNULL(w.current_advances_amount,0) + IFNULL(w.prev_amount,0)) - IFNULL(e.total_withdrawals,0) as ending_balance
                FROM (
                SELECT r1.*,IFNULL(r2.prev_amount,0) as prev_amount
                
            FROM ($q1) as r1
            LEFT JOIN ($q2) as r2
            ON (r1.province = r2.province AND r1.division = r2.division AND r1.`fund_source_type` = r2.`fund_source_type`)
            ) as w
            LEFT JOIN ($q3) as e
            ON (w.province = e.province AND w.division = e.division AND w.`fund_source_type` = e.`fund_source_type`)
            "
            )
                ->query();


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
    // public function actionQ()
    // {
    //     return substr(md5(uniqid('IDD', true)), 4, 8);
    // }
}
