<?php

namespace frontend\controllers;

use app\models\Liquidation;
use app\models\LiquidationView;
use app\models\Office;
use Yii;
use app\models\PoTransmittal;
use app\models\PoTransmittalEntries;
use app\models\PoTransmittalSearch;
use app\models\PoTransmittalsPendingSearch;
use app\models\TransmittalEntries;
use app\models\VwPoTransmittalIndexSearch;
use DateTime;
use ErrorException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoTransmittalController implements the CRUD actions for PoTransmittal model.
 */
class PoTransmittalController extends Controller
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
                    'update',
                    'create',
                    'insert-po-transmittal',
                    'delete',
                    'accept',
                    'return',
                    'returned-liquidation',


                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'create',
                            'insert-po-transmittal',
                            'delete',
                            'returned-liquidation',

                        ],
                        'allow' => true,
                        'roles' => ['create_po_transmittal']
                    ],
                    [
                        'actions' => [
                            'accept',
                            'return',
                        ],
                        'allow' => true,
                        'roles' => ['accept_transmittal_in_ro']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'accept' => ['POST'],
                    'return' => ['POST'],
                ],
            ],
        ];
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
                Yii::$app->db->createCommand("UPDATE po_transmittal_entries SET is_deleted = 1 
                WHERE 
                po_transmittal_entries.is_deleted = 0
                AND po_transmittal_entries.fk_po_transmittal_id = :id
                $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {
                $mdl = !empty($itm['item_id']) ? PoTransmittalEntries::findOne($itm['item_id']) : new PoTransmittalEntries();
                $mdl->fk_po_transmittal_id = $model_id;
                $mdl->liquidation_id = $itm['dv_id'];
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
    private function getItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        po_transmittal_entries.id as item_id,
        po_transmittal_entries.is_returned,
        liquidation_view.status as liquidation_status,
        liquidation_view.id as dv_id,
        liquidation_view.province,
        liquidation_view.check_date,
        liquidation_view.check_number,
        liquidation_view.dv_number,
        liquidation_view.reporting_period,
        liquidation_view.payee,
        liquidation_view.particular,
        liquidation_view.total_withdrawal,
        liquidation_view.total_vat,
        liquidation_view.total_expanded,
        liquidation_view.total_liquidation_damage,
        liquidation_view.gross_payment
         FROM po_transmittal_entries
        JOIN liquidation_view ON po_transmittal_entries.liquidation_id = liquidation_view.id
         WHERE 
         po_transmittal_entries.fk_po_transmittal_id =:id 
        AND po_transmittal_entries.is_deleted = 0
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    /**
     * Lists all PoTransmittal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VwPoTransmittalIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PoTransmittal model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Creates a new PoTransmittal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PoTransmittal();
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $model->fk_office_id = $user_data->office->id;
        }
        if ($model->load(Yii::$app->request->post())) {

            try {
                $txn = YIi::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $model->transmittal_number  = $this->getTransmittalNumber($model->date, $model->fk_office_id);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
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
     * Updates an existing PoTransmittal model.
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
                $txn = Yii::$app->db->beginTransaction();
                $items = Yii::$app->request->post('items') ?? [];
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                // return var_dump($items);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
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

    // public function actionInsertPoTransmittal()
    // {
    //     if ($_POST) {
    //         $date = $_POST['date'];
    //         $transmittal_update_id = $_POST['transmittal_update_id'];
    //         $liquidation_id = !empty($_POST['liquidation_id']) ? array_unique($_POST['liquidation_id']) : [];
    //         $transaction = Yii::$app->db->beginTransaction();

    //         if (!empty($transmittal_update_id)) {


    //             $po_transmittal = PoTransmittal::findOne($transmittal_update_id);
    //             if ($po_transmittal->status === 'at_ro') {
    //                 return json_encode([
    //                     'isSuccess' => false,
    //                     'error' => 'Cannot update transmittal is already at RO'
    //                 ]);
    //             }
    //             foreach ($po_transmittal->poTransmittalEntries as $d) {
    //                 $update_liq = Liquidation::findOne($d->liquidation_id);
    //                 $update_liq->status = 'at_po';
    //                 if ($update_liq->save(false)) {
    //                 }
    //                 $d->delete();
    //             }
    //             // return json_encode([
    //             //     'isSuccess' => false,
    //             //     'error' => $po_transmittal->toArray()
    //             // ]);
    //         } else {
    //             $po_transmittal = new PoTransmittal();
    //             $po_transmittal->transmittal_number  = $this->getTransmittalNumber($date, '');
    //         }
    //         $po_transmittal->date = $date;
    //         try {
    //             if ($po_transmittal->validate()) {

    //                 if ($flag = $po_transmittal->save(false)) {
    //                     foreach ($liquidation_id as $liq) {
    //                         $tr_entries = new PoTransmittalEntries();
    //                         $tr_entries->po_transmittal_number = $po_transmittal->transmittal_number;
    //                         $tr_entries->liquidation_id = $liq;
    //                         if ($tr_entries->validate()) {
    //                             if ($tr_entries->save(false)) {
    //                                 $liquidation = Liquidation::findOne($liq);
    //                                 $liquidation->status = 'pending_at_ro';
    //                                 if ($liquidation->save(false)) {
    //                                 }
    //                             }
    //                         } else {
    //                             $transaction->rollBack();
    //                             return json_encode([
    //                                 'isSuccess' => false,
    //                                 'error' => 'error'
    //                             ]);
    //                         }
    //                     }
    //                 }
    //                 if ($flag) {
    //                     $transaction->commit();
    //                     return $this->redirect(['view', 'id' => $po_transmittal->transmittal_number]);
    //                     // return json_encode([
    //                     //     'isSuccess' => true,
    //                     //     'error' => $po_transmittal->transmittal_number
    //                     // ]);
    //                 }
    //             } else {
    //                 $transaction->rollBack();
    //                 return json_encode([
    //                     'isSuccess' => false,
    //                     'error' => $po_transmittal->errors
    //                 ]);
    //             }
    //         } catch (ErrorException $error) {
    //             return json_encode([
    //                 'isSuccess' => false,
    //                 'error' => $error->getMessage()
    //             ]);
    //         }
    //     }
    // }


    /**
     * Deletes an existing PoTransmittal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the PoTransmittal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PoTransmittal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoTransmittal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getTransmittalNumber($date, $office_id)
    {
        $province = Office::findOne($office_id)->office_name;
        $year = DateTime::createFromFormat('Y-m-d', $date)->format('Y');
        $query = Yii::$app->db->createCommand("SELECT CAST(substring_index(transmittal_number,'-',-1 ) AS UNSIGNED) as id 
        FROM po_transmittal
        WHERE po_transmittal.transmittal_number LIKE :province
        AND po_transmittal.date LIKE :_year
        ORDER BY id DESC LIMIT 1
        ")
            ->bindValue(':province', $province . '%')
            ->bindValue(':_year', $year . '%')
            ->queryOne();
        $num = 1;
        if (!empty($query)) {
            $num = $query['id'] + 1;
        }
        $string = substr(str_repeat(0, 4) . $num, -4);

        $transmittal_number = strtoupper($province) . '-' . date('Y-m', strtotime($date)) . '-' . $string;
        return $transmittal_number;
    }
    public function actionUpdateTransmittal()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $entries = Yii::$app->db->createCommand("SELECT * FROM po_transmittal_entries 
            LEFT JOIN liquidation_view ON po_transmittal_entries.liquidation_id = liquidation_view.id
            WHERE po_transmittal_number = :id ")
                ->bindValue('id', $id)
                ->queryAll();
            return json_encode(['entries' => $entries]);
        }
    }
    public function actionAccept($id)
    {
        $model = $this->findModel($id);
        $model->is_accepted = $model->is_accepted === 1 ? 0 : 1;
        if ($model->save(false)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    public function actionReturn($id)
    {

        if (Yii::$app->request->post()) {
            try {
                $model = PoTransmittalEntries::findOne($id);
                $q  =  $model->status === 'returned' ? '' : 'returned';
                $model->status = $q;

                $model->is_returned = $model->is_returned === 0 ? 1 : 0;
                // $liquidation = Liquidation::findOne($model->liquidation->id);
                // $status = $liquidation->status == 'pending_at_ro' ? 'at_po' : 'pending_at_ro';
                // $liquidation->status = $status;

                // if ($liquidation->save(false)) {
                // } else {
                //     return json_encode('cant save');
                // }

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }


                return $this->redirect(['view', 'id' => $model->fk_po_transmittal_id]);
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }
    }
    public function actionPendingAtRo()
    {
        $searchModel = new PoTransmittalsPendingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render(
            'pending_at_ro',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]
        );
    }
    public function actionReturnedLiquidation()
    {
        // $query = PoTransmittalEntries::find()
        // ->joinWith('liquidation')
        // ->where("liquidation.status = 'at_po'");

        $query = LiquidationView::find()
            ->where("id IN (SELECT
        po_transmittal_entries.liquidation_id
        FROM po_transmittal_entries
        LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id = liquidation.id
        WHERE liquidation.`status`  ='at_po')");

        $province =  strtolower(Yii::$app->user->identity->province);
        if (
            $province === 'adn' ||
            $province === 'ads' ||
            $province === 'sds' ||
            $province === 'sdn' ||
            $province === 'pdi'
        ) {
            $query->andWhere('province =:province', ['province' => $province]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        // ob_clean();
        // echo "<pre>";
        // var_dump($query);
        // echo "</pre>";
        // return ob_get_clean();
        // return json_encode($dataProvider);
        return $this->render('returned_liquidations', [
            'dataProvider' => $dataProvider
        ]);
    }
    public function actionAddFileLink($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {

            $model->file_link = Yii::$app->request->post('PoTransmittal')['file_link'] ?? null;
            try {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                return $this->redirect(Yii::$app->request->referrer);
            } catch (ErrorException $e) {
                return $e->getMessage();
            }
        }
        return $this->renderAjax('_file_link_form', [
            'model' => $model,
        ]);
    }
}
