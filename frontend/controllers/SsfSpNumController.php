<?php

namespace frontend\controllers;

use app\models\Office;
use Yii;
use app\models\SsfSpNum;
use app\models\SsfSpNumSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SsfSpNumController implements the CRUD actions for SsfSpNum model.
 */
class SsfSpNumController extends Controller
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
                    'update',
                    'index',
                    'view',
                    'create',
                    'delete',
                    'search-SsfSp'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'index',
                            'view',
                            'create',
                            'delete',
                            'search-SsfSp'
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ],
                    [
                        'actions' => [
                            'search-SsfSp'
                        ],
                        'allow' => true,
                        'roles' => ['@']
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
     * Lists all SsfSpNum models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SsfSpNumSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SsfSpNum model.
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
     * Creates a new SsfSpNum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SsfSpNum();

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
            $model->fk_office_id = $office_id;
        }
        $model->date = date('Y-m-d');
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->serial_number = $this->getSerialNumber($model->fk_office_id);
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SsfSpNum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SsfSpNum model.
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
     * Finds the SsfSpNum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SsfSpNum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SsfSpNum::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function getSerialNumber($office_id)
    {

        $office_name = Office::findOne($office_id)->office_name;

        $lastNumQry = YIi::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as l_num FROM ssf_sp_num
        WHERE 
        ssf_sp_num.serial_number LIKE :offce
        ORDER BY l_num DESC
        LIMIT 1

        ")
            ->bindValue('offce', $office_name . '%')
            ->queryScalar();
        $lastNum = 1;
        if (!empty($lastNumQry)) {
            $lastNum = intval($lastNumQry) + 1;
        }
        $zero = '';
        $num_len =  5 - strlen($lastNum);
        if ($num_len > 0) {
            $zero = str_repeat(0, $num_len);
        }

        return strtoupper($office_name) . '-' . $zero . $lastNum;
    }

    public function actionSearchSsfSp($page = 1, $q = null, $id = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => SsfSpNum::findOne($id)->serial_number];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('ssf_sp_num.id, ssf_sp_num.serial_number AS text')
                ->from('ssf_sp_num')
                ->where(['like', 'ssf_sp_num.serial_number', $q]);
            if (!Yii::$app->user->can('super-user')) {
                $user_data = Yii::$app->memem->getUserData();
                $office_id = $user_data->office->id;
                $query->andWhere('fk_office_id = :ofc_id', ['ofc_id' => $office_id]);
            }
            $query->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
}
