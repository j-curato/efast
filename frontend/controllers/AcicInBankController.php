<?php

namespace frontend\controllers;

use Yii;
use app\models\AcicInBank;
use app\models\AcicInBankItems;
use app\models\AcicInBankSearch;
use DateTime;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AcicInBankController implements the CRUD actions for AcicInBank model.
 */
class AcicInBankController extends Controller
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
                    'delete',
                    'create',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'delete',
                            'create',
                            'update',
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
    private function insItems($model_id, $items, $isUpdate = false)
    {

        try {

            if ($isUpdate === true && !empty(array_column($items, 'item_id'))) {
                $itemIds = array_column($items, 'item_id');
                $params = [];
                $sql = ' AND ';
                $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $itemIds], $params);
                echo Yii::$app->db->createCommand("UPDATE acic_in_bank_items SET is_deleted = 1 
            WHERE 
            acic_in_bank_items.is_deleted = 0
            AND acic_in_bank_items.fk_acic_in_bank_id = :id
            $sql", $params)
                    ->bindValue(':id', $model_id)
                    ->execute();
            }
            foreach ($items as $itm) {

                if (!empty($itm['item_id'])) {
                    $model = AcicInBankItems::findOne($itm['item_id']);
                } else {
                    $model = new AcicInBankItems();
                }
                $model->fk_acic_in_bank_id = $model_id;
                $model->fk_acic_id = $itm['acic_id'];
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {

            return $e->getMessage();
        }
    }
    private function getSerialNum($period)
    {
        $yr = DateTime::createFromFormat('Y-m-d', $period)->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(acic_in_bank.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM acic_in_bank  
            WHERE 
            acic_in_bank.serial_number LIKE :yr
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
    private function getItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        acic_in_bank_items.id,
        acic_in_bank_items.fk_acic_id,
        acics.serial_number
        FROM acic_in_bank_items
        JOIN acics ON acic_in_bank_items.fk_acic_id = acics.id
        WHERE 
        acic_in_bank_items.is_deleted   = 0
        AND acic_in_bank_items.fk_acic_in_bank_id = :id
        ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    /**
     * Lists all AcicInBank models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AcicInBankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AcicInBank model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'items' => $this->getItems($id),

        ]);
    }

    /**
     * Creates a new AcicInBank model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AcicInBank();

        if ($model->load(Yii::$app->request->post())) {

            try {
                $items = Yii::$app->request->post('items') ?? [];
                $uniqueItems = array_map("unserialize", array_unique(array_map("serialize", $items)));
                $txn = Yii::$app->db->beginTransaction();
                $model->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $model->serial_number = $this->getSerialNum($model->date);
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItems = $this->insItems($model->id, $uniqueItems);
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
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
     * Updates an existing AcicInBank model.
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
                $txn = Yii::$app->db->beginTransaction();
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insItems = $this->insItems($model->id, $uniqueItems,true);
                if ($insItems !== true) {
                    throw new ErrorException($insItems);
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
            'items' => $this->getItems($id),
        ]);
    }

    /**
     * Deletes an existing AcicInBank model.
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
     * Finds the AcicInBank model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AcicInBank the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AcicInBank::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
