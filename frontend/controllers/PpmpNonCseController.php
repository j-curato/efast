<?php

namespace frontend\controllers;

use Yii;
use app\models\PpmpNonCse;
use app\models\PpmpNonCseItemCategories;
use app\models\PpmpNonCseItems;
use app\models\PpmpNonCseSearch;
use ErrorException;
use yii\db\ForeignKeyConstraint;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PpmpNonCseController implements the CRUD actions for PpmpNonCse model.
 */
class PpmpNonCseController extends Controller
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
     * Lists all PpmpNonCse models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PpmpNonCseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PpmpNonCse model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $query  = Yii::$app->db->createCommand("SELECT 
        ppmp_non_cse_items.id as item_id, 
        ppmp_non_cse_items.project_name,
        ppmp_non_cse_items.description,
        ppmp_non_cse_items.target_month,
        fund_source.`name` as fund_source_name,
        CONCAT(mfo_pap_code.`code` ,'-',mfo_pap_code.`name`) as mfo_name,
        responsibility_center.`name` end_user,
        
        
        pr_stock_type.type,
        ppmp_non_cse_item_categories.budget
        FROM ppmp_non_cse_items
        LEFT JOIN ppmp_non_cse_item_categories ON ppmp_non_cse_items.id = ppmp_non_cse_item_categories.ppmp_non_cse_item_id
        LEFT JOIN pr_stock_type ON ppmp_non_cse_item_categories.fk_stock_type = pr_stock_type.id
        LEFT JOIN responsibility_center ON ppmp_non_cse_items.fk_responsibility_center_id = responsibility_center.id
        LEFT JOIN mfo_pap_code ON ppmp_non_cse_items.fk_pap_code_id = mfo_pap_code.id
        LEFT JOIN fund_source ON ppmp_non_cse_items.fk_fund_source_id = fund_source.id
        
        
        WHERE 
        
         ppmp_non_cse_items.fk_ppmp_non_cse_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
        $result = ArrayHelper::index($query, null, 'item_id');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $result
        ]);
    }

    /**
     * Creates a new PpmpNonCse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertItems(
        $ppm_id,
        $project_name = [],
        $description = [],
        $target_month = [],
        $fund_source = [],
        $pap_code = [],
        $end_user = [],
        $stock_type = [],
        $categoriesAmount = []
    ) {

        try {
            foreach ($project_name as $index => $pjct_name) {

                $item = new PpmpNonCseItems();
                $item->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $item->project_name = $pjct_name;
                $item->target_month = !empty($target_month[$index]) ? $target_month[$index] : '';
                $item->fk_fund_source_id = !empty($fund_source[$index]) ? $fund_source[$index] : '';
                $item->fk_pap_code_id = !empty($pap_code[$index]) ? $pap_code[$index] : '';
                $item->fk_ppmp_non_cse_id = $ppm_id;
                $item->description = !empty($description[$index]) ? $description[$index] : '';
                $item->fk_responsibility_center_id = !empty($end_user[$index]) ? $end_user[$index] : '';
                if ($item->validate()) {
                    if ($item->save(false)) {
                        foreach ($stock_type[$index] as $stock_index => $stck_id) {
                            $category = new PpmpNonCseItemCategories();
                            // var_dump($categoriesAmount[$stock_index]);
                            // die();
                            $category->ppmp_non_cse_item_id =  $item->id;
                            $category->fk_stock_type =  $stck_id;
                            $category->budget =  !empty($categoriesAmount[$index][$stock_index]) ? $categoriesAmount[$index][$stock_index] : '';
                            if ($category->save(false)) {
                            }
                        }
                    }
                } else {
                    return $item->errors;
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new PpmpNonCse();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {


            $project_name = !empty($_POST['project_name']) ? $_POST['project_name'] : [];
            $description = !empty($_POST['description']) ? $_POST['description'] : [];
            $target_month = !empty($_POST['target_month']) ? $_POST['target_month'] : [];
            $fund_source = !empty($_POST['fund_source']) ? $_POST['fund_source'] : [];
            $pap_code = !empty($_POST['pap_code']) ? $_POST['pap_code'] : [];
            $end_user = !empty($_POST['end_user']) ? $_POST['end_user'] : [];
            $stock_type = !empty($_POST['stock_type']) ? $_POST['stock_type'] : [];
            $categoriesAmount = !empty($_POST['categoriesAmount']) ? $_POST['categoriesAmount'] : [];

            $transaction = YIi::$app->db->beginTransaction();
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            if ($model->save(false)) {
                $q = $this->insertItems(
                    $model->id,
                    $project_name,
                    $description,
                    $target_month,
                    $fund_source,
                    $pap_code,
                    $end_user,
                    $stock_type,
                    $categoriesAmount
                );

                if ($q === true) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return json_encode(['isSuccess' => false, 'error_message' => 'Error']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PpmpNonCse model.
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

        $items_query = Yii::$app->db->createCommand("SELECT 
         ppmp_non_cse_items.id as item_id, 
         ppmp_non_cse_items.project_name,
         ppmp_non_cse_items.description,
         ppmp_non_cse_items.target_month,
         ppmp_non_cse_items.fk_fund_source_id,
         ppmp_non_cse_items.fk_pap_code_id,
         mfo_pap_code.name as mfo_pap_name,
         responsibility_center.id as responsibility_center_id,
         responsibility_center.name as responsibility_center_name,
         pr_stock_type.id as stock_id,
         pr_stock_type.type,
         ppmp_non_cse_item_categories.id as category_id,
         ppmp_non_cse_item_categories.budget
         FROM ppmp_non_cse_items
         LEFT JOIN ppmp_non_cse_item_categories ON ppmp_non_cse_items.id = ppmp_non_cse_item_categories.ppmp_non_cse_item_id
         LEFT JOIN pr_stock_type ON ppmp_non_cse_item_categories.fk_stock_type = pr_stock_type.id
         LEFT JOIN responsibility_center ON ppmp_non_cse_items.fk_responsibility_center_id = responsibility_center.id
         LEFT JOIN mfo_pap_code ON ppmp_non_cse_items.fk_pap_code_id = mfo_pap_code.id
         WHERE 
         
          ppmp_non_cse_items.fk_ppmp_non_cse_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
        $result = ArrayHelper::index($items_query, null, 'item_id');
        return $this->render('update', [
            'model' => $model,
            'items' => $result
        ]);
    }

    /**
     * Deletes an existing PpmpNonCse model.
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
     * Finds the PpmpNonCse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PpmpNonCse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PpmpNonCse::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
