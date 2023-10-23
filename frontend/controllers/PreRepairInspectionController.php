<?php

namespace frontend\controllers;

use Yii;
use app\models\PreRepairInspection;
use app\models\PreRepairInspectionSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PreRepairInspectionController implements the CRUD actions for PreRepairInspection model.
 */
class PreRepairInspectionController extends Controller
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
                    'update',
                    'delete',

                ],
                'rules' => [
                    [
                        'actions' => [
                            'view',
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['view_pre_repair_inspection']
                    ],
                    [
                        'actions' => [

                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_pre_repair_inspection']
                    ],
                    [
                        'actions' => [
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['create_pre_repair_inspection']
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
     * Lists all PreRepairInspection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PreRepairInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PreRepairInspection model.
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
     * Creates a new PreRepairInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PreRepairInspection();

        if ($model->load(Yii::$app->request->post())) {
            $model->id = YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->serial_number = $this->serialNumber();
            if ($model->save(false)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PreRepairInspection model.
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
     * Deletes an existing PreRepairInspection model.
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
     * Finds the PreRepairInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PreRepairInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PreRepairInspection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function serialNumber()
    {


        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED) AS last_number 
        FROM pre_repair_inspection ORDER BY last_number DESC LIMIT 1")->queryScalar();

        if (!empty($last_num)) {
            $last_num = intval($last_num) + 1;
        } else {
            $last_num = 1;
        }
        $zero = '';
        for ($i = strlen($last_num); $i < 4; $i++) {
            $zero .= 0;
        }
        return date('Y') . '-' . $zero . $last_num;
    }
}
