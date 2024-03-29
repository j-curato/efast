<?php

namespace frontend\controllers;

use app\models\Books;
use Yii;
use app\models\GeneralJournal;
use app\models\GeneralJournalSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * GeneralJournalController implements the CRUD actions for GeneralJournal model.
 */
class GeneralJournalController extends Controller
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
                    'create',
                    'update',
                    'generate',
                    'export'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                        ],
                        'allow' => true,
                        'roles' => ['view_ro_general_journal']
                    ],
                    [
                        'actions' => [

                            'update',

                        ],
                        'allow' => true,
                        'roles' => ['update_ro_general_journal']
                    ],
                    [
                        'actions' => [
                            'create',
                            'generate',
                        ],
                        'allow' => true,
                        'roles' => ['create_ro_general_journal']
                    ],
                    [
                        'actions' => [
                            'export'
                        ],
                        'allow' => true,
                        'roles' => ['export_ro_general_journal']
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
     * Lists all GeneralJournal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GeneralJournalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GeneralJournal model.
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
     * Creates a new GeneralJournal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GeneralJournal();

        if ($model->load(Yii::$app->request->post())) {
            $checkIfPeriodExists = Yii::$app->db->createCommand('SELECT id FROM general_journal WHERE 
            reporting_period = :reporting_period
            AND book_id = :book_id')
                ->bindValue(':book_id', $model->book_id)
                ->bindValue(':reporting_period', $model->reporting_period)
                ->queryScalar();
            if (empty($id)) {
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save Failed");
                }
                $id = $model->id;
            }
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing GeneralJournal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            $id = Yii::$app->db->createCommand('SELECT id FROM general_journal WHERE 
            reporting_period = :reporting_period
            AND book_id = :book_id
            ')
                ->bindValue(':book_id', $model->book_id)
                ->bindValue(':reporting_period', $model->reporting_period)
                ->queryScalar();
            if (empty($id)) {
                if ($model->save(false)) {
                    $id = $model->id;
                }
            }
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GeneralJournal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.banks
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
     * Finds the GeneralJournal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GeneralJournal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GeneralJournal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function query($book_id = '', $reporting_period = '')
    {

        $query = Yii::$app->db->createCommand("SELECT 
        jev_preparation.date,
        jev_preparation.jev_number,
        jev_preparation.explaination,
        COALESCE(jev_accounting_entries.debit,0) as debit,
        COALESCE(jev_accounting_entries.credit,0) as credit,
        accounting_codes.object_code,
        accounting_codes.account_title
        FROM jev_preparation
        INNER JOIN jev_accounting_entries ON jev_preparation.id  = jev_accounting_entries.jev_preparation_id
        INNER JOIN accounting_codes ON jev_accounting_entries.object_code = accounting_codes.object_code
        WHERE
        jev_preparation.ref_number = 'GJ'
        AND jev_preparation.reporting_period = :reporting_period
        AND jev_preparation.book_id = :book_id")
            ->bindValue('book_id', $book_id)
            ->bindValue('reporting_period', $reporting_period)
            ->queryAll();
        $result = ArrayHelper::index($query, null, 'jev_number');
        return $result;
    }
    public function actionGenerate()
    {
        if (Yii::$app->request->post()) {
            $reporting_period  = Yii::$app->request->post('reporting_period');
            $book_id = Yii::$app->request->post('book_id');
            return json_encode(GeneralJournal::generateGeneralJournal($book_id, $reporting_period));
        }
    }
    public function actionExport()
    {

        if (Yii::$app->request->post()) {
            $model = GeneralJournal::findOne(Yii::$app->request->post('id'));
            $query = $model->getItems();
            $book_name = $model->book->name;
            $reporting_period = $model->reporting_period;
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->mergeCells('A1:F1');
            $sheet->setCellValue('A1', "GENERAL JOURNAL ");
            $sheet->getStyle('A1:D1')->getAlignment()->setHorizontal('center');

            $sheet->mergeCells('A2:C2');
            $sheet->setCellValue('A2', "Entity Name: DEPARTMENT OF TRADE AND INDUSTRY CARAGA ");

            $sheet->mergeCells('D2:F2');
            $sheet->setCellValue('D2', "Book: $book_name ");
            $sheet->setCellValue('A3', "Date");
            $sheet->setCellValue('B3', "JEV No.");
            $sheet->setCellValue('C3', "Particulars");
            $sheet->setCellValue('D3', "Object Code");
            $sheet->setCellValue('E3', "Debit");
            $sheet->setCellValue('F3', "Credit");


            $row = 4;

            foreach ($query  as $jev_number => $val) {
                $date = $val[0]['date'];
                $particular = $val[0]['explaination'];
                $sheet->setCellValue([1, $row], $date);
                $sheet->setCellValue([2, $row], $jev_number);
                $sheet->setCellValue([3, $row], $particular);
                $row++;
                foreach ($val  as $val2) {
                    $sheet->setCellValue([3, $row], $val2['account_title']);
                    $sheet->setCellValue([4, $row], $val2['object_code']);
                    $sheet->setCellValue([5, $row], $val2['debit']);
                    $sheet->setCellValue([6, $row], $val2['credit']);
                    $row++;
                }
            }
            foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
                $sheet->getColumnDimension($columnID)
                    ->setAutoSize(true);
            }
            date_default_timezone_set('Asia/Manila');
            $id = 'trial_balance_' . $book_name . '_' . $reporting_period . '_' . uniqid();
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
