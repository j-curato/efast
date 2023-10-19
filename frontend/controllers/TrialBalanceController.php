<?php

namespace frontend\controllers;

use app\models\Books;
use app\models\GeneralJournal;
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
                    'generate-trial-balance',
                    'export'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                        ],

                        'allow' => true,
                        'roles' => ['view_ro_trial_balance']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],

                        'allow' => true,
                        'roles' => ['update_ro_trial_balance']
                    ],
                    [
                        'actions' => [
                            'create',
                            'generate-trial-balance'
                        ],

                        'allow' => true,
                        'roles' => ['create_ro_trial_balance']
                    ],
                    [
                        'actions' => [
                            'export'
                        ],

                        'allow' => true,
                        'roles' => ['export_ro_trial_balance']
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

    public function actionGenerateTrialBalance()
    {
        if (Yii::$app->request->post()) {
            $to_reporting_period = Yii::$app->request->post('reporting_period');
            $book_id  = Yii::$app->request->post('book_id');
            $entry_type = strtolower(Yii::$app->request->post('entry_type'));
            $month  = date('F Y', strtotime($to_reporting_period));
            $book_name = Books::findOne($book_id)->name;
            // $query  = $this->query($to_reporting_period, $book_id, $entry_type);
            $query  = TrialBalance::generateTrialBalance($to_reporting_period, $book_id, $entry_type);
            return json_encode(['result' => $query, 'month' => $month, 'book_name' => $book_name]);
        }
    }
    public function actionExport()
    {

        if (Yii::$app->request->post()) {

            $model = TrialBalance::findOne(Yii::$app->request->post('id'));
            $book_name = $model->book->name;
            $query  = $model->getItems();
            $month = DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y');

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

                $sheet->setCellValue([1, $row],  $val['account_title']);
                $sheet->setCellValue([2, $row],  $val['object_code']);
                $sheet->setCellValue([3, $row], $debit);
                $sheet->setCellValue([4, $row], $credit);
                $row++;
            }
            $sheet->mergeCells([1, $row], 2, $row);
            $sheet->setCellValue([1, $row], 'Total');
            $sheet->setCellValue([3, $row], number_format($total_debit, 2));
            $sheet->setCellValue([4, $row], number_format($total_credit, 2));
            foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            date_default_timezone_set('Asia/Manila');
            $id = 'trial_balance_' . $book_name . '_' . $model->reporting_period . '_' . uniqid();
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
