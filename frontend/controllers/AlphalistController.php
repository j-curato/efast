<?php

namespace frontend\controllers;

use Yii;
use app\models\Alphalist;
use app\models\AlphalistSearch;
use DateTime;
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
                    'generate',
                    'final'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'create',
                            'update',
                            'delete',
                            'generate',
                            'final'
                        ],
                        'allow' => true,
                        'roles' => ['po_alphalist', 'super-user']
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
        if (!YIi::$app->user->can('ro_accounting_admin')) {
            $user_data = Yii::$app->memem->getUserData();
            $model->province = strtolower($user_data->office->office_name);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->id = YIi::$app->db->createCommand("SELECT UUID_SHORT() %9223372036854775807")->queryScalar();

            $model->alphalist_number = $this->alphalistNumber($model->province);

            if ($model->save(false)) {
                $d = new DateTime($model->check_range);
                $query =  Yii::$app->db->createCommand("UPDATE liquidation_entries  SET fk_alphalist_id = :id
                WHERE  EXISTS (SELECT z.id FROM (SELECT
                    x.id
                    FROM liquidation_entries as x
                    INNER JOIN liquidation ON x.liquidation_id = liquidation.id
                    INNER JOIN advances_entries ON x.advances_entries_id = advances_entries.id 
                    WHERE liquidation.province = :province
                    AND liquidation.check_date >='2022-04-01'
                    AND  liquidation.check_date <= :to_date
                    AND liquidation.is_cancelled !=1
                   AND x.fk_alphalist_id IS NULL
                    )  as z
                    WHERE   z.id =  liquidation_entries.id 
                    )
                ")
                    ->bindValue(':id', $model->id)
                    ->bindValue(':to_date', $d->format('Y-m-t'))
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post())) {
        if ($model->status === 10) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        if ($model->save(false)) {

            $d = new DateTime($model->check_range);
            // echo $d->format('Y-m-t');

            return Yii::$app->db->createCommand("UPDATE liquidation_entries  SET fk_alphalist_id = :id
              WHERE  EXISTS (SELECT z.id FROM (SELECT
                x.id
                FROM liquidation_entries as x
                INNER JOIN liquidation ON x.liquidation_id = liquidation.id
                INNER JOIN advances_entries ON x.advances_entries_id = advances_entries.id 
                WHERE liquidation.province = :province
                AND liquidation.check_date >='2022-04-01'
                AND  liquidation.check_date <= :to_date
                AND liquidation.is_cancelled !=1
               AND x.fk_alphalist_id IS NULL
                )  as z
                WHERE   z.id =  liquidation_entries.id 
                )
            ")
                ->bindValue(':id', $model->id)
                ->bindValue(':to_date', $d->format('Y-m-t'))
                ->bindValue(':province', $model->province)
                ->getRawSql();

            return $this->redirect(['view', 'id' => $model->id]);
        }
        // }

        // return $this->render('update', [
        //     'model' => $model,
        // ]);
    }

    /**
     * Deletes an existing Alphalist model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        if (Yii::$app->user->can('super-user')) {
            $model = $this->findModel($id);
            Yii::$app->db->createCommand("UPDATE liquidation_entries SET liquidation_entries.fk_alphalist_id=null WHERE liquidation_entries.fk_alphalist_id = :id")
                ->bindValue(':id', $model->id)
                ->execute();
            $model->delete();
        }
        return $this->redirect(['index']);
    }

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


        $d = new DateTime($range);
        // echo $d->format('Y-m-t');

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
            -- INNER JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
            WHERE liquidation.province = :province
            AND liquidation.check_date >='2022-04-01'
            AND  liquidation.check_date  <= :_range
            AND $sql
            AND liquidation.is_cancelled !=1
           
            GROUP BY 
            liquidation.province,
            liquidation.id) as detailed
            INNER JOIN liquidation ON detailed.id = liquidation.id
            INNER JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
            ")
            ->bindValue(':_range', $d->format('Y-m-t'))
            ->bindValue(':province', $province)
            ->queryAll();
        $conso = Yii::$app->db->createCommand("SELECT 
        conso.*,
        books.name as book_name
        FROM (SELECT
                            liquidation.province,
                            -- cash_disbursement.book_id,
                            advances_entries.book_id,
                            liquidation_entries.reporting_period ,
                            IFNULL(SUM(liquidation_entries.vat_nonvat),0)+
                            IFNULL(SUM(liquidation_entries.expanded_tax),0) as total_tax
                            
                            FROM liquidation_entries
                            INNER JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                            INNER JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id 
                            -- INNER JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
                            WHERE liquidation.province = :province
                            AND liquidation.check_date >='2022-04-01'
                            AND  liquidation.check_date <= :_range
                            AND $sql
                           
                            GROUP BY 
                            liquidation.province,
                            -- cash_disbursement.book_id,
                            advances_entries.book_id,
                            liquidation_entries.reporting_period) as conso
                            LEFT JOIN books on conso.book_id = books.id")
            ->bindValue(':_range', $d->format('Y-m-t'))
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

        if (Yii::$app->request->post()) {
            $user_data = Yii::$app->memem->getUserData();
            $range = $_POST['range'] ?? [];
            // $query->where('province = :province', ['province' => $user_data->office->office_name]);
            $province = '';

            if (empty($range)) {
                return;
            }
            if (Yii::$app->user->can('ro_accounting_admin')) {
                $province = YIi::$app->request->post('province');
            } else {
                $province = $user_data->office->office_name;
            }
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
    public function actionFinal()
    {
        if ($_POST) {
            if (Yii::$app->user->can('super-user')) {
                $id = $_POST['id'];


                $model = $this->findModel($id);

                if ($model->status == 9) {
                    $model->status = 10;
                } else {
                    $model->status  = 9;
                }
                if ($model->save(false)) {
                    return json_encode(['isSuccess' => true]);
                } else {
                    return json_encode(['isSuccess' => false]);
                }
            }
        }
    }
}
