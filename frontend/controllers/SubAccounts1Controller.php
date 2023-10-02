<?php

namespace frontend\controllers;

use app\models\ChartOfAccounts;
use Yii;
use app\models\SubAccounts1;
use app\models\SubAccounts1Search;
use app\models\SubAccounts2;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\validators\Validator;

/**
 * SubAccounts1Controller implements the CRUD actions for SubAccounts1 model.
 */
class SubAccounts1Controller extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                    'create-sub-account',
                    'import',

                    'get-all-sub-account1',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'view',
                            'create',
                            'create-sub-account',
                            'import',
                            'get-all-sub-account1',
                        ],
                        'allow' => true,
                        'roles' => ['sub_account_1']
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
     * Lists all SubAccounts1 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubAccounts1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubAccounts1 model.
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
     * Creates a new SubAccounts1 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($chartOfAccountId = '')
    {
        $model = new SubAccounts1();
        $model->chart_of_account_id = $chartOfAccountId;

        if ($model->load(Yii::$app->request->post())) {
            $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;
            $uacs = ChartOfAccounts::findOne($model->chart_of_account_id)->uacs;
            $zero = strlen($last_id) < 5 ? str_repeat('0',  5 - strlen($last_id)) : '';
            $model->object_code = $uacs . '_' . $zero . $last_id;

            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SubAccounts1 model.
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
     * Deletes an existing SubAccounts1 model.
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
     * Finds the SubAccounts1 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubAccounts1 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubAccounts1::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionCreateSubAccount()
    {

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {

        // }
        if ($_POST) {
            $model = new SubAccounts2();
            $account_title = $_POST['account_title'];
            $id = $_POST['id'];

            $sub_acc1_ojc_code = SubAccounts1::find()
                ->where("id = :id", ['id' => $id])->one()->object_code;
            $last_id = SubAccounts2::find()->orderBy('id DESC')->one()->id + 1;

            $uacs = $sub_acc1_ojc_code . '_';
            // echo $uacs;
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }
            $model->sub_accounts1_id = $id;
            $model->object_code = $uacs . $last_id;
            $model->name = $account_title;
            if ($model->validate()) {

                if ($model->save()) {
                    return $this->redirect('?r=sub-accounts2/view&id=' . $model->id);
                    return json_encode('success');
                }
            } else {
                $errors = $model->errors;
                return json_encode($errors);
            }
            // return $this->redirect(['view', 'id' => $model->id]);
        }
    }


    public function actionImport()

    {
        if (!empty($_POST)) {
            // $chart_id = $_POST['chart_id'];
            $name = $_FILES["file"]["name"];
            $id = uniqid();
            $file = "sub_account1/{$id}_{$name}";;
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
            $x = SubAccounts1::find()->orderBy('id DESC')->one();
            if (!empty($x)) {
                $last_id = $x->id + 1;
            } else {
                $last_id = 1;
            }

            // 

            $uacs_storage = [];
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    $cells[] =   $cell->getValue();
                }
                $obj_code = $cells[0];
                $name = $cells[1];
                if (!empty($cells[0])) {

                    // if (in_array($obj_code, $uacs_storage, true)) {

                    $chart_of_account = ChartOfAccounts::find()
                        ->where("uacs = :uacs", [
                            'uacs' => $obj_code
                        ])
                        ->one();
                    $uacs = $chart_of_account->uacs . '_';
                    for ($i = strlen($last_id); $i <= 4; $i++) {
                        $uacs .= 0;
                    }
                    $object_code = '';
                    $object_code = $uacs . $last_id;
                    // } else {
                    //     $uacs_storage[] = $obj_code;
                    // }


                    $data[] = [
                        'chart_of_account_id' => $chart_of_account->id,
                        'object_code' => $object_code,
                        'name' => $name
                    ];
                    $last_id++;
                }
            }

            $column = [
                'chart_of_account_id',
                'object_code',
                'name',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('sub_accounts1', $column, $data)->execute();

            return $this->redirect(['index']);
        }
    }

    public function actionGetAllSubAccount1()
    {
        $res = (new \yii\db\Query())
            ->select("*")
            ->from('sub_accounts1')
            ->all();
        return json_encode($res);
    }
}
