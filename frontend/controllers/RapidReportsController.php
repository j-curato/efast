<?php

namespace frontend\controllers;

use app\models\FmiSubprojects;
use Yii;
use app\models\Mgrfrs;
use app\models\RapidMgDatabaseSearch;
use yii\filters\AccessControl;

class RapidReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'mg-sord'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [
                            'mg-database'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => [
                            'fmi-sord'
                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }
    public function actionMgSord()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $reporting_period = Yii::$app->request->post('reporting_period');
            $model = Mgrfrs::findOne($id);

            $liquidatedFilters = [
                [
                    'value' => $reporting_period,
                    'operator' => '<',
                    'column' => 'tbl_mg_liquidations.reporting_period'
                ]
            ];
            $cashDepositFilter = [
                [
                    'value' => $reporting_period,
                    'operator' => '<',
                    'column' => 'cash_deposits.reporting_period'
                ]
            ];
            return json_encode(
                [
                    'cashDepositBalance' => $model->getCashBalanceById($reporting_period),
                    'liquidations' => $model->getLiquidations($reporting_period),
                    'cashDeposits' => $model->getCashDepositsByPeriod($reporting_period),
                    'mgrfrDetails' => $model->getMgrfrDetails()
                ]
            );
        }
        return $this->render('mg_sord');
    }
    public function actionMgDatabase()
    {

        $searchModel = new RapidMgDatabaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('mg_database', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionFmiSord()
    {
        if (Yii::$app->request->post()) {
            $id = Yii::$app->request->post('id');
            $reportingPeriod = Yii::$app->request->post('reporting_period');
            $model = FmiSubprojects::findOne($id);
            // return      $model->getGrantByPeriod($reportingPeriod);
            return json_encode(
                [
                    'beginningBalance' => $model->getBeginningBalance($reportingPeriod),
                    'liquidations' => $model->getLiquidationsA($reportingPeriod),
                    // 'cashDeposits' => $model->getFundReleasesA($reportingPeriod),
                    'details' => $model->getDetails(),
                    'grantDepositsForTheMonth' => $model->getGrantDepositsByPeriod($reportingPeriod),
                    'equityDepositsForTheMonth' => $model->getEquityDepositsByPeriod($reportingPeriod),
                    'otherDepositsForTheMonth' => $model->getOtherDepositsByPeriod($reportingPeriod),
                ]
            );
        }
        return $this->render('fmi_sord');
    }
}
