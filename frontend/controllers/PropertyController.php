<?php

namespace frontend\controllers;

use Yii;
use app\models\Property;
use app\models\PropertySearch;
use DateTime;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertyController implements the CRUD actions for Property model.
 */
class PropertyController extends Controller
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
                    'search-property',
                    'get-property',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'search-property',
                            'get-property',

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
     * Lists all Property models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Property model.
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
     * Creates a new Property model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Property();

        if ($model->load(Yii::$app->request->post())) {
            $model->property_number = $this->getPropertyNumber();

            if ($model->validate()) {
                if ($model->save(false)) {
                }
            } else {
                return json_encode($model->errors);
            }

            return $this->redirect(['view', 'id' => $model->property_number]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Property model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->property_number]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Property model.
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
     * Finds the Property model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Property the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Property::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getPropertyNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT
        SUBSTRING_INDEX(property.property_number,'-',-1) as p_number
        FROM property
        ORDER BY  p_number DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = 'DTI-XIII-' . $new_num;
        return $string;
    }
    public function actionSearchProperty($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('property.property_number as id, property.property_number AS text')
                ->from('property')
                ->where(['like', 'property.property_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        // elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        // }
        return $out;
    }
    public function actionGetProperty()
    {
        if ($_POST) {
            $query = Yii::$app->db->createCommand("SELECT
            CONCAT(employee.f_name,' ',employee.l_name) as disbursing_officer,
           
            DATE_FORMAT( property.date, '%M %d, %Y')  as `date`,
            property.article,
            property.iar_number,
            property.acquisition_amount,
            property.model,
            property.property_number,
            property.quantity,
            property.serial_number,
            books.`name` as book,
            unit_of_measure.unit_of_measure
            FROM property
            LEFT JOIN books ON property.book_id = books.id
            LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
            LEFT JOIN employee ON property.employee_id = employee.employee_id
            WHERE property.property_number = :property_number")
                ->bindValue(':property_number', $_POST['id'])
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionImport()
    {
        if (!empty($_POST)) {
            $name = $_FILES["file"]["name"];

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
            $excel->setActiveSheetIndexByName('Property');
            $worksheet = $excel->getActiveSheet();

            $data = [];

            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if (

                        $y === 0 ||
                        $y === 1 ||
                        $y === 2
                        || $y === 3
                        || $y === 4
                        || $y === 5
                        || $y === 6
                        || $y === 7
                        || $y === 8
                    ) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {
                    $d = new DateTime($cells[0]);
                    $date =  $d->format('Y-m-d');
                    $book_id =  $cells[1];
                    $unit_measure = $cells[2];
                    $serial = $cells[3];
                    $model = $cells[4];
                    $iar = $cells[5];
                    $quantity = $cells[6];
                    $article = $cells[7];
                    $amount =  $cells[8];
                    // return $book_id;


                    $p = new Property();
                    $p->property_number = $this->getPropertyNumber();
                    $p->book_id = $book_id;
                    $p->unit_of_measure_id = $unit_measure;
                    $p->employee_id = 'ro-1';
                    $p->iar_number = $iar;
                    $p->article = $article;
                    $p->model = $model;
                    $p->serial_number = $serial;
                    $p->quantity = $quantity;
                    $p->acquisition_amount = $amount;
                    $p->date = $date;
                    if ($p->save(false)) {
                    }
                    $data[] = [
                        $date,
                        $book_id,
                        $unit_measure,
                        $serial,
                        $model,
                        $quantity,
                        $article,
                        $amount,

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
                'ada_number',
            ];
            // $ja = Yii::$app->db->createCommand()->batchInsert('cash_disbursement', $column, $data)->execute();

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
