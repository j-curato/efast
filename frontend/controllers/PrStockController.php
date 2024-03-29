<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use ErrorException;
use app\models\PrStock;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use app\models\PrStockSearch;
use common\models\UploadForm;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\PrStockSpecification;

/**
 * PrStockController implements the CRUD actions for PrStock model.
 */
class PrStockController extends Controller
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
                    'search-stock',
                    'stock-info',
                    'import',
                    'get-part',
                    'search-paginated-stock',
                    'final'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',

                        ],
                        'allow' => true,
                        'roles' => ['view_stock']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_stock']
                    ],
                    [
                        'actions' => [
                            'create',

                        ],
                        'allow' => true,
                        'roles' => ['create_stock']
                    ],
                    [
                        'actions' => [
                            'import',

                        ],
                        'allow' => true,
                        'roles' => ['import_stock']
                    ],
                    // [
                    //     'actions' => [
                    //         'index',
                    //         'view',
                    //         'create',
                    //         'update',
                    //         'delete',
                    //         'search-stock',
                    //         'stock-info',
                    //         'import',
                    //         'get-part',
                    //         'search-paginated-stock',
                    //         'final'
                    //     ],
                    //     'allow' => true,
                    //     'roles' => ['stock']
                    // ],
                    [
                        'actions' => [
                            'search-stock',
                            'stock-info',
                            'get-part',
                            'search-paginated-stock',
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
     * Lists all PrStock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrStock model.
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
     * Creates a new PrStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertSpecs($specification, $model_id)
    {
        if (!empty($specification)) {
            foreach ($specification as $val) {
                $specs = new PrStockSpecification();
                $specs->pr_stock_id = $model_id;
                $specs->description = $val;

                if ($specs->save(false)) {
                } else {
                    return false;
                }
            }
        }
        return true;
    }
    public function getZero($num)
    {
        $final = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $final .= 0;
        }
        return $final;
    }
    public function bacCode($part, $type, $chart_of_account_id)
    {
        $bac_code = '';
        if ($part === 'part-2') {
            $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(bac_code,'-',-1) AS UNSIGNED)as num FROM pr_stock WHERE pr_stock.part = :part
            AND pr_stock.type = :_type
             ORDER BY num DESC LIMIT 1")
                ->bindValue(':part', $part)
                ->bindValue(':_type', $type)
                ->queryScalar();
            $num = 1;
            if (!empty($query)) {
                $num = intval($query) + 1;
            }
            $final = $this->getZero($num);
            $type_array = explode(' ', $type);
            $acro_name = '';
            if (count($type_array) > 1) {
                foreach ($type_array as $val) {
                    $acro_name .= $val[0];
                }
            } else {
                $acro_name = substr($type, 0, 4);
            }

            $bac_code = strtoupper(str_replace('-', '', $part)) . '-' . strtoupper($acro_name) . '-' . $final . $num;
        } else   if ($part === 'part-3') {
            $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(bac_code,'-',-1) AS UNSIGNED)as num
             FROM pr_stock
             WHERE pr_stock.chart_of_account_id = :chart_id
            AND pr_stock.part = :part
             ORDER BY num DESC LIMIT 1")
                ->bindValue(':chart_id', $chart_of_account_id)
                ->bindValue(':part', $part)
                ->queryScalar();
            $object_code = Yii::$app->db->createCommand("SELECT uacs FROM chart_of_accounts where id = :id")
                ->bindValue(':id', $chart_of_account_id)
                ->queryScalar();
            $num = 1;
            if (!empty($query)) {
                $num = intval($query) + 1;
            }
            $final = $this->getZero($num);
            $bac_code =  strtoupper(str_replace('-', '', $part)) . '-' . $object_code . '-' . $final . $num;
        }
        return $bac_code;
    }
    public function actionCreate()
    {
        $model = new PrStock();

        // if ($model->load(Yii::$app->request->post())) {

        //     // $specification = $_POST['specification'];
        //     $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
        //     $transaction = Yii::$app->db->beginTransaction();

        //     // if (Yii::$app->memem->serverIp() !== '10.20.17.35') {
        //     //     return $this->actionIndex();
        //     // }
        //     if ($model->part !== 'part-1') {

        //         $model->bac_code = $this->bacCode($model->part, $model->type, $model->chart_of_account_id);
        //         if ($model->part === 'part-2') {
        //             $model->bac_code = 'CSE Part II';
        //         } else {
        //         }
        //     }
        //     try {

        //         if ($flag = true) {

        //             if ($model->save(false)) {
        //                 // $flag = $this->insertSpecs($specification, $model->id);
        //             } else {
        //                 $flag = false;
        //             }
        //         }

        //         if ($flag) {
        //             $transaction->commit();
        //             return $this->redirect(['view', 'id' => $model->id]);
        //         } else {
        //             $transaction->rollBack();
        //             return json_encode('fail');
        //         }
        //     } catch (ErrorException $e) {
        //         $transaction->rollBack();
        //         return json_encode('fail');
        //     }
        // }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            // $specification = !empty($_POST['specification']) ? $_POST['specification'] : [];
            $transaction = Yii::$app->db->beginTransaction();

            try {

                foreach ($model->prStockSpecification as $val) {
                    $val->delete();
                }

                if ($flag = true) {

                    if ($model->save(false)) {
                        // $flag = $this->insertSpecs($specification, $model->id);
                    } else {
                        $flag = false;
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('fail');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrStock model.
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
     * Finds the PrStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PrStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PrStock::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchStock($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select([" id, UPPER(`stock_title`) as text"])
                ->from('pr_stock')
                ->where(['like', 'stock_title', $q])
                // ->andwhere('pr_stock.is_final = 1')
            ;

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }

        return $out;
    }
    public function actionSearchPaginatedStock($page = 0, $q = null, $id = null, $budget_year = null, $cse_type = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $limit = 10;
        $offset = ($page - 1) * $limit;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();

            $query->select(["CAST(id as CHAR(50)) as id, UPPER(`stock_title`) as text"])
                ->from('pr_stock')
                ->where(['like', 'stock_title', $q])
                ->andWhere('is_disabled = 0')
                ->andWhere('pr_stock.cse_type = :cse_type', ['cse_type' => $cse_type]);
            if ($cse_type === 'cse') {
                $query->andWhere('pr_stock.budget_year = :budget_year', ['budget_year' => $budget_year]);
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
    public function actionStockInfo()
    {

        if ($_POST) {

            $query = Yii::$app->db->createCommand("SELECT FORMAT(amount,2) as amount,unit_of_measure_id FROM `pr_stock` WHERE id =:id")
                ->bindValue(':id', $_POST['id'])
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionImport()
    {
        if (Yii::$app->request->post()) {
            try {
                $transaction = YIi::$app->db->beginTransaction();
                $model = new UploadForm();
                $file_path = '';
                if (isset($_FILES['file'])) {
                    $id = uniqid();
                    $file = $_FILES;
                    $file = \yii\web\UploadedFile::getInstanceByName('file');
                    $model->file = $file;
                    $path =   Yii::$app->basePath . '\imports';
                    FileHelper::createDirectory($path);
                    if ($model->validate()) {
                        $file_path =  $model->upload($path, "stocks_$id");
                    } else {
                        return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                    }
                }
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $excel = $reader->load($file_path);
                $excel->setActiveSheetIndexByName('stocks');
                $worksheet = $excel->getActiveSheet();
                foreach ($worksheet->getRowIterator(2) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    foreach ($cellIterator as $x => $cell) {
                        $cells[] =   $cell->getValue();
                    }
                    if (!empty($cells)) {
                        $budget_year = $cells[0];
                        $part = $cells[1];
                        $type = $cells[2];
                        $bac_code = $cells[3];
                        $stock_title = $cells[4];
                        $unit_of_measure = $cells[5];
                        $unit_cost = $cells[6];
                        $unit_of_measure_id = Yii::$app->db->createCommand("SELECT id FROM unit_of_measure WHERE unit_of_measure = :unit_of_measure")
                            ->bindValue(':unit_of_measure', $unit_of_measure)
                            ->queryScalar();
                        if (empty($unit_of_measure_id)) {
                            throw new ErrorException("UNIT OF MEASURE $key");
                        }
                        $pr_stock_type_id = Yii::$app->db->createCommand("SELECT * FROM `pr_stock_type`
                                WHERE pr_stock_type.part = :part
                                AND pr_stock_type.type = :stock_type ")
                            ->bindValue(':stock_type', $type)
                            ->bindValue(':part', $part)
                            ->queryScalar();
                        $model = new PrStock();
                        $model->stock_title = $stock_title;
                        $model->bac_code = $bac_code;
                        $model->unit_of_measure_id = $unit_of_measure_id;
                        $model->amount = $unit_cost;
                        $model->part = $part;
                        $model->type = $type;
                        $model->cse_type = 'cse';
                        $model->budget_year = $budget_year;
                        $model->pr_stock_type_id = $pr_stock_type_id;
                        if (!$model->validate()) {
                            throw new ErrorException(json_encode($model->errors));
                        }
                        if (!$model->save(false)) {
                            throw new ErrorException('Model save failed');
                        }
                    }
                }
                $transaction->commit();
                return json_encode(['isSuccess' => true]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        }
    }
    public function actionGetPart()
    {
        // $parts = YIi::$app->memem->getStockPart()[$part];
        // return json_encode($parts);

        if ($_POST) {
            $part = $_POST['part'];
            $query = Yii::$app->db->createCommand("SELECT pr_stock_type.id,`type`,chart_of_accounts.uacs as object_code
             FROM pr_stock_type
             LEFT JOIN chart_of_accounts ON pr_stock_type.fk_chart_of_account_id = chart_of_accounts.id
              WHERE pr_stock_type.part = :part")
                ->bindValue(':part', $part)
                ->queryAll();
            return json_encode($query);
        }
    }
    public function actionFinal()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $model = $this->findModel($id);
            if ($model->chart_of_account_id == null) {
                return json_encode(['isSuccess' => false, 'error' => 'No Chart of Account']);
            }
            if ($model->is_final) {
                $model->is_final = 0;
            } else {
                $model->is_final = 1;
                $model->bac_code = $this->bacCode($model->part, $model->type, $model->chart_of_account_id);
            }
            if ($model->save(false))
                return json_encode(['isSuccess' => true]);
        }
        return 'qweqwe';
    }
}
