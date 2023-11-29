<?php

namespace frontend\controllers;

use Yii;
use app\models\Barangays;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class BarangaysController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [

                    [
                        'actions' => [
                            'get-barangays',
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
    public function actionGetBarangays()
    {
        if (Yii::$app->request->post()) {
            return json_encode(Barangays::getBarangaysByMunicipalityId(YIi::$app->request->post('id')));
        }
    }
}
