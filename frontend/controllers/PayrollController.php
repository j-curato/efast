<?php

namespace frontend\controllers;

use Yii;
use app\models\Payroll;
use app\models\PayrollItems;
use app\models\PayrollSearch;
use app\models\SubAccounts1;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PayrollController implements the CRUD actions for Payroll model.
 */
class PayrollController extends Controller
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
                    'index',
                    'create',
                    'view',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'create',
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
     * Lists all Payroll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayrollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payroll model.
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
     * Creates a new Payroll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function insertItems($payroll_id, $remittance_payee, $payee_amounts, $payroll_number, $ors_number)
    {
        foreach ($remittance_payee as $index => $val) {
            $query  = Yii::$app->db->createCommand("SELECT payee.account_name as payee,remittance_payee.object_code ,
            chart_of_accounts.general_ledger,
            chart_of_accounts.id as chart_id
            FROM remittance_payee
            LEFT JOIN chart_of_accounts ON remittance_payee.object_code = chart_of_accounts.uacs
            LEFT JOIN payee ON remittance_payee.payee_id = payee.id
             WHERE remittance_payee.id =:id")
                ->bindValue(':id', $val)
                ->queryOne();
            $last_number = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(object_code,'_',-1)AS UNSIGNED) as last_number 
            FROM sub_accounts1 ORDER BY last_number DESC LIMIT 1 ")->queryScalar() + 1;
            $uacs = '';
            for ($i = strlen($last_number); $i <= 4; $i++) {
                $uacs .= 0;
            }
            $account_title = $query['general_ledger'] . '-ORS#' . $ors_number . '-PN#' . $payroll_number;
            if ($query['object_code'] !== '2020101000') {

                $account_title = $query['general_ledger'] . '-' . $query['payee'] . '-ORS# ' . $ors_number . '-PN# ' . $payroll_number;
            }
            $check_account_title = YIi::$app->db->createCommand("SELECT object_code FROM sub_accounts1 WHERE `name`=:_name")
                ->bindValue(':_name', $account_title)
                ->queryScalar();
            if (empty($check_account_title)) {
                $sub_account = new SubAccounts1();
                $sub_account->chart_of_account_id = $query['chart_id'];
                $sub_account->object_code = $query['object_code'] . '_' . $uacs . $last_number;
                $sub_account->name = $account_title;
                if ($sub_account->save(false)) {
                    $check_account_title = $sub_account->object_code;
                }
            }

            $items = new PayrollItems();
            $items->remittance_payee_id = $val;
            $items->amount = !empty($payee_amounts[$index]) ? $payee_amounts[$index] : 0;
            $items->object_code  = $check_account_title;
            $items->payroll_id = $payroll_id;
            if ($items->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }


    public function actionCreate()
    {
        $model = new Payroll();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->payroll_number = $this->payrollNumber($model->reporting_period);
            $remittance_payee = $_POST['remittance_payee'];
            $payee_amounts = $_POST['payee_amount'];
            if ($model->save()) {
                $this->insertItems(
                    $model->id,
                    $remittance_payee,
                    $payee_amounts,
                    $model->payroll_number,
                    $model->processOrs->serial_number



                );
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payroll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $remittance_payee = $_POST['remittance_payee'];
            $payee_amounts = $_POST['payee_amount'];
            $transaction = Yii::$app->db->beginTransaction();
            Yii::$app->db->createCommand("DELETE FROM payroll_items WHERE payroll_id = :id")
                ->bindValue(':id', $model->id)
                ->query();
            try {
                if ($flag = $model->save(false)) {
                    $flag =  $this->insertItems(
                        $model->id,
                        $remittance_payee,
                        $payee_amounts,
                        $model->payroll_number,
                        $model->processOrs->serial_number
                    );
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Payroll model.
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
     * Finds the Payroll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payroll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payroll::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function payrollNumber($reporting_period)
    {
        $query = YIi::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(payroll_number,'-',-1) AS UNSIGNED) as last_number
         FROM payroll ORDER BY last_number DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $zero .= 0;
        }
        return $reporting_period . '-' . $zero . $num;
    }
    public function actionSearchPayroll($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Payroll::findOne($id)->payroll_number];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('payroll.id, payroll.payroll_number AS text')
                ->from('payroll')
                ->where(['like', 'payroll.payroll_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionPayrollData()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT
            payroll.payroll_number,
            payroll.type,
            payroll.amount as amount_disbursed,
            IFNULL(due_to_bir.total_due_to_bir,0) + IFNULL(payroll.due_to_bir_amount,0) as total_due_to_bir,
            IFNULL(trust_liab.total_trust_liab,0) as total_trust_liab
            
             FROM payroll
            LEFT JOIN (SELECT SUM(payroll_items.amount)as total_due_to_bir,payroll_items.payroll_id 
            FROM payroll_items WHERE  payroll_items.object_code LIKE '2020101000%' GROUP BY payroll_items.payroll_id) as due_to_bir  ON payroll.id = due_to_bir.payroll_id
            LEFT JOIN (SELECT SUM(payroll_items.amount)as total_trust_liab,payroll_items.payroll_id 
            FROM payroll_items WHERE  payroll_items.object_code NOT LIKE '2020101000%' GROUP BY payroll_items.payroll_id) as trust_liab  
            ON payroll.id = trust_liab.payroll_id
            WHERE payroll.id = :id
            ")

                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
}
