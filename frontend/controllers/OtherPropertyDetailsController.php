<?php

namespace frontend\controllers;

use app\models\OtherPropertyDetailItems;
use Yii;
use app\models\OtherPropertyDetails;
use app\models\OtherPropertyDetailsSearch;
use Behat\Gherkin\Filter\RoleFilter;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OtherPropertyDetailsController implements the CRUD actions for OtherPropertyDetails model.
 */
class OtherPropertyDetailsController extends Controller
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
                    'delete',
                    'create',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'update',
                            'delete',
                            'create',
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
     * Lists all OtherPropertyDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OtherPropertyDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OtherPropertyDetails model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->findItems($id)
        ]);
    }
    public function findItems($id)
    {
        return Yii::$app->db->createCommand('SELECT 
            other_property_detail_items.id,
            other_property_detail_items.fk_other_property_details_id,
            other_property_detail_items.book_id,
            other_property_detail_items.amount,
            books.name as book_name
        FROM other_property_detail_items
        LEFT JOIN books ON other_property_detail_items.book_id = books.id
        WHERE other_property_detail_items.fk_other_property_details_id = :id
        AND other_property_detail_items.is_deleted !=1

        ')
            ->bindValue(':id', $id)
            ->queryAll();
    }

    /**
     * Creates a new OtherPropertyDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function insertItems($id = '', $items = [])

    {
        if (empty($id)) {
            return ['isSuccess' => false, 'error_message' => 'Parent ID Required'];
        }
        foreach ($items as $val) {
            if (!empty($val['item_id'])) {
                $item = OtherPropertyDetailItems::findOne($val['item_id']);
            } else {

                $item = new OtherPropertyDetailItems();
            }
            $item->fk_other_property_details_id = $id;
            $item->book_id = $val['book'];
            $item->amount = $val['amount'];
            if ($item->validate()) {
                if ($item->save(false)) {
                }
            } else {
                return ['isSuccess' => false, 'error_message' => $item->errors];
            }
        }
        return ['isSuccess' => true];
    }
    public function actionCreate()
    {
        $model = new OtherPropertyDetails();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $items = !empty($_POST['items']) ? $_POST['items'] : [];

            $model->id = Yii::$app->db->createCommand('SELECT UUID_SHORT()')->queryScalar();

            try {
                if ($model->validate()) {
                    if ($model->save(false)) {
                        if ($model->depreciation_schedule === 1) {

                            $insert_item = $this->insertItems($model->id, $items);
                            if ($insert_item['isSuccess'] === true) {
                            } else {
                                $transaction->rollBack();
                                return json_encode(['isSuccess' => false, 'error_message' => $insert_item]);
                            }
                        }
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode($model->errors);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OtherPropertyDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $items = !empty($_POST['items']) ? $_POST['items'] : [];
            try {
                if ($model->validate()) {
                    if ($model->save(false)) {
                        if (!empty(array_column($items, 'item_id'))) {
                            $params = [];
                            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', array_column($items, 'item_id')], $params);
                            Yii::$app->db->createCommand("UPDATE other_property_detail_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                                $sql AND other_property_detail_items.fk_other_property_details_id = :id", $params)
                                ->bindValue(':id', $model->id)->query();
                        } else {
                            Yii::$app->db->createCommand("UPDATE other_property_detail_items SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE 
                                 other_property_detail_items.fk_other_property_details_id = :id")
                                ->bindValue(':id', $model->id)->query();
                        }
                        $insert_item = $this->insertItems($model->id, $items);
                        if ($insert_item['isSuccess'] === true) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {
                            return json_encode(['isSuccess' => false, 'error_message' => $insert_item]);
                        }
                    }
                } else {
                    $transaction->rollBack();
                    return json_encode($model->errors);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->findItems($id)
        ]);
    }

    /**
     * Deletes an existing OtherPropertyDetails model.
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
     * Finds the OtherPropertyDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OtherPropertyDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OtherPropertyDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearchChartOfAccounts($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $uacs = [
            1060101000,
            1060201000,
            1060202000,
            1060299000,
            1060301000,
            1060302000,
            1060303000,
            1060304000,
            1060305000,
            1060306000,
            1060307000,
            1060308000,
            1060309000,
            1060399000,
            1060401000,
            1060402000,
            1060403000,
            1060404000,
            1060405000,
            1060406000,
            1060499000,
            1060501000,
            1060502000,
            1060503000,
            1060504000,
            1060505000,
            1060506000,
            1060507000,
            1060508000,
            1060509001,
            1060509002,
            1060509003,
            1060509004,
            1060509005,
            1060510000,
            1060511000,
            1060512000,
            1060513000,
            1060514000,
            1060599000,
            1060601000,
            1060602000,
            1060603000,
            1060604000,
            1060699000,
            1060701000,
            1060702000,
            1060801000,
            1060802000,
            1060803000,
            1060804000,
            1060805000,
            1060899000,
            1060901000,
            1060902000,
            1060999000,
            1061101000,
            1061102000,
            1061199000,
            1069901000,
            1069999000,

        ];
        $params = [];
        $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'chart_of_accounts.uacs', $uacs], $params);
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select(["id as id, CONCAT (uacs ,'-',general_ledger) as text"])
                ->from('chart_of_accounts')
                ->where(['like', 'general_ledger', $q])
                ->orWhere(['like', 'uacs', $q])
                ->andWhere('is_active = 1')
                ->andWhere($sql, $params);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {


            $query = new Query();
            $query->select(["chart_of_accounts.id as id, CONCAT (chart_of_accounts.uacs ,'-',chart_of_accounts.general_ledger) as text,
                ppe_useful_life.life_from,
                ppe_useful_life.life_to"])
                ->from('chart_of_accounts')
                ->join('LEFT JOIN', 'ppe_useful_life', ' chart_of_accounts.fk_ppe_useful_life_id = ppe_useful_life.id')
                ->where('chart_of_accounts.id=:id', ['id' => $id]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionItems()
    {
        if ($_POST) {
            $new_array = [];
            foreach ($_POST['items'] as $val) {
                $book_name = Yii::$app->db->createCommand("SELECT books.`name` FROM books WHERE id = :id")->bindValue(':id', $val['book'])->queryScalar();
                $new_array[] = [
                    'book' => $book_name,
                    'amount' => $val['amount']
                ];
                // return json_encode($val);
            }
            return json_encode($new_array);
        }
    }
    public function actionPropertyDetails()
    {

        if (Yii::$app->request->isPost) {
            $id = $_POST['property_id'];
            $query  = Yii::$app->db->createCommand("SELECT 
            property.property_number,
            property.article,
            property.model,
            property.date
             FROM property
             WHERE property.id = :id
            ")
                ->bindValue(':id', $id)
                ->queryOne();
            return json_encode($query);
        }
    }
}
