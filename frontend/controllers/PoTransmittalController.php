<?php

namespace frontend\controllers;

use app\models\Liquidation;
use app\models\LiquidationView;
use Yii;
use app\models\PoTransmittal;
use app\models\PoTransmittalEntries;
use app\models\PoTransmittalSearch;
use app\models\PoTransmittalsPendingSearch;
use app\models\TransmittalEntries;
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

    /**
     * Lists all PoTransmittal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoTransmittalSearch();
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {



            return $this->redirect(['view', 'id' => $model->transmittal_number]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionInsertPoTransmittal()
    {
        if ($_POST) {
            $date = $_POST['date'];
            $transmittal_update_id = $_POST['transmittal_update_id'];
            $liquidation_id = !empty($_POST['liquidation_id']) ? array_unique($_POST['liquidation_id']) : [];
            $transaction = Yii::$app->db->beginTransaction();

            if (!empty($transmittal_update_id)) {


                $po_transmittal = PoTransmittal::findOne($transmittal_update_id);
                if ($po_transmittal->status === 'at_ro') {
                    return json_encode([
                        'isSuccess' => false,
                        'error' => 'Cannot update transmittal is already at RO'
                    ]);
                }
                foreach ($po_transmittal->poTransmittalEntries as $d) {
                    $update_liq = Liquidation::findOne($d->liquidation_id);
                    $update_liq->status = 'at_po';
                    if ($update_liq->save(false)) {
                    }
                    $d->delete();
                }
                // return json_encode([
                //     'isSuccess' => false,
                //     'error' => $po_transmittal->toArray()
                // ]);
            } else {
                $po_transmittal = new PoTransmittal();
                $po_transmittal->transmittal_number  = $this->getTransmittalNumber($date);
            }
            $po_transmittal->date = $date;
            try {
                if ($po_transmittal->validate()) {

                    if ($flag = $po_transmittal->save(false)) {
                        foreach ($liquidation_id as $liq) {
                            $tr_entries = new PoTransmittalEntries();
                            $tr_entries->po_transmittal_number = $po_transmittal->transmittal_number;
                            $tr_entries->liquidation_id = $liq;
                            if ($tr_entries->validate()) {
                                if ($tr_entries->save(false)) {
                                    $liquidation = Liquidation::findOne($liq);
                                    $liquidation->status = 'pending_at_ro';
                                    if ($liquidation->save(false)) {
                                    }
                                }
                            } else {
                                $transaction->rollBack();
                                return json_encode([
                                    'isSuccess' => false,
                                    'error' => 'error'
                                ]);
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $po_transmittal->transmittal_number]);
                        // return json_encode([
                        //     'isSuccess' => true,
                        //     'error' => $po_transmittal->transmittal_number
                        // ]);
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode([
                        'isSuccess' => false,
                        'error' => $po_transmittal->errors
                    ]);
                }
            } catch (ErrorException $error) {
                return json_encode([
                    'isSuccess' => false,
                    'error' => $error->getMessage()
                ]);
            }
        }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->transmittal_number]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

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
    public function getTransmittalNumber($date)
    {
        $province = Yii::$app->user->identity->province;
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
        $model->status = $model->status === 'pending_at_ro' ? 'at_ro' : 'pending_at_ro';
        if ($model->save(false)) {
            return $this->redirect(['view', 'id' => $model->transmittal_number]);
        }
    }
    public function actionReturn($id)
    {
        $model = PoTransmittalEntries::findOne($id);
        $q  =  $model->status === 'returned' ? '' : 'returned';
        $model->status = $q;
        $po_tr = PoTransmittal::findOne($model->po_transmittal_number);
        $po_tr->edited = true;
        $liquidation = Liquidation::findOne($model->liquidation->id);
        $status = $liquidation->status == 'pending_at_ro' ? 'at_po' : 'pending_at_ro';
        $liquidation->status = $status;

        if ($liquidation->save(false)) {
            // return json_encode($liquidation->status);

        } else {
            return json_encode('cant save');
        }
        if ($model->save(false)) {
        }
        if ($po_tr->save(false)) {
        }

        return $this->redirect(['view', 'id' => $model->poTransmittal->transmittal_number]);
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
}
