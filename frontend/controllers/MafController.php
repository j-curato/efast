<?php

namespace frontend\controllers;

use app\models\ChartOfAccounts;
use app\models\RecordAllotmentDetailedSearch;
use app\models\RecordAllotments;
use ErrorException;
use Yii;
use yii\web\NotFoundHttpException;

class MafController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new RecordAllotmentDetailedSearch();
        $searchModel->isMaf = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'all', '');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionCreate()
    {
        $model = new RecordAllotments();
        $model->isMaf = true;

        if ($model->load(Yii::$app->request->post()) || Yii::$app->request->post()) {

            try {
                $txn = Yii::$app->db->beginTransaction();
                $mafItems = Yii::$app->request->post('mafItems') ?? [];
                $adjustmentItems = Yii::$app->request->post('adjustmentItems') ?? [];

                $res =  $this->checkItemTotal($adjustmentItems, $mafItems);
                if ($res !== true) {
                    throw new ErrorException($res);
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insMafItems = $model->insertMafItems($mafItems);
                if ($insMafItems !== true) {
                    throw new ErrorException($insMafItems);
                }
                $insAdjstItms = $model->insertAdjsutmentItems($adjustmentItems);
                if ($insAdjstItms !== true) {
                    throw new ErrorException($insAdjstItms);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollback();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) || Yii::$app->request->post()) {

            try {
                $txn = Yii::$app->db->beginTransaction();
                $mafItems = Yii::$app->request->post('mafItems') ?? [];
                $adjustmentItems = Yii::$app->request->post('adjustmentItems') ?? [];
                $res =  $this->checkItemTotal($adjustmentItems, $mafItems);
                if ($res !== true) {
                    throw new ErrorException($res);
                }
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                $insMafItems = $model->insertMafItems($mafItems);
                if ($insMafItems !== true) {
                    throw new ErrorException($insMafItems);
                }
                $insAdjstItms = $model->insertAdjsutmentItems($adjustmentItems);
                if ($insAdjstItms !== true) {
                    throw new ErrorException($insAdjstItms);
                }
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $txn->rollback();
                return $e->getMessage();
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = RecordAllotments::find()->andWhere(['id' => $id])->andWhere(['isMaf' => 1])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    private function checkItemTotal($adjustmentItems, $mafItems)
    {
        $adjustmentItemsTtl = floatval(abs(array_sum(array_column($adjustmentItems, 'amount'))));
        $mafItemsTtl = floatval(array_sum(array_column($mafItems, 'amount')));
        return $mafItemsTtl !== $adjustmentItemsTtl ? 'Not Equal' : true;
    }
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $view = 'view';
        return $this->render($view, [
            'model' => $model,
        ]);
    }
    public function actionSearchChartOfAccounts($page = null, $q = null, $id = null, $majorAccId = null)
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        // if ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => Payee::findOne($id)->account_name];
        // } else 
        if (!is_null($q) && !empty($majorAccId)) {
            $data = ChartOfAccounts::searchChartOfAccounts($q, $page, $majorAccId);
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
}
