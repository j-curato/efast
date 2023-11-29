<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\models\Municipalities;
use yii\filters\AccessControl;

class MunicipalitiesController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [

                    [
                        'actions' => [
                            'get-municipalities',
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
    public function actionGetMunicipalities()
    {
        if (Yii::$app->request->post()) {
            return json_encode(Municipalities::getMunicipalitiesByProvinceId(YIi::$app->request->post('id')));
        }
    }
}
