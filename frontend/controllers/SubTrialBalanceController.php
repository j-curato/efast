<?php

namespace frontend\controllers;

use app\models\Books;
use Yii;
use app\models\SubTrialBalance;
use app\models\SubTrialBalanceSearch;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubTrialBalanceController implements the CRUD actions for SubTrialBalance model.
 */
class SubTrialBalanceController extends Controller
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
                    'view',
                    'delete',
                    'index',
                    'create',
                    'generate-sub-trial-balance',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'view',
                            'delete',
                            'index',
                            'create',
                            'generate-sub-trial-balance',
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
     * Lists all SubTrialBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubTrialBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubTrialBalance model.
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
     * Creates a new SubTrialBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubTrialBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SubTrialBalance model.
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
     * Deletes an existing SubTrialBalance model.
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
     * Finds the SubTrialBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubTrialBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubTrialBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function query(
        $from_reporting_period,
        $to_reporting_period,
        $book_id,
        $year
    ) {
        $query = Yii::$app->db->createCommand("SELECT 
        accounting_codes.object_code,
        accounting_codes.account_title as account_title,
        accounting_codes.normal_balance,
        (CASE
        WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
        ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
        END) as total_debit_credit,
        beginning_balance.total_beginning_balance as begin_balance
        FROM (
        SELECT
        jev_accounting_entries.object_code
        FROM jev_accounting_entries 
        LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
        WHERE 
         jev_preparation.book_id = :book_id
        AND jev_preparation.reporting_period <= :to_reporting_period
        GROUP BY jev_accounting_entries.object_code
        UNION
        SELECT 
           jev_beginning_balance_item.object_code
            FROM jev_beginning_balance
            LEFT JOIN jev_beginning_balance_item ON jev_beginning_balance.id = jev_beginning_balance_item.jev_beginning_balance_id
        WHERE  jev_beginning_balance.book_id=:book_id
        GROUP BY object_code
        ) as jev_object_codes
        LEFT JOIN (SELECT
        SUM(jev_accounting_entries.debit) as debit,
        SUM(jev_accounting_entries.credit) as credit,
        jev_accounting_entries.object_code 
        FROM jev_accounting_entries 
        LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
        WHERE 
         jev_preparation.book_id = :book_id
        AND jev_preparation.reporting_period >= :from_reporting_period
        AND jev_preparation.reporting_period <= :to_reporting_period
        GROUP BY jev_accounting_entries.object_code) as accounting_entries ON jev_object_codes.object_code = accounting_entries.object_code
        LEFT JOIN (SELECT 
            accounting_codes.object_code,
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
            AND jev_beginning_balance.book_id = :book_id) as beginning_balance ON jev_object_codes.object_code = beginning_balance.object_code
        LEFT JOIN accounting_codes ON jev_object_codes.object_code = accounting_codes.object_code
        
        WHERE   (CASE
        WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
        ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
        END) !=0
        ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':_year', $year)
            ->queryAll();
        return $query;
    }
    public function actionGenerateSubTrialBalance()
    {
        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = $r_period_date->format('F Y');
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_id  = $_POST['book_id'];
            $query = $this->query(
                $from_reporting_period,
                $to_reporting_period,
                $book_id,
                $year
            );
            return json_encode($query);
        }
    }
    public function actionExport()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = $r_period_date->format('F Y');
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_id  = $_POST['book_id'];
            $book_name = Books::findOne($book_id)->name;
            $query = $this->query(
                $from_reporting_period,
                $to_reporting_period,
                $book_id,
                $year
            );


            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', "DEPARTMENT OF TRADE AND INDUSTRY ");
            $sheet->setCellValue('A2', "Account Name");
            $sheet->setCellValue('B2', "Object Code");
            $sheet->setCellValue('C2', "Debit");
            $sheet->setCellValue('D2', "Credit");



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
            $sheet->setCellValueByColumnAndRow(3, $row, number_format($total_debit));
            $sheet->setCellValueByColumnAndRow(4, $row, number_format($total_credit));
            date_default_timezone_set('Asia/Manila');
            $id = 'su_trial_balance_' . $book_name . '_' . $to_reporting_period . '_' . uniqid();
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
