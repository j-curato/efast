<?php

namespace frontend\controllers;

use Yii;
use app\models\Remittance;
use app\models\RemittanceItems;
use app\models\RemittanceSearch;
use app\models\WithholdingAndRemittanceSummarySearch;
use Codeception\GroupObject;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RemittanceController implements the CRUD actions for Remittance model.
 */
class RemittanceController extends Controller
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
                    'view',
                    'update',
                    'delete',
                    'create',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'update',
                            'delete',
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
     * Lists all Remittance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RemittanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Remittance model.
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
     * Creates a new Remittance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertItems($remittance_id, $dv_accounting_entry_id, $amount = [], $remittance_items_id = [])
    {
        if (empty($remittance_id) || empty($dv_accounting_entry_id)) {
            return false;
        }

        foreach ($dv_accounting_entry_id as $key => $val) {

            if (!empty($remittance_items_id[$key])) {
                $remittance_items = RemittanceItems::findOne($remittance_items_id[$key]);
            } else {
                $remittance_items = new RemittanceItems();
                $remittance_items->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            }

            $remittance_items->fk_remittance_id = $remittance_id;
            $remittance_items->fk_dv_acounting_entries_id = $val;
            $remittance_items->amount = !empty($amount[$key]) ? $amount[$key] : 0;
            if ($remittance_items->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new Remittance();

        $searchModel = new WithholdingAndRemittanceSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->remittance_number = $this->remittanceNumber($model->reporting_period);
            $dv_accounting_entry_id = $_POST['dv_accounting_entry_id'];
            $amount = $_POST['amount'];
            $model->payee_id = $_POST['remittance_payee'];

            if ($model->save(false)) {
                $this->insertItems($model->id, $dv_accounting_entry_id, $amount);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }


        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Remittance model.
     * If update is successful, the browser will be redirected to the d'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new WithholdingAndRemittanceSummarySearch();
        $searchModel->_newProperty = $model->type;
        $searchModel->payee_id = $model->payee_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($model->load(Yii::$app->request->post())) {
            $dv_accounting_entry_id = !empty($_POST['dv_accounting_entry_id']) ? $_POST['dv_accounting_entry_id'] : [];
            $amount = !empty($_POST['amount']) ? $_POST['amount'] : [];
            $remittance_items_id = !empty($_POST['remittance_items_id']) ? $_POST['remittance_items_id'] : [];
            $model->payee_id = $_POST['remittance_payee'];
            // var_dump($remittance_items_id);
            // die();
            if ($model->save(false)) {

                $params = [];

                $and = '';
                $sql = '';

                if (!empty($remittance_items_id)) {
                    $and = 'AND';
                    $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'remittance_items.id', $remittance_items_id], $params);
                    // var_dump($sql);
                    // echo "<br>";
                    // var_dump($params);
                }
                try {
                    Yii::$app->db->createCommand("UPDATE remittance_items SET remittance_items.is_removed = 1
                    WHERE remittance_items.fk_remittance_id =:id
                    $and $sql
                   ", $params)
                        ->bindValue(':id', $model->id)
                        ->query();
                    // Yii::$app->db->createCommand()
                    //     ->update('remittance_items', [' remittance_items.is_removed' => 1], $sql, $params)
                    //     ->bindValue(':id', $model->id)
                    //     ->execute();
                } catch (ErrorException $e) {
                    var_dump($e->getMessage());
                    die();
                }
                // die();
                $this->insertItems($model->id, $dv_accounting_entry_id, $amount, $remittance_items_id);
                if (!empty($model->dvAucs->id)) {
                    Yii::$app->db->createCommand("UPDATE `dv_accounting_entries` 
                    LEFT JOIN (SELECT 
                    dv_accounting_entries.object_code,
                    remittance_items.amount
                    FROM remittance_items
                    LEFT JOIN dv_accounting_entries ON remittance_items.fk_dv_acounting_entries_id = dv_accounting_entries.id
                    WHERE
                    remittance_items.fk_remittance_id= :remittance_id
                    AND remittance_items.is_removed = 0
                    ) as t2 ON dv_accounting_entries.object_code = t2.object_code
                    LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
                    SET
                    dv_accounting_entries.debit = IF( accounting_codes.normal_balance = 'Debit',  t2.amount, 0),
                    dv_accounting_entries.credit = IF( accounting_codes.normal_balance = 'Credit',  t2.amount, 0)
                    WHERE dv_aucs_id = :dv_id")
                        ->bindValue(':dv_id', $model->dvAucs->id)
                        ->bindValue(':remittance_id', $model->id)
                        ->query();
                    //     Yii::$app->db->createCommand("UPDATE dv_aucs_entries SET dv_aucs_entries.amount_disbursed = (SELECT SUM(remittance_items.amount) as amount

                    //     FROM remittance_items
                    //    WHERE remittance_items.fk_remittance_id = :remittance_id
                    //     AND remittance_items.is_removed = 0) WHERE dv_aucs_entries.dv_aucs_id =:dv_id")
                    //         ->bindValue(':dv_id', $model->dvAucs->id)
                    //         ->bindValue(':remittance_id', $model->id)
                    //         ->query();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Remittance model.
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
     * Finds the Remittance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Remittance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Remittance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function remittanceNumber($reporting_period)
    {
        $query = YIi::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(remittance_number,'-',-1) AS UNSIGNED) as last_number
         FROM remittance ORDER BY last_number DESC LIMIT 1")->queryScalar();
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
    public function actionSearchRemittance($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            // $out['results'] = ['id' => $id, 'text' => Payroll::findOne($id)->payroll_number];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('remittance.id, remittance.remittance_number AS text')
                ->from('remittance')
                ->where(['like', 'remittance.remittance_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionSearchRemittancePayee($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            // $out['results'] = ['id' => $id, 'text' => Payroll::findOne($id)->payroll_number];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('payee.id, payee.account_name AS text')
                ->from('remittance_payee')
                ->join('INNER JOIN ', 'dv_accounting_entries', 'remittance_payee.id  = dv_accounting_entries.remittance_payee_id')
                ->join('INNER JOIN ', 'payee', 'remittance_payee.payee_id = payee.id')
                ->where("dv_accounting_entries.dv_aucs_id IS NOT NULL")

                ->andWhere(['like', 'payee.account_name', $q])
                ->groupBy('payee.account_name');
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionDetails()
    {
        if ($_POST) {

            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand(" SELECT 
            remittance.reporting_period,
                        process_ors.id as ors_id,
                        remittance.book_id,
                        `transaction`.particular,
                        process_ors.serial_number as ors_number,
                        payee.account_name as payee,
                        payee.id as payee_id,
                        remittance_items.amount
            FROM remittance_items
            INNER JOIN dv_accounting_entries ON remittance_items.fk_dv_acounting_entries_id = dv_accounting_entries.id
            INNER JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
            INNER JOIN payroll ON dv_accounting_entries.payroll_id = payroll.id
            INNER JOIN payee ON remittance_payee.payee_id = payee.id
            INNER JOIN process_ors ON payroll.process_ors_id = process_ors.id
            INNER JOIN remittance ON remittance_items.fk_remittance_id = remittance.id
            INNER JOIN `transaction` ON process_ors.transaction_id  = `transaction`.id
            WHERE remittance_items.fk_remittance_id = :id
            AND remittance_items.is_removed = 0
            ")
                ->bindValue(':id', $id)
                ->queryAll();
            $remittance_data  = Yii::$app->db->createCommand("SELECT
            remittance.reporting_period,
            remittance.book_id,
            remittance.payee_id,
            payee.account_name as payee
            FROM remittance
             INNER JOIN payee ON remittance.payee_id = payee.id
             WHERE remittance.id  = :id
            ")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode(['remittance_items' => $query, 'remittance_details' => $remittance_data]);
        }
    }
    public function actionSearchPayee($q = null, $type = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($type === 'remittance_to_payee' && !is_null($q)) {
            $query = new Query();
            $query->select('payee.id, payee.account_name AS text')
                ->from('payroll')
                ->join('INNER JOIN', 'dv_accounting_entries', 'payroll.id = dv_accounting_entries.payroll_id')
                ->join('INNER JOIN', 'process_ors', 'payroll.process_ors_id = process_ors.id')
                ->join('INNER JOIN', 'dv_aucs', 'payroll.id = dv_aucs.payroll_id')
                ->join('INNER JOIN', 'remittance_payee', 'dv_accounting_entries.remittance_payee_id = remittance_payee.id')
                ->join('INNER JOIN', 'payee', ' remittance_payee.payee_id = payee.id')
                ->where(['like', 'payee.account_name', $q])
                ->groupBy(" payee.id ,
            payee.account_name ");
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('payee.id, payee.account_name AS text')
                ->from('payee')
                ->where(['like', 'payee.account_name', $q])
                ->andWhere('payee.isEnable = 1');

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
