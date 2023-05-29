<?php

use Symfony\Component\Mailer\Mailer;
use yii\web\JsonParser;


$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    // 'defaultRoute' => 'site/login',


    'components' => [
        'response' => [
            'on beforeSend' => function ($event) {
                $event->sender->headers->add('x-frame-options', 'DENY');
                $event->sender->headers->add('x-frame-options', 'nosniff');
                $event->sender->headers->add('strict-transport-security', 'max-age=31536000; preload');
                $event->sender->headers->add(' Server-Timing', 'miss, db;dur=53, app;dur=47.2d');
            },
        ],
        'request' => [
            'csrfParam' => '_csrf',


            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true

            ],
            'parsers' => [
                'application/json' => JsonParser::class
            ]
        ],
        // 'session' => [
        //     'class' => 'yii\web\Session',
        //     'httpOnly' => true,
        //     'secure' => true
        //     // 'setCookieParams' => [
        //     //     'httpOnly' => true,
        //     //     'secure' => true,
        //     // ],

        // ],
        // 'cookies' => [
        //     'class' => 'yii\web\Cookie',
        //     'httpOnly' => true,
        //     'secure' => true,
        // ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'identityCookie' => [
                'name' => '_frontendUser', // unique for frontend
                'path' => '/frontend/web'  // correct path for the frontend app.
            ]
        ],
        'session' => [
            'name' => '_frontendSessionId', // unique for frontend
            'savePath' => __DIR__ . '/../runtime', // a temporary folder on frontend
            // 'useStrictMode' => true,


        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultROles' => ['guest'],
        ],

        // 'urlManager' => [
        //     'enablePrettyUrl' => true,
        //     'showScriptName' => false,

        //     'rules' => [
        //         'index' => 'site/login'
        //     ],
        // ],

        'memem' => [
            'class' => 'frontend\components\MyComponent',
        ],


    ],
    // 'as beforeRequest' => [
    //     'class' => 'frontend\components\CheckIfLogin',

    // ],
    'params' => $params,
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],
];
