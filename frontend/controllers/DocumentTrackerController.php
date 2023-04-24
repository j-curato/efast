<?php

namespace frontend\controllers;

use Yii;
use app\models\DocumentTracker;
use app\models\DocumentTrackerComplinceLink;
use app\models\DocumentTrackerLinks;
use app\models\DocumentTrackerResponsibleOffice;
use app\models\DocumentTrackerSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentTrackerController implements the CRUD actions for DocumentTracker model.
 */
class DocumentTrackerController extends Controller
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
                    'delete',
                    'view',
                    'index',
                    'create'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'view',
                            'index',
                            'create'
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
     * Lists all DocumentTracker models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentTrackerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DocumentTracker model.
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
     * Creates a new DocumentTracker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DocumentTracker();
        $link = new DocumentTrackerLinks();
        $complianceLink = new DocumentTrackerComplinceLink();
        $re_office = new DocumentTrackerResponsibleOffice();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return json_encode($_POST['qwer']);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'link' => $link,
            'complianceLink' => $complianceLink,
            're_office' => $re_office,
        ]);
    }

    /**
     * Updates an existing DocumentTracker model.
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

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DocumentTracker model.
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
     * Finds the DocumentTracker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DocumentTracker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentTracker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionInsert()
    {
        if ($_POST) {
            $date_recieve = date('Y-m-d', strtotime($_POST['date_recieved']));
            $document_type = $_POST['document_type'];
            $status = $_POST['status'];
            $office = $_POST['office'];
            $document_number = $_POST['document_number'];
            $document_date = date('Y-m-d', strtotime($_POST['document_date']));
            $details = $_POST['details'];
            $link = $_POST['link'];
            $compliance = $_POST['compliance'];
            $update_id = $_POST['update_id'];
            // 


            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (!empty($update_id)) {
                    $documentTracker = DocumentTracker::findOne($update_id);
                    foreach ($documentTracker->documentTrackerComplinceLinks as $val) {
                        $val->delete();
                    }
                    foreach ($documentTracker->documentTrackerLinks as $val) {
                        $val->delete();
                    }
                    foreach ($documentTracker->documentTrackerOffice as $val) {
                        $val->delete();
                    }
                } else {
                    $documentTracker = new DocumentTracker();
                }

                $documentTracker->date_recieved = $date_recieve;
                $documentTracker->document_type = $document_type;
                $documentTracker->status = $status;
                $documentTracker->document_number = $document_number;
                $documentTracker->document_date = $document_date;
                $documentTracker->details = $details;
                if ($documentTracker->validate()) {
                    if ($flag = $documentTracker->save(false)) {
                        foreach ($link as $i => $val) {
                            $doc_link = new DocumentTrackerLinks();
                            $doc_link->document_tracker_id = $documentTracker->id;
                            $doc_link->link = $val;
                            if ($doc_link->validate()) {
                                if ($doc_link->save(false)) {
                                } else {
                                    return json_encode(['success' => false, 'error' => 'wala na save sa doc links']);
                                }
                            } else {
                                return json_encode(['success' => false, 'error' => $doc_link->errors]);
                            }
                        }
                        foreach ($compliance as $i => $val) {
                            $doc_compliance = new DocumentTrackerComplinceLink();
                            $doc_compliance->document_tracker_id = $documentTracker->id;
                            $doc_compliance->link = $val;
                            if ($doc_compliance->validate()) {
                                if ($doc_compliance->save(false)) {
                                } else {
                                    return json_encode(['success' => false, 'error' => 'wala na save sa doc compliance']);
                                }
                            } else {
                                return json_encode(['success' => false, 'error' => $doc_compliance->errors]);
                            }
                        }
                        foreach ($office as $i => $val) {
                            $office = new DocumentTrackerResponsibleOffice();
                            $office->document_tracker_id = $documentTracker->id;
                            $office->office = $val;
                            if ($office->validate()) {
                                if ($office->save(false)) {
                                } else {
                                    return json_encode(['success' => false, 'error' => 'wala na save sa doc office']);
                                }
                            } else {
                                return json_encode(['success' => false, 'error' => $office->errors]);
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return json_encode(['success' => true, 'id' => $documentTracker->id]);
                    }
                } else {
                    return json_encode(['success' => false, 'error' => $documentTracker->errors]);
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['error' => $e->getMessage()]);
            }
        }
    }
}
