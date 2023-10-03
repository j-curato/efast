<?php

namespace frontend\controllers;

use Yii;
use app\models\RoAlphalist;
use app\models\RoAlphalistSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * RoAlphalistController implements the CRUD actions for RoAlphalist model.
 */
class RoAlphalistController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                    'index',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'view',
                            'create',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'view',
                            'create',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['ro_alphalist']
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
     * Lists all RoAlphalist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoAlphalistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RoAlphalist model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $params = [];

        if ($model->is_final == 1) {
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['=', "dv_aucs.fk_ro_alphalist_id", $model->id], $params);
        } else {
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition([
                'AND',
                ["<=", 'cash_disbursement.reporting_period', $model->reporting_period],
                ['>=', 'cash_disbursement.reporting_period', '2022-01'],
                ['!=', 'dv_aucs.is_cancelled', 1],
                'dv_aucs.fk_ro_alphalist_id IS  NULL'
            ], $params);
        }

        return $this->render('view', [
            'model' => $model,
            'data' => $this->generate($model->reporting_period, $sql, $model->id, $params)
        ]);
    }

    /**
     * Creates a new RoAlphalist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RoAlphalist();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->alphalist_number = $this->alphalistNumber();
            if ($model->save(false)) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RoAlphalist model.
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
     * Deletes an existing RoAlphalist model.
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
     * Finds the RoAlphalist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoAlphalist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RoAlphalist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function alphalistNumber()
    {

        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(ro_alphalist.alphalist_number,'-',-1) AS UNSIGNED) as last_num 
        FROM ro_alphalist ORDER BY last_num DESC LIMIT 1")
            ->queryScalar();
        $final_number = '';
        if (empty($last_num)) {

            $last_num = 1;
        } else {
            $last_num = intval($last_num) + 1;
        }
        for ($i = strlen($last_num); $i <= 4; $i++) {
            $final_number .= 0;
        }
        return '2022' . '-' . $final_number . $last_num;
    }
    public function generate($reporting_period, $sql = '', $id = '', $params = '')
    {

        // echo   $detailed_query = Yii::$app->db->createCommand("SELECT 
        // dv_aucs.dv_number,
        // cash_disbursement.check_or_ada_no as check_number,
        // cash_disbursement.issuance_date  as check_date,
        // IFNULL(dv.amount_disbursed,0) as amount_disbursed,
        // IFNULL(dv.vat_nonvat,0) as vat_nonvat,
        // IFNULL(dv.ewt_goods_services,0) as ewt_goods_services,
        // IFNULL(dv.compensation,0) as compensation,
        // IFNULL(dv.other_trust_liabilities,0) as other_trust_liabilities,
        // books.name as book_name
        // FROM (
        // SELECT 
        // dv_aucs.id,
        // SUM(dv_aucs_entries.amount_disbursed) as amount_disbursed,
        // SUM(dv_aucs_entries.vat_nonvat) as vat_nonvat,
        // SUM(dv_aucs_entries.ewt_goods_services) as ewt_goods_services,
        // SUM(dv_aucs_entries.compensation) as compensation,
        // SUM(dv_aucs_entries.other_trust_liabilities) as other_trust_liabilities
        // FROM dv_aucs
        // LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
        // INNER JOIN cash_disbursement ON dv_aucs.id  = cash_disbursement.dv_aucs_id

        // WHERE 
        // dv_aucs_entries.is_deleted = 0
        // AND 
        // $sql
        // GROUP BY 
        // dv_aucs.id) as dv
        // INNER JOIN dv_aucs ON dv.id  = dv_aucs.id
        // LEFT JOIN books ON dv_aucs.book_id  = books.id
        // INNER JOIN cash_disbursement ON dv_aucs.id  = cash_disbursement.dv_aucs_id
        // WHERE cash_disbursement.is_cancelled !=1

        // ", $params)
        //     ->getRawSql();
        $detailed_query = Yii::$app->db->createCommand("WITH 
            cte_goodCashDvs as (
                SELECT 
                    cash_disbursement_items.fk_dv_aucs_id,
                    cash_disbursement.check_or_ada_no as check_number,
                    cash_disbursement.issuance_date  as check_date,
                    cash_disbursement.reporting_period
                FROM cash_disbursement
                JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                WHERE 
                    cash_disbursement.is_cancelled = 0
                    AND cash_disbursement_items.is_deleted  = 0
                    AND NOT EXISTS (SELECT * FROM cash_disbursement as c WHERE c.is_cancelled = 1 AND c.parent_disbursement = cash_disbursement.id) 
                    )
            SELECT 
                dv_aucs.dv_number,
                cte_goodCashDvs.check_number,
                cte_goodCashDvs.check_date,
                books.name as book_name,
                COALESCE(dv.amount_disbursed,0) as amount_disbursed,
                COALESCE(dv.vat_nonvat,0) as vat_nonvat,
                COALESCE(dv.ewt_goods_services,0) as ewt_goods_services,
                COALESCE(dv.compensation,0) as compensation,
                COALESCE(dv.other_trust_liabilities,0) as other_trust_liabilities
            FROM (
            SELECT 
                dv_aucs.id,
                SUM(dv_aucs_entries.amount_disbursed) as amount_disbursed,
                SUM(dv_aucs_entries.vat_nonvat) as vat_nonvat,
                SUM(dv_aucs_entries.ewt_goods_services) as ewt_goods_services,
                SUM(dv_aucs_entries.compensation) as compensation,
                SUM(dv_aucs_entries.other_trust_liabilities) as other_trust_liabilities
            FROM dv_aucs
            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
            JOIN cte_goodCashDvs ON dv_aucs.id = cte_goodCashDvs.fk_dv_aucs_id
            WHERE 
                dv_aucs_entries.is_deleted = 0
                AND
                $sql
              
            GROUP BY dv_aucs.id) as dv
            JOIN  dv_aucs ON dv.id  = dv_aucs.id
            LEFT JOIN books ON dv_aucs.book_id  = books.id
            LEFT JOIN cte_goodCashDvs ON dv_aucs.id = cte_goodCashDvs.fk_dv_aucs_id", $params)
            ->queryAll();

        // $conso_query = Yii::$app->db->createCommand("SELECT conso.*,books.name as book_name 
        //         FROM (SELECT 
        //             dv_aucs.reporting_period,
        //             dv_aucs.book_id,
        //             COALESCE(SUM(dv_aucs_entries.vat_nonvat),0) as total_vat_nonvat,
        //             COALESCE(SUM(dv_aucs_entries.vat_nonvat),0) + COALESCE(SUM(dv_aucs_entries.ewt_goods_services),0) as total_tax,
        //             COALESCE(SUM(dv_aucs_entries.ewt_goods_services),0) as total_ewt_goods_services,
        //             COALESCE(SUM(dv_aucs_entries.compensation),0) as total_compensation
        //         FROM dv_aucs
        //         LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
        //         JOIN cash_disbursement_items ON dv_aucs.id = cash_disbursement_items.fk_dv_aucs_id
        //         JOIN cash_disbursement ON cash_disbursement_items.fk_cash_disbursement_id  = cash_disbursement.id
        //         WHERE 
        //             dv_aucs_entries.is_deleted = 0
        //             AND cash_disbursement_items.is_deleted = 0
        //             AND cash_disbursement.is_cancelled = 0
        //             AND
        //             $sql
        //             GROUP BY 
        //             dv_aucs.reporting_period,
        //             dv_aucs.book_id)as conso
        //         LEFT JOIN books ON conso.book_id = books.id", $params)
        //     ->queryAll();
        $conso_query = Yii::$app->db->createCommand("SELECT conso.*,books.name as book_name 
        FROM (SELECT 
            dv_aucs.reporting_period,
            dv_aucs.book_id,
            COALESCE(SUM(dv_aucs_entries.vat_nonvat),0)+
            COALESCE(SUM(dv_aucs_entries.ewt_goods_services),0) as total_tax,
            COALESCE(SUM(dv_aucs_entries.vat_nonvat),0) as total_vat_nonvat,
            COALESCE(SUM(dv_aucs_entries.ewt_goods_services),0) as total_ewt_goods_services,
            COALESCE(SUM(dv_aucs_entries.compensation),0) as total_compensation
            FROM dv_aucs
            JOIN cash_disbursement_items ON dv_aucs.id = cash_disbursement_items.fk_dv_aucs_id
            JOIN cash_disbursement ON cash_disbursement_items.fk_cash_disbursement_id  = cash_disbursement.id
            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
            WHERE
            dv_aucs_entries.is_deleted = 0
             AND cash_disbursement_items.is_deleted = 0
              AND cash_disbursement.is_cancelled = 0
            AND 
             $sql
            GROUP BY 
            dv_aucs.reporting_period,
            dv_aucs.book_id)as conso
        LEFT JOIN books ON conso.book_id = books.id", $params)
            ->queryAll();
        $reporting_periods  = array_unique(array_column($conso_query, 'reporting_period'));
        $conso_result = ArrayHelper::index($conso_query, 'reporting_period', 'book_name');
        $detailed_result = ArrayHelper::index($detailed_query, null, 'book_name');
        return json_encode([
            'detailed' => $detailed_result,
            'conso' => $conso_result,
            'reporting_periods' => $reporting_periods,
        ]);
    }
    public function actionGenerate()
    {
        if ($_POST) {
            $reporting_period  = $_POST['reporting_period'];
            $params = [];

            $sql = Yii::$app->db->getQueryBuilder()->buildCondition([
                'AND',
                ["<=", 'cte_goodCashDvs.reporting_period', $reporting_period],
                ['>=', 'cte_goodCashDvs.reporting_period', '2022-01'],
                ['=', 'dv_aucs.is_cancelled', 0],
                'dv_aucs.fk_ro_alphalist_id IS  NULL'
            ], $params);

            return $this->generate($reporting_period, $sql, '', $params);
        }
    }
    public function actionFinal($id, $reporting_period)
    {
        $model = $this->findModel($id);

        if ($model->is_final == true) {
            $model->is_final = false;
        } else {
            $model->is_final = true;
        }



        if ($model->save(false)) {
            if ($model->is_final == true) {

                Yii::$app->db->createCommand("UPDATE 
                    dv_aucs
                    SET 
                    dv_aucs.fk_ro_alphalist_id = :id
                    WHERE
                    EXISTS (
                    SELECT dv.id
                    FROM 
                    (
                            SELECT 
                            dv_aucs.id,
                            SUM(dv_aucs_entries.amount_disbursed) as amount_disbursed,
                            SUM(dv_aucs_entries.vat_nonvat) as vat_nonvat,
                            SUM(dv_aucs_entries.ewt_goods_services) as ewt_goods_services,
                            SUM(dv_aucs_entries.compensation) as compensation,
                            SUM(dv_aucs_entries.other_trust_liabilities) as other_trust_liabilities
                            FROM dv_aucs
                            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
                            INNER JOIN cash_disbursement ON dv_aucs.id  = cash_disbursement.dv_aucs_id
                            WHERE cash_disbursement.reporting_period <= :reporting_period
                            and cash_disbursement.reporting_period >= '2022-01'
                            AND dv_aucs.is_cancelled !=1
                            AND dv_aucs.fk_ro_alphalist_id IS  NULL
                            GROUP BY 
                            dv_aucs.id) as dv
                    WHERE 
                    dv.id = dv_aucs.id
                    
                    )")
                    ->bindValue(':id', $id)
                    ->bindValue(':reporting_period', $reporting_period)
                    ->query();
            } else {
                Yii::$app->db->createCommand("UPDATE dv_aucs SET fk_ro_alphalist_id = NULL WHERE fk_ro_alphalist_id  = :id")
                    ->bindValue(':id', $model->id)
                    ->execute();
            }


            return $this->actionView($id);
        }
    }
}
