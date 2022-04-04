<?php

namespace frontend\controllers;

use Yii;
use app\models\ConsoTrialBalance;
use app\models\ConsoTrialBalanceSearch;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConsoTrialBalanceController implements the CRUD actions for ConsoTrialBalance model.
 */
class ConsoTrialBalanceController extends Controller
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
                    'create',
                    'delete',
                    'view',
                    'index',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'view',
                            'delete',
                            'index',
                            'create',
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
     * Lists all ConsoTrialBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ConsoTrialBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ConsoTrialBalance model.
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
     * Creates a new ConsoTrialBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ConsoTrialBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ConsoTrialBalance model.
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
     * Deletes an existing ConsoTrialBalance model.
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
     * Finds the ConsoTrialBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ConsoTrialBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConsoTrialBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGenerateConsoTrialBalance()
    {
        if ($_POST) {
            $params = [];
            // return json_encode(Yii::$app->db->createCommand("SELECT * FROM jev_preparation WHERE $book_sql",$params)->getRawSql());


            $to_reporting_period = $_POST['reporting_period'];
            $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
            $month  = $r_period_date->format('F Y');
            $year = $r_period_date->format('Y');
            $from_reporting_period = $year . '-01';
            $book_type  = $_POST['book_type'];
            $entry_type = strtolower($_POST['entry_type']);
            $book_name = strtoupper($book_type);
            $and = '';
            $sql = '';
            $type = '';

            // return json_encode($entry_type);
            if ($entry_type !== 'post-closing') {
                $and = 'AND ';
                if ($entry_type === 'pre-closing') {
                    $type = 'Non-Closing';
                } else if ($entry_type = 'closing') {
                    $type = 'Closing';
                }
                $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['=', 'jev_preparation.entry_type', $type], $params);
            }
            $book_and = '';
            $book_sql1 = '';
            $book_sql2 = '';
            $book_sql3 = '';
            $book_params = [];
            if ($book_type !== 'all') {
                $book_and = 'AND ';
                $book_sql1 = Yii::$app->db->getQueryBuilder()->buildCondition("EXISTS (SELECT id FROM books WHERE books.`type` =:book_type AND jev_preparation.book_id = books.id)", $book_params);
                $book_sql2 = Yii::$app->db->getQueryBuilder()->buildCondition("EXISTS (SELECT id FROM books WHERE books.`type` =:book_type AND jev_beginning_balance.book_id = books.id)", $book_params);

                // EXISTS (SELECT id FROM books WHERE books.`name` IN ('Fund 01','Rapid LP') AND jev_beginning_balance.book_id = books.id)
            }

            $query = Yii::$app->db->createCommand("SELECT 
            chart_of_accounts.uacs as object_code,
            chart_of_accounts.general_ledger as account_title,
            chart_of_accounts.normal_balance,
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) as total_debit_credit,
            begin_balance.total_beginning_balance as begin_balance
             FROM (
            SELECT
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as obj_code
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.reporting_period <=:to_reporting_period
             $book_and $book_sql1
            GROUP BY obj_code
            ) as jev_object_codes
            
            LEFT JOIN (
            SELECT

            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit,
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as chart
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
             jev_preparation.reporting_period >=:from_reporting_period
            AND jev_preparation.reporting_period <=:to_reporting_period
            $book_and $book_sql1
            $and $sql
            GROUP BY chart) as accounting_entries ON jev_object_codes.obj_code = accounting_entries.chart
            LEFT JOIN (SELECT 

                accounting_codes.object_code,
                (CASE
                    WHEN accounting_codes.normal_balance = 'Debit' THEN SUM(jev_beginning_balance_item.debit)  - SUM(jev_beginning_balance_item.credit)
                    ELSE SUM(jev_beginning_balance_item.credit) - SUM(jev_beginning_balance_item.debit)
                END) as total_beginning_balance
                FROM jev_beginning_balance_item 
              LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
              LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
              LEFT JOIN books ON jev_beginning_balance.book_id = books.id
              WHERE 
                    jev_beginning_balance.`year` = :_year
                    $book_and $book_sql2
            
            GROUP BY accounting_codes.object_code
            ) as begin_balance  ON jev_object_codes.obj_code = begin_balance.object_code
            LEFT JOIN chart_of_accounts ON jev_object_codes.obj_code = chart_of_accounts.uacs
            
            WHERE 
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) !=0
            ORDER BY chart_of_accounts.uacs  ASC
            ", $params)
                ->bindValue(':_year', $year)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':book_type', $book_type)
                ->queryAll();;
            // return json_encode($query->getRawSql());

            return json_encode(['result' => $query, 'month' => $month, 'book_name' => $book_name]);
        }

        return $this->render('conso_trial_balance');
    }
}
