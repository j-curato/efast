<?php

namespace frontend\controllers;

use Yii;
use app\models\ChartOfAccounts;
use app\models\ChartOfAccountsSearch;
use app\models\MajorAccounts;
use app\models\SubAccounts1;
use app\models\SubMajorAccounts;
use app\models\SubMajorAccounts2;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChartOfAccountsController implements the CRUD actions for ChartOfAccounts model.
 */
class ChartOfAccountsController extends Controller
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
     * Lists all ChartOfAccounts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChartOfAccountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ChartOfAccounts model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new ChartOfAccountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ChartOfAccounts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ChartOfAccounts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ChartOfAccounts model.
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
     * Deletes an existing ChartOfAccounts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the ChartOfAccounts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChartOfAccounts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChartOfAccounts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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
            // $excel->setActiveSheetIndexByName('Chart of Accounts - Final');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];

            foreach ($worksheet->getRowIterator() as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                if ($key > 2) {
                    foreach ($cellIterator as $x => $cell) {
                        $q = '';
                        $cells[] =   $cell->getValue();
                    }


                    if (!empty($cells[1])) {
                        $uacs = ChartOfAccounts::find()->where("uacs = :uacs", [
                            'uacs' => $cells[1]
                        ])->one();
                        if (empty($uacs)) {
                            //MAJOR ACOUNT INSERT IF DLI MA KITA
                            $major = MajorAccounts::find()->where("object_code = :object_code", [
                                'object_code' => $cells[6]
                            ])->one();
                            if (empty($major)) {
                                try {
                                    $maj = new MajorAccounts();
                                    $maj->object_code = $cells[6];
                                    $maj->name = $cells[7];
                                    if ($maj->save(false)) {
                                        $major = $maj;
                                    }
                                } catch (Exception $e) {
                                    echo '<pre>';
                                    var_dump($e);
                                    echo '</pre>';
                                }
                            }





                            // SUB MAJOR FIND
                            $sub_major = SubMajorAccounts::find()->where("object_code=:object_code", [
                                'object_code' => $cells[8]
                            ])->one();
                            if (empty($sub_major)) {

                                // echo '<pre>';
                                // var_dump($cells[15]);
                                // echo '</pre>';

                                try {
                                    $sub_maj = new SubMajorAccounts();
                                    $sub_maj->object_code = $cells[8];
                                    $sub_maj->name = $cells[9];


                                    if ($sub_maj->save(false)) {
                                        $sub_major = $sub_maj;
                                    }
                                } catch (Exception $e) {
                                    echo '<pre>';
                                    var_dump($e);
                                    echo '</pre>';
                                }
                            }
                            // SUB MAJOR 2
                            $sub_major2 = SubMajorAccounts2::find()->where("object_code = :object_code", [
                                'object_code' => $cells[10]
                            ])->one();
                            if (empty($sub_major2)) {
                                try {
                                    $sub_maj2 = new SubMajorAccounts();
                                    $sub_maj2->object_code = $cells[10];
                                    $sub_maj2->name = $cells[11];


                                    if ($sub_maj2->save(false)) {
                                        $sub_major2 = $sub_maj2;
                                    }
                                } catch (Exception $e) {
                                    echo '<pre>';
                                    var_dump($e);
                                    echo '</pre>';
                                }
                            }

                            // CHART OF ACCOUNTS 
                            $chart = [];
                            // try {
                            //     $coa = new ChartOfAccounts();
                            //     $coa->uacs = $cells[1];
                            //     $coa->general_ledger = $cells[2];
                            //     $coa->major_account_id = $major->id;
                            //     $coa->sub_major_account = $sub_major->id;
                            //     $coa->sub_major_account_2_id = $sub_major2->id;
                            //     $coa->account_group = $cells[4];
                            //     $coa->current_noncurrent = $cells[5];
                            //     $coa->enable_disable = 1;
                            //     $coa->normal_balance = $cells[12];
                            //     if ($coa->save(false)) {
                            //         $uacs = $coa;
                            //     }
                            // } catch (Exception $e) {
                            //     echo $e;
                            // }
                            // echo '<pre>';
                            // var_dump($sub_major2->id);
                            // echo '</pre>';
                            $data[] = [
                                $cells[1],
                                $cells[2],
                                $major->id,
                                $sub_major->id,
                                $sub_major2->id,
                                $cells[4], //account_group
                                $cells[5], //current_noncurrent
                                'enable',
                                $cells[12], //normal_balance
                                1, //isActive

                            ];
                        }
                    }
                }
            }
            $column = [
                'uacs',
                'general_ledger',
                'major_account_id',
                'sub_major_account',
                'sub_major_account_2_id',
                'account_group',
                'current_noncurrent',
                'enable_disable',
                'normal_balance',
                'is_active',

            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('chart_of_accounts', $column, $data)->execute();
            echo '<pre>';
            var_dump('data');
            echo '</pre>';
        }
    }

    public function subAccountUacs()
    {
    }
    public function actionCreateSubAccount()
    {

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {

        // }
        if ($_POST) {
            $model = new SubAccounts1();
            $account_title = $_POST['account_title'];
            $id = $_POST['id'];

            $chart_uacs = ChartOfAccounts::find()
                ->where("id = :id", ['id' => $id])->one()->uacs;
            $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;

            $uacs = $chart_uacs . '_';
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }
            // if ($account_title) {


            $model->chart_of_account_id = $id;
            $model->object_code = $uacs . $last_id;
            $model->name = $account_title;
            if ($model->validate()) {
                if ($model->save()) {
                    return 'success';
                }
            } else {
                // validation failed: $errors is an array containing error messages
                $errors = $model->errors;
                return json_encode($errors);
            }

            // }
            // return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionGetAllAccount()
    {
        $res = Yii::$app->db->createCommand('SELECT id,uacs as object_code, general_ledger as title FROM chart_of_accounts')->queryAll();
        $sub1 = (new \yii\db\Query())->select(['id', 'object_code', 'name as title'])->from('sub_accounts1')->all();
        $sub2 = (new \yii\db\Query())->select(['id', 'object_code', 'name as title'])->from('sub_accounts2')->all();
        $x = [];

        foreach ($res as $val) {
            $val['lvl'] = 1;
            $x[] = $val;
        }
        foreach ($sub1 as $val) {
            $val['lvl'] = 2;
            $x[] = $val;
        }
        foreach ($sub2 as $val) {
            $val['lvl'] = 3;
            $x[] = $val;
        }
        // $x= $res->push($sub1);
        // echo "<pre>";
        // var_dump($x);
        // echo "</pre>";
        return json_encode($x);
    }
    public function actionSampleIndex()
    {

        $searchModel = new ChartOfAccountsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('sample-index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionGetGeneralLedger()
    {
        // 	Personnel Services, Maintenance and Other Operating Expenses ,Capital Outlays
        $res = Yii::$app->db->createCommand('SELECT chart_of_accounts.id,uacs as object_code, general_ledger as title 
        FROM chart_of_accounts,major_accounts
        WHERE chart_of_accounts.major_account_id = major_accounts.id
        AND major_accounts.name IN ("Personnel Services","Maintenance and Other Operating Expenses","Capital Outlays")
        ')->queryAll();
        return json_encode($res);
    }
    public function actionChartOfAccounts()
    {
        $res = Yii::$app->db->createCommand('SELECT chart_of_accounts.id,uacs,
         general_ledger FROM chart_of_accounts 
        
        ')->queryAll();
        return json_encode($res);
    }
}
