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
