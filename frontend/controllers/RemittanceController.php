<?php

namespace frontend\controllers;

use Yii;
use app\models\Remittance;
use app\models\RemittanceItems;
use app\models\RemittanceSearch;
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
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->remittance_number = $this->remittanceNumber($model->reporting_period);
            $dv_accounting_entry_id = $_POST['dv_accounting_entry_id'];
            $amount = $_POST['amount'];

            if ($model->save(false)) {
                $this->insertItems($model->id, $dv_accounting_entry_id, $amount);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
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

        if ($model->load(Yii::$app->request->post())) {
            $dv_accounting_entry_id = !empty($_POST['dv_accounting_entry_id']) ? $_POST['dv_accounting_entry_id'] : [];
            $amount = !empty($_POST['amount']) ? $_POST['amount'] : [];
            $remittance_items_id = !empty($_POST['remittance_items_id']) ? $_POST['remittance_items_id'] : [];
            if ($model->type === 'adjustment') {
                $model->payroll_id = null;
            } else {
                $model->payee_id = null;
            }
            if ($model->save(false)) {

                $params = [];

                $and = '';
                $sql = '';

                if (!empty($remittance_items_id)) {
                    $and = 'AND';
                    $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['AND NOT IN', 'remittance_items.id', $remittance_items_id], $params);
                }
                Yii::$app->db->createCommand("UPDATE remittance_items SET remittance_items.is_removed = 1
                 WHERE remittance_items.fk_remittance_id =:id
                 $and $sql

                ", $params)
                ->bindValue(':id', $model->id)
                ->query();
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
                    Yii::$app->db->createCommand("UPDATE dv_aucs_entries SET dv_aucs_entries.amount_disbursed = (SELECT SUM(remittance_items.amount) as amount

                    FROM remittance_items
                   WHERE remittance_items.fk_remittance_id = :remittance_id
                    AND remittance_items.is_removed = 0) WHERE dv_aucs_entries.dv_aucs_id =:dv_id")
                        ->bindValue(':dv_id', $model->dvAucs->id)
                        ->bindValue(':remittance_id', $model->id)
                        ->query();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
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
    public function actionDetails()
    {
        if ($_POST) {

            $id = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT 
            remittance.reporting_period,
            process_ors.id as ors_id,
            remittance.book_id,
            `transaction`.particular,
            process_ors.serial_number as ors_number,
            payee.account_name as payee,
            payee.id as payee_id,
            rem_items.amount
            
             FROM `remittance`
            LEFT JOIN (
            SELECT SUM(remittance_items.amount) as amount,remittance_items.fk_remittance_id 
            FROM remittance_items
           WHERE remittance_items.fk_remittance_id = :id
            AND remittance_items.is_removed = 0
            ) as rem_items ON remittance.id = rem_items.fk_remittance_id
            LEFT JOIN payroll ON remittance.payroll_id = payroll.id
            LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            LEFT JOIN payee ON `transaction`.payee_id  = payee.id
            
            ")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
}
