<?php

namespace frontend\controllers;

use Yii;
use app\models\Mgrfrs;
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
                    ]
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
            // return json_encode(Mgrfrs::findOne(5)->getCashBalanceById($liquidatedFilters));
            // return  $model->getMgrfrDetails();
            return json_encode(
                [
                    'cashDepositBalance' => $model->getCashBalanceById($liquidatedFilters, $cashDepositFilter),
                    'liquidations' => $model->getLiquidations($reporting_period),
                    'cashDeposits' => $model->getCashDepositsByPeriod($reporting_period),
                    'mgrfrDetails' => $model->getMgrfrDetails()
                ]
            );
        }
        return $this->render('mg_sord');
    }
}
