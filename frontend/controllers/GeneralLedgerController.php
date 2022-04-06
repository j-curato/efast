<?php

namespace frontend\controllers;

use app\models\Books;
use Yii;
use app\models\GeneralLedger;
use app\models\GeneralLedgerSearch;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GeneralLedgerController implements the CRUD actions for GeneralLedger model.
 */
class GeneralLedgerController extends Controller
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
                    'index',
                    'view',
                    'update',
                    'delete',
                    'create',
                    'generate-general-ledger'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'delete',
                            'create',
                            'generate-general-ledger'
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
     * Lists all GeneralLedger models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GeneralLedgerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GeneralLedger model.
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
     * Creates a new GeneralLedger model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GeneralLedger();

        if ($model->load(Yii::$app->request->post())) {

            $query = Yii::$app->db->createCommand("SELECT id FROM general_ledger 
            WHERE object_code = :object_code
            AND reporting_period = :reporting_period
            AND book_id = :book_id")
                ->bindValue(':object_code', $model->object_code)
                ->bindValue(':reporting_period', $model->reporting_period)
                ->bindValue(':book_id', $model->book_id)
                ->queryScalar();
            if (!empty($query)) {
                return $this->redirect(['view', 'id' => $query]);
            } else {
                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GeneralLedger model.
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
     * Deletes an existing GeneralLedger model.
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
     * Finds the GeneralLedger model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GeneralLedger the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GeneralLedger::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function beginningBalance(
        $year,
        $book_id,
        $object_code
    ) {
        $beginning_balance = Yii::$app->db->createCommand("SELECT
        jev_beginning_balance_item.debit,
        jev_beginning_balance_item.credit,
        (CASE
            WHEN chart_of_accounts.normal_balance ='Debit' THEN jev_beginning_balance_item.debit - jev_beginning_balance_item.credit
        ELSE jev_beginning_balance_item.credit -  jev_beginning_balance_item.debit
        END) as beginning_balance_total
        
        FROM jev_beginning_balance_item 
        
        LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
        LEFT JOIN chart_of_accounts ON jev_beginning_balance_item.object_code = chart_of_accounts.uacs
            WHERE jev_beginning_balance.`year` = :_year
            AND jev_beginning_balance_item.object_code = :object_code
            AND jev_beginning_balance.book_id  = :book_id
        
        ")
            ->bindValue(':_year', $year)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':object_code', $object_code)
            ->queryOne();
        return $beginning_balance;
    }
    public function query(
        $from_reporting_period,
        $to_reporting_period,
        $book_id,
        $object_code
    ) {
        $query = Yii::$app->db->createCommand("SELECT
                accounting_entries.*,
                chart_of_accounts.normal_balance,
                (CASE 
                WHEN chart_of_accounts.normal_balance = 'Debit' THEN accounting_entries.debit - accounting_entries.credit
                ELSE accounting_entries.credit - accounting_entries.debit
                END) as total
                FROM(
                SELECT  
                jev_preparation.reporting_period,
                jev_preparation.date,
                jev_preparation.explaination as particular,
                jev_preparation.jev_number,
                jev_accounting_entries.debit,
                jev_accounting_entries.credit,
                SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as uacs
                
                
                FROM jev_accounting_entries
                LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
                LEFT JOIN books ON jev_preparation.book_id =  books.id
                WHERE jev_accounting_entries.object_code LIKE :object_code
                AND jev_preparation.reporting_period <= :to_reporting_period
                AND jev_preparation.reporting_period >=:from_reporting_period
                AND books.id = :book_id
                ) as accounting_entries
                INNER  JOIN chart_of_accounts ON accounting_entries.uacs = chart_of_accounts.uacs
                ORDER BY accounting_entries.`date`
              ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':object_code', $object_code . '%')
            ->queryAll();
        return $query;
    }
    public function actionGenerateGeneralLedger()
    {
        if ($_POST) {

            $to_reporting_period = $_POST['reporting_period'];
            $reporting_period = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $from_reporting_period = $reporting_period->format('Y') . '-01';
            $book_id = $_POST['book_id'];
            $object_code = $_POST['object_code'];
            $year = $reporting_period->format('Y');

            $beginning_balance = $this->beginningBalance(
                $year,
                $book_id,
                $object_code
            );
            $query = $this->query(
                $from_reporting_period,
                $to_reporting_period,
                $book_id,
                $object_code
            );
            return json_encode([
                'beginning_balance' => $beginning_balance,
                'query' => $query,
            ]);
        }
    }
    public function actionExport()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['reporting_period'];
            $reporting_period = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $from_reporting_period = $reporting_period->format('Y') . '-01';
            $book_id = $_POST['book_id'];
            $object_code = $_POST['object_code'];
            $year = $reporting_period->format('Y');
            $book_name = Books::findOne($book_id)->name;
            $general_ledger_account = Yii::$app->db->createCommand("SELECT uacs,general_ledger FROM chart_of_accounts WHERE uacs = :uacs")
                ->bindValue(':uacs', $object_code)
                ->queryOne();
            $beginning_balance_query = $this->beginningBalance(
                $year,
                $book_id,
                $object_code
            );
            $query = $this->query(
                $from_reporting_period,
                $to_reporting_period,
                $book_id,
                $object_code
            );

            $beginning_balance = floatval(!empty($beginning_balance_query['beginning_balance_total']) ? $beginning_balance_query['beginning_balance_total'] : 0);
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', "DEPARTMENT OF TRADE AND INDUSTRY ");
            $sheet->setCellValue('E1', 'Fund Cluster Code');
            $sheet->mergeCells('F1:G1');
            $sheet->setCellValue('F1', $book_name);
            $sheet->setCellValue('A2', 'Account Title');
            $sheet->mergeCells('B2:D2');
            $sheet->setCellValue('B2', $general_ledger_account['general_ledger']);
            $sheet->getStyle('B2:D2')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('E2', 'UACS Object Code');
            $sheet->mergeCells('F2:G2');
            $sheet->setCellValue('F2', $general_ledger_account['uacs']);
            $sheet->getStyle('F2:G2')->getAlignment()->setHorizontal('center');

            $sheet->setCellValue('A3', "Reporting Period");
            $sheet->setCellValue('B3', "Date");
            $sheet->setCellValue('C3', "Particulars");
            $sheet->setCellValue('D3', "Reference No.");
            $sheet->setCellValue('E3', "Debit");
            $sheet->setCellValue('F3', "Credit");
            $sheet->setCellValue('G3', "Balance");

            $sheet->setCellValueByColumnAndRow(3, 4, 'Beginning Balance');
            $sheet->setCellValueByColumnAndRow(5, 4, number_format($beginning_balance_query['debit']));
            $sheet->setCellValueByColumnAndRow(6, 4, number_format($beginning_balance_query['credit']));
            $row = 5;
            foreach ($query  as  $val) {
                $total = floatval($val['total']);
                $beginning_balance += $total;
                $debit = $val['debit'];
                $credit = $val['credit'];
                $sheet->setCellValueByColumnAndRow(1, $row,  $val['reporting_period']);
                $sheet->setCellValueByColumnAndRow(2, $row,  $val['date']);
                $sheet->setCellValueByColumnAndRow(3, $row,  $val['particular']);
                $sheet->setCellValueByColumnAndRow(4, $row,  $val['jev_number']);
                $sheet->setCellValueByColumnAndRow(5, $row, $debit);
                $sheet->setCellValueByColumnAndRow(6, $row, $credit);
                $sheet->setCellValueByColumnAndRow(7, $row, $beginning_balance);
                $row++;
            }
            foreach (range('A', $sheet->getHighestColumn()) as  $colId) {
                $sheet->getColumnDimension($colId)->setAutoSize(true);
            }
            date_default_timezone_set('Asia/Manila');
            $id = 'general_ledger' . $to_reporting_period . '_' . uniqid();
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
