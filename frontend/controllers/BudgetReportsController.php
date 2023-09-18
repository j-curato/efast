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

    public function actionSofPerMfo()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerMfo($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['book_name'];
            }, 'allotment_class', 'mfo_name']);
            return json_encode($result);
        }
        return $this->render('budget_status_of_funds_per_mfo');
    }
    // STATUS OF FUNDS PER OFFICE/DIVISIONS
    public function actionSofPerOffice()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerOffice($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['book_name'];
            }, 'allotment_class', 'office_name', 'division']);
            return json_encode($result);
        }
        return $this->render('sof_per_office');
    }
    // STATUS OF FUNDS PER MFO - OFFICE/Division
    public function actionSofPerMfoOffice()
    {
        if (Yii::$app->request->post()) {
            $to_period  = Yii::$app->request->post('reporting_period');
            $from_period = DateTime::createFromFormat('Y-m', $to_period)->format('Y');
            $qry = RecordAllotmentDetailed::getStatusOfFundsPerMfoOffice($from_period . '-01', $to_period);
            $result = ArrayHelper::index($qry, 'document_recieve', [function ($element) {
                return $element['allotment_class'];
            }, 'mfo_name', 'office_name', 'division']);
            return json_encode($result);
        }
        return $this->render('sof_per_mfo_office');
    }
}
