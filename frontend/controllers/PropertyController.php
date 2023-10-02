<?php

namespace frontend\controllers;

use app\models\DetailedPropertyDatabaseSearch;
use app\models\Office;
use app\models\Par;
use Yii;
use app\models\Property;
use app\models\PropertyArticles;
use app\models\PropertySearch;
use barcode\barcode\BarcodeGenerator;
use DateTime;
use ErrorException;
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
                    'import',
                    'create-blank',
                    'property-database',
                    'blank-sticker',
                    'search-ssf-category',
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
                            'import',
                            'blank-sticker',
                            'search-ssf-category',
                            'create-blank',
                        ],
                        'allow' => true,
                        'roles' => ['property']
                    ],
                    [
                        'actions' => [
                            'property-database',
                        ],
                        'allow' => true,
                        'roles' => ['property_database']
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
        $optionsArray = array(
            'elementId' => 'q', /* div or canvas id*/
            'value' => '127361827178263718', /* value for EAN 13 be careful to set right values for each barcode type */
            'type' => 'code128',/*supported types ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/

        );
        BarcodeGenerator::widget($optionsArray);
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

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
            $model->fk_office_id = $office_id;
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->property_number = $this->getPropertyNumber($model->fk_office_id);
            $model->id = Yii::$app->db->createCommand('SELECT UUID_SHORT() % 9223372036854775807')->queryScalar();
            $model->ppe_year = date('Y');
            $model->article = !empty($model->fk_property_article_id) ? PropertyArticles::findOne($model->fk_property_article_id)->article_name : '';
            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
            }
        }

        return $this->renderAjax('create', [
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
        $oldModel = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->article = !empty($model->fk_property_article_id) ? PropertyArticles::findOne($model->fk_property_article_id)->article_name : '';
            try {
                // var_dump($oldModel->fk_office_id );
                // var_dump($model->fk_office_id );
                // die();
                // return;
                if ($oldModel->fk_office_id != $model->fk_office_id) {

                    $chk_par = Yii::$app->db->createCommand("SELECT * FROM par WHERE par.fk_property_id = :pty_id LIMIT 1")->bindValue(':pty_id', $model->id)->queryScalar();
                    if (!empty($chk_par)) {
                        throw new ErrorException("Cannot Update Office, Property has PAR and PC");
                    }
                    $model->property_number = $this->getPropertyNumber($model->fk_office_id);
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Save Failed');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
            }
        }
        return $this->renderAjax('update', [
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
    private function getPropertyNumber($office_id)
    {

        $year = date('Y');
        $office_name = Office::findOne($office_id)->office_name;

        $query = Yii::$app->db->createCommand(
            "CALL search_property_number(:office_id,:_year)"
        )
            ->bindValue(':office_id', $office_id)
            ->bindValue(':_year',  $year)
            ->queryOne();
        $num = 1;
        if (!empty($query['vcnt_num'])) {
            $num = $query['vcnt_num'];
        } else if (!empty($query['lst_num'])) {
            $num = $query['lst_num'];
        }
        $zero = '';
        $num_len =  5 - strlen($num);
        if ($num_len > 0) {
            $zero = str_repeat(0, $num_len);
        }

        $string = strtoupper($office_name) . '-PPE-' . $zero . $num;
        return $string;
    }
    public function actionSearchProperty($q = null, $id = null, $page = null, $withOPD = false)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('property.id as id, property.property_number AS text')
                ->from('property');
            if ($withOPD) {
                $query->join("JOIN", 'other_property_details', 'property.id = other_property_details.fk_property_id');
            }
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $query->andWhere('property.fk_office_id = :id', ['id' => $user_data->office->id]);
            }
            $query->andWhere(['like', 'property.property_number', $q]);



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
            property.description,
            property.iar_number,
            property.acquisition_amount,
            property.property_number,
            property.quantity,
            property.serial_number,
            books.`name` as book,
            unit_of_measure.unit_of_measure,
            IFNULL(property_articles.article_name,property.article) as article
            FROM property
            LEFT JOIN books ON property.book_id = books.id
            LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
            LEFT JOIN employee ON property.employee_id = employee.employee_id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            WHERE property.id = :id")
                ->bindValue(':id', $_POST['id'])
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
            $excel->setActiveSheetIndexByName('PPE for Norman');
            $worksheet = $excel->getActiveSheet();
            $data = [];
            $transaction = Yii::$app->db->beginTransaction();
            try {

                foreach ($worksheet->getRowIterator(2) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    $y = 0;
                    foreach ($cellIterator as $x => $cell) {


                        if ($x === 'D' || $x === 'O') {
                            $cells[] = $cell->getFormattedValue();
                        } else {
                            $cells[] =   $cell->getValue();
                        }
                        // echo $x;
                    }
                    if (!empty($cells)) {
                        $d = new DateTime($cells[3]);
                        $date_acquired =  $d->format('Y-m-d');
                        $ppe_type =  $cells[1];
                        $ssf_category_number =  $cells[2];
                        $article =  $cells[4];
                        $description =  $cells[5];
                        $serial_number =  $cells[6];
                        $quantity =  $cells[7];
                        $unit_of_measure =  $cells[8];
                        $acquisition_amount =  $cells[9];
                        $province =  $cells[15];
                        $ssf_category_id = null;
                        $unit_of_measure_id = null;

                        $par_number = $cells[12];
                        $old_par_number = $cells[13];
                        // $par_date_format =  new DateTime($cells[14]);
                        $par_date = $cells[14];
                        $par_location = $cells[16];
                        $accountable_officer = $cells[18];
                        $recieve_by_jocos = $cells[20];
                        $par_issued_by = $cells[21];
                        $par_issued_to = $cells[22];
                        $par_remarks = $cells[23];
                        $db = Yii::$app->db;
                        if (!empty($ssf_category_number)) {

                            $ssf_category_id = $db->createCommand("SELECT id FROM ssf_category WHERE ssf_number = :ssf_number ")
                                ->bindValue(':ssf_number', $ssf_category_number)
                                ->queryScalar();
                        }
                        if (!empty($unit_of_measure)) {
                            $unit_of_measure_id = $db->createCommand('SELECT id FROM unit_of_measure WHERE unit_of_measure = :unit_of_measure')
                                ->bindValue(':unit_of_measure', $unit_of_measure)
                                ->queryScalar();
                        }
                        $property = new Property();
                        $property->id = $db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                        $property->property_number = $this->getPropertyNumber($date_acquired, $province);
                        $property->property_number = $cells[0];
                        $property->unit_of_measure_id = $unit_of_measure_id;
                        $property->employee_id = 'ro-1';
                        $property->article = $article;
                        $property->serial_number = $serial_number;
                        $property->quantity = $quantity;
                        $property->acquisition_amount = $acquisition_amount;
                        $property->date = $date_acquired;
                        $property->province = $province;
                        $property->ppe_type = $ppe_type;
                        $property->fk_ssf_category_id = $ssf_category_id;
                        $property->description = $description;

                        if ($property->save(false)) {
                            $par = new Par();
                            $par->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                            $par->par_number = $par_number;
                            // $par->par_number = $this->parNumber($par_date, $province);
                            $par->date = $par_date;
                            $par->employee_id = null;
                            $par->agency_id = null;
                            $par->actual_user = null;
                            $par->fk_property_id = $property->id;
                            $par->old_par_number = $old_par_number;
                            $par->location = $par_location;
                            $par->accountable_officer = $accountable_officer;
                            $par->recieve_by_jocos = $recieve_by_jocos;
                            $par->issued_by = $par_issued_by;
                            $par->issued_to = $par_issued_to;
                            $par->remarks = $par_remarks;
                            if ($par->save(false)) {
                            }
                        }
                    }
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
            $transaction->commit();

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
            var_dump('success');
            echo "</pre>";
            return ob_get_clean();
        }
    }
    public function parNumber($date, $province)
    {

        $year = DateTime::createFromFormat('Y-m-d', $date)->format('Y');
        $query = Yii::$app->db->createCommand("SELECT
                     CAST(SUBSTRING_INDEX(par.par_number,'-',-1)AS UNSIGNED) as p_number
                    FROM `par`
                    INNER JOIN property ON par.fk_property_id = property.id 
                    WHERE 
                    property.province = :province
                -- AND property.date LIKE :_year
                ORDER BY  p_number DESC LIMIT 1")
            ->bindValue(':province', $province)
            // ->bindValue(':_year', $year . '%')
            ->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        $num_len =  4 - strlen($num);
        if ($num_len > 0) {
            $zero = str_repeat(0, $num_len);
        }

        $string = strtoupper($province) . '-' . $year . '-' . $zero . $num;
        return $string;
    }
    public function actionBlankSticker()
    {
        return $this->render('property_sticker');
    }
    public function actionSearchSsfCategory($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(["ssf_category.id as id", "CONCAT (ssf_category.ssf_number ,' - ',ssf_category.project_title) AS text"])
                ->from('ssf_category')
                ->where(['like', 'ssf_category.ssf_number', $q])
                ->orwhere(['like', 'ssf_category.project_title', $q]);

            $command = $query->createCommand();
            // return json_encode($command->getRawSql());
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        // elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        // }
        return $out;
    }
    // public function actionCreateBlank()
    // {

    //     $query = Yii::$app->db->createCommand("SELECT
    //     CAST(SUBSTRING_INDEX(property.property_number,'-',-1)AS UNSIGNED) as p_number
    //             FROM property
    //             -- AND property.date LIKE :_year
    //             ORDER BY  p_number DESC LIMIT 1")
    //         ->queryScalar();



    //     $num  = 1400;
    //     $zero = '';


    //     for ($i = 0; $i < 100; $i++) {
    //         $num_len =  5 - strlen($num);
    //         if ($num_len > 0) {
    //             $zero = str_repeat(0, $num_len);
    //         }
    //         $property_number = 'PPE-' . $zero . $num;

    //         $check = YIi::$app->db->createCommand("SELECT id FROm property WHERE property_number = :property_number")
    //             ->bindValue(':property_number', $property_number)
    //             ->queryOne();
    //         if (!empty($check)) {
    //             $property = Property::findOne($check);
    //         } else {
    //             $property = new Property();
    //             $property->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
    //             $property->property_number = $property_number;
    //         }

    //         if ($property->save(false)) {

    //             $check_par = YIi::$app->db->createCommand("SELECT id FROM par WHERE fk_property_id = :property_id")
    //                 ->bindValue(':property_id', $property->id)
    //                 ->queryScalar();
    //             if (!empty($check_par)) {
    //                 $par  = Par::findOne($check_par);
    //             } else {

    //                 $par = new Par();
    //                 $par->fk_property_id = $property->id;
    //                 $par->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
    //             }
    //             $par->par_number = 'PAR-' . $zero . $num;
    //             if ($par->save(false)) {
    //             }
    //         } else {
    //             return 'failed';
    //         }
    //         $num++;
    //     }
    //     return 'success';
    // }
    public function actionPropertyDatabase()
    {
        $searchModel = new DetailedPropertyDatabaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('property_database_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
