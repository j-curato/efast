<?php

namespace frontend\controllers;

use Yii;
use app\models\Employee;
use app\models\EmployeeSearch;
use app\models\EmployeeSearchView;
use ErrorException;
use frontend\models\SignupForm;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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
                    'search-employee',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',

                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [

                            'search-employee',

                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],


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
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param string $id
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
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();

        if ($model->load(Yii::$app->request->post())) {

            $model->employee_id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->employee_id]);
            }
            // $sign_up = new SignupForm();

            // $sign_up->username = explode(' ', $model->f_name)[0] . '.' . $model->l_name;
            // $sign_up->email = 'email3@email.com';
            // $sign_up->password = 'abcde54321';
            // $sign_up->province = 'ro';
            // $sign_up->employee_id = $model->employee_id;
            // try {
            //     if ($sign_up->signup(false)) {
            //         return 'true';
            //     }
            //     else{
            //         return json_encode($sign_up->errors);
            //     }
            // } catch (ErrorException $e) {
            //     return $e->getMessage();
            // }



        } else {

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->employee_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchEmployee($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        // $user_province = strtolower(Yii::$app->user->identity->province);
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('employee_id as id, UPPER(employee_name) AS text,employee_search_view.position ')
                ->from('employee_search_view')
                ->where(['like', 'employee_name', $q])
                ->orwhere(['like', 'employee_id', $q]);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            // return $id;
            $emp = EmployeeSearchView::find()->where('employee_id = :id', ['id' => $id])->one();
            $out['results'] = ['id' => $id, 'text' => $emp->employee_name, 'position' => $emp->position];
        }
        return $out;
    }
    public function actionImport()

    {
        if (Yii::$app->user->can('import-jev')) {

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
                $excel->setActiveSheetIndexByName('Employee');
                $worksheet = $excel->getActiveSheet();
                $reader->setReadDataOnly(FALSE);
                // print_r($excel->getSheetNames());
                $rows = [];

                $transaction = Yii::$app->db->beginTransaction();
                foreach ($worksheet->getRowIterator(2) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    $y = 0;
                    // if ($key > 2) {
                    foreach ($cellIterator as $x => $cell) {
                        $cells[] = $cell->getValue();
                    }

                    $employee_number = $cells[0];
                    $l_name = $cells[1];
                    $f_name = $cells[2];
                    $m_name = $cells[3];
                    $suffix = $cells[4];
                    $position = $cells[5];
                    $status = $cells[6];
                    $office = $cells[7];


                    $employee = new Employee();
                    $employee->employee_id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                    $employee->f_name = $f_name;
                    $employee->l_name = $l_name;
                    $employee->m_name = $m_name;
                    $employee->status = $status;
                    $employee->position = $position;
                    $employee->office = $office;
                    $employee->employee_number = $employee_number;
                    $employee->suffix = $suffix;
                    if ($employee->save(false)) {
                    }
                }


                $transaction->commit();

                // ob_clean();
                echo '<pre>';
                var_dump("Success");
                echo '</pre>';
                // return ob_get_clean();
                // foreach ($jev_entries as $x => $val) {
                //     if ($x > 420) {
                //         echo '<pre>';
                //         var_dump($val);
                //         echo '</pre>';
                //     }
                // }
                // unlink($file . '.xlsx');

            }
        } else {
            throw new ForbiddenHttpException();
        }
    }
}
