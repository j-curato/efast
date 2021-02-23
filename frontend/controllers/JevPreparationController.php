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

    public function actionGeneralLedger()
    {

        if (!empty($_POST)) {
            // $gen = (!empty($_POST['gen'])) ? 'AND jev_accounting_entries.chart_of_account_id =' . $_POST['gen'] : '';
            // $fund = (!empty($_POST['fund'])) ? 'AND jev_preparation.fund_cluster_code_id =' . $_POST['fund'] : '';
            // $y = (!empty($_POST['reporting_period'])) ?  $_POST['reporting_period'] : '';
            $gen = $_POST['gen'];
            $fund = $_POST['fund'];
            $reporting_period = $_POST['reporting_period'];

            $x = explode('-', $reporting_period);
            // $reporting_period = $_POST['reporting_period'] ? "'AND jev_preparation.reporting_period ='" .  (String)$_POST['reporting_period'] ."'" : '';
            // $reporting_period = (!empty($y)) ? " AND jev_preparation.reporting_period like '{$x[0]}%' AND jev_preparation.reporting_period <='{$y}'"  : '';

            // $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
            // jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
            // chart_of_accounts.general_ledger,jev_accounting_entries.id,jev_preparation.ref_number
            // FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
            // AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            //  $gen $fund $reporting_period

            // ORDER BY jev_preparation.reporting_period

            // ")->queryAll();

            // GET THE BEGINNING BALANCE OF THE LAST YEAR OF INPUTED REPORTING PERIOD
            if ($reporting_period > 0) {
                $q = $x[0] - 1;

                $begin_balance = JevPreparation::find()
                    ->select('jev_preparation.reporting_period')
                    ->where("jev_preparation.reporting_period LIKE :reporting_period", [
                        'reporting_period' => "$q%"
                    ])->orderBy('date DESC')->one()->reporting_period;
            }
            // echo '<pre>';
            // var_dump($begin_balance);
            // echo '</pre>';
            $general_ledger = (new \yii\db\Query());
            $general_ledger->select([
                'jev_preparation.reporting_period', 'jev_preparation.explaination',
                'chart_of_accounts.uacs', 'chart_of_accounts.general_ledger', 'jev_preparation.ref_number',
                'jev_accounting_entries.credit', 'jev_accounting_entries.debit',
                'chart_of_accounts.normal_balance', 'jev_preparation.date'
            ])
                ->from('jev_accounting_entries')
                ->join('LEFT JOIN', 'jev_preparation', 'jev_accounting_entries.jev_preparation_id=jev_preparation.id')
                ->join('LEFT JOIN', 'chart_of_accounts', 'jev_accounting_entries.chart_of_account_id=chart_of_accounts.id');
            if (!empty($reporting_period)) {


                // KUHAAON ANG MGA DATA BETWEEN 
                $general_ledger->andwhere(['between', 'jev_preparation.reporting_period', $begin_balance, $reporting_period]);
            }
            if (!empty($gen)) {
                $general_ledger->andWhere("jev_accounting_entries.chart_of_account_id = :chart_of_account_id", [
                    'chart_of_account_id' => $gen
                ]);
            }
            if (!empty($fund)) {
                $general_ledger->andWhere("jev_preparation.fund_cluster_code_id = :fund_cluster_code_id", [
                    'fund_cluster_code_id' => $fund
                ]);
            }
            // $general_ledger->orderBy('jev_preparation.reporting_period');
            $chart = $general_ledger->orderBy('jev_preparation.reporting_period')->all();



            $balance_per_uacs = [];
            $qwe = [];

            // MANIPULATE  THE DATA THEN SAVE TO A TEMPORARY ARRAY WITH ITS TOTAL BALANCE
            $reporting_period = '';
            foreach ($chart as $key => $val) {
                $x = array_key_exists($val['uacs'], $balance_per_uacs);

                if ($x === false) {
                    
                    if ($val['normal_balance'] =='Credit' )  {
                        $balance_per_uacs[$val['uacs']] = $val['credit'] - $val['debit'];
                    } else {
                        $balance_per_uacs[$val['uacs']] =  $val['debit'] - $val['credit'];
                    }
                } else {
                    if ($val['normal_balance'] == 'Credit') {
                        $balance_per_uacs[$val['uacs']] = $balance_per_uacs[$val['uacs']] + $val['credit'] - $val['debit'];
                    } else {
                        $balance_per_uacs[$val['uacs']] = $balance_per_uacs[$val['uacs']] + $val['debit'] - $val['credit'];
                    }
                }

                $credit = $val['credit'] ? number_format($val['credit'], 2) : '';
                $debit = $val['debit'] ? number_format($val['debit'], 2) : '';
                if ($key > 0 && $chart[$key - 1]['reporting_period'] == $val['reporting_period']) {
                    $reporting_period = '';
                } else {
                    $reporting_period = $val['reporting_period'];
                }
                $qwe[] = [
                    'reporting_period' => $reporting_period,
                    'explaination' => $val['explaination'],
                    'uacs' => $val['uacs'],
                    'general_ledger' => $val['general_ledger'],
                    'ref_number' =>  $val['ref_number'],
                    'debit' => $val['debit'],
                    'credit' => $val['credit'],
                    'date' => $val['date'],
                    'balance' => $balance_per_uacs[$val['uacs']],
                ];
            }

            $result = ArrayHelper::index($qwe, null, 'uacs');
            // $q = ArrayHelper::multisort($result, ['reporting_period'], [SORT_ASC, SORT_DESC]);
            // $result = ArrayHelper::index($qwe, 'reporting_period', [function ($element) {
            //     return $element['reporting_period'];
            // }, '']);

            // array_push($chart,['balance'=>$balance])
            // return json_encode(['results' => $chart,]);
            // return json_encode(['results' => $balance_per_uacs, ]);



            $object_code = '';
            $ledger = '';
            if (!empty($qwe)) {

                $object_code = $gen ? $qwe[0]['uacs'] : '';
                $ledger = $gen ? $qwe[0]['general_ledger'] : '';
            }
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";
            $fund_cluster_code = '';
            if ($fund) {
                $fund_cluster_code = FundClusterCode::find()->where("id = :id", [
                    'id' => $fund
                ])->one()->name;
            }
            if ($_POST['print'] == 1) {
                return json_encode([
                    'results' => $result,
                    'fund_cluster_code' => $fund_cluster_code,
                    'reporting_period' => date('F Y', strtotime($reporting_period))
                ]);
            }

            return $this->render('general_ledger_view', [
                'data' => $qwe,
                'object_code' => $object_code,
                'account_title' => $ledger,
                'print' => json_encode($result),
                'fund_cluster_code' => $fund_cluster_code
            ]);
        } else {

            return $this->render('general_ledger_view', [
                'object_code' => '',
                'x' => '',
                'print' => ''
            ]);
        }
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
    // public function actionJevImport()
    // {
    //     if (!empty($_POST)) {

    //         $name = $_FILES["file"]["name"];

    //         $id = uniqid();
    //         $file_name = "payee/{$id}_{$name}";

    //         if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_name)) {
    //         } else {
    //             return "ERROR 2: MOVING FILES FAILED.";
    //             die();
    //         }

    //         $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
    //         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    //         $file = $reader->load($file_name);
    //         $sheetName = $file->getSheetNames();
    //         $file->setActiveSheetIndexByName('CKDJ');
    //         $worksheet = $file->getActiveSheet();
    //         $hRow = $worksheet->getHighestDataRow();

    //         $payee_data = [];

    //         foreach ($worksheet->getRowIterator(14) as $key => $row) {

    //             $cellIterator = $row->getCellIterator('G', 'G');
    //             $cellIterator->setIterateOnlyExistingCells(true);
    //             $rowData = [];

    //             foreach ($cellIterator as $cell) {
    //                 $rowData[] = $cell->getValue();
    //             }
    //             // $payee = Payee::find()->where("account_name = :account_name", [
    //             //     'account_name' => $rowData[6]
    //             // ]);
    //             // if (!empty($payee)) {
    //             //     $payee_data[] = $rowData[6];
    //             // }
    //             if (!empty($rowData[0])) {

    //                 $payee_exist = Payee::find()->where("account_name= :account_name", [
    //                     'account_name' => $rowData[0]
    //                 ])->one();
    //                 if (empty($payee_exist)) {

    //                     $payee_data[] = $rowData;
    //                 }
    //             }

    //             // if ($key == 30) {
    //             //     break;
    //             // }
    //         }
    //         $array = array_values(array_unique($payee_data, SORT_REGULAR));
    //         Yii::$app->db->createCommand()->batchInsert('payee', ['account_name'], $array)->execute();
    //         // echo "<pre>";
    //         // var_dump($payee_data);
    //         // echo "</pre>";
    //         // ob_start();

    //         // echo "<pre>";
    //         // var_dump($payee_data);
    //         // echo "</pre>";
    //         // return ob_get_clean();
    //     }
    // }
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
            $excel->setActiveSheetIndexByName('Conso-For upload');
            $worksheet = $excel->getActiveSheet();
            $reader->setReadDataOnly(FALSE);
            // print_r($excel->getSheetNames());
            $rows = [];
            $jev = [];
            $jev_entries = [];
            $temp_data = [];
            $no_jev_number = [];
            $entry2 = [];
            $id = (!empty($w = JevPreparation::find()->orderBy('id DESC')->one())) ? $w->id : 0;

            foreach ($worksheet->getRowIterator() as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                if ($key > 2) {
                    foreach ($cellIterator as $x => $cell) {

                        // $cells[] =   $cell->getValue()->getCalculatedValue();
                        $qwe = 0;
                        if ($y == 4) {
                            $cells[] =   $cell->getFormattedValue();
                            // echo '<pre>';
                            // var_dump('qwe');
                            // echo '</pre>';

                            // $rows[] =  $cell->getCalculatedValue();
                        } elseif ($y == 8) {
                            $qwe = $cell->getCalculatedValue();
                            $cells[] = $qwe;
                        } elseif ($y == 9) {
                            $qwe = $cell->getCalculatedValue();
                            $cells[] = $qwe;
                        } else {
                            $cells[] = $cell->getValue();
                        }

                        $y++;
                    }
                    // echo '<pre>';
                    // var_dump($cells);
                    // echo '</pre>';

                    // if ($key > 2483) {
                    //     echo '<pre>';
                    //     var_dump($cells, $key);
                    //     echo '</pre>';
                    // }
                    if (!empty($cells[0])) {
                        $uacs = ChartOfAccounts::find()->where("uacs = :uacs", [
                            'uacs' => $cells[0]
                        ])->one();
                        if (empty($uacs)) {
                            //MAJOR ACOUNT INSERT IF DLI MA KITA


                            // $major = MajorAccounts::find()->where("object_code = :object_code", [
                            //     'object_code' => $cells[13]
                            // ])->one();
                            // if (empty($major)) {


                            //     try {
                            //         $maj = new MajorAccounts();
                            //         $maj->object_code = $cells[13];
                            //         $maj->name = $cells[14];


                            //         if ($maj->save(false)) {
                            //             $major = $maj;
                            //         }
                            //     } catch (Exception $e) {
                            //         echo '<pre>';
                            //         var_dump($e);
                            //         echo '</pre>';
                            //     }
                            // }





                            // // SUB MAJOR FIND
                            // $sub_major = SubMajorAccounts::find()->where("object_code=:object_code", [
                            //     'object_code' => $cells[15]
                            // ])->one();
                            // if (empty($sub_major)) {

                            //     echo '<pre>';
                            //     var_dump($cells[15]);
                            //     echo '</pre>';

                            //     try {
                            //         $sub_maj = new SubMajorAccounts();
                            //         $sub_maj->object_code = $cells[15];
                            //         $sub_maj->name = $cells[16];


                            //         if ($sub_maj->save(false)) {
                            //             $sub_major = $sub_maj;
                            //         }
                            //     } catch (Exception $e) {
                            //         echo '<pre>';
                            //         var_dump($e);
                            //         echo '</pre>';
                            //     }
                            // }
                            // // SUB MAJOR 2
                            // $sub_major2 = SubMajorAccounts2::find()->where("object_code = :object_code", [
                            //     'object_code' => $cells[17]
                            // ])->one();
                            // if (empty($sub_major2)) {
                            //     try {
                            //         $sub_maj2 = new SubMajorAccounts();
                            //         $sub_maj2->object_code = $cells[17];
                            //         $sub_maj2->name = $cells[18];


                            //         if ($sub_maj2->save(false)) {
                            //             $sub_major2 = $sub_maj2;
                            //         }
                            //     } catch (Exception $e) {
                            //         echo '<pre>';
                            //         var_dump($e);
                            //         echo '</pre>';
                            //     }
                            // }

                            // // CHART OF ACCOUNTS 
                            // $chart = [];
                            // try {
                            //     $coa = new ChartOfAccounts();
                            //     $coa->uacs = $cells[0];
                            //     $coa->general_ledger = $cells[1];
                            //     $coa->major_account_id = $major->id;
                            //     $coa->sub_major_account = $sub_major->id;
                            //     $coa->sub_major_account_2_id = $sub_major2->id;
                            //     $coa->account_group = $cells[11];
                            //     $coa->current_noncurrent = $cells[12];
                            //     $coa->enable_disable = 1;
                            //     if ($coa->save(false)) {
                            //         $uacs = $coa;
                            //     }
                            // } catch (Exception $e) {
                            //     echo $e;
                            // }
                            // echo '<pre>';
                            // var_dump($sub_major2->id);
                            // echo '</pre>';
                        }
                    }

                    $fund_cluster = FundClusterCode::find()->where("name= :name", [
                        'name' => $cells[2]
                    ])->one();



                    $reporting_period = date("Y-m", strtotime($cells[3]));
                    $date = $cells[4] ? date("Y-m-d", strtotime($cells[4])) : '';
                    // echo '<pre>';
                    // var_dump($cells[4], $key);
                    // echo '</pre>';

                    if ($cells[7] != null) {
                        $id++;

                        // $jev_number_exist = JevPreparation::findOne($cells[7]);
                        // if (empty($jev_number_exist)) {
                        //     try {

                        //         $jv = new JevPreparation();
                        //         $jv->fund_cluster_code_id = (!empty($fund_cluster)) ? $fund_cluster->id : '';
                        //         $jv->reporting_period = $reporting_period;
                        //         $jv->date = $cells[4];
                        //         $jv->explaination = $cells[5];
                        //         $jv->ref_number = $cells[6];
                        //         $jv->jev_number = $cells[7];

                        //         if ($jv->save(false)) {
                        //             $jev_entries[] = [$jv->id, $uacs->id, $cells[8] ? $cells[8] : 0, $cells[9] ? $cells[9] : 0];
                        //         }
                        //     } catch (InvalidArgumentException $e) {
                        //         echo  $e->getMessage();
                        //     }
                        // } else {

                        //     $jev_entries[] = [$jev_number_exist->id, $uacs->id, $cells[8] ? $cells[8] : 0, $cells[9] ? $cells[9] : 0];
                        // }



                        // BATCH INSERRRRRRRRRRRRRRT
                        //cell[7] jev number
                        $s = array_search($cells[7], array_column($temp_data, 6));
                        // echo '<pre>';
                        // var_dump($s, $cells[7]);
                        // echo '</pre>';
                        if ($s === false) {
                            $temp_data[] = [
                                $id,
                                (!empty($fund_cluster)) ? $fund_cluster->id : '',
                                $reporting_period, $date, $cells[5], $cells[6], $cells[7]

                            ];


                            $jev_entries[] = [
                                $id,
                                $uacs->id,
                                $cells[8], //debit amount
                                $cells[9], //credit amount
                                $cells[10] ? $cells[10] : '', //current/noncurrent
                                $cells[11] ? $cells[11] : '', //closing nonclosing
                                $cells[12] ? $cells[12] : '', //cash flow transaction

                            ];
                        } else {
                            // $jev_entries[] = [
                            //     $uacs->id,
                            //     $cells[8] ? $cells[8] : 0,
                            //     $cells[9] ? $cells[9] : 0
                            // ];

                            $jev_entries[] = [
                                $temp_data[$s][0],
                                $uacs->id,
                                $cells[8] ? $cells[8] : 0, //debit amount
                                $cells[9] ? $cells[9] : 0, //credit amount
                                $cells[10] ? $cells[10] : '', //current/noncurrent
                                $cells[11] ? $cells[11] : '', //closing nonclosing
                                $cells[12] ? $cells[12] : '', //cash flow transaction

                            ];
                            // echo '<pre>';
                            // var_dump($s,$temp_data[$s]);
                            // echo '</pre>';
                        }
                    } else if (!empty($reporting_period) && $cells[7] == null) {
                        $id++;

                        $s = array_search($reporting_period, array_column($temp_data, 2));

                        if ($s === false) {
                            $temp_data[] = [
                                $id,
                                (!empty($fund_cluster)) ? $fund_cluster->id : '',
                                $reporting_period, $date, $cells[5], $cells[6], 'Sample'

                            ];


                            // echo '<pre>';
                            // var_dump($id);
                            // echo '</pre>';
                            $jev_entries[] = [
                                $id,
                                $uacs->id,
                                $cells[8] ? $cells[8] : 0, //debit amount
                                $cells[9] ? $cells[9] : 0, //credit amount
                                $cells[10] ? $cells[10] : '', //current/noncurrent
                                $cells[11] ? $cells[11] : '', //closing nonclosing
                                $cells[12] ? $cells[12] : '', //cash flow transaction

                            ];
                        } else {
                            $jev_entries[] = [
                                $id,
                                $uacs->id,
                                $cells[8] ? $cells[8] : 0, //debit amount
                                $cells[9] ? $cells[9] : 0, //credit amount
                                $cells[10] ? $cells[10] : '', //current/noncurrent
                                $cells[11] ? $cells[11] : '', //closing nonclosing
                                $cells[12] ? $cells[12] : '', //cash flow transaction

                            ];
                        }
                    }
                    // if ($key == 50) {
                    //     break;
                    // }
                    // else {
                    //     $q = array_search($reporting_period, $temp_data);



                    //     if (empty($q)) {


                    //         $jv = new JevPreparation();
                    //         $jv->fund_cluster_code_id = (!empty($fund_cluster)) ? $fund_cluster->id : '';
                    //         $jv->reporting_period = $reporting_period;
                    //         $jv->date = $cells[4];
                    //         $jv->explaination = $cells[5];
                    //         $jv->ref_number = $cells[6];
                    //         $jv->jev_number = $cells[7];

                    //         if ($jv->save(false)) {
                    //             $jev_entries[] = [$jv->id, $uacs->id, $cells[8] ? $cells[8] : 0, $cells[9] ? $cells[9] : 0];

                    //             $temp_data[] = [$jv->id => $jv->reporting_period];
                    //         }
                    //     }
                    //     $id = array_search($reporting_period,$temp_data);

                    //   $jev_entries[] = [$id, $uacs->id, $cells[8] ? $cells[8] : 0, $cells[9] ? $cells[9] : 0];

                    // }

                }
            }


            $column = [
                'jev_preparation_id',
                'chart_of_account_id',
                'debit',
                'credit',
                'current_noncurrent',
                'closing_nonclosing',
                'cash_flow_transaction',
            ];
            $jev_column = [
                'id',
                'fund_cluster_code_id',
                'reporting_period',
                'date',
                'explaination',
                'ref_number',
                'jev_number'
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('jev_accounting_entries', $column, $jev_entries)->execute();
            $qwe = Yii::$app->db->createCommand()->batchInsert('jev_preparation', $jev_column, $temp_data)->execute();

            // echo '<pre>';
            // var_dump($jev_entries);
            // echo '</pre>';
            // echo '<pre>';
            // var_dump($entry2, $no_jev_number);
            // echo '</pre>';
            // unset($rows[0]);
            // unset($rows[1]);
            // echo json_encode(['results' => $major]);
            // return "qwe";
        }
    }


    public function actionGeneralJournal()
    {


        if (!empty($_POST)) {
            $fund = $_POST['fund'] ? $_POST['fund'] : '';
            $reporting_period = $_POST['reporting_period'] ? "{$_POST['reporting_period']}" : '';
            $journal = JevPreparation::find()
                ->joinWith(['jevAccountingEntries', 'jevAccountingEntries.chartOfAccount']);

            if (!empty($fund)) {

                $journal->andwhere("fund_cluster_code_id  = :fund_cluster_code_id", [
                    'fund_cluster_code_id' => $fund
                ]);
            }
            if (!empty($reporting_period)) {

                $journal->andwhere("jev_preparation.reporting_period  = :reporting_period", [
                    'reporting_period' => $reporting_period
                ]);
            }
            // echo '<pre>';
            // var_dump($reporting_period);
            // echo '</pre>';


            $x = $journal->all();
            $fund_cluster_code = '';
            if (!empty($fund)) {
                $fund_cluster_code = $this->getFundClusterCode($fund);
            }
            // echo '<pre>';
            // var_dump($fund);
            // echo '</pre>';
            return $this->render(
                'general_journal',
                [
                    'journal' => $x,
                    'fund_cluster_code' => $fund_cluster_code,
                    'reporting_period' => $reporting_period
                ]
            );
        } else {
            return $this->render(
                'general_journal',
                [
                    'journal' => ''
                ]
            );
        }
    }
    public function actionAdadjFilter()
    {
        return $this->render('adadj_filter', []);
    }
    public function actionAdadj()
    {

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'] ? "{$_POST['reporting_period']}" : '';
            $fund = $_POST['fund'];

            $data = JevPreparation::find()
                ->joinWith(['jevAccountingEntries' => function ($query) {
                    $query->joinWith('chartOfAccount')
                        ->orderBy('chart_of_accounts.uacs');
                }])
                ->joinWith('fundClusterCode')
                ->where("jev_preparation.jev_number like :jev_number", [
                    'jev_number' => 'ADADJ%'
                ]);
            if (!empty($reporting_period)) {
                $data->andWhere("jev_preparation.reporting_period =:reporting_period", [
                    'reporting_period' => $reporting_period
                ]);
            }
            if (!empty($fund)) {
                $data->andWhere("jev_preparation.fund_cluster_code_id = :fund_cluster_code_id", [
                    'fund_cluster_code_id' => $fund
                ]);
            }
            // ->andWhere($sa)

            $x = $data->orderBy('id')
                ->all();

            $credit = $this->creditDebit('credit', $fund, $reporting_period, 'ADADJ');
            $debit = $this->creditDebit('debit', $fund, $reporting_period, 'ADADJ');

            //     $credit = Yii::$app->db->createCommand("
            // SELECT DISTINCT chart_of_accounts.uacs,chart_of_accounts.general_ledger
            // from jev_preparation,jev_accounting_entries,chart_of_accounts
            // where jev_preparation.id  = jev_accounting_entries.jev_preparation_id
            // and jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            // AND jev_preparation.reporting_period = '$reporting_period'
            // AND jev_accounting_entries.credit>0
            //  ORDER BY chart_of_accounts.uacs

            // ")->queryAll();

            //     $debit = Yii::$app->db->createCommand("
            // SELECT DISTINCT chart_of_accounts.uacs,chart_of_accounts.general_ledger,jev_preparation.reporting_period
            // from jev_preparation,jev_accounting_entries,chart_of_accounts
            // where jev_preparation.id  = jev_accounting_entries.jev_preparation_id
            // and jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
            // AND jev_preparation.reporting_period = '$reporting_period'
            // AND jev_accounting_entries.debit>0
            //  ORDER BY chart_of_accounts.uacs
            // ")->queryAll();

            // echo '<pre>';   
            // var_dump($x[0]['reporting_period']);
            // echo '</pre>';
            $title = "ADVICE TO DEBIT ACCOUNT DISBURSEMENT JOURNAL";
            if ($_POST['export'] > 0) {
                $this->ExcelExport($x, $credit, $debit, $reporting_period, $fund, $title);
            }
            return $this->render('adadj_view', [
                'data' => $x,
                'credit' => $credit,
                'debit' => $debit,
            ]);
        } else {
            return $this->render('adadj_view', []);
        }
    }
    public function actionCkdj()
    {

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'] ? "{$_POST['reporting_period']}" : '';
            $fund = $_POST['fund'];

            $data = JevPreparation::find()

                ->joinWith([
                    'jevAccountingEntries',

                ])
                ->joinWith(['jevAccountingEntries.chartOfAccount' => function ($query) {
                    $query->orderBy('uacs');
                }])
                ->joinWith('fundClusterCode')
                ->where("jev_preparation.jev_number like :jev_number", [
                    'jev_number' => 'CKDJ%'
                ]);

            if (!empty($reporting_period)) {
                $data->andWhere("jev_preparation.reporting_period =:reporting_period", [
                    'reporting_period' => $reporting_period
                ]);
            }
            if (!empty($fund)) {
                $data->andWhere("jev_preparation.fund_cluster_code_id = :fund_cluster_code_id", [
                    'fund_cluster_code_id' => $fund
                ]);
            }
            // $data->addSelect(['total'=>$query = (new \yii\db\Query())->from('billing')
            // $sum = $query->sum('amount')]);
            $x = $data->orderBy('id')->all();
            $credit = $this->creditDebit('credit', $fund, $reporting_period, 'CKDJ');
            $debit = $this->creditDebit('debit', $fund, $reporting_period, 'CKDJ');


            // echo '<pre>';
            // var_dump($data);
            // echo '</pre>';
            $title = "CHECK DISBURSEMENT JOURNAL";
            if ($_POST['export'] > 0) {
                $this->ExcelExport($x, $credit, $debit, $reporting_period, $fund, $title);
            }
            return $this->render('ckdj_view', [
                'credit' => $credit,
                'debit' => $debit,
                'data' => $x
            ]);
        } else {
            return $this->render('ckdj_view',);
        }
    }

    // PAG KUHA SA MGA  CREDIT/DEBIT HEADER SA ADADJ OG SA CKDJ
    public function creditDebit($type, $fund, $reporting_period, $jev_type)
    {
        $x =  JevPreparation::find()
            ->joinWith(['jevAccountingEntries', 'jevAccountingEntries.chartOfAccount'])
            ->select([
                'chart_of_accounts.id',
                'chart_of_accounts.uacs',
                'chart_of_accounts.general_ledger',
            ])
            ->where("jev_preparation.jev_number like :jev_number", [
                'jev_number' => "$jev_type%"
            ]);

        if (!empty($reporting_period)) {
            $x->andwhere("reporting_period = :reporting_period", [
                'reporting_period' => $reporting_period
            ]);
        }
        if (!empty($fund)) {
            $x->andWhere("jev_preparation.fund_cluster_code_id = :fund_cluster_code_id", [
                'fund_cluster_code_id' => $fund
            ]);
        }
        // ->andWhere("jev_accounting_entries.credit > :credit", [
        //     'credit' => 0
        // ]);


        if ($type == 'credit') {
            $x->andWhere("jev_accounting_entries.credit > :credit", [
                'credit' => 0
            ]);
        } else if ($type == 'debit') {
            $x->andWhere("jev_accounting_entries.debit > :debit", [
                'debit' => 0
            ]);
        }
        $y = $x->orderBy('chart_of_accounts.uacs')->asArray()->all();
        return $y;
    }
    // use PhpOffice\PhpSpreadsheet\Spreadsheet;
    // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    public function ExcelExport($data, $credit, $debit, $reporting_period, $fund, $title)
    {


        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A2', "$title");
        $sheet->setCellValue('A3', "For the Month of $reporting_period");
        $sheet->setCellValue('A4', "Entity Name:");
        $sheet->setCellValue('B4', "DEPARTMENT OF TRADE AND INDUSTRY CARAGA");
        // $sheet->setCellValue('A5', "Fund Cluster:");
        // $sheet->setCellValue('B5', "$fund");
        $sheet->setCellValue('A6', 'DATE');
        $sheet->setCellValue('B6', 'JEV No,');
        $sheet->setCellValue('C6', 'DV No.');
        $sheet->setCellValue('D6', 'LDDAP !');
        $sheet->setCellValue('E6', 'NAME !');
        $sheet->setCellValue('F6', 'PAYEE !');
        $x = 7;
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        // $sheet->getStyle()->applyFromArray($styleArray);

        foreach ($credit as $val) {

            $sheet->setCellValueByColumnAndRow($x, 6,  $val['general_ledger'] . '---' . $val['uacs']);

            // echo "<pre>";
            // var_dump($val['general_ledger']);
            // echo "</pre>";
            $x++;
        }
        // $x++;
        $sheet->setCellValueByColumnAndRow($x, 6,  'TOTAL ');
        $x++;
        foreach ($debit as $val) {

            $sheet->setCellValueByColumnAndRow($x, 6,  $val['general_ledger'] . '---' . $val['uacs']);

            // echo "<pre>";
            // var_dump($val['general_ledger']);
            // echo "</pre>";
            $x++;
        }
        $sheet->setCellValueByColumnAndRow($x, 6,  'TOTAL ');
        $row = 7;
        $col = 1;
        foreach ($data as $d) {
            $sheet->setCellValueByColumnAndRow(1, $row,  $d->reporting_period);
            $total = 0;
            foreach ($d->jevAccountingEntries as $ae) {

                if (!empty($ae->credit)) {
                    $index  = array_search($ae->chartOfAccount->uacs, array_column($credit, 'uacs'));

                    $sheet->setCellValueByColumnAndRow($index + 7, $row,  $ae->credit . '---' . $ae->chartOfAccount->uacs);
                    $total += $ae->credit;
                }
                if (!empty($ae->debit)) {
                    $index  = array_search($ae->chartOfAccount->uacs, array_column($debit, 'uacs'));

                    $sheet->setCellValueByColumnAndRow($index + 7 + count($credit) + 1, $row,  $ae->debit .  '---' . $ae->chartOfAccount->uacs);
                    $total += $ae->debit;


                    // echo "<pre>";
                    // var_dump($ae->chartOfAccount->uacs, $index, $index + 7 + count($credit) + 2);
                    // echo "</pre>";
                }
            }
            // PAG BUTANG OG VALUE SA CREDIT TOTAL
            $sheet->setCellValueByColumnAndRow(7 + count($credit), $row, number_format($total));
            // PAG BUTANG OG VALUE SA DEBIT TOTAL

            $sheet->setCellValueByColumnAndRow(8 + count($credit) + count($debit), $row,  number_format($total));

            $row++;
            $col++;
        }

        $id = uniqid();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("C:\Users\Reynan\Downloads\sample-excel\ckdj_excel_$id.xlsx");
        return "SUCCESS";
    }


    public function actionTrialBalance()
    {

        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];
            $fund = $_POST['fund'] ? $_POST['fund'] : '';
            // $t_balance = Yii::$app->db->createCommand("SELECT jev_preparation.reporting_period,y.total_credit,y.total_debit,
            // chart_of_accounts.uacs,chart_of_accounts.general_ledger
            // FROM jev_preparation,
            // (SELECT jev_accounting_entries.*, SUM(jev_accounting_entries.credit) as total_credit  ,
            // SUM(jev_accounting_entries.debit) as total_debit  
            // FROM jev_accounting_entries
            // GROUP BY jev_accounting_entries.chart_of_account_id) as y,
            // chart_of_accounts
            // where jev_preparation.id = y.jev_preparation_id and y.chart_of_account_id = chart_of_accounts.id
            // AND jev_preparation.reporting_period = '$reporting_period'

            // ")->queryAll();
            // $t_balance = JevAccountingEntries::find()
            //     ->joinWith(["jevPreparation"])
            //     ->select('SUM(jev_accounting_entries.debit)')
            //     ->where("jev_preparation.reporting_period = :reporting_period", [
            //         'reporting_period' =>'2020-07'
            //     ])->groupBy('chart_of_account_id')->all();

            $t_balance = (new \yii\db\Query())
                ->select([
                    'SUM(jev_accounting_entries.credit) as total_credit',
                    'SUM(jev_accounting_entries.debit) as total_debit',
                    'jev_accounting_entries.chart_of_account_id',
                    'chart_of_accounts.uacs', 'chart_of_accounts.general_ledger',
                    'jev_preparation.reporting_period'

                ])
                ->from(['jev_accounting_entries',])
                ->join('LEFT JOIN', 'chart_of_accounts', 'jev_accounting_entries.chart_of_account_id =chart_of_accounts.id ')
                ->join('LEFT JOIN', "jev_preparation", 'jev_accounting_entries.jev_preparation_id=jev_preparation.id  ')


                // ->where("jev_accounting_entries.jev_preparation_id = :jev_preparation_id", [
                //     'jev_preparation_id' => 'jev_accounting_entries.id'
                // ])
                // ->andWhere("jev_accounting_entries.chart_of_account_id = :chart_of_account_id", [
                //     'chart_of_account_id' => 'chart_of_accounts.id'
                // ])
                ->where("jev_preparation.reporting_period = :reporting_period", [
                    'reporting_period' => $reporting_period
                ])
                ->andwhere("jev_preparation.fund_cluster_code_id = :fund_cluster_code_id", [
                    'fund_cluster_code_id' => $fund
                ])

                ->groupBy('chart_of_account_id')
                ->orderBy('chart_of_accounts.uacs')
                // ->limit(10)
                ->all();

            $total_debit = array_sum(array_column($t_balance, 'total_debit'));
            $total_credit = array_sum(array_column($t_balance, 'total_credit'));
            $fund_cluster_code = '';
            if (!empty($fund)) {
                $fund_cluster_code = $this->getFundClusterCode($fund);
            }
            // echo "<pre>";
            // var_dump($total_credit,$total_debit);
            // echo "</pre>";


            return $this->render('trial_balance_view', [
                't_balance' => $t_balance,
                'reporting_period' => date('F Y', strtotime($reporting_period)),
                'debit_total' => $total_debit,
                'credit_total' => $total_credit,
                'fund_cluster_code' => $fund_cluster_code
            ]);
        } else {
            return $this->render('trial_balance_view');
        }
        // $t_balance = JevPreparation::find()
        //     ->joinWith(['jev' => function () {
        //         // $query->joinWith('chartOfAccount')
        //         Yii::$app->db->createCommand("
        //         select jev_accounting_entries.*,
        //         SUM(jev_accounting_entries.credit) as total_credit,
        //         SUM(jev_accounting_entries.debit) as total_debit
        //         from jev_accounting_entries
        //         group by jev_accounting_entries.chart_of_account_id
        //         ")->queryAll();
        //     }])
        //     ->select("jev.total_credit")
        //     ->where("reporting_period = :reporting_period", [
        //         'reporting_period' => '2020-02'
        //     ])->all();
        // $t_balance =Yii::$app->db->createCommand("select jev_preparation.*,y.*
        // from
        // jev_preparation
        // ,
        // (
        //     select jev_accounting_entries.*,
        //         SUM(jev_accounting_entries.credit) as total_credit,
        //         SUM(jev_accounting_entries.debit) as total_debit
        //     from jev_accounting_entries

        //     group by jev_accounting_entries.chart_of_account_id

        // ) as y,chart_of_accounts where jev_preparation.id = y.jev_preparation_id 
        // and y.chart_of_account_id = chart_of_accounts.id 
        // and jev_preparation.reporting_period = "2020-01"
        // ORDER BY chart_of_accounts.uacs");

    }

    public function getFundClusterCode($fund)
    {
        $fund_cluster_code = FundClusterCode::find()->where("id=:id", ['id' => $fund])->one()->name;
        return $fund_cluster_code;
    }

    public function actionSubsidiaryLedger()
    {
        return $this->render('subsidiary_ledger_view');
    }
}
