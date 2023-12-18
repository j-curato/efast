<?php

namespace frontend\controllers;

use app\models\DvAccountingEntries;
use app\models\DvAucsEntries;
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
                    'remove-row'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete',
                            'remove-row'
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'create',
                            'delete',
                            'remove-row'
                        ],
                        'allow' => true,
                        'roles' => ['ro_payroll']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'remove-row' => ['POST'],
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
    public function checkObjectCode($remittance_payee_id, $ors_number, $payroll_number)
    {
        $query  = Yii::$app->db->createCommand("SELECT payee.account_name as payee,
        remittance_payee.object_code ,
    chart_of_accounts.general_ledger,
    chart_of_accounts.id as chart_id
    FROM remittance_payee
    LEFT JOIN chart_of_accounts ON remittance_payee.object_code = chart_of_accounts.uacs
    LEFT JOIN payee ON remittance_payee.payee_id = payee.id
     WHERE remittance_payee.id =:id")
            ->bindValue(':id', $remittance_payee_id)
            ->queryOne();
        $account_title = $query['general_ledger'] . '-ORS#' . $ors_number . '-PN#' . $payroll_number;
        if ($query['object_code'] !== '2020101000') {

            $account_title = $query['general_ledger'] . '-' . $query['payee'] . '-ORS# ' . $ors_number . '-PN# ' . $payroll_number;
        }
        $check_account_title = $this->createSubAccount($account_title, $query['object_code']);
        return $check_account_title;
    }
    public function insertItems($payroll_id, $remittance_payee, $payee_amounts, $payroll_number, $ors_number)
    {
        foreach ($remittance_payee as $index => $val) {
            $check_account_title = $this->checkObjectCode($val, $ors_number, $payroll_number);
            $normal_balance = $this->checkNormalBalance($check_account_title);
            // CHECK IF PAYROLL HAS DV
            $dv_id = Yii::$app->db->createCommand("SELECT dv_aucs.id FROM dv_aucs WHERE dv_aucs.payroll_id = :payroll_id")
                ->bindValue(':payroll_id', $payroll_id)
                ->queryScalar();
            $debit = 0;
            $credit = 0;
            $amount = !empty($payee_amounts[$index])  ? $payee_amounts[$index] : 0;
            if ($normal_balance === 'Debit') {
                $debit = $amount;
            } else {
                $credit = $amount;
            }
            $this->insertDvAccountingEntries($val, $debit, $credit, $payroll_id, $check_account_title);
            // $items = new DvAccountingEntries();
            // $items->remittance_payee_id = $val;
            // $items->debit = !empty($payee_amounts[$index])  && $normal_balance == 'Debit' ? $payee_amounts[$index] : 0;
            // $items->credit = !empty($payee_amounts[$index])  && $normal_balance == 'Credit' ? $payee_amounts[$index] : 0;
            // $items->object_code  = $check_account_title;
            // $items->payroll_id = $payroll_id;
            // if (!empty($dv_id)) {
            //     $items->dv_aucs_id = $dv_id;
            // }
            // if ($items->save(false)) {
            // } else {
            //     return false;
            // }
        }
        return true;
    }
    public function checkNormalBalance($object_code)
    {
        $normal_balance =
            YIi::$app->db->createCommand("SELECT normal_balance FROM chart_of_accounts WHERE  uacs = :uacs")
            ->bindValue(':uacs', explode('_', $object_code)[0])
            ->queryScalar();
        return $normal_balance;
    }
    public function insertDvAccountingEntries($remittance_payee_id = null, $debit = 0, $credit = 0, $payroll_id, $object_code)
    {
        $items = new DvAccountingEntries();
        $items->remittance_payee_id = $remittance_payee_id;
        $items->debit = $debit;
        $items->credit = $credit;
        $items->object_code  = $object_code;
        $items->payroll_id = $payroll_id;
        if (!empty($dv_id)) {
            $items->dv_aucs_id = $dv_id;
        }
        if ($items->save(false)) {
        } else {
            return false;
        }
    }
    public function createSubAccount($account_title, $parent_object_code)
    {

        $check_account_title = YIi::$app->db->createCommand("SELECT object_code FROM sub_accounts1 WHERE `name`=:_name")
            ->bindValue(':_name', $account_title)
            ->queryScalar();
        $uacs = '';
        $last_number = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(object_code,'_',-1)AS UNSIGNED) as last_number 
        FROM sub_accounts1 ORDER BY last_number DESC LIMIT 1 ")->queryScalar() + 1;
        for ($i = strlen($last_number); $i <= 4; $i++) {
            $uacs .= 0;
        }

        $parent_id = Yii::$app->db->createCommand("SELECT id FROM chart_of_accounts WHERE uacs = :uacs")
            ->bindValue(':uacs', $parent_object_code)
            ->queryScalar();
        if (empty($check_account_title)) {
            $sub_account = new SubAccounts1();
            $sub_account->chart_of_account_id = $parent_id;
            $sub_account->object_code = $parent_object_code . '_' . $uacs . $last_number;
            $sub_account->name = $account_title;
            if ($sub_account->save(false)) {
                $check_account_title = $sub_account->object_code;
            }
        }
        return $check_account_title;
    }
    public function createDueToBir($object_code, $ors_number, $payroll_number, $amount, $payroll_id)
    {
        $query = Yii::$app->db->createCommand("SELECT general_ledger FROM chart_of_accounts WHERE uacs =:uacs")
            ->bindValue(':uacs', $object_code)
            ->queryOne();
        $account_title = $query['general_ledger'] . '-ORS#' . $ors_number . '-PN#' . $payroll_number;
        $object_code = $this->createSubAccount($account_title, $object_code);

        $normal_balance = YIi::$app->db->createCommand("SELECT normal_balance FROM chart_of_accounts WHERE  uacs = :uacs")
            ->bindValue(':uacs', explode('_', $object_code)[0])
            ->queryScalar();

        $debit = 0;
        $credit = 0;
        if ($normal_balance === 'Debit') {
            $debit = $amount;
        } else {
            $credit = $amount;
        }
        $this->insertDvAccountingEntries(null, $debit, $credit, $payroll_id, $object_code);
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
                // return $model->due_to_bir_amount;
                // $this->createDueToBir(2020101000, $model->processOrs->serial_number, $model->payroll_number, $model->due_to_bir_amount, $model->id);
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
            $remittance_payee = !empty($_POST['remittance_payee']) ? $_POST['remittance_payee'] : [];
            $payee_amounts = !empty($_POST['payee_amount']) ? $_POST['payee_amount'] : [];
            $transaction = Yii::$app->db->beginTransaction();
            // Yii::$app->db->createCommand("DELETE FROM payroll_items WHERE payroll_id = :id")
            //     ->bindValue(':id', $model->id)
            //     ->query();
            try {
                if ($flag = $model->save(false)) {
                    YIi::$app->db->createCommand("UPDATE dv_accounting_entries SET credit = :debit WHERE dv_accounting_entries.payroll_id =:payroll_id
                    AND remittance_payee_id IS NULL AND object_code LIKE :object_code")
                        ->bindValue(':debit', $model->due_to_bir_amount)
                        ->bindValue(':payroll_id', $model->id)
                        ->bindValue(':object_code', '2020101000%')
                        ->query();
                    $flag =  $this->insertItems(
                        $model->id,
                        $remittance_payee,
                        $payee_amounts,
                        $model->payroll_number,
                        $model->processOrs->serial_number
                    );
                    if (!empty($model->dvAucs->id)) {
                        $query = Yii::$app->db->createCommand("SELECT  
                        payroll.payroll_number,
                        payroll.reporting_period,
                        process_ors.serial_number as ors_number,
                        process_ors.id as ors_id,
                        payroll.amount as amount_disbursed,
                        (CASE
                        WHEN payroll.type = 2307 THEN IFNULL(due_to_bir.total_due_to_bir,0) + IFNULL(payroll.due_to_bir_amount,0)
                        ELSE 0
                        END) as ewt,
                       (CASE
                       WHEN payroll.type = '1601c' THEN IFNULL(due_to_bir.total_due_to_bir,0) + IFNULL(payroll.due_to_bir_amount,0)
                       ELSE 0
                       END) as compensation,
                        IFNULL(trust_liab.total_trust_liab,0) as total_trust_liab
                        FROM payroll
                        LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
                        LEFT JOIN (SELECT SUM(dv_accounting_entries.debit) + SUM(dv_accounting_entries.credit)as total_due_to_bir,
                        dv_accounting_entries.payroll_id 
                        FROM dv_accounting_entries
                        WHERE  dv_accounting_entries.object_code LIKE '2020101000%' 
                        AND dv_accounting_entries.remittance_payee_id IS NOT NULL
                        GROUP BY dv_accounting_entries.payroll_id) as due_to_bir  ON payroll.id = due_to_bir.payroll_id
                        LEFT JOIN (SELECT SUM(dv_accounting_entries.debit) + SUM(dv_accounting_entries.credit)as total_trust_liab,
                        dv_accounting_entries.payroll_id 
                        FROM dv_accounting_entries
                        WHERE  dv_accounting_entries.object_code NOT LIKE '2020101000%' 
                        AND dv_accounting_entries.remittance_payee_id IS NOT NULL
                        GROUP BY dv_accounting_entries.payroll_id) as trust_liab  
                        ON payroll.id = trust_liab.payroll_id
                        LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
                        LEFT JOIN payee ON `transaction`.payee_id = payee.id
                        WHERE payroll.id = :id")
                            ->bindValue(':id', $model->id)
                            ->queryOne();
                        $entry_id = Yii::$app->db->createCommand("SELECT id FROM dv_aucs_entries WHERE dv_aucs_id = :id")
                            ->bindValue(':id', $model->dvAucs->id)
                            ->queryOne();
                        $entry = DvAucsEntries::findOne($entry_id);
                        $entry->amount_disbursed = $query['amount_disbursed'];
                        $entry->ewt_goods_services = $query['ewt'];
                        $entry->compensation = $query['compensation'];
                        $entry->other_trust_liabilities = $query['total_trust_liab'];
                        $entry->process_ors_id = $query['ors_id'];
                        if ($entry->save(false)) {
                        }
                    }
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
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

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


        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Payroll::findOne($id)->payroll_number];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('CAST(payroll.id AS CHAR(50)) as id, payroll.payroll_number AS text')
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
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $query = Yii::$app->db->createCommand("SELECT
            payroll.payroll_number,
            payroll.type,
            payroll.reporting_period,
            process_ors.serial_number as ors_number,
            process_ors.book_id,
            process_ors.id as ors_id,
            payroll.amount as amount_disbursed,
            `transaction`.particular,
            payee.id as payee_id,
            payee.account_name as payee,
            IFNULL(due_to_bir.total_due_to_bir,0) + IFNULL(payroll.due_to_bir_amount,0) as total_due_to_bir,
            IFNULL(trust_liab.total_trust_liab,0) as total_trust_liab
            
             FROM payroll
             LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
            LEFT JOIN (SELECT SUM(dv_accounting_entries.debit) + SUM(dv_accounting_entries.credit)as total_due_to_bir,
            dv_accounting_entries.payroll_id 
            FROM dv_accounting_entries
            WHERE  dv_accounting_entries.object_code LIKE '2020101000%' 
            AND dv_accounting_entries.remittance_payee_id IS NOT NULL
            GROUP BY dv_accounting_entries.payroll_id) as due_to_bir  ON payroll.id = due_to_bir.payroll_id
            LEFT JOIN (SELECT SUM(dv_accounting_entries.debit) + SUM(dv_accounting_entries.credit)as total_trust_liab,
            dv_accounting_entries.payroll_id 
            FROM dv_accounting_entries
            WHERE  dv_accounting_entries.object_code NOT LIKE '2020101000%' 
            AND dv_accounting_entries.remittance_payee_id IS NOT NULL
            GROUP BY dv_accounting_entries.payroll_id) as trust_liab  
            ON payroll.id = trust_liab.payroll_id
            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            LEFT JOIN payee ON `transaction`.payee_id = payee.id
            WHERE payroll.id = :id
            ")

                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
    public function actionUpdateChild($id)
    {
        if ($_POST) {
            $model = DvAccountingEntries::findOne($id);
            $remittance_payee = !empty($_POST['remittance_payee_id']) ? $_POST['remittance_payee_id'] : null;
            $amount = !empty($_POST['amount']) ? $_POST['amount'] : 0;
            $query  = Yii::$app->db->createCommand("SELECT 
            process_ors.serial_number as ors_number,
            payroll.payroll_number
            FROM payroll
            LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
             WHERE payroll.id =:id")
                ->bindValue(':id', $model->payroll_id)
                ->queryOne();
            $check_account_title = $this->checkObjectCode($remittance_payee, $query['ors_number'], $query['payroll_number']);
            $normal_balance = YIi::$app->db->createCommand("SELECT normal_balance FROM chart_of_accounts WHERE  uacs = :uacs")
                ->bindValue(':uacs', explode('_', $check_account_title)[0])
                ->queryScalar();
            $model->debit = !empty($amount)  && $normal_balance == 'Debit' ? $amount : 0;
            $model->credit = !empty($amount)  && $normal_balance == 'Credit' ? $amount : 0;
            $model->remittance_payee_id  = $remittance_payee;
            $model->object_code  = $check_account_title;

            if ($model->save(false)) {

                return json_encode("succes");
            }
        }
    }
    public function actionRemoveRow($id)
    {
        if (Yii::$app->request->isPost) {
            DvAccountingEntries::findOne($id)->delete();
            return json_encode(['isSuccess' => true]);
        }
    }
    public function actionPayrollItems()
    {

        if ($_POST) {
            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT 
            payroll.payroll_number,
            process_ors.serial_number as ors_number,
            dv_aucs.dv_number,
            dv_accounting_entries.object_code,
            accounting_codes.account_title,
            payroll.amount as amount_disbursed,
            payee.account_name as payee,
            dv_accounting_entries.id as dv_accounting_entries_id,
            dv_accounting_entries.credit + dv_accounting_entries.debit as amount
            FROM payroll
            LEFT JOIN dv_aucs ON payroll.id = dv_aucs.payroll_id
            LEFT JOIN dv_accounting_entries ON payroll.id = dv_accounting_entries.payroll_id
            LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
            LEFT JOIN accounting_codes ON dv_accounting_entries.object_code =  accounting_codes.object_code
            LEFT JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
            LEFT JOIN payee ON remittance_payee.payee_id = payee.id
            WHERE 
            payroll.id = :id
            ")
                ->bindValue(':id', $id)
                ->queryAll();
            return json_encode($query);
        }
    }
}
