<?php

namespace frontend\controllers;

use app\models\Books;
use Yii;
use app\models\Cdr;
use app\models\CdrSearch;
use app\models\LiquidationReportingPeriod;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CdrController implements the CRUD actions for Cdr model.
 */
class CdrController extends Controller
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
                    'create',
                    'view',
                    'update',
                    'delete',
                    'cdr',
                    'cdr-final',
                    'insert-cdr'
                ],
                'rules' => [
                    [
                        'actions' => [

                            'create',

                            'update',
                            'delete',

                            'cdr-final',
                            'insert-cdr'
                        ],
                        'allow' => true,
                        'roles' => ['create_cdr']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'cdr',

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
     * Lists all Cdr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CdrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cdr model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }
        $query = (new \yii\db\Query())
            ->select(
                'check_date,
            check_number,
            particular,
            amount,
            withdrawals,
            gl_object_code,
            gl_account_title,
            reporting_period,
            vat_nonvat,
            expanded_tax
        '
            )
            ->from('advances_liquidation')
            ->where('reporting_period <=:reporting_period', ['reporting_period' => $model->reporting_period])
            ->andWhere('book_name =:book_name', ['book_name' => $model->book_name])
            ->andWhere('province LIKE :province', ['province' => $model->province])
            ->andWhere('advances_type LIKE :advances_type', ['advances_type' => $model->report_type])
            ->orderBy('reporting_period,check_date')
            ->all();
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => '',
            'reporting_period' => '',
            'province' => '',
            'consolidated' => '',
            'book' => '',
            'cdr' => '',
        ]);
    }

    /**
     * Creates a new Cdr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cdr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cdr model.
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
            'dataProvider' => '',
            'reporting_period' => '',
            'province' => '',
            'consolidated' => '',
            'book' => '',
            'cdr' => '',
        ]);
    }

    /**
     * Deletes an existing Cdr model.
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
     * Finds the Cdr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cdr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cdr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionCdr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $book  = $_POST['book'];
            $province = $_POST['province'];
            $advance_type = $_POST['report_type'];

            // $cdr = Yii::$app->memem->cdrFilterQuery($reporting_period, $book, $province, $advance_type);
            // $query = (new \yii\db\Query())
            //     ->select(
            //         'check_date,
            //         check_number,
            //         particular,
            //         amount,
            //         withdrawals,
            //         gl_object_code,
            //         gl_account_title,
            //         reporting_period,
            //         vat_nonvat,
            //         expanded_tax
            //     '
            //     )
            //     ->from('advances_liquidation')
            //     ->where('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
            //     ->orWhere('book =:book', ['book' => $book])
            //     ->andWhere('province LIKE :province', ['province' => $province])
            //     ->andWhere('report_type LIKE :report_type', ['report_type' => $advance_type])
            //     ->orderBy('reporting_period,check_date,check_number')
            //     ->all();
            $query = Yii::$app->db->createCommand("SELECT
                    liquidation.check_date,
                    liquidation.check_number,
                    liquidation.particular,
                     0 as amount,
                    liquidation_entries.withdrawals,
                    liquidation_entries.vat_nonvat,
                    liquidation_entries.expanded_tax,
                    liquidation_entries.reporting_period,
                    chart_of_accounts.uacs as gl_object_code,
                    chart_of_accounts.general_ledger as gl_account_title
                    
                    
                    
                    FROM liquidation_entries
                    LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id= chart_of_accounts.id
                    LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                    LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id  = advances_entries.id
                    LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id =  cash_disbursement.id
                    WHERE liquidation_entries.reporting_period = :reporting_period
                    AND cash_disbursement.book_id = :book
                    AND liquidation.province = :province
                    AND advances_entries.advances_type =:advances_type
                    
                    
                    UNION ALL
                    
                    SELECT 
                    cash_disbursement.issuance_date as check_date,
                    cash_disbursement.check_or_ada_no as check_number,
                    dv_aucs.particular,
                    advances_entries.amount ,
                     0 as withdrawals,
                    0 as vat_nonvat,
                    0 as expanded_tax,
                    advances_entries.reporting_period,
                    '' as gl_object_code,
                    '' as gl_account_title
                    FROM 
                    advances_entries
                    LEFT JOIN advances ON advances_entries.advances_id = advances.id
                    LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
                    LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
                    WHERE advances_entries.reporting_period = :reporting_period
                    AND cash_disbursement.book_id = :book
                    AND advances.province = :province
                    AND advances_entries.advances_type =:advances_type 
            
            
            
 
            ")->bindValue(':reporting_period',  $reporting_period)
                ->bindValue(':book', $book)
                ->bindValue(':province', $province)
                ->bindValue(':advances_type', $advance_type)
                ->queryAll();

            $advances_balance = 0;
            $liquidation_balance = 0;
            $advances_balance = Yii::$app->db->createCommand("SELECT ROUND(SUM(balance),2)as balance
                    FROM cdr_advances_balance
                    WHERE reporting_period <:reporting_period
                    AND book_id =:book
                    AND province LIKE :province
                    AND advances_type LIKE :advances_type")
                ->bindValue(':reporting_period',  $reporting_period)
                ->bindValue(':book', $book)
                ->bindValue(':province', $province)
                ->bindValue(':advances_type', $advance_type)
                ->queryScalar();
            $liquidation_balance = Yii::$app->db->createCommand("SELECT ROUND(SUM(total_withdrawals),2)as balance
                    FROM cdr_liquidation_balance
                    WHERE reporting_period <:reporting_period
                    AND book_id =:book
                    AND province LIKE :province
                    AND advances_type LIKE :advances_type")
                ->bindValue(':reporting_period',  $reporting_period)
                ->bindValue(':book', $book)
                ->bindValue(':province', $province)
                ->bindValue(':advances_type', $advance_type)
                ->queryScalar();
            $balance  = $advances_balance - $liquidation_balance;


            $result = ArrayHelper::index($query, null, [function ($element) {
                return $element['reporting_period'];
            }, 'gl_object_code']);
            // ob_clean();
            // echo "<pre>";
            // var_dump($liquidation_balance);
            // echo "</pre>";

            // return ob_get_clean();
            $consolidated = [];
            if (!empty($result[$reporting_period])) {

                foreach ($result[$reporting_period] as $key => $res) {
                    $gross_amount = 0;
                    $vat_nonvat = 0;
                    $expanded_tax = 0;
                    $account_title =  $res[0]['gl_account_title'];

                    foreach ($res as $data) {
                        $gross_amount += (float)$data['withdrawals'];
                        $vat_nonvat += (float)$data['vat_nonvat'];
                        $expanded_tax += (float)$data['expanded_tax'];
                    }

                    $consolidated[] = [
                        'object_code' => $key,
                        'account_title' => $account_title,
                        'gross_amount' => round($gross_amount, 2),
                        'vat_nonvat' => round($vat_nonvat, 2),
                        'expanded_tax' => round($expanded_tax, 2),
                        'gross_expense' => round($gross_amount + $vat_nonvat + $expanded_tax, 2)
                    ];
                }
            }
            $prov = [];
            $municipality = '';
            $officer = '';
            $location = '';

            $prov = Yii::$app->memem->cibrCdrHeader($province);
            $municipality = $prov['province'];
            $officer = $prov['officer'];
            $location = $prov['location'];
            // $q = array_sum($consolidated['gross_amount']);
            // return (['res' => $q]);
            // ob_clean();
            // echo "<pre>";
            // var_dump($query);
            // echo "</pre>";
            // return ob_get_clean();
            $book_name = Yii::$app->db->createCommand('SELECT books.name FROM books where books.id =:id')
                ->bindValue(':id', $book)->queryOne();
            return json_encode([
                'cdr' => $query,
                'consolidate' => $consolidated,
                'book' => $book_name['name'],
                'reporting_period' => date('F, Y', strtotime($reporting_period)),
                'municipality' => $municipality,
                'officer' => $officer,
                'location' => $location,
                'balance' => $balance,
                'advance_type' => $advance_type

            ]);

            // return $this->render('update', [
            //     'dataProvider' => $query,
            //     'reporting_period' => $reporting_period,
            //     'province' => $province,
            //     'consolidated' => $consolidated,
            //     'book' => $book_name,
            //     'cdr' => $cdr,
            //     'model' => ''


            // ]);
        } else {

            return $this->render('update', []);
        }
    }
    public function actionCdrFinal()
    {
        if ($_POST) {
            $id = $_POST['id'];
            try {
                $cdr = Cdr::findOne($id);
                $cdr->is_final = $cdr->is_final === 0 ? true : false;
                $cdr->serial_number = $this->getSerialNumber($cdr->reporting_period, $cdr->report_type, $cdr->book_name, $cdr->province);
                if ($cdr->save(false)) {
                    $r = Yii::$app->db->createCommand('SELECT reporting_period FROM liquidation_reporting_period 
                    WHERE reporting_period = :reporting_period ')
                        ->bindValue(':reporting_period', $cdr->reporting_period)
                        ->queryOne();
                    if (empty($r)) {

                        $liq_reporting_period = new LiquidationReportingPeriod();
                        $liq_reporting_period->reporting_period = $cdr->reporting_period;
                        $liq_reporting_period->province = $cdr->province;
                        if ($liq_reporting_period->save(false)) {
                        }
                    }

                    return json_encode(['isScuccess' => true, 'message' => 'success']);
                } else {
                }
            } catch (ErrorException $e) {
                return json_encode(['isScuccess' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    public function actionInsertCdr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $province = $_POST['province'];
            $book_name = $_POST['book'];
            $report_type = $_POST['report_type'];
            $query = (new \yii\db\Query())
                ->select('id')
                ->from('cdr')
                ->where('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                ->andWhere('province LIKE :province', ['province' => $province])
                ->andWhere('book_name LIKE :book_name', ['book_name' => $book_name])
                ->andWhere('report_type LIKE :report_type', ['report_type' => $report_type])
                ->one();
            if (!empty($query)) {
                return json_encode(['isSuccess' => false, 'error' => 'na save na ']);
            }
            $cdr = new Cdr();
            $cdr->reporting_period = $reporting_period;
            $cdr->province = $province;
            $cdr->book_name = $book_name;
            $cdr->report_type = $report_type;

            if ($cdr->validate()) {
                if ($cdr->save(false)) {
                    return json_encode(['isSuccess' => true, 'error' => 'Success', 'id' => $cdr->id]);
                }
            } else {
                return json_encode(['isSuccess' => false, 'error' => $cdr->errors]);
            }
        }
    }
    public function getSerialNumber($reporting_period, $report_type, $book_id, $province)
    {
        // $report_type = 'Advances for Operating Expenses';
        // $province = 'ADN';
        // $reporting_period = '2021-02';
        $book_name = Yii::$app->db->createCommand('SELECT books.name FROM books where id =:id')
            ->bindValue(':id', $book_id)
            ->queryOne();
        $serial_number = 'CDR ';
        if ($report_type === 'Advances for Operating Expenses') {
            $type = 'OPEX';
        } else if ($report_type === 'Advances to Special Disbursing Officer') {
            $type = 'SDO';
        }

        $serial_number .= $book_name['name'] . '-' . $type . '-' . strtoupper($province) . '-' . $reporting_period;

        return $serial_number;
    }
}
