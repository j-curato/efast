<?php

namespace frontend\controllers;

use Yii;
use app\models\Alphalist;
use app\models\AlphalistSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * AlphalistController implements the CRUD actions for Alphalist model.
 */
class AlphalistController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'class' => [
                'class' => AccessControl::class,
                'only' => [
                    'view',
                    'index',
                    'create',
                    'update',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
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

    /**
     * Lists all Alphalist models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlphalistSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alphalist model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $params = [];
        $sql = Yii::$app->db->getQueryBuilder()->buildCondition("liquidation_entries.fk_alphalist_id ={$model->id}", $params);
        return $this->render('view', [
            'model' => $model,
            'res' => $this->generateQuery($model->province, $model->check_range, $sql, $model->id)
        ]);
    }

    /**
     * Creates a new Alphalist model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Alphalist();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $province = YIi::$app->user->identity->province;
            if (
                $province === 'adn' ||
                $province === 'ads' ||
                $province === 'sdn' ||
                $province === 'sds' ||
                $province === 'pdi'
            ) {
                $model->province = $province;
            }
            $model->alphalist_number = $this->alphalistNumber($model->province);

            if ($model->save(false)) {

                $query =  Yii::$app->db->createCommand("UPDATE liquidation_entries  SET fk_alphalist_id = :id
                WHERE  EXISTS (SELECT z.id FROM (SELECT
                    x.id
                    FROM liquidation_entries as x
                    INNER JOIN liquidation ON x.liquidation_id = liquidation.id
                    INNER JOIN advances_entries ON x.advances_entries_id = advances_entries.id 
                    INNER JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
                    WHERE liquidation.province = :province
                    AND liquidation.check_date >='2021-10-01'
                    AND  liquidation.check_date <=:to_date
                    AND liquidation.is_cancelled !=1
                   
                    )  as z
                    WHERE   z.id =  liquidation_entries.id 
                    )
                ")
                    ->bindValue(':id', $model->id)
                    ->bindValue(':to_date', $model->check_range)
                    ->bindValue(':province', $model->province)
                    ->query();

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Alphalist model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    /**
     * Deletes an existing Alphalist model.
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
     * Finds the Alphalist model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Alphalist the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alphalist::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function generateQuery($province, $range, $sql)
    {
        $detailed = Yii::$app->db->createCommand("SELECT 
        detailed.*,
        liquidation.dv_number,
        liquidation.check_number,
        po_transaction.payee
        FROM 
        (
        SELECT
            liquidation.province,
            liquidation.id,
            liquidation.check_date,
            IFNULL(SUM(liquidation_entries.withdrawals),0) as withdrawals,
            IFNULL(SUM(liquidation_entries.expanded_tax),0) as expanded_tax,
            IFNULL(SUM(liquidation_entries.vat_nonvat),0) as vat_nonvat,
            IFNULL(SUM(liquidation_entries.liquidation_damage),0) as liquidation_damage,
            IFNULL(SUM(liquidation_entries.withdrawals),0)+
            IFNULL(SUM(liquidation_entries.vat_nonvat),0)+
            IFNULL(SUM(liquidation_entries.liquidation_damage),0)+
            IFNULL(SUM(liquidation_entries.expanded_tax),0) as gross_amount,

            IFNULL(SUM(liquidation_entries.vat_nonvat),0)+
            IFNULL(SUM(liquidation_entries.expanded_tax),0) as total_tax
            FROM liquidation_entries
            INNER JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
            INNER JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id 
            INNER JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
            WHERE liquidation.province = :province
            AND liquidation.check_date >='2022-04-01'
            AND  liquidation.check_date  LIKE :_range
            AND $sql
            AND liquidation.is_cancelled !=1
            GROUP BY 
            liquidation.province,
            liquidation.id) as detailed
            INNER JOIN liquidation ON detailed.id = liquidation.id
            INNER JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
            ")
            ->bindValue(':_range', $range.'%')
            ->bindValue(':province', $province)
            ->queryAll();
        $conso = Yii::$app->db->createCommand("SELECT 
        conso.*,
        books.name as book_name
        FROM (SELECT
                            liquidation.province,
                            cash_disbursement.book_id,
                            liquidation.reporting_period ,
                            IFNULL(SUM(liquidation_entries.vat_nonvat),0)+
                            IFNULL(SUM(liquidation_entries.expanded_tax),0) as total_tax
                            
                            FROM liquidation_entries
                            INNER JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                            INNER JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id 
                            INNER JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
                            WHERE liquidation.province = :province
                            AND liquidation.check_date >='2022-04-01'
                            AND  liquidation.check_date LIKE :_range
                            AND $sql
                            GROUP BY 
                            liquidation.province,
                            cash_disbursement.book_id,
                            liquidation.reporting_period) as conso
                            LEFT JOIN books on conso.book_id = books.id")
            ->bindValue(':_range', $range.'%')
            ->bindValue(':province', $province)
            ->queryAll();
        $reporting_periods  = array_unique(array_column($conso, 'reporting_period'));
        $conso_result = ArrayHelper::index($conso, 'reporting_period', 'book_name');
        return json_encode([
            'detailed' => $detailed,
            'conso' => $conso_result,
            'r' => $reporting_periods
        ]);
    }
    public function actionGenerate()
    {

        if ($_POST) {

            if (empty($_POST['range'])) {
                return;
            }
            if (Yii::$app->user->identity->province !== 'ro_admin') {
                $province = Yii::$app->user->identity->province;
            } else {
                if (empty($_POST['province'])) {
                    return;
                }
                $province = $_POST['province'];
            }
            $range = $_POST['range'];
            $params = [];
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition('liquidation_entries.fk_alphalist_id IS NULL', $params);
            return $this->generateQuery($province, $range, $sql);
        }
    }
    public function alphalistNumber($province)
    {
        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(alphalist_number,'-',-1) AS UNSIGNED) as last_num FROM alphalist
        WHERE alphalist.province =:province
         ORDER BY last_num DESC LIMIT 1")
            ->bindValue(':province', $province)
            ->queryScalar();
        if (empty($last_num)) {
            $last_num = 1;
        } else {
            $last_num = intval($last_num) + 1;
        }
        $zero = '';
        for ($i = strlen($last_num); $i <= 4; $i++) {
            $zero .= 0;
        }
        return strtoupper($province) . '-' . $zero . $last_num;
    }
}
