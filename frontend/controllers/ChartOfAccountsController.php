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
use yii\db\Query;
use yii\filters\AccessControl;
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
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                    'import',
                    'create-sub-account',
                    'get-all-account',
                    'sample-index',
                    'get-general-ledger',
                    'chart-of-accounts',
                    'accounting-codes',
                    'accounting-codes-dv',
                    'search-chart-of-accounts',
                    'search-accounting-code',
                    'get-chart-info',

                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'import',
                            'create-sub-account',
                            'get-all-account',
                            'sample-index',
                            'get-general-ledger',
                            'chart-of-accounts',


                        ],
                        'allow' => true,
                        'roles' => ['accounting', 'super-user']
                    ],
                    [
                        'actions' => [

                            'get-all-account',
                            'chart-of-accounts',
                            'accounting-codes',
                            'accounting-codes-dv',
                            'search-chart-of-accounts',
                            'search-accounting-code',
                            'get-chart-info',

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

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save(false)) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
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
            $reporting_period = !empty($_POST['reporting_period']) ? $_POST['reporting_period'] : null;
            $chart_uacs = ChartOfAccounts::find()
                ->where("id = :id", ['id' => $id])->one()->uacs;
            $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;
            $uacs = $chart_uacs . '_';
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }


            $model->chart_of_account_id = $id;
            $model->object_code = $uacs . $last_id;
            $model->name = $account_title;
            $model->reporting_period = $reporting_period;
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->redirect('?r=sub-accounts1/view&id=' . $model->id);
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
    public function actionGetGeneralLedger($id)
    {
        // 	Personnel Services, Maintenance and Other Operating Expenses ,Capital Outlays
        $res = Yii::$app->db->createCommand('SELECT 
        chart_of_accounts.id,uacs as object_code, 
        general_ledger as title 
        FROM chart_of_accounts,major_accounts
        WHERE chart_of_accounts.major_account_id = major_accounts.id
        AND major_accounts.name IN ("Personnel Services","Maintenance and Other Operating Expenses","Capital Outlays")
        AND chart_of_accounts.is_active = 1
        ')->queryAll();

        $params = [];
        if (!empty($id)) {

            $query1 = Yii::$app->db->createCommand("SELECT chart_of_account_id FROM record_allotment_entries WHERE record_allotment_id =:id")
                ->bindValue(':id', $id)
                ->queryAll();
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'chart_of_accounts.id', $query1], $params);
        }

        $query2 = (new \yii\db\Query())
            ->select('chart_of_accounts.id,
            chart_of_accounts.uacs  object_code, 
            chart_of_accounts.general_ledger  title ')
            ->from('chart_of_accounts')
            ->join('LEFT JOIN', 'major_accounts', 'chart_of_accounts.major_account_id = major_accounts.id')
            ->where('major_accounts.name IN ("Personnel Services","Maintenance and Other Operating Expenses","Capital Outlays")');
        return json_encode($query2->all());
    }
    public function actionChartOfAccounts()
    {
        $res = Yii::$app->db->createCommand('SELECT chart_of_accounts.id,uacs,
         general_ledger FROM chart_of_accounts 
        
        ')->queryAll();
        return json_encode($res);
    }
    public function actionAccountingCodes($id)
    {

        $params = [];
        if (!empty($id)) {
            $query1 = Yii::$app->db->createCommand("SELECT object_code FROM jev_accounting_entries WHERE jev_preparation_id =:id")
                ->bindValue(':id', $id)
                ->queryAll();
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'object_code', $query1], $params);
        }


        $query2 = (new \yii\db\Query())
            ->select('object_code,account_title')
            ->from('accounting_codes')
            ->where('is_active =1 AND coa_is_active = 1 AND sub_account_is_active = 1');
        if (!empty($query1)) {
            $query2->orWhere("$sql", $params);
        }
        // $res = Yii::$app->db->createCommand('SELECT object_code, account_title FROM accounting_codes 

        // ')->queryAll();
        return json_encode($query2->all());
    }
    public function actionAccountingCodesDv($id)
    {
        $params = [];
        if (!empty($id)) {

            $query1 = Yii::$app->db->createCommand("SELECT object_code FROM dv_accounting_entries WHERE dv_aucs_id =:id")
                ->bindValue(':id', $id)
                ->queryAll();
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'object_code', $query1], $params);
        }

        $query2 = (new \yii\db\Query())
            ->select("object_code ,
             account_title")
            ->from('accounting_codes')
            ->where('is_active =1 AND coa_is_active = 1 AND sub_account_is_active = 1');
        if (!empty($query1)) {
            $query2->orWhere("$sql", $params);
        }
        // $res = Yii::$app->db->createCommand('SELECT object_code, account_title FROM accounting_codes 

        // ')->queryAll();

        return json_encode($query2->all());
    }
    public function actionSearchChartOfAccounts($q = null, $id = null, $page = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(["id as id, CONCAT (uacs ,'-',general_ledger) as text"])
                ->from('chart_of_accounts')
                ->where(['like', 'general_ledger', $q])
                ->orWhere(['like', 'uacs', $q])
                ->andWhere('is_active = 1');
            if (!empty($page)) {

                $query->offset($offset)
                    ->limit($limit);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            if (!empty($page)) {
                $out['pagination'] = ['more' => !empty($data) ? true : false];
            }
        }
        //  elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => AdvancesEntries::find($id)->fund_source];
        // }
        return $out;
    }
    public function actionSearchGeneralLedger($q = null, $id = null, $base_uacs = null, $page = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(["uacs as id, CONCAT (uacs ,'-',general_ledger) as text"])
                ->from('chart_of_accounts')
                ->where(['like', 'general_ledger', $q])
                ->orWhere(['like', 'uacs', $q])
                ->andWhere('is_active = 1');
            if (!empty($base_uacs)) {
                $query->andWhere('major_object_code = :base_uacs', ['base_uacs' => $base_uacs]);
            }
            if (!empty($page)) {

                $query->offset($offset)
                    ->limit($limit);
            }


            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            if (!empty($page)) {
                $out['pagination'] = ['more' => !empty($data) ? true : false];
            }
        }



        return $out;
    }
    public function actionSearchAllotmentGeneralLedger($q = null, $id = null, $base_uacs = null, $page = 1)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(["chart_of_accounts.id, CONCAT (chart_of_accounts.uacs ,'-',chart_of_accounts.general_ledger) as text"])
                ->from('chart_of_accounts')
                ->join('LEFT JOIN', 'major_accounts', 'chart_of_accounts.major_account_id = major_accounts.id')
                ->where(['like', 'chart_of_accounts.general_ledger', $q])
                ->orWhere(['like', 'chart_of_accounts.uacs', $q])
                ->andWhere('chart_of_accounts.is_active = 1');
            if (!empty($base_uacs)) {
                $query->andWhere('major_accounts.object_code = :base_uacs', ['base_uacs' => $base_uacs]);
            }
            $query->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }

        return $out;
    }
    public function actionSearchLiquidationAccountingCode($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select(["object_code as id, CONCAT (object_code ,'-',account_title) as text"])
                ->from('accounting_codes')
                ->where(['or', ['like', 'account_title', $q], ['like', 'object_code', $q]])
                ->andWhere('is_active =1 AND coa_is_active = 1 AND sub_account_is_active = 1 AND is_province_visible = 1');

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif (!empty($id)) {

            $query = Yii::$app->db->createCommand("SELECT object_code , CONCAT (object_code ,'-',account_title) as account_title 
            FROM accounting_codes WHERE object_code  = :object_code")
                ->bindValue(':object_code', $id)
                ->queryOne();

            return json_encode($query);
        }
        return $out;
    }
    public function actionSearchAccountingCode($q = null, $id = null, $base_uacs = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            // $query->select('advances_entries.id, advances_entries.fund_source AS text')
            //     ->from('advances_entries')
            //     ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
            //     ->where(['like', 'advances_entries.fund_source', $q])
            //     ->andWhere('advances_entries.is_deleted !=1');
            $query->select(["object_code as id, CONCAT (object_code ,'-',account_title) as text"])
                ->from('accounting_codes')
                ->where(['like', 'account_title', $q])
                ->orWhere(['like', 'object_code', $q])
                ->andWhere('is_active =1 AND coa_is_active = 1 AND sub_account_is_active = 1');

            if (!empty($base_uacs)) {
                $query->andWhere('major_object_code = :base_uacs', ['base_uacs' => $base_uacs]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif (!empty($id)) {

            $query = Yii::$app->db->createCommand("SELECT object_code , CONCAT (object_code ,'-',account_title) as account_title 
            FROM accounting_codes WHERE object_code  = :object_code")
                ->bindValue(':object_code', $id)
                ->queryOne();

            return json_encode($query);
        }
        return $out;
    }
    public function actionGetChartInfo()
    {
        if ($_POST) {

            $query = Yii::$app->db->createCommand("SELECT id,CONCAT(uacs,'-',general_ledger) as chart_account FROM chart_of_accounts WHERE uacs = :object_code")
                ->bindValue(':object_code', $_POST['object_code'])
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionSearchSubAccount($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select(["object_code as id, CONCAT (object_code ,'-',account_title) as text"])
                ->from('sub_accounts_view')
                ->where(['like', 'account_title', $q])
                ->orWhere(['like', 'object_code', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        //  elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => AdvancesEntries::find($id)->fund_source];
        // }
        return $out;
    }
}
