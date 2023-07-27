<?php

namespace frontend\controllers;

use app\models\PoTransmittal;
use app\models\PoTransmittalEntries;
use Yii;
use app\models\PoTransmittalToCoa;
use app\models\PoTransmittalToCoaEntries;
use app\models\PoTransmittalToCoaSearch;
use ErrorException;
use Mpdf\Tag\Q;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoTransmittalToCoaController implements the CRUD actions for PoTransmittalToCoa model.
 */
class PoTransmittalToCoaController extends Controller
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
                    'insert-transmittal'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'insert-transmittal'
                        ],
                        'allow' => true,
                        'roles' => ['po_transmittal_to_coa']
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
        return YIi::$app->db->createCommand("SELECT
            po_transmittal_to_coa_entries.id as item_id,
            po_transmittal.id as transmittal_id,
            po_transmittal.transmittal_number,
            po_transmittal.date,
            po_tmtl.total_withdrawals,
            po_tmtl.cnt_dv as total_dv
            FROM
            po_transmittal_to_coa_entries
            JOIN po_transmittal ON po_transmittal_to_coa_entries.fk_po_transmittal_id = po_transmittal.id
            JOIN (
            SELECT  
            po_transmittal_entries.fk_po_transmittal_id,
            SUM(liquidation_total.total_withdrawals) as total_withdrawals,
            COUNT(liquidation_total.liquidation_id)as cnt_dv
            FROM 
            po_transmittal_entries 
            JOIN (SELECT 
            liquidation_entries.liquidation_id,
            SUM(liquidation_entries.withdrawals)as total_withdrawals
            FROM liquidation_entries
            GROUP BY liquidation_entries.liquidation_id) as liquidation_total ON po_transmittal_entries.liquidation_id = liquidation_total.liquidation_id 
            WHERE 
            po_transmittal_entries.is_deleted  = 0
            GROUP BY
            po_transmittal_entries.fk_po_transmittal_id
            )as po_tmtl ON po_transmittal_to_coa_entries.fk_po_transmittal_id = po_tmtl.fk_po_transmittal_id
            WHERE 
            po_transmittal_to_coa_entries.is_deleted = 0
            AND po_transmittal_to_coa_entries.fk_po_transmittal_to_coa_id = :id")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function insItems($model_id, $items = [], $isUpdate = false)
    {
        try {
            if ($isUpdate === true) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = '';
                if (!empty($itemIds)) {
                    $sql = ' AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                }
                Yii::$app->db->createCommand("UPDATE po_transmittal_to_coa_entries SET is_deleted = 1 
                WHERE 
                po_transmittal_to_coa_entries.is_deleted = 0
                AND po_transmittal_to_coa_entries.fk_po_transmittal_to_coa_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {
                $mdl = !empty($itm['item_id']) ? PoTransmittalToCoaEntries::findOne($itm['item_id']) : new PoTransmittalToCoaEntries();
                $mdl->fk_po_transmittal_to_coa_id = $model_id;
                $mdl->fk_po_transmittal_id = $itm['transmittal_id'];
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
     * Lists all PoTransmittalToCoa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoTransmittalToCoaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PoTransmittalToCoa model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $query = Yii::$app->db->createCommand("SELECT po_transmittal.* ,
            total.total_withdrawals,
			dv_count.total_dv
            FROM
            po_transmittal_to_coa_entries
            LEFT JOIN po_transmittal ON po_transmittal_to_coa_entries.po_transmittal_number = po_transmittal.transmittal_number
            LEFT JOIN (SELECT 
            SUM(liquidation_entries.withdrawals) as total_withdrawals,
            po_transmittal_entries.po_transmittal_number
            FROM 
            po_transmittal_entries
            LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id = liquidation.id
            LEFT JOIN liquidation_entries ON liquidation.id = liquidation_entries.liquidation_id
            WHERE liquidation.`status` != 'at_po'
            GROUP BY po_transmittal_entries.po_transmittal_number
            ) as total ON po_transmittal.transmittal_number = total.po_transmittal_number
                    LEFT JOIN (

                        SELECT COUNT(liquidation_id) as total_dv,
                po_transmittal_entries.po_transmittal_number
                        FROM po_transmittal_entries 
                        LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id=liquidation.id
                        where liquidation.`status` != 'at_po'
                        GROUP BY po_transmittal_entries.po_transmittal_number
                            )as dv_count ON po_transmittal.transmittal_number = dv_count.po_transmittal_number
            WHERE po_transmittal_to_coa_entries.po_transmittal_to_coa_number = :transmittal_number
            ")
            ->bindValue(':transmittal_number', $id)
            ->queryAll();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Creates a new PoTransmittalToCoa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PoTransmittalToCoa();
        if ($model->load(Yii::$app->request->post())) {
            try {

                $items = Yii::$app->request->post('items') ?? [];
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                $txn  = Yii::$app->db->beginTransaction();
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                $model->transmittal_number = $this->getTransmittalNumber($model->date);

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save FAiled");
                }
                $insItms = $this->insItems($model->id, $uniqueItems);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollback();
                return $e->getMessage();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PoTransmittalToCoa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
                $txn  = Yii::$app->db->beginTransaction();
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Model Save FAiled");
                }
                $insItms = $this->insItems($model->id, $uniqueItems, true);
                if ($insItms !== true) {
                    throw new ErrorException($insItms);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollback();
                return $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Deletes an existing PoTransmittalToCoa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        Yii::$app->db->createCommand("UPDATE po_transmittal  SET po_transmittal.status ='at_ro'
      WHERE EXISTS (
      SELECT po_transmittal_to_coa_entries.po_transmittal_number FROM `po_transmittal_to_coa_entries` WHERE po_transmittal_to_coa_number = :po_transmittal_to_coa_number 
      AND po_transmittal_to_coa_entries.po_transmittal_number = po_transmittal.transmittal_number
          )
        ")
            ->bindValue(':po_transmittal_to_coa_number', $model->transmittal_number)
            ->query();
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the PoTransmittalToCoa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PoTransmittalToCoa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoTransmittalToCoa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionInsertTransmittal()
    {
        if ($_POST) {
            $po_transmittal_number = $_POST['po_transmittal_number'];
            $date = $_POST['date'];

            $transaction = Yii::$app->db->beginTransaction();


            try {
                $to_coa = new PoTransmittalToCoa();
                $to_coa->date = $date;
                $to_coa->transmittal_number = $this->getTransmittalNumber($date);
                if ($to_coa->validate()) {
                    if ($flag = $to_coa->save(false)) {

                        foreach ($po_transmittal_number as $val) {
                            $entry = new PoTransmittalToCoaEntries();
                            $entry->po_transmittal_number = $val;
                            $entry->po_transmittal_to_coa_number = $to_coa->transmittal_number;
                            if ($entry->save(false)) {
                                $po_transmittal = PoTransmittal::findOne($val);
                                $po_transmittal->status = 'at_coa';
                                if ($po_transmittal->save(false)) {
                                }
                            }
                        }
                    }
                } else {
                    return json_encode(['isSuccess' => false, 'error' => $to_coa->errors]);
                }
                if ($flag) {
                    $transaction->commit();


                    return $this->redirect(['view', 'id' => $to_coa->transmittal_number]);
                    return json_encode(['isSuccess' => true,]);
                }
            } catch (ErrorException $e) {
                return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    public function getTransmittalNumber($date)
    {
        $query = Yii::$app->db->createCommand("SELECT CAST(substring_index(transmittal_number,' ',-1) AS UNSIGNED) as q 
        FROM po_transmittal_to_coa
        ORDER BY q DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = $query + 1;
        }
        if (strlen($num) < 4) {

            $string = substr(str_repeat(0, 4) . $num, -4);
        } else {
            $string = $num;
        }
        return 'RO-' . date('Y-m', strtotime($date)) . '-PO ' . $string;
    }
}
