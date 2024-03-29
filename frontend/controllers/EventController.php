<?php

namespace frontend\controllers;

use Yii;
use app\models\Event;
use app\models\EventSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
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
                    'create',
                    'delete',
                    'index',
                    'view',
                    'update'
                ],
                'rules' => [

                    [
                        'actions' => ['create', 'delete', 'update'],
                        'allow' => true,
                        'roles' => ['super-user', '@'],
                    ],

                ],
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
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
        $ev = Event::find()->all();
        $events = [];
        foreach ($ev as $e) {

            $event = new \edofre\fullcalendar\models\Event();
            $event->id = $e->id;
            $event->title = $e->title;
            $event->start = $e->created_at;
            $event->end = $e->end_date;
            $events[] = $event;
        }
        return $this->renderAjax('index', [
            'events' => $events
        ]);
    }

    /**
     * Displays a single Event model.
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
     * Creates a new Event model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date)
    {

        $model = new Event();
        $model->created_at = $date;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $url = Yii::$app->request->baseUrl . '?r=site';
            return $this->redirect($url);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->can('super-user')) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $url = Yii::$app->request->baseUrl . '?r=site';
                return $this->redirect($url);
            }

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        } else {
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $url = Yii::$app->request->baseUrl . '?r=site';
        return $this->redirect($url);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
