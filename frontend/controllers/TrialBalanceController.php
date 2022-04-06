<?php

namespace frontend\controllers;

use app\models\Books;
use Yii;
use app\models\TrialBalance;
use app\models\TrialBalanceSearch;
use DateTime;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrialBalanceController implements the CRUD actions for TrialBalance model.
 */
class TrialBalanceController extends Controller
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
                    'update',
                    'create',
                    'view',
                    'index',
                    'delete',
                    'generate-trial-balance'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'create',
                            'view',
                            'index',
                            'delete',
                            'generate-trial-balance'
                        ],

                        'allow' => true,
                        'roles' => ['@']
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

    /**
     * Lists all TrialBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrialBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrialBalance model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrialBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrialBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TrialBalance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TrialBalance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrialBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrialBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrialBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function query($to_reporting_period, $book_id, $entry_type)
    {
        $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);

        $year = $r_period_date->format('Y');
        $from_reporting_period = $year . '-01';

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
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition('jev_preparation.entry_type = :entry_type', $params);
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
            ->bindValue(':entry_type', $type)
            ->queryAll();
        return $query;
    }
    public function actionGenerateTrialBalance()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $book_id  = $_POST['book_id'];
            $entry_type = strtolower($_POST['entry_type']);

            $month  = date('F Y', strtotime($to_reporting_period));
            $book_name = Books::findOne($book_id)->name;
            $query  = $this->query($to_reporting_period, $book_id, $entry_type);
            return json_encode(['result' => $query, 'month' => $month, 'book_name' => $book_name]);
        }
    }
    public function actionExport()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $book_id  = $_POST['book_id'];
            $entry_type = strtolower($_POST['entry_type']);
            $book_name = Books::findOne($book_id)->name;
            $query  = $this->query($to_reporting_period, $book_id, $entry_type);
            $month = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('F Y');


            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', "DEPARTMENT OF TRADE AND INDUSTRY ");
            $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A2:D2');
            $sheet->setCellValue('A2', "CARAGA REGIONAL OFFICE");
            $sheet->getStyle('A2:D2')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A3:D3');
            $sheet->setCellValue('A3', "Trial Balance $book_name");
            $sheet->getStyle('A3:D3')->getAlignment()->setHorizontal('center');
            $sheet->mergeCells('A4:D4');
            $sheet->setCellValue('A4', "As of $month");
            $sheet->getStyle('A4:D4')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A5', "Acount Name");
            $sheet->setCellValue('B5', "Object COde");
            $sheet->setCellValue('C5', "Debit");
            $sheet->setCellValue('D5', "Credit");



            $row = 6;
            $total_debit = 0;
            $total_credit = 0;
            foreach ($query  as  $val) {

                $total = $val['total_debit_credit'];
                $normal_balance = $val['normal_balance'];
                $debit = '';
                $credit = '';
                if (strtolower($normal_balance) == null) {
                    $debit = "No Normal Balance";
                    $credit = "No Normal Balance";
                } else if (strtolower($normal_balance) == "debit") {
                    if ($total < 0) {

                        $credit = number_format($total * -1, 2);
                        $total_credit += $total * -1;
                    } else {
                        $debit = number_format($total, 2);
                        $total_debit += $total;
                    }
                } else if (strtolower($normal_balance) == "credit") {
                    if ($total < 0) {

                        $debit = number_format($total * -1, 2);
                        $total_debit += $total * -1;
                    } else {
                        $credit = number_format($total, 2);
                        $total_credit += $total;
                    }
                }

                $sheet->setCellValueByColumnAndRow(1, $row,  $val['account_title']);
                $sheet->setCellValueByColumnAndRow(2, $row,  $val['object_code']);
                $sheet->setCellValueByColumnAndRow(3, $row, $debit);
                $sheet->setCellValueByColumnAndRow(4, $row, $credit);
                $row++;
            }
            $sheet->mergeCellsByColumnAndRow(1, $row, 2, $row);
            $sheet->setCellValueByColumnAndRow(1, $row, 'Total');
            $sheet->setCellValueByColumnAndRow(3, $row, number_format($total_debit, 2));
            $sheet->setCellValueByColumnAndRow(4, $row, number_format($total_credit, 2));
            foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            date_default_timezone_set('Asia/Manila');
            $id = 'trial_balance_' . $book_name . '_' . $to_reporting_period . '_' . uniqid();
            $file_name = "$id.xlsx";
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            $path = Yii::getAlias('@webroot') . '/transaction';

            $file = $path . "/$id.xlsx";
            $file2 = "transaction/$id.xlsx";
            $writer->save($file);
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");

            return json_encode($file2);


            exit();
        }
    }
}
