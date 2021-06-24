<?php

namespace frontend\controllers;

use app\models\AdvancesLiquidation;
use app\models\AdvancesLiquidationSearch;
use app\models\Cdr;
use app\models\ConsoDetailedDv;
use app\models\ConsoDetailedDvSearch;
use app\models\DetailedDvAucs;
use app\models\DetailedDvAucsSearch;
use app\models\DvAucs;
use app\models\JevAccountingEntries;
use app\models\Liquidation;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use kartik\grid\GridView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
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
                    'export-jev',
                    'cibr',
                    'cdr',
                    // actionPendingOrs
                    // actionUnobligatedTransaction
                    // actionPendingDv
                    // actionUnpaidObligation
                    // actionSaob
                    // actionGetCash
                    // actionSample
                    // actionExportJev
                    // actionCibr
                    // actionCdr
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
                            'get-cash',
                            'export-jev',
                            'cibr',
                            'cdr',
                        ],
                        'allow' => true,
                        'roles' => ['@']
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

    public function actionExportJev()
    {

        // if ($_POST) {
        //     $reporting_period = date('Y',strtotime($_POST['reporting_period']));
        $query = JevAccountingEntries::find()
            ->joinWith('jevPreparation')
            ->where('jev_preparation.reporting_period >=:reporting_period', ['reporting_period' => '2021'])
            ->all();
        $q1 = (new \yii\db\Query())
            ->select([
                'SUM(jev_accounting_entries.debit) as total_debit',
                'SUM(jev_accounting_entries.credit) as total_credit',
                'jev_accounting_entries.object_code',
                'jev_accounting_entries.lvl',
            ])
            ->from('jev_accounting_entries')
            ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id = jev_preparation.id')
            ->where("jev_preparation.reporting_period <:reporting_period", ['reporting_period' => '2021'])
            ->groupBy("jev_accounting_entries.object_code")
            ->all();

        $sub1 = (new \yii\db\Query())
            ->select('*')
            ->from('sub_accounts1')
            ->all();

        $sub2 = (new \yii\db\Query())
            ->select('*')
            ->from('sub_accounts2')
            ->all();
        $chart = (new \yii\db\Query())
            ->select('*')
            ->from('chart_of_accounts')
            ->all();

        // $rrr = array_search("1030501000_00052", array_column($sub1, 'object_code'));
        // ob_clean();
        // echo "<pre>";
        // var_dump($q1);
        // echo "</pre>";
        // return ob_get_clean();







        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // header
        $sheet->setAutoFilter('A1:N1');
        $sheet->setCellValue('A1', "JEV Number");
        $sheet->setCellValue('B1', "DV Number");
        $sheet->setCellValue('C1', "Check/ADA Number");
        $sheet->setCellValue('D1', "Payee");
        $sheet->setCellValue('E1', "UACS");
        $sheet->setCellValue('F1', "General Ledger");
        $sheet->setCellValue('G1', 'Entry Object Code');
        $sheet->setCellValue('H1', 'Entry Account Title');
        $sheet->setCellValue('I1', 'Reporting Period');
        $sheet->setCellValue('J1', 'Date');
        $sheet->setCellValue('K1', 'Particular');
        $sheet->setCellValue('L1', 'Debit');
        $sheet->setCellValue('M1', 'Credit');
        $sheet->setCellValue('N1', 'Reference');

        // BEGINNING BALANCE
        $sheet->setCellValue('K2', 'Beginning Balance');
        // $sheet->setCellValue('L2', $q1['total_debit']);
        // $sheet->setCellValue('M2', $q1['total_credit']);
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
        foreach ($q1 as $x) {
            $general_ledger = '';
            $object_code = '';
            if (intval($x['lvl']) === 1) {
                $index = array_search($x['object_code'], array_column($chart, 'uacs'));
                // $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $x['object_code']])->one();
                $general_ledger = $chart[$index]['general_ledger'];
                $object_code =  $chart[$index]['uacs'];
            } else if (intval($x['lvl']) === 2) {
                $index = array_search($x['object_code'], array_column($sub1, 'object_code'));
                // $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $x['object_code']])->one();
                $general_ledger = $sub1[$index]['name'];
                $object_code =  $sub1[$index]['object_code'];
            } else if (intval($x['lvl']) === 3) {
                $index = array_search($x['object_code'], array_column($sub2, 'object_code'));
                // $q = SubAccounts2::find()->where("object_code =:object_code", ['object_code' => $x['object_code']])->one();
                $general_ledger =  $sub2[$index]['name'];
                $object_code =  $sub2[$index]['object_code'];
            }
            $sheet->setCellValueByColumnAndRow(
                7,
                $row,
                $object_code
            );
            //ENTRY ACCOUNT TITLE
            $sheet->setCellValueByColumnAndRow(
                8,
                $row,
                $general_ledger
            );
            $sheet->setCellValueByColumnAndRow(
                11,
                $row,
                'Beginning Balance'
            );
            //DEBIT
            $sheet->setCellValueByColumnAndRow(
                12,
                $row,
                !empty($x['total_debit']) ? $x['total_debit'] : ''
            );
            //CREDIT
            $sheet->setCellValueByColumnAndRow(
                13,
                $row,
                !empty($x['total_credit']) ? $x['total_credit'] : ''
            );
            $row++;
        }
        foreach ($query  as  $val) {

            // jev_number
            $sheet->setCellValueByColumnAndRow(1, $row,  !empty($val->jevPreparation->jev_number) ? $val->jevPreparation->jev_number : '');
            // dv number
            $sheet->setCellValueByColumnAndRow(2, $row,  !empty($val->jevPreparation->dv_number) ? $val->jevPreparation->dv_number : '');
            // check ada number
            $sheet->setCellValueByColumnAndRow(3, $row,  !empty($val->jevPreparation->check_ada_number) ? $val->jevPreparation->check_ada_number : '');
            //payee
            $sheet->setCellValueByColumnAndRow(
                4,
                $row,
                !empty($val->jevPreparation->payee_id) ? $val->jevPreparation->payee->account_name : ''
            );
            //UACS
            $sheet->setCellValueByColumnAndRow(
                5,
                $row,
                !empty($val->chart_of_account_id) ? $val->chartOfAccount->uacs : ''
            );
            //GENERAL LEDGER
            $sheet->setCellValueByColumnAndRow(
                6,
                $row,
                !empty($val->chart_of_account_id) ? $val->chartOfAccount->general_ledger : ''
            );
            $general_ledger = '';
            $object_code = '';
            if ($val->lvl === 1) {
                $general_ledger = $val->chartOfAccount->general_ledger;
                $object_code = $val->chartOfAccount->uacs;
            } else if ($val->lvl === 2) {
                // $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $val->object_code])->one();
                // $general_ledger = $q->name;
                // $object_code = $q->object_code;

                $eee = array_search($val->object_code, array_column($sub1, 'object_code'));
                $general_ledger = $sub1[$eee]['name'];
                $object_code = $sub1[$eee]['object_code'];
                // ob_clean();
                // echo "<pre>";
                // var_dump($sub1[$eee]['object_code']);
                // echo "</pre>";
                // return ob_get_clean();
            } else if ($val->lvl === 3) {
                // $q = SubAccounts2::find()->where("object_code =:object_code", ['object_code' => $val->object_code])->one();
                // $general_ledger = $q->name;
                // $object_code = $q->object_code;
                $y = array_search($val->object_code, array_column($sub2, 'object_code'));
                $general_ledger = $sub2[$y]['name'];
                $object_code = $sub2[$y]['object_code'];
            }
            //ENTRY OBJECT CODE

            $sheet->setCellValueByColumnAndRow(
                7,
                $row,
                $object_code
            );
            //ENTRY ACCOUNT TITLE
            $sheet->setCellValueByColumnAndRow(
                8,
                $row,
                $general_ledger
            );
            //REPORTING PERIOD
            $sheet->setCellValueByColumnAndRow(
                9,
                $row,
                !empty($val->jevPreparation->reporting_period) ? $val->jevPreparation->reporting_period : ''
            );
            //DATE
            $sheet->setCellValueByColumnAndRow(
                10,
                $row,
                !empty($val->jevPreparation->date) ? $val->jevPreparation->date : ''
            );
            //PARTICULAR
            $sheet->setCellValueByColumnAndRow(
                11,
                $row,
                !empty($val->jevPreparation->explaination) ? $val->jevPreparation->explaination : ''
            );
            //DEBIT
            $sheet->setCellValueByColumnAndRow(
                12,
                $row,
                !empty($val->debit) ? $val->debit : ''
            );
            //CREDIT
            $sheet->setCellValueByColumnAndRow(
                13,
                $row,
                !empty($val->credit) ? $val->credit : ''
            );
            //REFERENCE
            $sheet->setCellValueByColumnAndRow(
                14,
                $row,
                !empty($val->jevPreparation->ref_number) ? $val->jevPreparation->ref_number : ''
            );

            $row++;
        }

        $id = uniqid();
        $file_name = "ckdj_excel_$id.xlsx";
        // header('Content-Type: application/vnd.ms-excel');
        // header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
        // header('Content-Transfer-Encoding: binary');
        // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        // header('Pragma: public'); // HTTP/1.0
        // echo readfile($file);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $file = "transaction\ckdj_excel_$id.xlsx";
        $file2 = "transaction/ckdj_excel_$id.xlsx";

        $writer->save($file);
        // return ob_get_clean();
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file2) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file2));
        flush(); // Flush system output buffer
        readfile($file2);
        // flush();
        // ob_clean();
        // flush();

        // // echo "<script> window.location.href = '$file';</script>";
        // echo "<script>window.open('$file2','_self')</script>";

        //    echo readfile("../../frontend/web/transaction/" . $file_name);
        exit();
        // return json_encode(['res' => "transaction\ckdj_excel_$id.xlsx"]);
        // return json_encode($file);
        // exit;
        // }
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
        } else {

            return $this->render('cdr');
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
        // if ($_POST) {
        // $id = $_POST['id'];
        $id = 10;
        $cdr  = Cdr::findOne($id);
        // $query = (new \yii\db\Query())
        //     ->select('id')
        //     ->from('cdr')
        //     ->where('reporting_period =:reporting_period', ['reporting_period' => $cdr->reporting_period])
        //     ->andWhere('province LIKE :province', ['province' => $cdr->province])
        //     ->andWhere('book_name LIKE :book_name', ['book_name' => $cdr->book_name])
        //     ->andWhere('report_type LIKE :report_type', ['report_type' => $cdr->report_type])
        //     ->one();

        $q = Yii::$app->db->createCommand("SELECT  gl_object_code, gl_account_title,
                reporting_period, vat_nonvat, expanded_tax,
                ROUND(SUM(expanded_tax),2) as total_expanded,
                ROUND(SUM(vat_nonvat),2) as total_vat,
                ROUND(SUM(withdrawals),2) as total_withdrawals,
                CONCAT(chart_of_accounts.id,'-',chart_of_accounts.uacs,'-',1) as code,
                ROUND(SUM(withdrawals),2)+ROUND(SUM(vat_nonvat),2)+ROUND(SUM(expanded_tax),2) as debit
                FROM advances_liquidation,chart_of_accounts
                WHERE 
                advances_liquidation.gl_object_code = chart_of_accounts.uacs
                AND(reporting_period = :reporting_period) 
                AND (book_name = :book_name)
                AND (province LIKE :province) 
                AND (report_type LIKE :report_type)
                GROUP BY gl_object_code
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
        // var_dump($advances);
        // echo "</pre>";
        // return ob_get_clean();
        if (!empty($cdr)) {
            return json_encode(['result' => $q, 'vat' => $vat, 'expanded' => $expanded]);
        }
        // }
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

            // return json_encode($allotment_class);
            $dataProvider = Yii::$app->db->createCommand("CALL conso_dv(:reporting_period,:year,:book_id,:allotment_class)")
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':year', $year)
                ->bindValue(':allotment_class', $allotment_class)
                ->bindValue(':book_id', $book_id)
                ->queryAll();
            return json_encode($dataProvider);
        } else {

            return $this->render('conso_dv');
        }
    }
}



