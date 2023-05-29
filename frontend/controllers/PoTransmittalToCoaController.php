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
            'dataProvider' => $query,
            'model' => $this->findModel($id)
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->transmittal_number]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->transmittal_number]);
        }

        return $this->render('update', [
            'model' => $model,
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
        $query = Yii::$app->db->createCommand("SELECT substring_index(transmittal_number,' ',-1) as q 
        FROM po_transmittal_to_coa
        ORDER BY q DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = $query + 1;
        }
        $string = substr(str_repeat(0, 4) . $num, -4);
        return 'RO-' . date('Y-m', strtotime($date)) . '-PO ' . $string;
    }
}
