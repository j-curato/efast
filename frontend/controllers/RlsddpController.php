<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use app\models\Office;
use Yii;
use app\models\Rlsddp;
use app\models\RlsddpIndexSearch;
use app\models\RlsddpItems;
use app\models\RlsddpSearch;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RlsddpController implements the CRUD actions for Rlsddp model.
 */
class RlsddpController extends Controller
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
                    'get-pars',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'get-pars',
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
    private function getItems($id)
    {
        return Yii::$app->db->createCommand("SELECT 
                rlsddp_items.id as item_id,
                par.id,
                 par.par_number,
                 par.date as par_date,
                 IFNULL(actual_user.employee_name,'') as actual_user,
                 location.location,
                 property.property_number,
                 property.date as acquisition_date,
                 property.acquisition_amount,
                 property.description,
                 property.serial_number,
                 IFNULL(property_articles.article_name,property.article) as article,
                 (CASE
                 WHEN par.is_unserviceable =1 THEN 'UnServiceable'
                 ELSE 'Serviceable' 
                 END ) as is_unserviceable
     
     FROM rlsddp_items
     JOIN par ON rlsddp_items.fk_par_id = par.id 
     JOIN property ON par.fk_property_id = property.id
     LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
       LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
     LEFT JOIN location ON par.fk_location_id = location.id
     WHERE rlsddp_items.fk_rlsddp_id = :id
     AND rlsddp_items.is_deleted = 0 ")
            ->bindValue(':id', $id)
            ->queryAll();
    }
    private function checkIfHasRlsddp($par_id, $item_id = null)
    {
        $params = [];
        $sql = '';
        if (!empty($item_id)) {
            $sql = 'AND ';
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'rlsddp_items.id', $item_id], $params);
        }

        $qry = YIi::$app->db->createCommand("SELECT EXISTS (SELECT 
        property.id
        FROM property
        JOIN par ON property.id = par.fk_property_id
        JOIN rlsddp_items ON par.id = rlsddp_items.fk_par_id
        WHERE 
        EXISTS
        
        
         (SELECT 
         par.id 
         FROM 
         par
        join property as pty ON par.fk_property_id = pty.id
        join rlsddp_items on par.id = rlsddp_items.fk_par_id
        WHERE 
        rlsddp_items.is_deleted = 0
        AND par.id = :par_id
        $sql
        )
        AND rlsddp_items.is_deleted = 0
        )", $params)
            ->bindValue(':par_id', $par_id)
            ->queryScalar();
        return $qry;
    }
    private function insertItems($rlsddp_id, $items = [], $type = '')
    {
        try {
            if ($type === 'update') {
                $item_ids = array_column($items, 'item_id');
                $params = [];
                $sql = '';
                if (!empty($item_ids)) {
                    $sql = 'AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['NOT IN', 'id', $item_ids], $params);
                }
                Yii::$app->db->createCommand("UPDATE rlsddp_items SET is_deleted = 1 WHERE 
                       rlsddp_items.fk_rlsddp_id = :id  $sql", $params)
                    ->bindValue(':id', $rlsddp_id)
                    ->execute();
            }
            foreach ($items as $key => $itm) {
                $itm_id = null;
                if (!empty($itm['item_id'])) {
                    $rlsddp_item  = RlsddpItems::findOne($itm['item_id']);
                    $itm_id = $itm['item_id'];
                } else {
                    $rlsddp_item = new RlsddpItems();
                }

                $row = $key + 1;
                $qry = $this->checkIfHasRlsddp($itm['par_id'], $itm_id);
                if (intval($qry) === 1) {
                    throw new ErrorException("Row $row already has an RLSDDP");
                }
                $rlsddp_item->fk_rlsddp_id = $rlsddp_id;
                $rlsddp_item->fk_par_id = $itm['par_id'];
                $rlsddp_item->is_deleted = 0;
                if (!$rlsddp_item->validate()) {
                    throw new ErrorException(json_encode($rlsddp_item->errors));
                }
                if (!$rlsddp_item->save(false)) {
                    throw new ErrorException("Item Save Failed");
                }
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
        return true;
    }
    /**
     * Lists all Rlsddp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RlsddpIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rlsddp model.
     * @param integer $id
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
     * Creates a new Rlsddp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rlsddp();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = MyHelper::getUuid();
            $model->serial_number =  $this->getSerialNo($model->fk_office_id);
            $items = !empty(Yii::$app->request->post('items')) ? Yii::$app->request->post('items') : [];

            try {
                $txn = MyHelper::beginTxn();
                if (empty($items)) {
                    throw new ErrorException('Items Must be More Than 1');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $ins = $this->insertItems($model->id, $items);
                if ($ins !== true) {
                    throw new ErrorException($ins);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollback();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Rlsddp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel =  $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $items = !empty(Yii::$app->request->post('items')) ? Yii::$app->request->post('items') : [];
            if ($oldModel->fk_office_id != $model->fk_office_id) {
                $model->serial_number =  $this->getSerialNo($model->fk_office_id);
            }
            try {
                $txn = MyHelper::beginTxn();
                if (empty($items)) {
                    throw new ErrorException('Items Must be More Than 1');
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $ins = $this->insertItems($model->id, $items, 'update');
                if ($ins !== true) {
                    throw new ErrorException($ins);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollback();
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $this->getItems($id)
        ]);
    }

    /**
     * Deletes an existing Rlsddp model.
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
     * Finds the Rlsddp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rlsddp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rlsddp::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetPars()
    {
        if (Yii::$app->request->post()) {
            $emp_id = Yii::$app->request->post('id');
            $qry = YIi::$app->db->createCommand("SELECT 
            par.id,
                     par.par_number,
                     par.date as par_date,
                   
                     IFNULL(actual_user.employee_name,'') as actual_user,
                     location.location,
                     property.property_number,
                     property.date as acquisition_date,
                     property.acquisition_amount,
                     property.description,
                     property.serial_number,
                     IFNULL(property_articles.article_name,property.article) as article,
                     (CASE
                     WHEN par.is_unserviceable =1 THEN 'UnServiceable'
                     ELSE 'Serviceable' 
                     END ) as is_unserviceable
         
         FROM par 
         JOIN property ON par.fk_property_id = property.id
         LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
           LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
         LEFT JOIN location ON par.fk_location_id = location.id
         WHERE par.fk_received_by = :emp_id
         AND par.is_current_user = 1
         ")
                ->bindValue(':emp_id', $emp_id)
                ->queryAll();
            return json_encode($qry);
        }
    }
    private  function getSerialNo($office_id)
    {
        $office_name = Office::findOne($office_id)->office_name;
        $query = Yii::$app->db->createCommand("call getRlsddpNo(:office_id)")
            ->bindValue(':office_id', $office_id)
            ->queryOne();
        $num = 1;
        if (!empty($query['vcnt_num'])) {
            $num = intval($query['vcnt_num']);
        } else if (!empty($query['lst_num'])) {
            $num = intval($query['lst_num']);
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = strtoupper($office_name) . '-RLSDDP-' . $new_num;
        return $string;
    }
    public function actionSearchRlsddp($q = null, $id = null, $page = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $limit = 5;
        $offset = ($page - 1) * $limit;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('rlsddp.id, rlsddp.serial_number AS text')
                ->from('rlsddp')
                ->where(['like', 'rlsddp.serial_number', $q]);
            if (!empty($page)) {

                $query->offset($offset)
                    ->limit($limit);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            if (!empty($page)) {
                $out['pagination'] = ['more' => !empty($data) ? true : false];
            }
        }
        // elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        // }
        return $out;
    }
}
