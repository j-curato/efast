<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use yii\db\Query;
use app\models\Books;
use app\models\Office;
use yii\db\Expression;
use common\models\User;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\helpers\MyHelper;

class PropertyReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'user-properties',
                    'ppelc',


                ],
                'rules' => [
                    [
                        'actions' => [
                            'user-properties',
                        ],
                        'allow' => true,
                        'roles' => ['property_accountabilities']
                    ],
                    [
                        'actions' => [
                            'ppelc',
                        ],
                        'allow' => true,
                        'roles' => ['ppelc']
                    ]
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
    public function actionUserProperties()
    {
        if (Yii::$app->request->post()) {


            $act_usr_id  = !empty(MyHelper::post('act_usr_id')) ? MyHelper::post('act_usr_id') : null;
            $actbl_ofr  = !empty(MyHelper::post('actbl_ofr')) ? MyHelper::post('actbl_ofr') : null;
            $user_data = User::getUserDetails();
            $office  = Yii::$app->user->can('ro_property_admin') ? Yii::$app->request->post('office') ?? null : $user_data->employee->office->id;
            // if (empty($act_usr_id) && empty($actbl_ofr)) {
            //     return json_encode('');
            // }
            if (!Yii::$app->user->can('po_property_admin') && empty($actbl_ofr)) {
                return json_encode([]);
            }
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
                ->join('LEFT JOIN', 'par', 'property.id = par.fk_property_id')
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
            if (YIi::$app->user->can('ro_property_admin')) {
                $qry->andWhere("property.fk_office_id= :office", ['office' => $office]);
            }
            $qry->orderBy("location.id");

            return json_encode($result = ArrayHelper::index($qry->all(), null, 'actble_ofr'));
        }
        return $this->render('user_properties');
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
        $book_id = '',
        $employee_id = null,
        $office_id = null,
        $reporting_period = '',
        $uacs_id = ''
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
            // ->andWhere("detailed_property_database.isUnserviceable = 'serviceable'")
            ->andWhere("detailed_property_database.strt_mnth <=:reporting_period", ['reporting_period' => $reporting_period])
            ->andWhere("detailed_property_database.uacs =:uacs", ['uacs' => $uacs])
            ->andWhere("detailed_other_property_details.book_name =:book_name", ['book_name' => $book_name])
            ->andWhere("DATE_FORMAT(detailed_property_database.derecognition_date,'%Y-%m') >= :reporting_period 
        OR detailed_property_database.derecognition_num IS NULL", ['reporting_period' => $reporting_period]);

        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $office_id = $user_data->employee->office->id;
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

    public function actionExportPropertyDatabase()
    {
        // return YIi::$app->memem->getWorkdays('2022-06-17', '2022-06-20');
        if (Yii::$app->request->post()) {

            $reporting_period = Yii::$app->request->post('reporting_period');
            if (!Yii::$app->user->can('ro_property_admin')) {
                $user_data = User::getUserDetails();
                $office =   $user_data->employee->office->office_name;
            }
            // $query = Yii::$app->db->createCommand("SELECT * FROM detailed_property_database")->queryAll();

            $q  = new Query();
            $q->select("*")
                ->from('detailed_property_database')
                ->andWhere("DATE_FORMAT(detailed_property_database.date_acquired,'%Y-%m') <=:reporting_period", ['reporting_period' => $reporting_period]);

            if (!Yii::$app->user->can('ro_property_admin')) {
                $q->andWhere('office_name = :office_name', ['office_name' => $user_data->employee->office->office_name]);
            }
            $query = $q->all();
            // $items  = Yii::$app->db->createCommand("SELECT * FROM detailed_other_property_details")->queryAll();
            $i  = new Query();
            $i->select("*")
                ->from('detailed_other_property_details');
            if (!Yii::$app->user->can('ro_property_admin')) {
                $i->andWhere('fk_office_id = :office_id', ['office_id' => $user_data->employee->office->id]);
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
                "GL UACS",
                "GL Account Title",
                "Contra Asset Sub Account",
                "Contra Asset Sub Account Title",
                "Contra Asset Sub Account Title",
                "Depreciation Sub Account ",
                "Depreciation Sub Account Title",

            ];
            foreach ($headers as $key => $head) {
                $sheet->setCellValue([$key + 1, 2], $head);
            }

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
                $sheet->setCellValue(
                    [32, $row],
                    $val['uacs']
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
}
