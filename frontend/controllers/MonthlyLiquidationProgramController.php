<?php

namespace frontend\controllers;

use Yii;
use app\models\MonthlyLiquidationProgram;
use app\models\MonthlyLiquidationProgramSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MonthlyLiquidationProgramController implements the CRUD actions for MonthlyLiquidationProgram model.
 */
class MonthlyLiquidationProgramController extends Controller
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
                    'create',
                    'index',
                    'delete',
                    'view',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'index',
                            'delete',
                            'view',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
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
     * Lists all MonthlyLiquidationProgram models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MonthlyLiquidationProgramSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MonthlyLiquidationProgram model.
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
     * Creates a new MonthlyLiquidationProgram model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MonthlyLiquidationProgram();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MonthlyLiquidationProgram model.
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MonthlyLiquidationProgram model.
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
     * Finds the MonthlyLiquidationProgram model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MonthlyLiquidationProgram the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MonthlyLiquidationProgram::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionImport()
    {


        if (!empty($_POST)) {
            $name = $_FILES["file"]["name"];
            $year = !empty($_POST['year']) ? $_POST['year'] : '';
            if (empty($year)) {
                return json_encode(['isSuccess' => false, 'message' => 'Please Select Year']);
            }
            $id = uniqid();
            $file = "jev/{$id}_{$name}";;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }

            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file);
            // $excel->setActiveSheetIndexByName('Conso-For upload');
            $worksheet = $excel->getActiveSheet();
            $reader->setReadDataOnly(FALSE);
            // print_r($excel->getSheetNames());
            $rows = [];
            $jev = [];
            $jev_entries = [];
            $temp_data = [];
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {


                    $cells[] = $cell->getValue();
                }
                if (!empty($cells)) {

                    $fund_source_type = $cells[0];
                    $province = $cells[1];
                    $months = [

                        '01' => $cells[2],
                        '02' => $cells[3],
                        '03' => $cells[4],
                        '04' => $cells[5],
                        '05' => $cells[6],
                        '06' => $cells[7],
                        '07' => $cells[8],
                        '08' => $cells[9],
                        '09' => $cells[10],
                        '10' => $cells[11],
                        '11' => $cells[12],
                        '12' => $cells[13],
                    ];
                    $book_name = $cells[15];
                    $book_id = Yii::$app->db->createCommand("SELECT id FROM books WHERE books.name = :book_name")->bindValue(':book_name', $book_name)->queryScalar();

                    foreach ($months as $month => $amount) {

                        $reporting_period = $year . '-' . $month;

                        $liq_program = new MonthlyLiquidationProgram();
                        $liq_program->reporting_period = $reporting_period;
                        $liq_program->amount = $amount;
                        $liq_program->book_id = $book_id;
                        $liq_program->province = $province;
                        $liq_program->fund_source_type = $fund_source_type;
                        if ($liq_program->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return json_encode(['isSuccess' => false, 'message' => 'insert error']);
                        }
                    }
                }
            }

            // JEV ACCOUNTING ENTRIES COLUMNS
            // $column = [
            //     'jev_preparation_id',
            //     // 'chart_of_account_id',
            //     'debit',
            //     'credit',
            //     'current_noncurrent',
            //     'closing_nonclosing',
            //     'cashflow_id',
            //     'net_asset_equity_id',
            //     'object_code',
            //     'lvl',

            // ];
            // // JEV PREPARATION COLUMN
            // $jev_column = [
            //     'id',
            //     'book_id',
            //     'reporting_period',
            //     'date',
            //     'explaination',
            //     'ref_number',
            //     'jev_number',
            //     'dv_number',
            //     'check_ada',
            //     'check_ada_number',
            //     'payee_id'

            // ];
            // // Yii::$app->db->createCommand()->batchInsert('jev_preparation', $jev_column, $temp_data)->execute();

            // try {

            //     Yii::$app->db->createCommand()->batchInsert('jev_accounting_entries', $column, $jev_entries)->execute();
            $transaction->commit();
            // } catch (ErrorException $error) {
            //     $transaction->rollback();
            // }
            // ob_clean();
            // echo '<pre>';
            // var_dump("Success");
            // echo '</pre>';
            return json_encode(['isSuccess' => true, 'message' => 'Import Successfuly']);
        }
    }
}
