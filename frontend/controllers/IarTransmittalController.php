<?php

namespace frontend\controllers;

use app\models\IarIndexSearch;
use Yii;
use app\models\IarTransmittal;
use app\models\IarTransmittalItems;
use app\models\IarTransmittalSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * IarTransmittalController implements the CRUD actions for IarTransmittal model.
 */
class IarTransmittalController extends Controller
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
                    'index',
                    'update',
                    'create',
                    'delete',

                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['view_iar_transmittal']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_iar_transmittal']
                    ],
                    [
                        'actions' => [
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_iar_transmittal']
                    ],
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
     * Lists all IarTransmittal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IarTransmittalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single IarTransmittal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->transmittalItems($id),
        ]);
    }
    public function transmittalItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        iar_transmittal_items.id as item_id,
        iar_index.*
        FROM 
        
        iar_transmittal_items
        INNER JOIN iar_index ON iar_transmittal_items.fk_iar_id = iar_index.id
        WHERE iar_transmittal_items.fk_iar_transmittal_id = :id
        AND iar_transmittal_items.is_deleted = 0
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }

    /**
     * Creates a new IarTransmittal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function insertItems($id, $po_item_ids = [], $item_id = [])
    {
        foreach ($po_item_ids as $index => $val) {

            if (!empty($item_id[$index])) {
                $item = IarTransmittalItems::findOne($item_id[$index]);
            } else {
                $item = new IarTransmittalItems();
            }

            $item->fk_iar_transmittal_id = $id;
            $item->fk_iar_id = $val;
            if ($item->save(false)) {
            } else {
                return ['isSuccess' => true, 'error_message' => $item->errors];
            }
        }
        return ['isSuccess' => true];
    }
    public function actionCreate()
    {
        $model = new IarTransmittal();
        $searchModel = new IarIndexSearch();
        $model->fk_approved_by = 99684622555676858;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 10];
        $dataProvider->query->andWhere('NOT EXISTS (SELECT * FROM iar_transmittal_items WHERE iar_transmittal_items.fk_iar_id = iar_index.id AND iar_transmittal_items.is_deleted =0) ');
        if ($model->load(Yii::$app->request->post())) {

            try {
                $transaction = YIi::$app->db->beginTransaction();
                $items  = Yii::$app->request->post('items');
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }

                $insertItems = $model->insertItems($items);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing IarTransmittal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $searchModel = new IarIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 10];
        $dataProvider->query->andWhere('NOT EXISTS (SELECT * FROM iar_transmittal_items WHERE iar_transmittal_items.fk_iar_id = iar_index.id AND iar_transmittal_items.is_deleted =0) ');
        if ($model->load(Yii::$app->request->post())) {

            try {
                $transaction = YIi::$app->db->beginTransaction();
                $items  = Yii::$app->request->post('items');
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }

                $insertItems = $model->insertItems($items);
                if ($insertItems !== true) {
                    throw new ErrorException($insertItems);
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'items' => $this->transmittalItems($id),
            'action' => 'iar-transmittal/update',
        ]);
    }

    /**
     * Deletes an existing IarTransmittal model.
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
     * Finds the IarTransmittal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return IarTransmittal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = IarTransmittal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function serialNumber($date)
    {
        $year = DateTime::createFromFormat('Y-m-d', $date)->format('Y');

        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num 
        FROM iar_transmittal
        ORDER BY last_num DESC LIMIT 1
        
        ")
            ->queryScalar();
        if (empty($last_num)) {
            $last_num = 1;
        } else {
            $last_num = intval($last_num) + 1;
        }
        $zero = '';
        for ($i = strlen($last_num); $i < 4; $i++) {
            $zero .= 0;
        }
        return 'RO-' . $year . '-' . $zero . $last_num;
    }
}
