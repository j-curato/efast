<?php

namespace frontend\controllers;

use Yii;
use app\models\PrStock;
use app\models\PrStockSearch;
use app\models\PrStockSpecification;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'search-stock',
                            'stock-info',
                            'import',
                            'get-part',
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

        if ($model->load(Yii::$app->request->post())) {

            // $specification = $_POST['specification'];
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $transaction = Yii::$app->db->beginTransaction();

            if (Yii::$app->memem->serverIp() !== '10.20.17.35') {
                return $this->actionIndex();
            }
            if ($model->part !== 'part-1') {

                $model->bac_code = $this->bacCode($model->part, $model->type, $model->chart_of_account_id);
            }
            try {

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
                return json_encode('fail');
            }
        }

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

                    if ($model->save()) {
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
                return json_encode('fail');
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
        if (!empty($_POST)) {
            // $chart_id = $_POST['chart_id'];
            $name = $_FILES["file"]["name"];
            // var_dump($_FILES['file']);
            // die();
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
            $excel->setActiveSheetIndexByName('stocks');
            $worksheet = $excel->getActiveSheet();

            $data = [];
            $transaction = YIi::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(2) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    $q = '';
                    if ($y === 7) {
                        $cells[] = $cell->getFormattedValue();
                    } else {
                        $cells[] =   $cell->getValue();
                    }
                    $y++;
                }
                if (!empty($cells)) {


                    $part = $cells[0];
                    $type = $cells[1];
                    $bac_code = $cells[2];
                    $stock_title = $cells[3];
                    $unit_of_measure = $cells[4];
                    $unit_cost = $cells[5];
                    $uacs = $cells[6];



                    $unit_of_measure_id = Yii::$app->db->createCommand("SELECT id FROM unit_of_measure WHERE unit_of_measure = :unit_of_measure")
                        ->bindValue(':unit_of_measure', $unit_of_measure)
                        ->queryScalar();
                    $chart_of_account_id = Yii::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :uacs")
                        ->bindValue(':uacs', $uacs)
                        ->queryScalar();
                    if (empty($unit_of_measure_id)) {
                        return "UNIT OF MEASURE $key";
                        $transaction->rollBack();
                    }
                    if (empty($chart_of_account_id)) {
                        return "CHART OF  $key";
                        $transaction->rollBack();
                    }

                    $model = new PrStock();
                    $model->stock_title = $stock_title;
                    $model->bac_code = $bac_code;
                    $model->unit_of_measure_id = $unit_of_measure_id;
                    $model->amount = $unit_cost;
                    $model->chart_of_account_id = $chart_of_account_id;
                    $model->part = $part;
                    $model->type = $type;
                    if ($model->save(false)) {
                    } else {
                        $transaction->rollBack();
                    }
                }
            }
            $transaction->commit();


            // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            ob_clean();
            echo "<pre>";
            var_dump('success');
            echo "</pre>";
            return ob_get_clean();
        }
    }
    public function actionGetPart()
    {
        // $parts = YIi::$app->memem->getStockPart()[$part];
        // return json_encode($parts);

        if ($_POST) {
            $part = $_POST['part'];
            $query = Yii::$app->db->createCommand("SELECT `type`,chart_of_accounts.uacs as object_code
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
