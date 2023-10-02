<?php

namespace frontend\controllers;

use Yii;
use app\models\RoCheckRanges;
use app\models\RoCheckRangesSearch;
use Error;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoCheckRangesController implements the CRUD actions for RoCheckRanges model.
 */
class RoCheckRangesController extends Controller
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
                    'create',
                    'delete',
                    'update',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                            'create',
                            'delete',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['ro_check_range']
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
    private function validateLbpCheckRange($from, $to, $model_id = null)
    {
        $sql  = '';
        $params = [];

        if (!empty($model_id)) {
            $sql .= ' AND ';
            $sql  .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'ro_check_ranges.id', $model_id], $params);
        }

        $qry =  Yii::$app->db->createCommand("SELECT EXISTS(SELECT *
        FROM ro_check_ranges
        WHERE 
       ( ( :_from BETWEEN ro_check_ranges.`from` AND ro_check_ranges.`to`) OR  ( :_to BETWEEN ro_check_ranges.`from` AND ro_check_ranges.`to`))
        $sql
        LIMIT 1)
        ", $params)
            ->bindValue(':_from', $from)
            ->bindValue(':_to', $to)
            ->queryScalar();

        if ($qry) {
            return  'Check Range exists a case where either the `From` or `To` values are in between other Check Range';
        }
        $range = intval($to) - intval($from) + 1;
        if ($range <> 100) {
            return 'Check type is LBP Check, check range must be equal to 100';
        }
        return true;
    }
    /**
     * Lists all RoCheckRanges models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoCheckRangesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RoCheckRanges model.
     * @param integer $id
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
     * Creates a new RoCheckRanges model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Check Type 1 for LBP check 0 form eCheck
        $model = new RoCheckRanges();
        if ($model->load(Yii::$app->request->post())) {

            try {
                if ($model->to < $model->from) {
                    throw new ErrorException('`To` Check Number Cannot be less than `From`');
                }
                if (intval($model->check_type) === 1) {
                    $vldt = $this->validateLbpCheckRange($model->from, $model->to);
                    if ($vldt !== true) {
                        throw new ErrorException($vldt);
                    }
                }


                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model save failed');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RoCheckRanges model.
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
                if ($model->to < $model->from) {
                    throw new ErrorException('`To` Check Number Cannot be less than `From`');
                }
                if (intval($model->check_type) === 1) {
                    $vldt = $this->validateLbpCheckRange($model->from, $model->to, $model->id);
                    if ($vldt !== true) {
                        throw new ErrorException($vldt);
                    }
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model save failed');
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                return json_encode(['error_message' => $e->getMessage()]);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RoCheckRanges model.
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
     * Finds the RoCheckRanges model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RoCheckRanges the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RoCheckRanges::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearchCheckRange(
        $q = null,
        $id = null,
        $page = null,
        $book_id = null,
        $mode_of_payment_id = null
    ) {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (

            !is_null($q) &&
            !is_null($book_id) &&
            !is_null($mode_of_payment_id)

        ) {
            $check_type = Yii::$app->db->createCommand("SELECT mode_of_payments.check_type FROM mode_of_payments WHERE id = :mode_of_payment_id")
                ->bindValue(':mode_of_payment_id', $mode_of_payment_id)
                ->queryScalar();
            $query = new Query();

            $query->select(["ro_check_ranges.id,CONCAT(ro_check_ranges.`from`,'-',ro_check_ranges.`to`) as text"])
                ->from('ro_check_ranges')
                ->where(['like', 'ro_check_ranges.from', $q])
                ->orWhere(['like', 'ro_check_ranges.to', $q])
                ->andWhere('ro_check_ranges.check_type = :check_type', ['check_type' => $check_type])
                ->andWhere(['like', 'ro_check_ranges.fk_book_id', $book_id]);

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
        //  elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => AdvancesEntries::find($id)->fund_source];
        // }
        return $out;
    }
}
