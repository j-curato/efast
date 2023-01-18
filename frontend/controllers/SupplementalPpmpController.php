<?php

namespace frontend\controllers;

use Yii;
use app\models\SupplementalPpmp;
use app\models\SupplementalPpmpCse;
use app\models\SupplementalPpmpIndexSearch;
use app\models\SupplementalPpmpNonCse;
use app\models\SupplementalPpmpNonCseItems;
use app\models\SupplementalPpmpSearch;
use common\models\UploadForm;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * SupplementalPpmpController implements the CRUD actions for SupplementalPpmp model.
 */
class SupplementalPpmpController extends Controller
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
    public function viewNoncseItems($id)
    {

        return YIi::$app->db->createCommand("SELECT 
        supplemental_ppmp.budget_year,
        UPPER(REPLACE(supplemental_ppmp.cse_type,'_','-')) as cse_type,
        mfo_pap_code.`code` as mfo_code,
        supplemental_ppmp_non_cse.activity_name,
        pr_stock.bac_code,
        pr_stock.stock_title,
        supplemental_ppmp_non_cse_items.description,
        IF(supplemental_ppmp_non_cse.early_procurement=1,'Yes','No') as early_procurement,
        supplemental_ppmp_non_cse_items.amount,
        UPPER(unit_of_measure.unit_of_measure) as unit_of_measure,
        supplemental_ppmp_non_cse_items.quantity,
        pr_mode_of_procurement.mode_name
        FROM supplemental_ppmp
        INNER JOIN supplemental_ppmp_non_cse ON supplemental_ppmp.id  = supplemental_ppmp_non_cse.fk_supplemental_ppmp_id
        LEFT JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
        LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
        LEFT JOIN pr_stock_type ON pr_stock.pr_stock_type_id = pr_stock_type.id
        LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
        LEFT JOIN mfo_pap_code ON division_program_unit.fk_mfo_pap_id = mfo_pap_code.id
        LEFT JOIN unit_of_measure ON supplemental_ppmp_non_cse_items.fk_unit_of_measure_id  = unit_of_measure.id
        LEFT JOIN pr_mode_of_procurement ON supplemental_ppmp_non_cse.fk_mode_of_procurement_id= pr_mode_of_procurement.id
        WHERE 
        supplemental_ppmp.id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function serialNumber($budget_year, $cse_type)
    {
        $latest_dv = Yii::$app->db->createCommand("SELECT CAST(substring_index(serial_number, '-', -1)AS UNSIGNED) as q 
                from supplemental_ppmp
                WHERE 
                budget_year = :budget_year
                AND
               cse_type = :cse_type
                ORDER BY q DESC  LIMIT 1")
            ->bindValue(':budget_year', $budget_year)
            ->bindValue(':cse_type', $cse_type)
            ->queryScalar();
        !empty($book_id) ? $book_id : $book_id = 5;
        $num = 1;
        if (!empty($latest_dv)) {
            $num = (int) $latest_dv + 1;
        }
        $x = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $x .= 0;
        }

        return strtoupper(str_replace('_', '-', $cse_type)) . '-' . $budget_year . '-' . $x . $num;
    }
    public function insertCseItems($id, $items = [])
    {
        $c = 1;

        foreach ($items as $i => $item) {

            if (empty($item['unit_of_measure_id'])) {
                echo json_encode([$item, $c]);
                die();
            }
            if (!empty($item['cse_item_id'])) {
                $cse_item = SupplementalPpmpCse::findOne($item['cse_item_id']);
            } else {

                $cse_item = new SupplementalPpmpCse();
            }
            $cse_item->fk_supplemental_ppmp_id = $id;
            $cse_item->fk_pr_stock_id = $item['stock_id'];
            $cse_item->fk_unit_of_measure_id = $item['unit_of_measure_id'];
            $cse_item->amount = $item['amount'];
            $cse_item->jan_qty = $item['jan_qty'];
            $cse_item->feb_qty = $item['feb_qty'];
            $cse_item->mar_qty = $item['mar_qty'];
            $cse_item->apr_qty = $item['apr_qty'];
            $cse_item->may_qty = $item['may_qty'];
            $cse_item->jun_qty = $item['jun_qty'];
            $cse_item->jul_qty = $item['jul_qty'];
            $cse_item->aug_qty = $item['aug_qty'];
            $cse_item->sep_qty = $item['sep_qty'];
            $cse_item->oct_qty = $item['oct_qty'];
            $cse_item->nov_qty = $item['nov_qty'];
            $cse_item->dec_qty = $item['dec_qty'];
            if ($cse_item->save(false)) {
            }
            $c++;
        }
        return true;
    }
    public function insertNonCseItems($id, $non_cse = [])
    {





        foreach ($non_cse as $noncse) {



            if (!empty($noncse['non_cse_id'])) {
                $ppmp_non_cse = SupplementalPpmpNonCse::findOne($noncse['non_cse_id']);


                $update_non_cse_item_ids = array_column($noncse['items'], 'non_cse_item_id');
                $params = [];
                $sql = '';
                $item_ids = array_column($noncse['items'], 'non_cse_item_id');
                if (!empty($item_ids)) {
                    $sql = 'AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                }

                Yii::$app->db->createCommand("UPDATE supplemental_ppmp_non_cse_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                        supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id = :id $sql", $params)
                    ->bindValue(':id', $ppmp_non_cse->id)->query();
            } else {

                $ppmp_non_cse = new SupplementalPpmpNonCse();
            }
            $ppmp_non_cse->fk_supplemental_ppmp_id = $id;
            $ppmp_non_cse->type = $noncse['type'];
            $ppmp_non_cse->early_procurement = $noncse['early_procurement'];
            $ppmp_non_cse->fk_fund_source_id = $noncse['fk_fund_source_id'];
            $ppmp_non_cse->activity_name = $noncse['activity_name'];
            $ppmp_non_cse->fk_mode_of_procurement_id = $noncse['fk_mode_of_procurement_id'];
            if ($ppmp_non_cse->type === 'fixed expenses') {
                $ppmp_non_cse->activity_name = 'Fixed Expenses';
            }
            if ($ppmp_non_cse->validate()) {
                if ($ppmp_non_cse->save(false)) {

                    foreach ($noncse['items'] as $item) {

                        if (!empty($item['non_cse_item_id'])) {
                            $ppmp_non_cse_item = SupplementalPpmpNonCseItems::findOne($item['non_cse_item_id']);
                        } else {
                            $ppmp_non_cse_item = new SupplementalPpmpNonCseItems();
                        }

                        $ppmp_non_cse_item->fk_supplemental_ppmp_non_cse_id = $ppmp_non_cse->id;
                        $ppmp_non_cse_item->amount = $item['amount'];
                        $ppmp_non_cse_item->fk_pr_stock_id = $item['stock_id'];
                        $ppmp_non_cse_item->description = $item['description'];
                        $ppmp_non_cse_item->quantity = $item['qty'];
                        $ppmp_non_cse_item->fk_unit_of_measure_id = $item['unit_of_measure_id'];
                        if ($ppmp_non_cse_item->validate()) {
                            if ($ppmp_non_cse_item->save(false)) {
                            }
                        } else {
                            return $ppmp_non_cse_item->errors;
                        }
                    }
                }
            } else {

                return  $ppmp_non_cse->errors;
                // die();
            }
        }
        return true;
    }
    public function getCseItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        supplemental_ppmp_cse.id,
       
        supplemental_ppmp_cse.amount,
        supplemental_ppmp_cse.jan_qty,
        supplemental_ppmp_cse.feb_qty,
        supplemental_ppmp_cse.mar_qty,
        supplemental_ppmp_cse.apr_qty,
        supplemental_ppmp_cse.may_qty,
        supplemental_ppmp_cse.jun_qty,
        supplemental_ppmp_cse.jul_qty,
        supplemental_ppmp_cse.aug_qty,
        supplemental_ppmp_cse.sep_qty,
        supplemental_ppmp_cse.oct_qty,
        supplemental_ppmp_cse.nov_qty,
        supplemental_ppmp_cse.dec_qty,
        pr_stock.id as stock_id,
        pr_stock.stock_title,
        unit_of_measure.id as unit_of_measure_id,
        unit_of_measure.unit_of_measure
        
         FROM `supplemental_ppmp_cse`
         LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
         LEFT JOIN unit_of_measure ON supplemental_ppmp_cse.fk_unit_of_measure_id = unit_of_measure.id

        WHERE 
        supplemental_ppmp_cse.is_deleted = 0
        AND supplemental_ppmp_cse.fk_supplemental_ppmp_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    public function getNonCseItems($id)
    {
        $query = YIi::$app->db->createCommand("SELECT 

        supplemental_ppmp_non_cse.id as supplemental_ppmp_non_cse_id,
        supplemental_ppmp_non_cse.fk_supplemental_ppmp_id,
        supplemental_ppmp_non_cse.type,
        supplemental_ppmp_non_cse.early_procurement,
        supplemental_ppmp_non_cse.fk_mode_of_procurement_id,
        supplemental_ppmp_non_cse.activity_name,
        supplemental_ppmp_non_cse.fk_fund_source_id,
        supplemental_ppmp_non_cse.proc_act_sched,
        supplemental_ppmp_non_cse_items.id as non_cse_item_id,
        supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id,
        supplemental_ppmp_non_cse_items.amount,
        supplemental_ppmp_non_cse_items.quantity,
        supplemental_ppmp_non_cse_items.description,
        pr_stock.stock_title,
        pr_stock.id as stock_id,
        fund_source.id as fund_source_id,
        fund_source.name as fund_source_name,
        pr_mode_of_procurement.id as mode_of_procurement_id,
        pr_mode_of_procurement.mode_name as mode_of_procurement_name,
        unit_of_measure.unit_of_measure,
        unit_of_measure.id as unit_of_measure_id
        
        
        
        
        FROM
        supplemental_ppmp_non_cse 
        LEFT JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
        LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id  = pr_stock.id
        LEFT JOIN fund_source ON supplemental_ppmp_non_cse.fk_fund_source_id  = fund_source.id
        LEFT JOIN pr_mode_of_procurement ON supplemental_ppmp_non_cse.fk_mode_of_procurement_id = pr_mode_of_procurement.id
        LEFT JOIN unit_of_measure ON supplemental_ppmp_non_cse_items.fk_unit_of_measure_id = unit_of_measure.id
        WHERE 
        supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = :id
        AND supplemental_ppmp_non_cse.is_deleted = 0
        AND supplemental_ppmp_non_cse_items.is_deleted = 0
        
        ")
            ->bindValue(':id', $id)
            ->queryAll();

        $result = ArrayHelper::index($query, 'non_cse_item_id', [function ($element) {
            return $element['supplemental_ppmp_non_cse_id'];
        }]);
        // echo json_encode($result);
        // die();
        return $result;
    }
    /**
     * Lists all SupplementalPpmp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SupplementalPpmpIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplementalPpmp model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $items = [];
        if ($model->cse_type === 'non_cse') {
            $items = $this->viewNoncseItems($id);
        } else if ($model->cse_type === 'cse') {
            $items = $this->getCseItems($id);
        }
        return $this->render('view', [
            'model' => $model,
            'items' => $items,

        ]);
    }

    /**
     * Creates a new SupplementalPpmp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SupplementalPpmp();
        $model->fk_approved_by = '99684622555676858';
        $model->fk_certified_funds_available_by = '99684622555676773';

        if (Yii::$app->request->isPost) {

            // return $model->serial_number;
            // die();
            $cse_items = !empty($_POST['cse_items']) ? $_POST['cse_items'] : [];
            $non_cse_items = !empty($_POST['ppmp_non_cse']) ? $_POST['ppmp_non_cse'] : [];
            // return json_encode($cse_items);





            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->date = date("Y-m-d");
            $model->budget_year = $_POST['budget_year'];
            $model->cse_type = $_POST['cse_type'];
            $model->fk_prepared_by = $_POST['fk_prepared_by'];
            $model->fk_reviewed_by = $_POST['fk_reviewed_by'];
            $model->fk_division_program_unit_id = $_POST['fk_division_program_unit_id'];
            $model->fk_approved_by = $_POST['fk_approved_by'];
            $model->fk_certified_funds_available_by = $_POST['fk_certified_funds_available_by'];
            if (!YIi::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $model->fk_office_id = $user_data->office->id;
                $model->fk_division_id = $user_data->divisionName->id;
            } else {
                $model->fk_division_id = $_POST['fk_division_id'];
                $model->fk_office_id = $_POST['fk_office_id'];
            }
            $model->serial_number = $this->serialNumber($model->budget_year, $model->cse_type);
            // if (!Yii::$app->user->can('super-user') && $model->cse_type === 'cse') {
            //     return json_encode(['isSuccess' => false, 'error_message' => 'Only Supply Office Can Create CSE PPMP']);
            //     die();
            // }
            $model->is_supplemental = 1;
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($model->validate()) {
                    if ($model->save(false)) {

                        if ($model->cse_type === 'cse') {
                            $insert_cse = $this->insertCseItems($model->id, $cse_items);

                            if ($insert_cse !== true) {
                                $transaction->rollBack();
                                return json_encode(['isSuccess' => false, 'error_message' => $insert_cse]);
                                die();
                            }
                        } else {
                            $insert_non_cse = $this->insertNonCseItems($model->id, $non_cse_items);
                            if ($insert_non_cse !== true) {

                                $transaction->rollBack();
                                return json_encode(['isSuccess' => false, 'error_message' => $insert_non_cse]);
                                die();
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                    die();
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'action' => 'supplemental-ppmp/create',
        ]);
    }

    /**
     * Updates an existing SupplementalPpmp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if (Yii::$app->request->isPost) {
            $cse_items = !empty($_POST['cse_items']) ? $_POST['cse_items'] : [];
            $non_cse_items = !empty($_POST['ppmp_non_cse']) ? $_POST['ppmp_non_cse'] : [];
            $model->date = date("Y-m-d");
            $model->budget_year = $_POST['budget_year'];
            $model->cse_type = $_POST['cse_type'];

            $model->fk_division_program_unit_id = $_POST['fk_division_program_unit_id'];

            $model->fk_prepared_by = $_POST['fk_prepared_by'];
            $model->fk_reviewed_by = $_POST['fk_reviewed_by'];
            $model->fk_approved_by = $_POST['fk_approved_by'];
            $model->fk_certified_funds_available_by = $_POST['fk_certified_funds_available_by'];
            if (!YIi::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $model->fk_office_id = $user_data->office->id;
                $model->fk_division_id = $user_data->divisionName->id;
            } else {
                $model->fk_division_id = $_POST['fk_division_id'];
                $model->fk_office_id = $_POST['fk_office_id'];
            }
            if (intval($model->is_final) === 1) {
                return json_encode(['isSuccess' => false, 'error_message' => 'Cannot Update Supplemental is Already Final']);
                //     die();
            }
            // if (!Yii::$app->user->can('super-user') && $model->cse_type === 'cse') {
            //     return json_encode(['isSuccess' => false, 'error_message' => 'Only Supply Office Can Edit CSE PPMP']);
            //     die();
            // }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->validate()) {
                    if ($model->save(false)) {
                        $update_non_cse_ids = array_column($non_cse_items, 'non_cse_id');
                        $update_cse_items = array_column($cse_items, 'cse_item_id');

                        $params = [];
                        $sql = '';
                        if (!empty($update_non_cse_ids)) {
                            $sql = 'AND ';
                            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $update_non_cse_ids], $params);
                        }
                        Yii::$app->db->createCommand("UPDATE supplemental_ppmp_non_cse SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                                supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = :id $sql", $params)
                            ->bindValue(':id', $model->id)->execute();
                        $params2 = [];
                        $sql2 = '';
                        if (!empty($update_cse_items)) {
                            $sql2 = 'AND ';
                            $sql2 .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $update_cse_items], $params2);
                        }
                        Yii::$app->db->createCommand("UPDATE supplemental_ppmp_cse SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                                supplemental_ppmp_cse.fk_supplemental_ppmp_id = :id $sql2", $params2)
                            ->bindValue(':id', $model->id)->execute();



                        if ($model->cse_type === 'cse') {
                            $insert_cse = $this->insertCseItems($model->id, $cse_items);
                            if ($insert_cse !== true) {
                                $transaction->rollBack();
                                return json_encode(['isSuccess' => false, 'error_message' => $insert_cse]);
                                die();
                            }
                        } else if ($model->cse_type === 'non_cse') {
                            $check_pr = YII::$app->db->createCommand("SELECT 
                            COUNT(supplemental_ppmp_non_cse.id)
                             FROM supplemental_ppmp_non_cse
                            INNER  JOIN pr_purchase_request ON supplemental_ppmp_non_cse.id = pr_purchase_request.fk_supplemental_ppmp_noncse_id
                             WHERE supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = :id
                            ")
                                ->bindValue(':id', $model->id)
                                ->queryScalar();


                            if (intval($check_pr) > 0) {
                                return json_encode(['isSuccess' => false, 'error_message' => "Cannot be edited because a purchase request has already been made for it."]);
                            }
                            $insert_non_cse = $this->insertNonCseItems($model->id, $non_cse_items);

                            if ($insert_non_cse !== true) {

                                $transaction->rollBack();
                                return json_encode(['isSuccess' => false, 'error_message' => $insert_non_cse]);
                                die();
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode($model->errors);
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        $items = [];
        if ($model->cse_type === 'non_cse') {
            $items = $this->getNonCseItems($id);
        } else if ($model->cse_type === 'cse') {
            $items = $this->getCseItems($id);
        }
        return $this->render('update', [
            'model' => $model,
            'items' => $items,
            'action' => 'supplemental-ppmp/update',
        ]);
    }

    /**
     * Deletes an existing SupplementalPpmp model.
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
     * Finds the SupplementalPpmp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SupplementalPpmp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupplementalPpmp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionImport()
    {
        if (Yii::$app->request->isPost) {
            $model = new UploadForm();

            $file_path = '';

            // $q = $_FILES['file'];
            if (isset($_FILES['file'])) {
                $id = uniqid();
                $dv_number =  "\imports";
                $file = $_FILES;
                $file = \yii\web\UploadedFile::getInstanceByName('file');
                $model->file = $file;
                $path =  Yii::$app->basePath . $dv_number;
                FileHelper::createDirectory($path);
                if ($model->validate()) {
                    $file_path =  $model->upload($path, "ppmp_$id");
                } else {
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
            }






            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_path);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file_path);

            $excel->setActiveSheetIndexByName('noncse');
            // $excel->setActiveSheetIndexByName('cse');
            $worksheet = $excel->getActiveSheet();
            // print_r($excel->getSheetNames());

            $data = [];
            $transaction = YIi::$app->db->beginTransaction();

            foreach ($worksheet->getRowIterator(3) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {

                    $cells[] =   $cell->getValue();
                }
                // CSE
                // $budget_year = $cells[0];
                // $cse_type = $cells[1];
                // $stock_name_or_activity_name = $cells[2];
                // $office = $cells[3];
                // $division = $cells[4];
                // $division_program_unit = $cells[5];
                // $unit_of_measure = $cells[6];
                // $jan_qty = $cells[7];
                // $feb_qty = $cells[8];
                // $mar_qty = $cells[9];
                // $apr_qty = $cells[10];
                // $may_qty = $cells[11];
                // $jun_qty = $cells[12];
                // $jul_qty = $cells[13];
                // $aug_qty = $cells[14];
                // $sep_qty = $cells[15];
                // $oct_qty = $cells[16];
                // $nov_qty = $cells[17];
                // $dec_qty = $cells[18];
                // $total_amt = $cells[19];

                // NON CSE
                $cse_type = 'non_cse';
                $budget_year = $cells[0];
                $activity_name = $cells[1];
                $type = $cells[2];
                $stock_name_or_activity_name = $cells[3];
                $description = $cells[4];
                $office = $cells[5];
                $division = $cells[6];
                $division_program_unit = $cells[7];
                $fund_source = $cells[8];
                $amount = $cells[9];

                $fund_source_id = Yii::$app->db->createCommand("SELECT id FROM fund_source WHERE fund_source.name = :fund_source")->bindValue(':fund_source', $fund_source)->queryScalar();
                // if (empty($fund_source_id)) {
                //     $transaction->rollBack();
                //     return json_encode(['isSuccess' => false, 'error_message' => $fund_source . ' Fund Source Does not exists in line' . $key]);
                // }
                $office_id = Yii::$app->db->createCommand("SELECT id FROM office WHERE office.office_name = :office")->bindValue(':office', $office)->queryScalar();
                if (empty($office_id)) {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $office . ' Office Does not exists in line' . $key]);
                }
                $division_id = Yii::$app->db->createCommand("SELECT id FROM divisions WHERE divisions.division = :division")->bindValue(':division', $division)->queryScalar();
                if (empty($division_id)) {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $division . ' Division Does not exists in line' . $key]);
                }
                $division_program_unit_id = Yii::$app->db->createCommand("SELECT id FROM division_program_unit WHERE division_program_unit.name = :nme")->bindValue(':nme', $division_program_unit)->queryScalar();
                if (empty($division_program_unit_id)) {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $division_program_unit . ' division_program_unit Does not exists in line' . $key]);
                }

                $stock_id = Yii::$app->db->createCommand("SELECT id FROM pr_stock WHERE pr_stock.stock_title = :stock_name_or_activity_name")->bindValue(':stock_name_or_activity_name', $stock_name_or_activity_name)->queryScalar();
                if (empty($stock_id)) {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $stock_name_or_activity_name . ' Does not exists in line' . $key]);
                }
                // $unit_of_measure_id = Yii::$app->db->createCommand("SELECT id FROM unit_of_measure WHERE unit_of_measure.unit_of_measure = :unit_of_measure")
                //     ->bindValue(':unit_of_measure', $unit_of_measure)->queryScalar();
                // if (empty($unit_of_measure_id)) {
                //     $transaction->rollBack();
                //     return json_encode(['isSuccess' => false, 'error_message' => $unit_of_measure . 'unit of measure Does not exists in line' . $key]);
                // }


                $exists_act = Yii::$app->db->createCommand("SELECT fk_supplemental_ppmp_id FROM supplemental_ppmp_non_cse WHERE supplemental_ppmp_non_cse.activity_name = :activity_name")
                    ->bindValue(':activity_name', $activity_name)
                    ->queryScalar();
                if (empty($exists_act)) {
                    $ppmp = new SupplementalPpmp();

                    $ppmp->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                    $ppmp->serial_number = $this->serialNumber($budget_year, $cse_type);
                    $ppmp->budget_year = $budget_year;
                    $ppmp->cse_type = strtolower($cse_type);
                    $ppmp->fk_office_id = $office_id;
                    $ppmp->fk_division_id = $division_id;
                    $ppmp->fk_division_program_unit_id = $division_program_unit_id;
                    if ($ppmp->validate()) {
                        if ($ppmp->save(false)) {
                            // $ppmp_cse = new SupplementalPpmpCse();
                            // $ppmp_cse->id = $x;
                            // $ppmp_cse->fk_supplemental_ppmp_id = $ppmp->id;
                            // $ppmp_cse->fk_pr_stock_id = $stock_id;
                            // $ppmp_cse->fk_unit_of_measure_id = $unit_of_measure_id;
                            // $ppmp_cse->amount = $total_amt;
                            // $ppmp_cse->jan_qty = $jan_qty;
                            // $ppmp_cse->feb_qty = $feb_qty;
                            // $ppmp_cse->mar_qty = $mar_qty;
                            // $ppmp_cse->apr_qty = $apr_qty;
                            // $ppmp_cse->may_qty = $may_qty;
                            // $ppmp_cse->jun_qty = $jun_qty;
                            // $ppmp_cse->jul_qty = $jul_qty;
                            // $ppmp_cse->aug_qty = $aug_qty;
                            // $ppmp_cse->sep_qty = $sep_qty;
                            // $ppmp_cse->oct_qty = $oct_qty;
                            // $ppmp_cse->nov_qty = $nov_qty;
                            // $ppmp_cse->dec_qty = $dec_qty;
                            // if ($ppmp_cse->save(false)) {
                            // }

                            $ppmp_non_cse = new SupplementalPpmpNonCse();
                            $ppmp_non_cse->fk_supplemental_ppmp_id = $ppmp->id;
                            $ppmp_non_cse->type = $type;
                            $ppmp_non_cse->early_procurement = 0;
                            $ppmp_non_cse->fk_fund_source_id = $fund_source_id;
                            $ppmp_non_cse->activity_name = $activity_name;
                            // if ($ppmp_non_cse->type === 'fixed expenses') {
                            //     $ppmp_non_cse->activity_name = 'fixed';
                            // }
                            if ($ppmp_non_cse->validate()) {
                                if ($ppmp_non_cse->save(false)) {
                                    $exists_act = $ppmp_non_cse->id;
                                }
                            } else {
                                return json_encode(['isSuccess' => false, 'error_message' => $ppmp_non_cse->errors, 'key' => $key]);
                            }
                        }
                    } else {
                        return json_encode(['isSuccess' => false, 'error_message' => $ppmp->errors]);
                    }
                }

                $ppmp_non_cse_item = new SupplementalPpmpNonCseItems();
                $ppmp_non_cse_item->fk_supplemental_ppmp_non_cse_id = $ppmp_non_cse->id;
                $ppmp_non_cse_item->amount = $amount;
                $ppmp_non_cse_item->fk_pr_stock_id = $stock_id;
                $ppmp_non_cse_item->description = $description;
                $ppmp_non_cse_item->quantity = 0;
                if ($ppmp_non_cse_item->validate()) {
                    if ($ppmp_non_cse_item->save(false)) {
                    }
                } else {
                    return json_encode([$ppmp_non_cse_item->errors, $key]);
                }
            }


            $transaction->commit();
            // return $this->redirect(['index']);
            return json_encode(['isSuccess' => true]);
        }
    }
    public function actionGetStockAmount()
    {
        if (YIi::$app->request->isPost) {

            return json_encode(YIi::$app->db->createCommand("SELECT amount,unit_of_measure.id,unit_of_measure.unit_of_measure 
            FROM pr_stock 
            LEFT JOIN unit_of_measure ON pr_stock.unit_of_measure_id = unit_of_measure.id
            WHERE pr_stock.id = :id")
                ->bindValue(':id', $_POST['id'])
                ->queryOne());
        }
    }
}
