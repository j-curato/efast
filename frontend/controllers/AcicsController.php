<?php

namespace frontend\controllers;

use Yii;
use app\models\Acics;
use app\models\AcicsCashItems;
use app\models\AcicsSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AcicsController implements the CRUD actions for Acics model.
 */
class AcicsController extends Controller
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
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
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

    private function getSerialNum($period)
    {
        $yr = DateTime::createFromFormat('Y-m-d', $period)->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(acics.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM acics  
            WHERE 
            acics.serial_number LIKE :yr
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr', $yr . '%')
            ->queryScalar();
        if (empty($qry)) {
            $qry = 1;
        }
        $num = '';
        if (strlen($qry) < 5) {
            $num .= str_repeat(0, 5 - strlen($qry));
        }
        $num .= $qry;
        return $period . '-' . $num;
    }

    private function insCashItems($model_id, $items, $isUpdate = false)
    {

        // $uniqueItms = array_unique(array_column($items, 'cash_id'));
        // echo json_encode($uniqueItms);
        // die();
        try {

            if ($isUpdate === true && !empty(array_column($items, 'item_id'))) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                echo Yii::$app->db->createCommand("UPDATE acics_cash_items SET is_deleted = 1 
                WHERE 
                acics_cash_items.is_deleted = 0
                AND acics_cash_items.fk_acic_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {

                if (!empty($itm['item_id'])) {
                    $model = AcicsCashItems::findOne($itm['item_id']);
                } else {
                    $model = new AcicsCashItems();
                }
                $model->fk_acic_id = $model_id;
                $model->fk_cash_disbursement_id = $itm['cash_id'];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Cash Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {

            return $e->getMessage();
        }
    }
    private function getCashItems($id)
    {
        $qry = Yii::$app->db->createCommand("SELECT 
        acics_cash_items.id as item_id,
        cash_disbursement.id as cash_id,
        cash_disbursement.reporting_period,
        mode_of_payments.`name` as mode_name,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        cash_disbursement.issuance_date,
        books.`name` as book_name
         FROM 
        acics_cash_items
        JOIN cash_disbursement ON acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id
        LEFT JOIN books ON cash_disbursement.book_id = books.id
        LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id  = mode_of_payments.id
        WHERE acics_cash_items.is_deleted = 0 
        AND acics_cash_items.fk_acic_id  = :id")
            ->bindValue(':id', $id)
            ->queryAll();
        return $qry;
    }
    /**
     * Lists all Acics models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AcicsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Acics model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => []
        ]);
    }

    /**
     * Creates a new Acics model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Acics();

        if ($model->load(Yii::$app->request->post())) {
            $cashItems = Yii::$app->request->post('cashItems') ?? [];
            $uniqueCashItems = array_map("unserialize", array_unique(array_map("serialize", $cashItems)));

            try {
                $txn  = Yii::$app->db->beginTransaction();
                if (empty($cashItems)) {
                    throw new ErrorException('Cash Disbursements is Required');
                }
                $model->serial_number = $this->getSerialNum($model->date_issued);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insCashItms = $this->insCashItems($model->id, $uniqueCashItems);
                if ($insCashItms != true) {
                    throw new ErrorException($insCashItms);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Acics model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $cashItems = Yii::$app->request->post('cashItems') ?? [];
            $uniqueCashItems = array_map("unserialize", array_unique(array_map("serialize", $cashItems)));

            try {
                $txn  = Yii::$app->db->beginTransaction();
                if (empty($cashItems)) {
                    throw new ErrorException('Cash Disbursements is Required');
                }
                $model->serial_number = $this->getSerialNum($model->date_issued);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insCashItms = $this->insCashItems($model->id, $uniqueCashItems, true);
                if ($insCashItms != true) {
                    throw new ErrorException($insCashItms);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'cashItems' => $this->getCashItems($model->id),
        ]);
    }

    /**
     * Deletes an existing Acics model.
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
     * Finds the Acics model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Acics the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Acics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
