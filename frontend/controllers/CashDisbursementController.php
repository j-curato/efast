<?php

namespace frontend\controllers;

use Yii;
use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use app\models\DvAucs;
use app\models\DvAucsEntries;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CashDisbursementController implements the CRUD actions for CashDisbursement model.
 */
class CashDisbursementController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CashDisbursement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CashDisbursementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CashDisbursement model.
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
     * Creates a new CashDisbursement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CashDisbursement();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CashDisbursement model.
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
     * Deletes an existing CashDisbursement model.
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
     * Finds the CashDisbursement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CashDisbursement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CashDisbursement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetDv()
    {
        if (!empty($_POST)) {
            $dv_id = $_POST['dv_id'];
        }
    }
    public function actionInsertCashDisbursement()
    {

        $reporting_period = $_POST["reporting_period"];
        $book_id = $_POST["book"];
        $check_ada_no = $_POST["check_ada_no"];
        $good_cancelled = $_POST["good_cancelled"];
        $issuance_date = $_POST["issuance_date"];
        $mode_of_payment = $_POST["mode_of_payment"];

        if (count($_POST['selection']) > 1) {
            return json_encode(["error" => "Selected Dv is More Than 1"]);
        } else {

            if (!empty($_POST['update_id'])) {
                $cd = CashDisbursement::findOne($_POST['update_id']);
            } else {

                $cd = new CashDisbursement();
            }
            $cd->book_id = $book_id;
            $cd->reporting_period = $reporting_period;
            $cd->mode_of_payment = $mode_of_payment;
            $cd->check_or_ada_no = $check_ada_no;
            $cd->is_cancelled = $good_cancelled;
            $cd->issuance_date = $issuance_date;
            $cd->dv_aucs_id = $_POST['selection'][0];

            if ($cd->validate()) {
                if ($cd->save()) {
                }
            } else {
                return json_encode(["isSuccess" => false, "error" => $cd->errors]);
            }
        }

        return json_encode(["isSuccess" => true]);
    }
    public function actionImport()
    {
        if (!empty($_POST)) {
            // $chart_id = $_POST['chart_id'];
            $name = $_FILES["file"]["name"];
            // var_dump($_FILES['file']);
            // die();
            $id = uniqid();
            $file = "transaction/{$id}_{$name}";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file);
            $excel->setActiveSheetIndexByName('Cash Disbursement');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;

            $latest_tracking_no = (new \yii\db\Query())
                ->select('tracking_number')
                ->from('transaction')
                ->orderBy('id DESC')->one();
            if ($latest_tracking_no) {
                $x = explode('-', $latest_tracking_no['tracking_number']);
                $last_number = $x[2] + 1;
            } else {
                $last_number = 1;
            }
            // 
            $qwe = 1;
            foreach ($worksheet->getRowIterator(4) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 6) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {

                    $book_name = trim($cells[1]);
                    $reporting_period =  date("Y-m", strtotime($cells[2]));
                    $mode_of_payment = trim($cells[3]);
                    $check_ada_number = trim($cells[4]);
                    $good_cancelled = strtolower(trim($cells[5]));
                    $issuance_date = date('m-d-Y', strtotime($cells[6]));
                    $dv_number = trim($cells[7]);
                    $dv_id = null;
                    if ($good_cancelled === 'good') {

                        $dv = (new \yii\db\Query)
                            ->select("id")
                            ->from("dv_aucs")
                            ->where("dv_number =:dv_number", ['dv_number' => $dv_number])
                            ->one();

                        if (empty($dv)) {
                            return json_encode(['isSuccess' => false, 'error' => "DV Number Does not exist in line $key"]);
                        }

                        $dv_id = $dv['id'];
                    }
                    $book = (new \yii\db\Query())
                        ->select("books.id")
                        ->from('books')
                        ->where("books.name = :name", ['name' => $book_name])
                        ->one();
                    if (empty($book)) {
                        return json_encode(['isSuccess' => false, 'error' => "Book Does not exist in line $key"]);
                    }
                    strtolower(trim($good_cancelled)) === 'good' ? false : true;
                    $data[] = [
                        'book_id' => $book['id'],
                        'dv_id' => $dv_id,
                        'reporting_period' => $reporting_period,
                        'issuance_date' => $issuance_date,
                        'mode_of_payment' => $mode_of_payment,
                        'check_ada_number' => $check_ada_number,
                        'good_cancelled' => $good_cancelled

                    ];
                }
            }

            $column = [
                'book_id',
                'dv_aucs_id',
                'reporting_period',
                'issuance_date',
                'mode_of_payment',
                'check_or_ada_no',
                'is_cancelled',
                // 'transaction_date',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('cash_disbursement', $column, $data)->execute();

            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            return ob_get_clean();
        }
    }
}
