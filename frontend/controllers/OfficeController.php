<?php

namespace frontend\controllers;

use Yii;
use app\models\Office;
use app\models\OfficeSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OfficeController implements the CRUD actions for Office model.
 */
class OfficeController extends Controller
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
                    'delete',
                    'create',
                    'search-office'

                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'delete',
                            'create',
                        ],
                        'allow' => true,
                        'roles' => ['office']
                    ],
                    [
                        'actions' => [
                            'search-office'
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
    public function actionSearchOffice($q = null, $id = null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            // $out['results'] = ['id' => $id, 'text' => Payee::findOne($id)->account_name];
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('office.id, office.office_name AS text')
                ->from('office')
                ->where(['like', 'office.office_name', $q]);


            // $query->offset($offset)
            //     ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    /**
     * Lists all Office models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OfficeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Office model.
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
     * Creates a new Office model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Office();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Office model.
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
     * Deletes an existing Office model.
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
     * Finds the Office model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Office the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Office::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    // public function actionCreate()
    // {
    //     $offices = Office::find()->all();


    //     if (Yii::$app->request->isPost) {
    //         // return json_encode($_POST);
    //         if (Office::loadMultiple($offices, Yii::$app->request->post()) && Office::validateMultiple($offices)) {

    //             // $data = json_decode(Yii::$app->request->rawBody, true);
    //             // return json_encode(Yii::$app->request->rawBody);
    //             return json_encode(Yii::$app->request->post('Office'));

    //             foreach (Yii::$app->request->post('Office') ?? [] as $officeData) {
    //                 if (empty($officeData['id'])) {

    //                     $office = new Office();
    //                     $office->attributes = $officeData;
    //                     $offices[] = $office;
    //                 }
    //             }
    //             // $o = [];
    //             // foreach (Yii::$app->request->post('Office') as $officeData) {
    //             //     return json_encode($officeData->isNewR);
    //             //     $office = new Office();
    //             //     $office->attributes = $officeData;
    //             //     $o[] = $office;
    //             // }
    //             foreach ($offices as $order) {
    //                 $order->save(false); // Save each order without re-validating
    //             }
    //             Yii::$app->session->setFlash('success', 'Orders updated successfully.');
    //             return $this->refresh();
    //         }
    //     }

    //     return $this->render('sample_form', ['items' => $offices]);
    // }
}
