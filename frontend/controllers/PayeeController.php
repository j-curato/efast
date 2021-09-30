<?php

namespace frontend\controllers;

use Yii;
use app\models\Payee;
use app\models\PayeeSearch;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\db\conditions\LikeCondition;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PayeeController implements the CRUD actions for Payee model.
 */
class PayeeController extends Controller
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
                    'create',
                    'delete',
                    'update',
                    'view',
                    'get-payee',
                    'import',
                ],
                'rules' => [
                    [

                        'actions' => [
                            'index',
                            'create',
                            'delete',
                            'update',
                            'view',
                            'import',
                        ],
                        'allow' => true,
                        'roles' => ['accounting']
                    ],
                    [

                        'actions' => [

                            'get-payee',
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
     * Lists all Payee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payee model.
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
     * Creates a new Payee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payee model.
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
     * Deletes an existing Payee model.
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
     * Finds the Payee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImport()
    {
        if (!empty($_POST)) {

            $name = $_FILES["file"]["name"];

            $id = uniqid();
            $file_name = "payee/{$id}_{$name}";

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_name)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }

            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $file = $reader->load($file_name);
            $sheetName = $file->getSheetNames();
            $file->setActiveSheetIndexByName('CKDJ');
            $worksheet = $file->getActiveSheet();
            $hRow = $worksheet->getHighestDataRow();

            $payee_data = [];

            foreach ($worksheet->getRowIterator(14) as $key => $row) {

                $cellIterator = $row->getCellIterator('G', 'G');
                $cellIterator->setIterateOnlyExistingCells(true);
                $rowData = [];

                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }
                // $payee = Payee::find()->where("account_name = :account_name", [
                //     'account_name' => $rowData[6]
                // ]);
                // if (!empty($payee)) {
                //     $payee_data[] = $rowData[6];
                // }
                if (!empty($rowData[0])) {

                    $payee_exist = Payee::find()->where("account_name= :account_name", [
                        'account_name' => $rowData[0]
                    ])->one();
                    if (empty($payee_exist)) {

                        $payee_data[] = $rowData;
                    }
                }

                // if ($key == 30) {
                //     break;
                // }
            }
            $array = array_values(array_unique($payee_data, SORT_REGULAR));
            Yii::$app->db->createCommand()->batchInsert('payee', ['account_name'], $array)->execute();
            // echo "<pre>";
            // var_dump($payee_data);
            // echo "</pre>";
            // ob_start();

            // echo "<pre>";
            // var_dump($payee_data);
            // echo "</pre>";
            // return ob_get_clean();
        }
    }
    public function actionGetPayee()
    {
        $p = Yii::$app->db->createCommand("SELECT * FROM payee WHERE isEnable=1")->queryAll();
        return json_encode($p);
    }
    public function getPayee()
    {
        $p = Yii::$app->db->createCommand("SELECT * FROM payee WHERE isEnable=1")->queryAll();
        return json_encode($p);
    }
    public function actionSearchPayee($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Payee::findOne($id)->account_name];
        }
       else if (!is_null($q)) {
            $query = new Query();
            $query->select('payee.id, payee.account_name AS text')
                ->from('payee')
                ->where(['like', 'payee.account_name', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } 
        return $out;
    }
}
