<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use ErrorException;
use app\models\Books;
use app\models\FundSource;
use app\models\MfoPapCode;
use app\models\AllotmentType;
use app\models\MajorAccounts;
use app\models\ChartOfAccounts;
use app\models\DocumentRecieve;
use app\models\RecordAllotments;
use yii\web\NotFoundHttpException;
use app\models\RecordAllotmentDetailedSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class MafController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'update',
                    'view',
                    'create',
                    'search-chart-of-accounts',
                    'get-allotments'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',

                        ],
                        'allow' => true,
                        'roles' => ['view_maf']
                    ],
                    [
                        'actions' => [
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['update_maf']
                    ],
                    [
                        'actions' => [
                            'create',

                        ],
                        'allow' => true,
                        'roles' => ['create_maf']
                    ],
                    [
                        'actions' => [
                            'search-chart-of-accounts',
                            'get-allotments'

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
        return number_format($mafItemsTtl, 2) !== number_format($adjustmentItemsTtl, 2) ? 'Not Equal' : true;
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
    private function getAllotmentDetailsQry($fundSourceName, $documentReceiveName, $allotmentType, $mfoPapCode, $bookName, $year, $majorAccountName)
    {
        return Yii::$app->db->createCommand(
            "SELECT 
                    record_allotment_detailed.allotment_entry_id,
                    record_allotment_detailed.balAfterObligation,
                    record_allotment_detailed.account_title,
                    record_allotment_detailed.uacs,
                    record_allotment_detailed.office_name,
                    record_allotment_detailed.division,
                    record_allotment_detailed.book_name,
                    record_allotment_detailed.fund_source_name,
                    record_allotment_detailed.document_recieve,
                     0 as amount
                FROM record_allotment_detailed
                WHERE 
                document_recieve = :documentReceiveName
                 AND fund_source_name = :fundSourceName
                AND
                 mfo_code = :mfoPapCode
                AND book_name = :bookName
                AND allotment_type = :allotmentTypeName
                AND budget_year = :yr
                AND allotment_class = :allotment_class
                AND record_allotment_detailed.balAfterObligation > 0"

        )
            ->bindValue(':fundSourceName', $fundSourceName)
            ->bindValue(':documentReceiveName', $documentReceiveName)
            ->bindValue(':allotmentTypeName', $allotmentType)
            ->bindValue(':mfoPapCode', $mfoPapCode)
            ->bindValue(':bookName', $bookName)
            ->bindValue(':yr', $year)
            ->bindValue(':allotment_class', $majorAccountName)
            ->queryAll();
    }
    public  function actionGetAllotments()
    {

        if (Yii::$app->request->post()) {
            // return json_encode('qwe');
            $allotmentTypeId = Yii::$app->request->post('allotmentTypeId');
            $bookId = Yii::$app->request->post('bookId');
            $documentReceiveId = Yii::$app->request->post('documentReceiveId');
            $mfoPapId = Yii::$app->request->post('mfoPapId');
            $reportingPeriod = Yii::$app->request->post('reportingPeriod');
            $fundSourceId = Yii::$app->request->post('fundSourceId');
            $majorAccountId = Yii::$app->request->post('majorAccountId');
            if (
                !empty($allotmentTypeId) &&
                !empty($bookId) &&
                !empty($documentReceiveId) &&
                !empty($mfoPapId) &&
                !empty($fundSourceId) &&
                !empty($majorAccountId) &&
                !empty($reportingPeriod)
            ) {
                $documentReceive = DocumentRecieve::findOne($documentReceiveId);
                $fundSource = FundSource::findOne($fundSourceId);
                $allotmentType = AllotmentType::findOne($allotmentTypeId);
                $mfoPap = MfoPapCode::findOne($mfoPapId);
                $book = Books::findOne($bookId);
                $majorAccount = MajorAccounts::findOne($majorAccountId);
                $d = DateTime::createFromFormat('Y-m', $reportingPeriod);
                $qry = $this->getAllotmentDetailsQry($fundSource->name, $documentReceive->name, $allotmentType->type, $mfoPap->code, $book->name, $d->format('Y'), $majorAccount->name);
                return  json_encode($qry);
            }
        }
    }
}
