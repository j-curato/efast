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
                            'update',
                            'create',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
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

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere('NOT EXISTS (SELECT * FROM iar_transmittal_items WHERE iar_transmittal_items.fk_iar_id = iar_index.id AND iar_transmittal_items.is_deleted =0) ');
        if (Yii::$app->request->isPost) {
            $items  = !empty($_POST['iar_ids']) ? array_unique($_POST['iar_ids']) : [];
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->date = $_POST['date'];
            $model->serial_number = $this->serialNumber($model->date);
            $transaction = YIi::$app->db->beginTransaction();
            try {
                if ($model->validate()) {
                    if ($model->save(false)) {

                        $insert_entry = $this->insertItems($model->id, $items);
                        if ($insert_entry['isSuccess']) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {

                            $transaction->rollBack();
                            return json_encode(['isSuccess' => false, 'error_message' => $insert_entry['error_message']]);
                        }
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();

                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'action' => 'iar-transmittal/create',
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
        $dataProvider->query->andWhere('NOT EXISTS (SELECT * FROM iar_transmittal_items WHERE iar_transmittal_items.fk_iar_id = iar_index.id AND iar_transmittal_items.is_deleted =0) ');
        if (Yii::$app->request->isPost) {
            $items  = !empty($_POST['iar_ids']) ? array_unique($_POST['iar_ids']) : [];
            $item_id  = !empty($_POST['item_id']) ? $_POST['item_id'] : [];
            $model->date = $_POST['date'];
            $transaction = YIi::$app->db->beginTransaction();
            try {
                if ($model->validate()) {
                    if ($model->save(false)) {
                        $params = [];
                        if (!empty($item_id)) {

                            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'iar_transmittal_items.id', $item_id], $params);
                            YIi::$app->db->createCommand("UPDATE iar_transmittal_items SET is_deleted = 1   
                        WHERE 
                        iar_transmittal_items.fk_iar_transmittal_id = :id
                        AND $sql
                        ", $params)
                                ->bindValue(':id', $model->id)->query();
                        }

                        $insert_entry = $this->insertItems($model->id, $items, $item_id);
                        if ($insert_entry['isSuccess']) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {

                            $transaction->rollBack();
                            return json_encode(['isSuccess' => false, 'error_message' => $insert_entry['error_message']]);
                        }
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode(['isSuccess' => false, 'error_message' => $model->errors]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();

                return json_encode(['isSuccess' => false, 'error_message' => $e->getMessage()]);
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
