<?php

namespace frontend\controllers;

use app\models\RecordAllotmentDetailed;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class BudgetReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'export-rao',
                    'sof-per-mfo',
                    'sof-per-office',
                    'sof-per-mfo-office',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'sof-per-mfo',
                        ],
                        'allow' => true,
                        'roles' => ['sof_per_mfo']
                    ],
                    [
                        'actions' => [
                            'export-rao'
                        ],
                        'allow' => true,
                        'roles' => ['rao']
                    ],
                    [
                        'actions' => [
                            'sof-per-office',
                        ],
                        'allow' => true,
                        'roles' => ['sof_per_office']
                    ],
                    [
                        'actions' => [
                            'sof-per-mfo-office',
                        ],
                        'allow' => true,
                        'roles' => ['sof_per_mfo_office']
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

    public function actionSofPerMfo()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerMfo($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['book_name'];
            }, 'allotment_class', 'mfo_name']);
            return json_encode($result);
        }
        return $this->render('budget_status_of_funds_per_mfo');
    }
    // STATUS OF FUNDS PER OFFICE/DIVISIONS
    public function actionSofPerOffice()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerOffice($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['book_name'];
            }, 'allotment_class', 'office_name', 'division']);
            return json_encode($result);
        }
        return $this->render('sof_per_office');
    }
    // STATUS OF FUNDS PER MFO - OFFICE/Division
    public function actionSofPerMfoOffice()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerMfoOffice($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['book_name'];
            }, 'allotment_class', 'mfo_name', 'office_name', 'division']);
            return json_encode($result);
        }
        return $this->render('sof_per_mfo_office');
    }
    // EXPORT RAO
    public function actionExportRao()
    {
        if (Yii::$app->request->post()) {

            $year = Yii::$app->request->post('year');

            return json_encode(YIi::$app->db->createCommand("
            WITH 
                allotmentAdjustments as (
                    SELECT
                    record_allotment_adjustments.fk_record_allotment_entry_id as allotment_entry_id,
                    record_allotment_adjustments.amount as allotAmt,
                    NULL as prId,
                    NULL as txnId,
                    NULL as orsId,
                    0 as prAmt,
                    0 as txnAmt,
                    0 as orsAmt,
                    '' as ors_reporting_period
                    FROM record_allotment_adjustments
                    WHERE record_allotment_adjustments.is_deleted = 0
                    ORDER BY fk_record_allotment_entry_id
            ),
            alltEntys as (
            SELECT 
            record_allotment_entries.id as allotment_entry_id,
            record_allotment_entries.amount as allotAmt,
            NULL as prId,
            NULL as txnId,
            NULL as orsId,
            0 as prAmt,
            0 as txnAmt,
            0 as orsAmt,
                    '' as ors_reporting_period
            FROM record_allotment_entries
            ),
            prAllots as (
            SELECT 
            pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            pr_purchase_request.id as prId,
            NULL as txnId,
            NULL as orsId,
            pr_purchase_request_allotments.amount as prAmt,
            0 as txnAmt,
            0 as orsAmt,
                '' as ors_reporting_period

            FROM pr_purchase_request_allotments 
            JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id

            WHERE
            pr_purchase_request_allotments.is_deleted = 0
                AND pr_purchase_request.is_cancelled = 0
            ),
            txnPrAllots as (
            SELECT 
            pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            pr_purchase_request.id as prId,
            `transaction`.id as txnId,
            ors.id as orsId,
            transaction_pr_items.amount *-1 as prAmt,
            0 as txnAmt,
            0 as orsAmt,
            '' as ors_reporting_period
            FROM transaction_pr_items
            LEFT JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
            JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id
            JOIN `transaction` ON  transaction_pr_items.fk_transaction_id = `transaction`.id
            LEFT JOIN (
            SELECT process_ors.transaction_id,
            process_ors.id
            FROM process_ors WHERE process_ors.is_cancelled = 0
            ) as ors ON `transaction`.id = ors.transaction_id
            WHERE
            transaction_pr_items.is_deleted = 0
            ORDER BY pr_purchase_request.pr_number),
            txnItms as (
            SELECT 
            transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            NULL as prId,
            `transaction`.id as txnId,
            ors.id as orsId,
            0 as prAmt,
            transaction_items.amount as txnAmt,
            0 as orsAmt,
                    '' as ors_reporting_period
            FROM transaction_items
            JOIN `transaction` ON transaction_items.fk_transaction_id = `transaction`.id
            LEFT JOIN (SELECT process_ors.id ,process_ors.transaction_id FROM process_ors WHERE  process_ors.is_cancelled = 0) as ors ON `transaction`.id = ors.transaction_id
            WHERE
            transaction_items.is_deleted = 0
            ),
            orsTxnItms as (
            SELECT 
            transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            NULL as prId,
            `transaction_items`.fk_transaction_id as txnId,
            process_ors_txn_items.fk_process_ors_id,
            0 as prAmt,
            process_ors_txn_items.amount,
            0 as orsAmt,
                '' as ors_reporting_period
            FROM process_ors_txn_items
            JOIN transaction_items ON process_ors_txn_items.fk_transaction_item_id = transaction_items.id
            JOIN process_ors ON process_ors_txn_items.fk_process_ors_id = process_ors.id
            WHERE
            process_ors_txn_items.is_deleted = 0
            AND process_ors.is_cancelled = 0

            ),
            orsItms as (
          SELECT 
            process_ors_entries.record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            NULL as prId,
            process_ors.transaction_id as txnId,
            process_ors.id as orsId,
            0 as prAmt,
            0 as txnAmt,
            process_ors_entries.amount as orsAmt,
						(CASE
							WHEN  process_ors_entries.reporting_period IS NULL  THEN process_ors.reporting_period
              ELSE process_ors_entries.reporting_period
						END) as ors_reporting_period,
						chart_of_accounts.uacs as ors_object_code,
						chart_of_accounts.general_ledger as ors_account_title,
						process_ors_entries.serial_number as ors_serial_number,
						entryDvs.amount_disbursed,
						entryDvs.vat_nonvat,
						entryDvs.ewt_goods_services,
						entryDvs.compensation,
						entryDvs.other_trust_liabilities,
						entryDvs.liquidation_damage,
						entryDvs.tax_portion_of_post,
						entryDvs.dv_numbers,
						entryDvs.check_numbers
            FROM process_ors_entries
            JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
						LEFT JOIN chart_of_accounts ON process_ors_entries.chart_of_account_id = chart_of_accounts.id
						LEFT JOIN (SELECT 
						tbl_dv_aucs_ors_breakdown.fk_process_ors_entry_id,
						GROUP_CONCAT(dv_aucs.dv_number) as dv_numbers,
						GROUP_CONCAT(disbursements.check_number) as check_numbers,
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.amount_disbursed,0)) as amount_disbursed, 
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.vat_nonvat,0)) as vat_nonvat, 
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.ewt_goods_services,0)) as ewt_goods_services, 
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.compensation,0)) as compensation, 
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.other_trust_liabilities,0)) as other_trust_liabilities, 
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.liquidation_damage,0)) as liquidation_damage, 
						SUM(COALESCE(tbl_dv_aucs_ors_breakdown.tax_portion_of_post,0)) as tax_portion_of_post
						FROM tbl_dv_aucs_ors_breakdown
						JOIN dv_aucs ON tbl_dv_aucs_ors_breakdown.fk_dv_aucs_id = dv_aucs.id
						LEFT JOIN (
						SELECT 
						cash_disbursement_items.fk_dv_aucs_id,
						cash_disbursement.check_or_ada_no as check_number
						FROM cash_disbursement
						JOIN cash_disbursement_items ON cash_disbursement_items.fk_cash_disbursement_id = cash_disbursement.id
						WHERE 
						cash_disbursement_items.is_deleted = 0
						AND NOT EXISTS (SELECT  c.parent_disbursement FROM cash_disbursement c WHERE c.parent_disbursement = cash_disbursement.id)
						)disbursements ON dv_aucs.id = disbursements.fk_dv_aucs_id
						WHERE tbl_dv_aucs_ors_breakdown.is_deleted = 0
						GROUP BY tbl_dv_aucs_ors_breakdown.fk_process_ors_entry_id) as entryDvs ON process_ors_entries.id = entryDvs.fk_process_ors_entry_id
            WHERE
            process_ors.is_cancelled = 0),
            consoAllotments as (


            SELECT alltEntys.*,
							NULL as ors_object_code,
							NULL as ors_account_title,
							NULL as ors_serial_number,
							NULL as amount_disbursed,
							NULL as vat_nonvat,
							NULL as ewt_goods_services,
							NULL as compensation,
							NULL as other_trust_liabilities,
							NULL as liquidation_damage,
							NULL as tax_portion_of_post,
							NULL as dv_numbers,
							NULL as check_numbers
						 FROM alltEntys
            UNION  ALL
            SELECT prAllots.*,
							NULL as ors_object_code,
							NULL as ors_account_title,
							NULL as ors_serial_number,
							NULL as amount_disbursed,
							NULL as vat_nonvat,
							NULL as ewt_goods_services,
							NULL as compensation,
							NULL as other_trust_liabilities,
							NULL as liquidation_damage,
							NULL as tax_portion_of_post,
							NULL as dv_numbers,
							NULL as check_numbers
						FROM prAllots
            UNION ALL
            SELECT txnPrAllots.*,
							NULL as ors_object_code,
							NULL as ors_account_title,
							NULL as ors_serial_number,
							NULL as amount_disbursed,
							NULL as vat_nonvat,
							NULL as ewt_goods_services,
							NULL as compensation,
							NULL as other_trust_liabilities,
							NULL as liquidation_damage,
							NULL as tax_portion_of_post,
							NULL as dv_numbers,
							NULL as check_numbers 
							FROM txnPrAllots
            UNION ALL
            SELECT txnItms.*,
							NULL as ors_object_code,
							NULL as ors_account_title,
							NULL as ors_serial_number,
							NULL as amount_disbursed,
							NULL as vat_nonvat,
							NULL as ewt_goods_services,
							NULL as compensation,
							NULL as other_trust_liabilities,
							NULL as liquidation_damage,
							NULL as tax_portion_of_post,
							NULL as dv_numbers,
							NULL as check_numbers 
						 FROM txnItms
            UNION ALL 
            SELECT orsTxnItms.*,
							NULL as ors_object_code,
							NULL as ors_account_title,
							NULL as ors_serial_number,
							NULL as amount_disbursed,
							NULL as vat_nonvat,
							NULL as ewt_goods_services,
							NULL as compensation,
							NULL as other_trust_liabilities,
							NULL as liquidation_damage,
							NULL as tax_portion_of_post,
							NULL as dv_numbers,
							NULL as check_numbers 
						 FROM orsTxnItms
            UNION ALL 
            SELECT * FROM orsItms
            UNION ALL 
            SELECT allotmentAdjustments.*,
							NULL as ors_object_code,
							NULL as ors_account_title,
							NULL as ors_serial_number,
							NULL as amount_disbursed,
							NULL as vat_nonvat,
							NULL as ewt_goods_services,
							NULL as compensation,
							NULL as other_trust_liabilities,
							NULL as liquidation_damage,
							NULL as tax_portion_of_post,
							NULL as dv_numbers,
							NULL as check_numbers 
						 FROM allotmentAdjustments
            )
            SELECT 
            record_allotment_detailed.budget_year,
            record_allotment_detailed.office_name,
            record_allotment_detailed.division,
            record_allotment_detailed.allotmentNumber as allotment_number,
            record_allotment_detailed.mfo_name,
            record_allotment_detailed.fund_source_name,
            record_allotment_detailed.book_name,
            record_allotment_detailed.uacs as allotment_object_code,
            record_allotment_detailed.account_title as allotment_account_title,
            pr_purchase_request.pr_number,
            pr_purchase_request.date as pr_date,
            pr_purchase_request.purpose as pr_purpose,
            `transaction`.tracking_number as transaction_number,
            `transaction`.transaction_date ,
            `transaction`.particular as transaction_particular,
            payee.account_name as transaction_payee,
            consoAllotments.ors_serial_number as ors_id,
            process_ors.serial_number as ors_number,
            consoAllotments.ors_object_code,
            consoAllotments.ors_account_title,
            consoAllotments.ors_reporting_period,
            consoAllotments.allotAmt as allotment_amount,
            consoAllotments.prAmt as pr_amount,
            consoAllotments.txnAmt as transaction_amount,
            consoAllotments.orsAmt as ors_amount,
            consoAllotments.dv_numbers,
            consoAllotments.check_numbers,
            consoAllotments.amount_disbursed,
            consoAllotments.vat_nonvat,
            consoAllotments.ewt_goods_services,
            consoAllotments.compensation,
            consoAllotments.other_trust_liabilities,
            consoAllotments.liquidation_damage,
            consoAllotments.tax_portion_of_post
      
    


            FROM consoAllotments
            LEFT JOIN record_allotment_detailed ON consoAllotments.allotment_entry_id  = record_allotment_detailed.allotment_entry_id
            LEFT JOIN pr_purchase_request ON consoAllotments.prId = pr_purchase_request.id
            LEFT JOIN `transaction` ON consoAllotments.txnId = `transaction`.id
            LEFT JOIN process_ors ON consoAllotments.orsId = process_ors.id
            LEFT JOIN payee ON `transaction`.payee_id = payee.id
            WHERE 
            record_allotment_detailed.budget_year = :yr
            ORDER BY

            record_allotment_detailed.allotmentNumber,
            consoAllotments.allotAmt DESC,
            pr_purchase_request.pr_number DESC,
            consoAllotments.prAmt DESC,
            `transaction`.tracking_number DESC,
            consoAllotments.txnAmt DESC,
            process_ors.serial_number DESC;")->bindValue(':yr', $year)
                ->queryAll());
            $qry  = Yii::$app->db->createCommand("CALL rao(:yr)")->bindValue(':yr', $year)->queryAll();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // header
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
                "ORS Reporting Period",
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
                'ors_reporting_period',
                'allotAmt',
                'prAmt',
                'txnAmt',
                'orsAmt',

            ];
            foreach ($headers as $key => $head) {
                $sheet->setCellValue([$key + 1, 2], $head);
            }
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
            // echo "<script>window.open('$fileDwnldLoc','_self')</script>";
            exit();
        }
        return $this->render('rao');
    }
}
