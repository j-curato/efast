<?php

namespace frontend\controllers;

use app\models\Payee;
use app\models\ResponsibilityCenter;
use app\models\SubAccounts1;
use Yii;
use app\models\Transaction;
use app\models\TransactionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // [
                    //     'actions' => ['create'],
                    //     'allow' => true,
                    //     'roles' => ['accounting'],
                    // ],


                ],


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
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
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
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // if (Yii::$app->user->can('create-transaction')) {

        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
        // } else {
        //     throw new ForbiddenHttpException();
        // }
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // if (Yii::$app->user->can('update-transaction')) {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
        // } else {
        //     throw new ForbiddenHttpException();
        // }
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // if (Yii::$app->user->can('delete-transaction')) {

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
        // } else {

        //     throw new ForbiddenHttpException();
        // }
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionOrsForm()
    {
        return $this->render('ors_form');
    }
    public function actionVoucher()
    {
        return $this->render('disbursement_voucher');
    }
    public function actionGetTransaction()
    {
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('transaction')
            ->all();
        return json_encode($query);
    }
    public function actionImportTransaction()
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
            // $excel->setActiveSheetIndexByName('Chart of Accounts - Final');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            // $chart_uacs = ChartOfAccounts::find()->where("id = :id", ['id' => $chart_id])->one()->uacs;


            // 

            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    $cells[] =   $cell->getValue();
                }
                $tracking_number = $cells[0];
                $payee_name = $cells[1];
                $particular = $cells[2];
                $amount = $cells[3];
                $responsibility_center_name = $cells[4];
                $earmark_number = $cells[5];
                $payroll_number = $cells[6];
                $date = $cells[7];

                // $name = $cells[1];
                if (
                    empty($tracking_number)
                    || empty($payee_name)
                    || empty($particular)
                    || empty($amount)
                    || empty($responsibility_center_name)
                    || empty($earmark_number)
                    || empty($payroll_number)
                    || empty($date)
                ) {
                    return json_encode(['isSuccess' => false, 'error' => "Error Somthing is Missing in Line $key"]);
                    die();
                } else {
                    $payee  = (new \yii\db\Query())
                        ->select(['account_name', 'id'])
                        ->from('payee')
                        ->where('account_name LIKE :account_name', ['account_name' => $payee_name])
                        ->one();
                    $responsibility_center  = (new \yii\db\Query())
                        ->select(['name', 'id'])
                        ->from('responsibility_center')
                        ->where('name LIKE :name', ['name' => $responsibility_center_name])
                        ->one();
                    // $responsibility_center = ResponsibilityCenter::find()->where('like', 'name', $responsibility_center_name)->one();
                    if (!empty($payee)) {
                        $payee_id = $payee['id'];
                    } else {
                        return json_encode(['isSuccess' => false, 'error' => "Payee Does Not Exist in line $key"]);
                        break;
                    }
                    if (!empty($responsibility_center)) {
                        $responsibility_center_id = $responsibility_center['id'];
                    } else {
                        return json_encode(['isSuccess' => false, 'error' => "Responsibility Center Does Not Exist in Line $key"]);
                        break;
                    }
                    
                    $data[] = [
                        'responsibility_center_id' => $responsibility_center_id,
                        'payee_id' => $payee_id,
                        'particular' => $particular,
                        'gross_amount' => $amount,
                        'tracking_number' => $tracking_number,
                        'earmark_no' => $earmark_number,
                        'payroll_number' => $payroll_number,
                        'transaction_date' => $date
                    ];
                }
            }

            $column = [
                'responsibility_center_id',
                'payee_id',
                'particular',
                'gross_amount',
                'tracking_number',
                'earmark_no',
                'payroll_number',
                'transaction_date',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('transaction', $column, $data)->execute();

            // return $this->redirect(['index']);
            return json_encode(['isSuccess' => true]);
            // ob_clean();
            // echo "<pre>";
            // var_dump($payee);
            // echo "<pre>";
            // return ob_get_clean();
        }
    }
    public function actionSample($id)
    {
        return $this->render('view_sample', [
            'model' => $this->findModel2($id),
        ]);
        
    }
    protected function findModel2($id)
    {
        if (($model = SubAccounts1::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}
