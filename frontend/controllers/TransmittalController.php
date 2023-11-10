<?php

namespace frontend\controllers;

use Yii;
use app\models\Transmittal;
use app\models\TransmittalEntries;
use app\models\TransmittalSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransmittalController implements the CRUD actions for Transmittal model.
 */
class TransmittalController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                    'insert-transmittal'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'view',
                            'create',
                            'insert-transmittal'
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'index',
                            'update',
                            'view',
                            'create',
                            'insert-transmittal'
                        ],
                        'allow' => true,
                        'roles' => ['ro_transmittal']
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
    private function getItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        transmittal_entries.id as item_id,
        dv_aucs.id as dv_id,
        cash_disbursement.issuance_date,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.reporting_period,
        payee.account_name as payee,
        dv_aucs.particular,
        dv_aucs.dv_number,
        t_dv.amtDisbursed,
        t_dv.taxWitheld,
        cash_disbursement.is_cancelled
        FROM transmittal_entries
        JOIN dv_aucs ON transmittal_entries.fk_dv_aucs_id = dv_aucs.id
        JOIN cash_disbursement_items ON dv_aucs.id = cash_disbursement_items.fk_dv_aucs_id
        JOIN cash_disbursement ON cash_disbursement_items.fk_cash_disbursement_id = cash_disbursement.id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        LEFT JOIN (SELECT 
        dv_aucs_entries.dv_aucs_id,
        SUM(dv_aucs_entries.amount_disbursed)as amtDisbursed,
        SUM(COALESCE(dv_aucs_entries.vat_nonvat,0) + COALESCE(dv_aucs_entries.ewt_goods_services,0)+COALESCE(dv_aucs_entries.compensation,0))as taxWitheld
        FROM dv_aucs_entries 
        WHERE dv_aucs_entries.is_deleted = 0
        GROUP BY dv_aucs_entries.dv_aucs_id ) as t_dv ON dv_aucs.id = t_dv.dv_aucs_id 
        WHERE 
         transmittal_entries.is_deleted = 0
         AND transmittal_entries.transmittal_id = :id
        AND cash_disbursement_items.is_deleted = 0
        AND cash_disbursement.is_cancelled = 0
        AND NOT EXISTS (SELECT c.parent_disbursement FROM cash_disbursement  c WHERE c.is_cancelled  = 1
        AND c.parent_disbursement IS NOT NULL AND c.parent_disbursement  = cash_disbursement.id)
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function insertItems($model_id, $items, $is_update = false)
    {
        try {
            if ($is_update === true) {

                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = '';
                if (!empty($itemIds)) {
                    $sql = ' AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                }
                Yii::$app->db->createCommand("UPDATE transmittal_entries SET is_deleted = 1 
                WHERE 
                transmittal_entries.is_deleted = 0
                AND transmittal_entries.transmittal_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {


                if (!empty($itm['item_id'])) {
                    $mdl = TransmittalEntries::findOne($itm['item_id']);
                } else {

                    $mdl = new TransmittalEntries();
                }
                $mdl->transmittal_id = $model_id;
                $mdl->fk_dv_aucs_id = $itm['dv_id'];
                if (!$mdl->validate()) {
                    throw new ErrorException(json_encode($mdl->errors));
                }
                if (!$mdl->save(false)) {
                    throw new ErrorException('Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    /**
     * Lists all Transmittal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transmittal model.
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
     * Creates a new Transmittal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transmittal();
        $model->fk_approved_by = 99684622555676858;
        if ($model->load(Yii::$app->request->post())) {
            try {
                $items = Yii::$app->request->post('items') ?? [];
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                $model->transmittal_number =  $this->getTransmittalNumber($model->date);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItem = $this->insertItems($model->id, $uniqueItems);
                if ($insItem !== true) {
                    throw new ErrorException($insItem);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Transmittal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                $items = Yii::$app->request->post('items') ?? [];
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItem = $this->insertItems($model->id, $uniqueItems, true);
                if ($insItem !== true) {
                    throw new ErrorException($insItem);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }
        return $this->render('update', [
            'model' => $model,
            // 'items' => $this->getItems($id)
        ]);
    }

    /**
     * Deletes an existing Transmittal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        // return $this->redirect(['index']);
    }

    /**
     * Finds the Transmittal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transmittal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transmittal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionInsertTransmittal()
    {
        if ($_POST) {
            $date = $_POST['date'];
            $cash_disbursement_id = $_POST['cash_disbursement_id'];
            $update_id  = !empty($_POST['update_id']) ? $_POST['update_id'] : '';

            $transmittal_number =  $this->getTransmittalNumber($date);

            if (!empty($update_id)) {
                $tr = Transmittal::findOne($update_id);
                foreach ($tr->transmittalEntries as $q) {
                    $q->delete();
                }
            } else {

                $tr = new Transmittal();
                $tr->transmittal_number = $transmittal_number;
            }
            $tr->location = 'COA';
            $tr->date = $date;
            if ($tr->validate()) {
                if ($tr->save(false)) {

                    foreach ($cash_disbursement_id as $val) {
                        $tr_entries = new TransmittalEntries();
                        $tr_entries->cash_disbursement_id = $val;
                        $tr_entries->transmittal_id = $tr->id;
                        if ($tr_entries->validate()) {
                            if ($tr_entries->save(false)) {
                            }
                        } else {
                            return json_encode(['isSuccess' => false, 'error' => $tr_entries->errors]);
                            die();
                        }
                    }
                }
            } else {

                return json_encode(['isSucces' => false, 'error' => $tr->errors]);
            }

            return json_encode(['isSuccess' => true, 'id' => $tr->id]);
        }
    }
    public function getTransmittalNumber($date)
    {
        $query = Yii::$app->db->createCommand("SELECT SUBSTRING_INDEX(transmittal_number,'-',-1) as q 
        FROM transmittal
        ORDER BY q DESC LIMIT 1")->queryScalar();
        $id = 1;
        if (!empty($query)) {
            $id = $query + 1;
        }
        // $final_id = '';
        // for ($y = strlen($id); $y < 4; $y++) {
        //     $final_id .= 0;
        // }
        // $final_id .= $id;

        $final_id = substr(str_repeat(0, 4) . $id, -4);
        $transmittal_number = 'RO-' . date('Y', strtotime($date)) . '-' . $final_id;
        return $transmittal_number;
    }
}
