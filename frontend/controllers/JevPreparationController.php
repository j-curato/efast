<?php

namespace frontend\controllers;

use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\JevAccountingEntries;
use Yii;
use app\models\JevPreparation;
use app\models\JevPreparationSearch;
use app\models\MajorAccounts;
use app\models\SubMajorAccounts;
use app\models\SubMajorAccounts2;
use Exception;
use frontend\models\Model;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * JevPreparationController implements the CRUD actions for JevPreparation model.
 */
class JevPreparationController extends Controller
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
    public function beforeAction($action)
    {
        if ($action->id == 'ledger') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all JevPreparation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JevPreparationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JevPreparation model.
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
    public function actionGeneralLedgerIndex()
    {
        // echo "success";
        // $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        // jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        // chart_of_accounts.general_ledger
        // FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
        // AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        // AND jev_preparation.fund_cluster_code_id =1 AND jev_accounting_entries.chart_of_account_id =1 
        // ORDER BY jev_preparation.reporting_period")->queryAll();
        $model = new JevPreparation();
        return $this->render('general_ledger_view', [
            'model' => $model,
        ]);
    }
    public function actionLedger()
    {
        $gen = (!empty($_POST['gen'])) ? 'AND jev_accounting_entries.chart_of_account_id =' . $_POST['gen'] : '';
        $fund = (!empty($_POST['fund'])) ? 'AND jev_preparation.fund_cluster_code_id =' . $_POST['fund'] : '';
        $y = (!empty($_POST['reporting_period'])) ?  $_POST['reporting_period'] : '';
        $x = explode('-', $y);
        // $reporting_period = $_POST['reporting_period'] ? "'AND jev_preparation.reporting_period ='" .  (String)$_POST['reporting_period'] ."'" : '';
        $reporting_period = (!empty($_POST['reporting_period'])) ? " AND jev_preparation.reporting_period like '{$x[0]}%' AND jev_preparation.reporting_period <='{$_POST['reporting_period']}'"  : '';

        $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        chart_of_accounts.general_ledger,jev_accounting_entries.id,jev_preparation.ref_number
        FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
        AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
         $gen $fund $reporting_period

        ORDER BY jev_preparation.reporting_period

        ")->queryAll();
        $qwe = $x[0] ? $x[0] - 1 : '';
        // $q = "$qwe";
        // $y= Yii::$app->db->createCommand(" SELECT jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        // jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        // chart_of_accounts.general_ledger,jev_accounting_entries.id,jev_preparation.ref_number
        // FROM jev_preparation,jev_accounting_entries,chart_of_accounts  where jev_preparation.reporting_period like  '2020%' ORDER BY jev_preparation.id DESC ")->queryAll();
        // echo json_encode(['results' => $chart]);
        // array_push($chart,$y);
        $begin_balance =
            JevPreparation::find()
            ->select("reporting_period")
            ->where("reporting_period like :reporting_period", [
                'reporting_period' => "$qwe%"
            ])
            ->orderBy("reporting_period DESC")
            ->one();
        $balance = JevPreparation::find()
            ->joinWith(['jevAccountingEntries', 'jevAccountingEntries.chartOfAccount'])
            ->select([
                'chart_of_accounts.id', 'jev_preparation.explaination', 'jev_preparation.jev_number', 'jev_preparation.reporting_period',
                'jev_accounting_entries.id', 'jev_accounting_entries.debit', 'jev_accounting_entries.credit', 'chart_of_accounts.uacs',
                'chart_of_accounts.general_ledger', 'jev_accounting_entries.id', 'jev_preparation.ref_number'
            ])
            ->where("reporting_period = :reporting_period", [
                'reporting_period' => $begin_balance->reporting_period
            ])

            ->asArray()->all();

        // array_push($chart,['balance'=>$balance]);
        return json_encode(['results' => $chart, 'balance' => $balance]);
        // echo $balance;
        // echo '<pre>';
        // var_dump($balance);
        // echo '</pre>';
    }



    /**
     * Creates a new JevPreparation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JevPreparation();

        $modelJevItems = [new JevAccountingEntries()];
        if ($model->load(Yii::$app->request->post())) {
            $modelJevItems = Model::createMultiple(JevAccountingEntries::class);
            Model::loadMultiple($modelJevItems, Yii::$app->request->post());

            // ajax validation
            // if (Yii::$app->request->isAjax) {
            //     Yii::$app->response->format = Response::FORMAT_JSON;
            //     return ArrayHelper::merge(
            //         ActiveForm::validateMultiple($modelsAddress),
            //         ActiveForm::validate($modelCustomer)
            //     );
            // }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelJevItems) && $valid;
            // $model->jev_number .= '-' . $model->fund_cluster_code_id . '-' . $this->jevNumber($model->reporting_period);

            if ($valid) {


                if ($this->checkIfBalance($modelJevItems)) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {

                            foreach ($modelJevItems as $modelJevItem) {
                                $modelJevItem->jev_preparation_id = $model->id;
                                if (!($flag = $modelJevItem->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
            // return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelJevItems' => (empty($modelJevItems)) ? [new JevAccountingEntries] : $modelJevItems
        ]);
    }

    /**
     * Updates an existing JevPreparation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // $modelCustomer = $this->findModel($id);
        // $modelsAddress = $modelCustomer->addresses;

        $model = $this->findModel($id);
        $modelJevItems = $model->jevAccountingEntries;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $oldIDs = ArrayHelper::map($modelJevItems, 'id', 'id');
            $modelJevItems = Model::createMultiple(JevAccountingEntries::class, $modelJevItems);
            Model::loadMultiple($modelJevItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelJevItems, 'id', 'id')));


            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelJevItems);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            JevAccountingEntries::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelJevItems as $modelAddress) {
                            $modelAddress->jev_preparation_id = $model->id;
                            if (!($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    // var_dump($e);
                    $transaction->rollBack();
                }
            }
            // return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelJevItems' => (empty($modelJevItems)) ? [new JevAccountingEntries] : $modelJevItems
        ]);
    }

    /**
     * Deletes an existing JevPreparation model.
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
     * Finds the JevPreparation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JevPreparation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JevPreparation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function jevNumber($reporting_period)
    {


        $query = JevPreparation::find()
            ->select('jev_number')
            ->orderBy([
                'id' => SORT_DESC
            ])->one();
        if (!empty($query)) {
            $x = explode('-', $query->jev_number)[4] + 1;
        } else {
            $x = 1;
        }
        $y = null;
        $len = strlen($x);

        // add zero bag.o mag last number
        for ($i = $len; $i < 4; $i++) {
            $y .= 0;
        }
        $year = date('Y', strtotime($reporting_period));
        $year .= '-' . date('m');
        $year .= '-' . $y . $x;

        VarDumper::dump($year);

        return $year;
    }
    public function checkIfBalance($jevItems)
    {
        $debit = 0;
        $credit = 0;
        foreach ($jevItems as $item) {
            $debit += $item->debit;
            $credit += $item->credit;
        }
        if ($debit == $credit) {
            return true;
        } else {
            return false;
        }
    }


    public function actionReport()

    {
        // $x = Yii::$app->request->post('gen');
        $x = $_POST['gen'];

        echo $x;
        // return json_encode($x);

        // $gen = $_POST['gen'];
        // $gen = (!empty($_POST['gen'])) ? 'AND jev_accounting_entries.chart_of_account_id =' . $_POST['gen'] : '';
        // $fund = (!empty($_POST['fund'])) ? 'AND jev_preparation.fund_cluster_code_id =' . $_POST['fund'] : '';
        // $yawa =$_POST['report'];
        // $x = explode('-', $_POST['reporting_period']);
        // $reporting_period = $_POST['reporting_period'] ? "'AND jev_preparation.reporting_period ='" .  (String)$_POST['reporting_period'] ."'" : '';
        // $reporting_period = (!empty($_POST['reporting_period'])) ? " AND jev_preparation.reporting_period like '{$x[0]}%' AND jev_preparation.reporting_period <='{$_POST['reporting_period']}'"  : '';

        //     $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        //     jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        //     chart_of_accounts.general_ledger,jev_accounting_entries.id,jev_preparation.ref_number
        //     FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
        //     AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        //   $gen $fund $reporting_period
        //     ORDER BY jev_preparation.reporting_period

        //     ")->queryAll();
        // $q = "$qwe";
        // $r = $x[0] - 1;

        // $qwe = Yii::$app->db->createCommand("SELECT MAX( jev_preparation.reporting_period ) as reporting_period from jev_preparation where   jev_preparation.reporting_period like '{$r}%' ")->queryAll();
        // jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        // chart_of_accounts.general_ledger,jev_accounting_entries.id,jev_preparation.ref_number
        // FROM jev_preparation,jev_accounting_entries,chart_of_accounts  where jev_preparation.reporting_period like  '2020%' ORDER BY jev_preparation.id DESC ")->queryAll();
        // echo json_encode(['results' => $chart]);
        // array_push($chart,$y);

        // $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        // jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        // chart_of_accounts.general_ledger,jev_accounting_entries.id,jev_preparation.ref_number
        // FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
        // AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        // -- AND jev_preparation.reporting_period like '{$qwe}%'
        // ORDER BY jev_preparation.reporting_period

        //   ")->queryAll();
        // print_r("$qwe") ;
        // return json_encode(['results' => $reporting_period]);
    }
    public function actionImport()

    {

        if (!empty($_POST)) {
            $name = $_FILES["file"]["name"];


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
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());
            $rows = [];
            $jev = [];
            $jev_entries = [];
            foreach ($worksheet->getRowIterator() as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];

                if ($key > 2) {
                    foreach ($cellIterator as $x => $cell) {

                        $cells[] =   $cell->getValue();
                    }
                    // echo '<pre>';
                    // var_dump($cells);
                    // echo '</pre>';
                    $uacs = ChartOfAccounts::find()->where("uacs = :uacs", [
                        'uacs' => $cells[0]
                    ])->one();

                    $fund_cluster = FundClusterCode::find()->where("name= :name", [
                        'name' => $cells[2]
                    ])->one();
                    // if (empty($uacs)) {
                    //     //MAJOR ACOUNT INSERT IF DLI MA KITA
                    //     $major = MajorAccounts::find()->where("object_code = :object_code", [
                    //         'object_code' => $cells[13]
                    //     ])->one();
                    //     if (empty($major)) {


                    //         try {
                    //             $maj = new MajorAccounts();
                    //             $maj->object_code = $cells[13];
                    //             $maj->name = $cells[14];


                    //             if ($maj->save(false)) {
                    //                 $major = $maj;
                    //             }
                    //         } catch (Exception $e) {
                    //             echo '<pre>';
                    //             var_dump($e);
                    //             echo '</pre>';
                    //         }
                    //     }





                    //     // SUB MAJOR FIND
                    //     $sub_major = SubMajorAccounts::find()->where("object_code=:object_code", [
                    //         'object_code' => $cells[15]
                    //     ])->one();
                    //     if (empty($sub_major)) {

                    //         echo '<pre>';
                    //         var_dump($cells[15]);
                    //         echo '</pre>';

                    //         try {
                    //             $sub_maj = new SubMajorAccounts();
                    //             $sub_maj->object_code = $cells[15];
                    //             $sub_maj->name = $cells[16];


                    //             if ($sub_maj->save(false)) {
                    //                 $sub_major = $sub_maj;
                    //             }
                    //         } catch (Exception $e) {
                    //             echo '<pre>';
                    //             var_dump($e);
                    //             echo '</pre>';
                    //         }
                    //     }
                    //     // SUB MAJOR 2
                    //     $sub_major2 = SubMajorAccounts2::find()->where("object_code = :object_code", [
                    //         'object_code' => $cells[17]
                    //     ])->one();
                    //     if (empty($sub_major2)) {
                    //         try {
                    //             $sub_maj2 = new SubMajorAccounts();
                    //             $sub_maj2->object_code = $cells[17];
                    //             $sub_maj2->name = $cells[18];


                    //             if ($sub_maj2->save(false)) {
                    //                 $sub_major2 = $sub_maj2;
                    //             }
                    //         } catch (Exception $e) {
                    //             echo '<pre>';
                    //             var_dump($e);
                    //             echo '</pre>';
                    //         }
                    //     }

                    //     // CHART OF ACCOUNTS 
                    //     $chart = [];
                    //     try {
                    //         $coa = new ChartOfAccounts();
                    //         $coa->uacs = $cells[0];
                    //         $coa->general_ledger = $cells[1];
                    //         $coa->major_account_id = $major->id;
                    //         $coa->sub_major_account = $sub_major->id;
                    //         $coa->sub_major_account_2_id = $sub_major2->id;
                    //         $coa->account_group = $cells[11];
                    //         $coa->current_noncurrent = $cells[12];
                    //         $coa->enable_disable = 1;
                    //         if ($coa->save(false)) {
                    //             $uacs = $coa->id;
                    //         }
                    //     } catch (Exception $e) {
                    //         echo $e;
                    //     }
                    //     // echo '<pre>';
                    //     // var_dump($sub_major2->id);
                    //     // echo '</pre>';
                    // }


                    $reporting_period = date("Y-m", strtotime($cells[3]));
                    // echo '<pre>';
                    // var_dump($reporting_period);
                    // echo '</pre>';
                    try {

                        $jv = new JevPreparation();
                        $jv->fund_cluster_code_id = (!empty($fund_cluster)) ? $fund_cluster->id : '';
                        $jv->reporting_period = $reporting_period;
                        $jv->date = $cells[4];
                        $jv->explaination = $cells[5];
                        $jv->ref_number = $cells[6];

                        if ($jv->save(false)) {


                            $jev_entries[] = [$jv->id, $uacs->id, $cells[8] ? $cells[8] : 0, $cells[9] ? $cells[9] : 0];
                        }
                    } catch (InvalidArgumentException $e) {
                        echo  $e->getMessage();;
                    }
                }
            }

            $column = ['jev_preparation_id', 'chart_of_account_id', 'debit', 'credit'];
            $ja = Yii::$app->db->createCommand()->batchInsert('jev_accounting_entries', $column, $jev_entries)->execute();


            echo '<pre>';
            var_dump("SUCCESS");
            echo '</pre>';
            // unset($rows[0]);
            // unset($rows[1]);
            // echo json_encode(['results' => $major]);
            return "qwe";
        }
    }


    public function actionGeneralJournal()
    {


        return $this->render(
            'general_journal',
        );
    }
    public function actionAdadj()
    {


        $data = JevPreparation::find()
            ->joinWith(['jevAccountingEntries' => function ($query) {
                $query->joinWith('chartOfAccount')
                    ->orderBy('chart_of_accounts.uacs');
            }])
            ->joinWith('fundClusterCode')

            // ->where("jev_accounting_entries.debit > :debit", [
            //     "debit" => 0
            // ])
            ->orderBy('id')
            ->all();

        $credit = Yii::$app->db->createCommand("
        SELECT DISTINCT chart_of_accounts.uacs,chart_of_accounts.general_ledger
        from jev_preparation,jev_accounting_entries,chart_of_accounts
        where jev_preparation.id  = jev_accounting_entries.jev_preparation_id
        and jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        AND jev_accounting_entries.credit>0
         ORDER BY chart_of_accounts.uacs
        ")->queryAll();

        $debit = Yii::$app->db->createCommand("
        SELECT DISTINCT chart_of_accounts.uacs,chart_of_accounts.general_ledger
        from jev_preparation,jev_accounting_entries,chart_of_accounts
        where jev_preparation.id  = jev_accounting_entries.jev_preparation_id
        and jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        AND jev_accounting_entries.debit>0
         ORDER BY chart_of_accounts.uacs
        ")->queryAll();

        // echo '<pre>';
        // var_dump($credit);
        // echo '</pre>';
        return $this->render('adadj_view', [
            'data' => $data,
            'credit' => $credit,
            'debit' => $debit,
        ]);
    }
}
