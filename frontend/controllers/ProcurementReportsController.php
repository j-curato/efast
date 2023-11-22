<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\VwProcurementToIarTracking;
use app\models\VwProcurementToIarTrackingSearch;

class ProcurementReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'procurement-to-inspection-tracking'
                ],
                'rules' => [
                    [

                        'actions' => [
                            'procurement-to-inspection-tracking'
                        ],
                        'allow' => true,
                        'roles' => ['pr_to_iar_tracking']
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
    public function actionProcurementToInspectionTracking()
    {
        $searchModel = new VwProcurementToIarTrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->post()) {
            $year = Yii::$app->request->post('year');
            return $this->exportProcurementToInspectionTracking($year);
        }

        return $this->render('procurement_to_inspection_tracking', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    private function exportProcurementToInspectionTracking($year)
    {


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $attributes = [
            'office_name',
            'division',
            'pr_number',
            'pr_date',
            'purpose',
            'stock_name',
            'specification',
            'pr_is_cancelled',
            'quantity',
            'unit_cost',
            'rfq_number',
            'rfq_date',
            'rfq_deadline',
            'rfq_is_cancelled',
            'aoq_number',
            'aoq_is_cancelled',
            'payee_name',
            'bidAmount',
            'bidGrossAmount',
            'po_number',
            'po_is_cancelled',
            'poTransmittalNumber',
            'poTransmittalDate',
            'rfi_number',
            'rfi_date',
            'inspection_from',
            'inspection_to',
            'inspected_quantity',
            'ir_number',
            'iar_number',
            'iarTransmittalNumber',
            'iarTransmittalDate',
        ];
        $model = new VwProcurementToIarTracking();
        $modelAttributeLabels = $model->attributeLabels();
        foreach ($attributes as $key => $attribute) {
            $sheet->setCellValue([$key + 1, 2], $modelAttributeLabels[$attribute]);
        }
        $row = 3;
        foreach ($model->getItems($year) as $val) {
            foreach ($attributes as $idx => $attribute) {
                $sheet->setCellValue(
                    [$idx + 1, $row],
                    $val[$attribute]
                );
            }
            $row++;
        }


        date_default_timezone_set('Asia/Manila');
        $id = uniqid() . '_' . date('Y-m-d h A');
        $file_name = "liquidation_$id.xlsx";

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $path = Yii::getAlias('@webroot') . '/exports';

        $file = $path . "/liquidation_$id.xlsx";
        $file2 = "exports/liquidation_$id.xlsx";
        $writer->save($file);
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");

        return json_encode($file2);


        date_default_timezone_set('Asia/Manila');
        $file_name = "detailed_dv_$year.xlsx";
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $fileSaveLoc =  "exports\\" . $file_name;
        $path = Yii::getAlias('@webroot') . '/exports';
        $file = $path . "/$file_name";
        $writer->save($file);
        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
        header('Content-Transfer-Encoding: binary');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Pragma: public'); // HTTP/1.0
        return  json_encode($fileSaveLoc);





        exit();
    }
}