// saobl last query
// $query = Yii::$app->db->createCommand("SELECT ors.*,
// record_allotment_entries.amount,

// chart_of_accounts.uacs,
// chart_of_accounts.major_account_id,
// chart_of_accounts.general_ledger from record_allotment_entries,chart_of_accounts,
// (

// SELECT q1.record_allotment_entries_id as allotment_id,
// q1.total_obligation,q1.reporting_period,
// q2.total_obligation1,q2.reporting_period1
// FROM (
//     SELECT SUM(raoud_entries.amount) as total_obligation,
//     raouds.record_allotment_entries_id,
//     process_ors.reporting_period
//     from raouds,raoud_entries,process_ors
//     where raouds.id = raoud_entries.raoud_id
    
//     AND raouds.process_ors_id = process_ors.id
//     AND process_ors.reporting_period = :last_month
//     GROUP BY raouds.record_allotment_entries_id
// ) as q1
// ,(
//     SELECT SUM(raoud_entries.amount) total_obligation1,raouds.record_allotment_entries_id,
//     process_ors.reporting_period as reporting_period1
//     from raouds,raoud_entries,process_ors
//     where raouds.id = raoud_entries.raoud_id
    
//     AND raouds.process_ors_id = process_ors.id
//     AND process_ors.reporting_period = :this_month
//     GROUP BY raouds.record_allotment_entries_id
    
// ) as q2

// WHERE q1.record_allotment_entries_id = q2.record_allotment_entries_id

// ) as ors
// where record_allotment_entries.id = ors.allotment_id
// AND record_allotment_entries.chart_of_account_id =chart_of_accounts.id

// ORDER BY record_allotment_entries.id

// ")
//     ->bindValue(':this_month', $reporting_period_this_month)
//     ->bindValue(':last_month', $reporting_period_last_month)

//     ->queryAll();
// $result = ArrayHelper::index($query, null, [function ($element) {
//     return $element['uacs'];
// },]);
