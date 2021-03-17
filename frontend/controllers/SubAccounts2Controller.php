<?php

namespace frontend\controllers;

use app\models\SubAccounts1;
use Yii;
use app\models\SubAccounts2;
use app\models\SubAccounts2Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubAccounts2Controller implements the CRUD actions for SubAccounts2 model.
 */
class SubAccounts2Controller extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SubAccounts2 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubAccounts2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SubAccounts2 model.
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
     * Creates a new SubAccounts2 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SubAccounts2();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SubAccounts2 model.
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
     * Deletes an existing SubAccounts2 model.
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
     * Finds the SubAccounts2 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubAccounts2 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubAccounts2::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionImport()

    {
        if (!empty($_POST)) {
            $sub_account1 = $_POST['sub_account1'];
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
            $last_id = 0;
            $sub_account1_object_code = SubAccounts1::find()->where("id = :id", ['id' => $sub_account1])->one()->object_code;
            $x = SubAccounts2::find()->orderBy('id DESC')->one();
            if (!empty($x)) {
                $last_id = $x->id + 1;
            } else {
                $last_id = 1;
            }
            $uacs = $sub_account1_object_code . '_';
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }
            foreach ($worksheet->getRowIterator() as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    $cells[] =   $cell->getValue();
                }
                $object_code = '';
                $object_code = $uacs . $last_id;
                if (!empty($cells[0])) {
                    $data[] = [
                        'sub_accounts1_id' => $sub_account1,
                        'object_code' => $object_code,
                        'name' => $cells[0]
                    ];
                }
                $last_id++;
            }
            $column = [
                'sub_accounts1_id',
                'object_code',
                'name',
            ];
            $ja = Yii::$app->db->createCommand()->batchInsert('sub_accounts2', $column, $data)->execute();
            // echo '<pre>';
            // var_dump('success');
            // echo '</pre>';
            return $this->redirect(['index']);
        }
    }
}
