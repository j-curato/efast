<?php

namespace frontend\controllers;

use app\models\RecordAllotmentDetailed;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class BudgetReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'status-of-funds-per-mfo',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'status-of-funds-per-mfo',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
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

    function actionStatusOfFundsPerMfo()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerMfo($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['allotment_class'];
            }, 'mfo_name']);
            return json_encode($result);
        }
        return $this->render('budget_status_of_funds_per_mfo');
    }
}
