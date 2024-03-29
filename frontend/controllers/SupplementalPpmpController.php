<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use app\models\Office;
use common\models\User;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use common\models\UploadForm;
use yii\filters\AccessControl;
use app\models\SupplementalPpmp;
use yii\web\NotFoundHttpException;
use app\models\SupplementalPpmpCse;
use app\components\helpers\MyHelper;
use app\models\SupplementalPpmpNonCse;
use app\models\SupplementalPpmpSearch;
use app\models\SupplementalPpmpIndexSearch;
use app\models\SupplementalPpmpNonCseItems;

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
                    'import',
                    'get-stock-amount',
                    'import-cse',
                    'import-non-cse'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'delete',
                            'import',
                        ],
                        'allow' => true,
                        'roles' => ['ppmp']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'get-stock-amount'
                        ],
                        'allow' => true,
                        'roles' => ['ppmp']
                    ],
                    [
                        'actions' => [
                            'import-cse',
                            'import-non-cse'
                        ],
                        'allow' => true,
                        'roles' => ['import_supplemental_ppmp']
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
    private function viewNoncseItems($id)
    {

        return YIi::$app->db->createCommand("SELECT 
        supplemental_ppmp_non_cse_items.id,
        supplemental_ppmp.budget_year,
        UPPER(REPLACE(supplemental_ppmp.cse_type,'_','-')) as cse_type,
        mfo_pap_code.`code` as mfo_code,
        mfo_pap_code.`name` as mfo_name,
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
        supplemental_ppmp.id = :id
        AND 
        supplemental_ppmp_non_cse.is_deleted = 0 
        AND 
        supplemental_ppmp_non_cse_items.is_deleted = 0
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function serialNumber($budget_year, $cse_type, $office_id)
    {
        $office  = Office::findOne($office_id);
        $latest_dv = Yii::$app->db->createCommand("SELECT CAST(substring_index(serial_number, '-', -1)AS UNSIGNED) as q 
                from supplemental_ppmp
                WHERE 
                budget_year = :budget_year
                AND
               cse_type = :cse_type
               AND fk_office_id = :office_id
                ORDER BY q DESC  LIMIT 1")
            ->bindValue(':budget_year', $budget_year)
            ->bindValue(':office_id', $office_id)
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

        return strtoupper($office->office_name) . '-' . strtoupper(str_replace('_', '-', $cse_type)) . '-' . $budget_year . '-' . $x . $num;
    }
    private function insertCseItems($id, $items = [])
    {
        $c = 1;

        try {

            foreach ($items as $i => $item) {
                if (empty($item['unit_of_measure_id'])) {
                    echo json_encode([$item, $c]);
                    die();
                }
                if (!empty($item['cse_item_id'])) {
                    $cse_item = SupplementalPpmpCse::findOne($item['cse_item_id']);
                } else {

                    $cse_item = new SupplementalPpmpCse();
                    $cse_item->id  = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
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
                if (!$cse_item->validate()) {
                    throw new ErrorException(json_encode($cse_item->errors));
                }
                if ($cse_item->save(false)) {
                }
                $c++;
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function insertNonCseItems($id, $non_cse = [])
    {
        try {
            foreach ($non_cse as $noncse) {
                if (!empty($noncse['non_cse_id'])) {
                    $ppmp_non_cse = SupplementalPpmpNonCse::findOne($noncse['non_cse_id']);
                    // $withPr = YIi::$app->db->createCommand("SELECT EXISTS (SELECT 
                    // pr_purchase_request_item.id
                    // FROM supplemental_ppmp_non_cse_items
                    // INNER JOIN pr_purchase_request_item ON supplemental_ppmp_non_cse_items.id = pr_purchase_request_item.fk_ppmp_non_cse_item_id
                    //  WHERE supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id =:id)")
                    //     ->bindValue(':id', $ppmp_non_cse->id)
                    //     ->queryScalar();
                    // if (intval($withPr) === 1) {
                    //     return "Cannot be edited because a purchase request has already been made for it.";
                    // }
                    // $update_non_cse_item_ids = array_column($noncse['items'], 'non_cse_item_id');
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
                    $ppmp_non_cse->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
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
                if (!$ppmp_non_cse->validate()) {
                    throw  new ErrorException(json_encode($ppmp_non_cse->errors));
                }
                if (!$ppmp_non_cse->save(false)) {
                    throw new ErrorException("ppmp_non_cse save failed");
                }
                foreach ($noncse['items'] as $item) {

                    if (!empty($item['non_cse_item_id'])) {
                        $ppmp_non_cse_item = SupplementalPpmpNonCseItems::findOne($item['non_cse_item_id']);
                    } else {
                        $ppmp_non_cse_item = new SupplementalPpmpNonCseItems();
                        $ppmp_non_cse_item->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                    }

                    $ppmp_non_cse_item->fk_supplemental_ppmp_non_cse_id = $ppmp_non_cse->id;
                    $ppmp_non_cse_item->amount = $item['amount'] ?? 0;
                    $ppmp_non_cse_item->fk_pr_stock_id = $item['stock_id'];
                    $ppmp_non_cse_item->description = $item['description'];
                    $ppmp_non_cse_item->quantity = $item['qty'];
                    $ppmp_non_cse_item->fk_unit_of_measure_id = $item['unit_of_measure_id'];
                    if (!$ppmp_non_cse_item->validate()) {
                        throw new ErrorException(json_encode($ppmp_non_cse_item->errors));
                    }
                    if (!$ppmp_non_cse_item->save(false)) {
                        throw new ErrorException("ppmp_non_cse_item");
                    }
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function getCseItems($id)
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
    private function getNonCseItems($id)
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

        // if (strtotime(date('Y-m-d')) > strtotime(date('2023-11-28'))) {
        //     return $this->redirect(['index']);
        // }
        $model = new SupplementalPpmp();
        $model->fk_approved_by = '99684622555676858';
        $model->fk_certified_funds_available_by = '99684622555676773';

        if (Yii::$app->request->isPost) {


            try {
                $transaction = Yii::$app->db->beginTransaction();
                $cse_items = !empty($_POST['cse_items']) ? $_POST['cse_items'] : [];
                $non_cse_items = !empty($_POST['ppmp_non_cse']) ? $_POST['ppmp_non_cse'] : [];
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                $model->date = date("Y-m-d");
                $model->budget_year = $_POST['budget_year'];
                $model->cse_type = $_POST['cse_type'];
                $model->fk_prepared_by = $_POST['fk_prepared_by'];
                $model->fk_reviewed_by = $_POST['fk_reviewed_by'];
                $model->fk_division_program_unit_id = $_POST['fk_division_program_unit_id'];
                $model->fk_approved_by = $_POST['fk_approved_by'];
                $model->fk_certified_funds_available_by = $_POST['fk_certified_funds_available_by'];
                $model->fk_created_by = Yii::$app->user->identity->id;
                if (Yii::$app->user->can('ro_procurement_admin')) {
                    $model->fk_division_id = $_POST['fk_division_id'];
                    $model->fk_office_id = $_POST['fk_office_id'];
                } else {
                    $user_data = User::getUserDetails();
                    $model->fk_office_id = $user_data->employee->office->id;
                    $model->fk_division_id =  $_POST['fk_division_id'] ?? $user_data->employee->empDivision->id;
                }
                // $model->serial_number = $this->serialNumber($model->budget_year, $model->cse_type, $model->fk_office_id);
                $model->is_supplemental = 1;

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('PPMP Save Failed');
                }
                if ($model->cse_type === 'cse') {
                    $insert_cse = $this->insertCseItems($model->id, $cse_items);
                    if ($insert_cse !== true) {
                        throw new ErrorException($insert_cse);
                    }
                } else if ($model->cse_type === 'non_cse') {
                    $insert_non_cse = $this->insertNonCseItems($model->id, $non_cse_items);
                    if ($insert_non_cse !== true) {
                        throw new ErrorException($insert_non_cse);
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
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

        if (
            strtotime(date('Y-m-d')) > strtotime(date('2023-11-28'))
            && !Yii::$app->user->can('super-user')
            && intval($model->budget_year) < 2024
        ) {
            return $this->redirect(['index']);
        }
        if (Yii::$app->request->post()) {
            try {
                $transaction = Yii::$app->db->beginTransaction();
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
                if (Yii::$app->user->can('ro_procurement_admin')) {
                    $model->fk_division_id = $_POST['fk_division_id'];
                    $model->fk_office_id = $_POST['fk_office_id'];
                } else {
                    $user_data = User::getUserDetails();
                    $model->fk_office_id = $user_data->employee->office->id;
                    $model->fk_division_id =  $_POST['fk_division_id'] ?? $user_data->employee->empDivision->id;
                }

                if (intval($model->is_final) === 1) {
                    throw new ErrorException('Cannot Update Supplemental is Already Final');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }

                if (!$model->save(false)) {
                    throw new ErrorException('PPMP Save Failed');
                }

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
                    $check_pr = Yii::$app->db->createCommand("SELECT 
                    GROUP_CONCAT(vw_supplemental_cse_prs.pr_number)as prs 
                     FROM vw_supplemental_cse_prs
                     WHERE vw_supplemental_cse_prs.ppmp_id = :id
                    GROUP BY
                    vw_supplemental_cse_prs.ppmp_id")
                        ->bindValue(':id', $model->id)
                        ->queryScalar();
                    if (!empty($check_pr) && !Yii::$app->user->can('super-user')) {
                        throw new ErrorException("This item cannot be edited because a purchase request has already been made for it. Please advise the procurement unit to cancel Purchase Request No./s $check_pr");
                    }
                    $insert_cse = $this->insertCseItems($model->id, $cse_items);
                    if ($insert_cse !== true) {
                        throw new ErrorException($insert_cse);
                    }
                } else if ($model->cse_type === 'non_cse') {
                    $check_pr = Yii::$app->db->createCommand("SELECT 
                    GROUP_CONCAT(supplemental_non_cse_prs.pr_number)as prs 
                     FROM supplemental_non_cse_prs
                     WHERE supplemental_non_cse_prs.ppmp_id = :id
                    GROUP BY
                    supplemental_non_cse_prs.ppmp_id")
                        ->bindValue(':id', $model->id)
                        ->queryScalar();

                    if (!empty($check_pr) && !Yii::$app->user->can('super-user')) {
                        throw new ErrorException("This item cannot be edited because a purchase request has already been made for it. Please advise the procurement unit to cancel Purchase Request No./s $check_pr");
                    }
                    $insert_non_cse = $this->insertNonCseItems($model->id, $non_cse_items);
                    if ($insert_non_cse !== true) {
                        throw new ErrorException($insert_non_cse);
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
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
    public function actionImportCse()
    {
        if (Yii::$app->request->isPost) {
            try {
                $transaction = YIi::$app->db->beginTransaction();
                $model = new UploadForm();
                $file_path = '';
                // $q = $_FILES['file'];
                if (isset($_FILES['file'])) {
                    $id = uniqid();
                    $file = $_FILES;
                    $file = \yii\web\UploadedFile::getInstanceByName('file');
                    $model->file = $file;
                    $path =  Yii::$app->basePath .  "\imports";
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
                $excel->setActiveSheetIndexByName('cse');
                $worksheet = $excel->getActiveSheet();
                foreach ($worksheet->getRowIterator(2) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    $y = 0;
                    foreach ($cellIterator as $x => $cell) {

                        $cells[] =   $cell->getValue();
                    }
                    // CSE
                    $budget_year = $cells[0];
                    $stock_name = $cells[1];
                    $office = $cells[2];
                    $division = $cells[3];
                    $division_program_unit = $cells[4];
                    $unit_of_measure = $cells[5];
                    $jan_qty = $cells[6];
                    $feb_qty = $cells[7];
                    $mar_qty = $cells[8];
                    $apr_qty = $cells[9];
                    $may_qty = $cells[10];
                    $jun_qty = $cells[11];
                    $jul_qty = $cells[12];
                    $aug_qty = $cells[13];
                    $sep_qty = $cells[14];
                    $oct_qty = $cells[15];
                    $nov_qty = $cells[16];
                    $dec_qty = $cells[17];
                    $amount = $cells[18];

                    $office_id = Yii::$app->db->createCommand("SELECT id FROM office WHERE office.office_name = :office")->bindValue(':office', $office)->queryScalar();
                    if (empty($office_id)) {
                        throw new ErrorException($office . ' Office Does not exists in line' . $key);
                    }
                    $division_id = Yii::$app->db->createCommand("SELECT id FROM divisions WHERE divisions.division = :division")->bindValue(':division', $division)->queryScalar();
                    if (empty($division_id)) {
                        throw new ErrorException($division . ' Division Does not exists in line' . $key);
                    }
                    $division_program_unit_id = Yii::$app->db->createCommand("SELECT id FROM division_program_unit WHERE division_program_unit.name = :nme")->bindValue(':nme', $division_program_unit)->queryScalar();
                    if (empty($division_program_unit_id)) {
                        throw new ErrorException($division_program_unit . ' division_program_unit Does not exists in line' . $key);
                    }
                    $stock_id = Yii::$app->db->createCommand("SELECT id FROM pr_stock WHERE pr_stock.stock_title = :stock_name
                            AND cse_type= 'cse'
                            AND budget_year = :budget_year")
                        ->bindValue(':stock_name', $stock_name)
                        ->bindValue(':budget_year', $budget_year)
                        ->queryScalar();
                    if (empty($stock_id)) {

                        throw new ErrorException($stock_name . ' Does not exists in line' . $key);
                    }
                    $unit_of_measure_id = Yii::$app->db->createCommand("SELECT id FROM unit_of_measure WHERE unit_of_measure.unit_of_measure = :unit_of_measure")
                        ->bindValue(':unit_of_measure', $unit_of_measure)->queryScalar();
                    if (empty($unit_of_measure_id)) {
                        throw new ErrorException($unit_of_measure . 'unit of measure Does not exists in line' . $key);
                    }
                    $ppmp = new SupplementalPpmp();
                    $ppmp->budget_year = $budget_year;
                    $ppmp->cse_type = 'cse';
                    $ppmp->fk_office_id = $office_id;
                    $ppmp->fk_division_id = $division_id;
                    $ppmp->is_supplemental = 0;
                    $ppmp->fk_division_program_unit_id = $division_program_unit_id;
                    if (!$ppmp->validate()) {
                        throw new ErrorException(json_encode($ppmp->errors));
                    }
                    if (!$ppmp->save(false)) {
                        throw new ErrorException('PPMP Model Save Failed');
                    }
                    $ppmp_cse = new SupplementalPpmpCse();
                    $ppmp_cse->fk_supplemental_ppmp_id = $ppmp->id;
                    $ppmp_cse->fk_pr_stock_id = $stock_id;
                    $ppmp_cse->fk_unit_of_measure_id = $unit_of_measure_id;
                    $ppmp_cse->amount = $amount;
                    $ppmp_cse->jan_qty = $jan_qty;
                    $ppmp_cse->feb_qty = $feb_qty;
                    $ppmp_cse->mar_qty = $mar_qty;
                    $ppmp_cse->apr_qty = $apr_qty;
                    $ppmp_cse->may_qty = $may_qty;
                    $ppmp_cse->jun_qty = $jun_qty;
                    $ppmp_cse->jul_qty = $jul_qty;
                    $ppmp_cse->aug_qty = $aug_qty;
                    $ppmp_cse->sep_qty = $sep_qty;
                    $ppmp_cse->oct_qty = $oct_qty;
                    $ppmp_cse->nov_qty = $nov_qty;
                    $ppmp_cse->dec_qty = $dec_qty;
                    if (!$ppmp_cse->validate()) {
                        throw new ErrorException(json_encode($ppmp_cse->errors));
                    }
                    if (!$ppmp_cse->save(false)) {
                        throw new ErrorException('PPMP CSE item Save Failed');
                    }
                }
                $transaction->commit();
                return json_encode(['isSuccess' => true]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
        }
    }
    public function actionImportNonCse()
    {
        if (Yii::$app->request->post()) {
            try {
                $transaction = Yii::$app->db->beginTransaction();
                $model = new UploadForm();
                $file_path = '';
                if (isset($_FILES['file'])) {
                    $id = uniqid();
                    $file = $_FILES;
                    $file = \yii\web\UploadedFile::getInstanceByName('file');
                    $model->file = $file;
                    $path =  Yii::$app->basePath . "\imports";
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
                $worksheet = $excel->getActiveSheet();


                foreach ($worksheet->getRowIterator(2) as $key => $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    $y = 0;
                    foreach ($cellIterator as $x => $cell) {

                        $cells[] =   $cell->getValue();
                    }

                    // NON CSE
                    $budget_year = $cells[0];
                    $office = $cells[1];
                    $division = $cells[2];
                    $division_program_unit = $cells[3];
                    $type = $cells[4];
                    $activity_name = trim($cells[5]);
                    $is_early_procurement = strtolower(trim($cells[6]));
                    $fund_source  = $cells[7];
                    $mode_of_procurement  = $cells[8];
                    $stock_name = $cells[9];
                    $quantity = $cells[10];
                    $unit_of_measure = $cells[11];
                    $gross_amount = $cells[12];
                    $description = $cells[13];
                    $mode_of_procurement_id = Yii::$app->db->createCommand("SELECT id FROM `pr_mode_of_procurement` WHERE pr_mode_of_procurement.mode_name = :mode_of_procurement")
                        ->bindValue(':mode_of_procurement', $mode_of_procurement)->queryScalar();
                    if (empty($mode_of_procurement_id)) {
                        throw new ErrorException($mode_of_procurement . 'Mode of Procurement not exists in line' . $key);
                    }
                    $fund_source_id = Yii::$app->db->createCommand("SELECT id FROM fund_source WHERE fund_source.name = :fund_source")->bindValue(':fund_source', $fund_source)->queryScalar();
                    if (empty($fund_source_id)) {
                        throw new ErrorException($fund_source . ' Fund Source Does not exists in line' . $key);
                    }
                    $office_id = Yii::$app->db->createCommand("SELECT id FROM office WHERE office.office_name = :office")->bindValue(':office', $office)->queryScalar();
                    if (empty($office_id)) {
                        throw new ErrorException($office . ' Office Does not exists in line' . $key);
                    }
                    $division_id = Yii::$app->db->createCommand("SELECT id FROM divisions WHERE divisions.division = :division")->bindValue(':division', $division)->queryScalar();
                    if (empty($division_id)) {

                        throw new ErrorException($division . ' Division Does not exists in line' . $key);
                    }
                    $division_program_unit_id = Yii::$app->db->createCommand("SELECT id FROM division_program_unit WHERE division_program_unit.name = :nme")->bindValue(':nme', $division_program_unit)->queryScalar();
                    if (empty($division_program_unit_id)) {

                        throw new ErrorException($division_program_unit . ' division_program_unit Does not exists in line' . $key);
                    }
                    $stock_id = Yii::$app->db->createCommand("SELECT id FROM pr_stock WHERE pr_stock.stock_title = :stock_name")
                        ->bindValue(':stock_name', $stock_name)->queryScalar();
                    if (empty($stock_id)) {
                        throw new ErrorException($stock_name . ' Stock Name Does not exists in line' . $key);
                    }
                    $unit_of_measure_id = Yii::$app->db->createCommand("SELECT id FROM unit_of_measure WHERE unit_of_measure.unit_of_measure = :unit_of_measure")
                        ->bindValue(':unit_of_measure', $unit_of_measure)->queryScalar();
                    if (empty($unit_of_measure_id)) {
                        throw new ErrorException($unit_of_measure . ' unit of measure Does not exists in line' . $key);
                    }
                    $exists_act = Yii::$app->db->createCommand("SELECT supplemental_ppmp_non_cse.id FROM supplemental_ppmp_non_cse
                    JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
                     WHERE supplemental_ppmp_non_cse.activity_name = :activity_name
                     AND supplemental_ppmp.budget_year = :budget_year
                     AND supplemental_ppmp.fk_office_id = :office_id
                     AND supplemental_ppmp.fk_division_id = :division_id
                     
                     ")
                        ->bindValue(':activity_name', $activity_name)
                        ->bindValue(':budget_year', $budget_year)
                        ->bindValue(':office_id', $office_id)
                        ->bindValue(':division_id', $division_id)
                        ->queryScalar();
                    if (empty($exists_act)) {
                        $ppmp = new SupplementalPpmp();
                        $ppmp->budget_year = $budget_year;
                        $ppmp->cse_type = 'non_cse';
                        $ppmp->fk_office_id = $office_id;
                        $ppmp->fk_division_id = $division_id;
                        $ppmp->is_supplemental = 0;
                        $ppmp->fk_division_program_unit_id = $division_program_unit_id;
                        if (!$ppmp->validate()) {
                            throw new ErrorException(json_encode($ppmp->errors));
                        }
                        if (!$ppmp->save(false)) {
                            throw new ErrorException('PPMP Model Save Failed');
                        }
                        $ppmp_non_cse = new SupplementalPpmpNonCse();
                        $ppmp_non_cse->fk_supplemental_ppmp_id = $ppmp->id;
                        $ppmp_non_cse->type = $type;
                        $ppmp_non_cse->early_procurement = $is_early_procurement === 'yes' ? 1 : 0;
                        $ppmp_non_cse->fk_fund_source_id = $fund_source_id;
                        $ppmp_non_cse->activity_name = $activity_name;
                        $ppmp_non_cse->fk_mode_of_procurement_id = $mode_of_procurement_id;
                        if (!$ppmp_non_cse->validate()) {
                            throw new ErrorException(json_encode($ppmp_non_cse));
                        }
                        if (!$ppmp_non_cse->save(false)) {
                            throw new ErrorException('PPMP NON CSE Model Save Failed');
                        }
                        $exists_act = $ppmp_non_cse->id;
                    }
                    $ppmp_non_cse_item = new SupplementalPpmpNonCseItems();
                    $ppmp_non_cse_item->fk_supplemental_ppmp_non_cse_id = $exists_act;
                    $ppmp_non_cse_item->amount = $gross_amount;
                    $ppmp_non_cse_item->fk_pr_stock_id = $stock_id;
                    $ppmp_non_cse_item->description = $description;
                    $ppmp_non_cse_item->quantity = $quantity;
                    $ppmp_non_cse_item->fk_unit_of_measure_id = $unit_of_measure_id;
                    if (!$ppmp_non_cse_item->validate()) {
                        throw new ErrorException(json_encode($ppmp_non_cse_item->errors, $exists_act));
                    }
                    if (!$ppmp_non_cse_item->save(false)) {
                        throw new ErrorException('ppmp_non_cse_item Model Save Failed');
                    }
                }


                $transaction->commit();
                return json_encode(['isSuccess' => true]);
            } catch (ErrorException $e) {
                $transaction->rollback();
                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
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
    public function actionItemPrs()
    {

        if (YIi::$app->request->post()) {
            $type = Yii::$app->request->post('type');
            $id = Yii::$app->request->post('id');
            if (strtolower($type) === 'non_cse') {
                return json_encode(SupplementalPpmpNonCseItems::findOne($id)->purchaseRequestsDataA);
            }
            if (strtolower($type) === 'cse') {
                return json_encode(SupplementalPpmpCse::findOne($id)->purchaseRequestsDataA);
            }
        }
    }
}
