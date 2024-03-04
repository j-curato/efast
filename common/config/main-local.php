<?php
return [
    'components' => [
        //'db' => [
        //    'class' => 'yii\db\Connection',
        //    'dsn' => 'mysql:host=db-efast.cur0n7vldhz5.ap-southeast-1.rds.amazonaws.com;dbname=efast',
        //    'username' => 'efastadmin',
        //    'password' => 'Dticaraga13',
        //    'charset' => 'utf8',
        //],
         'db' => [
             'class' => 'yii\db\Connection',
             'dsn' => 'mysql:host=dtilocalserver;dbname=efast',
             'username' => 'dbadmin2',
             'password' => 'password',
             'charset' => 'utf8',
         ],
        // 'db' => [
        //     'class' => 'yii\db\Connection',
        //     'dsn' => 'mysql:host=db-efast.cur0n7vldhz5.ap-southeast-1.rds.amazonaws.com;dbname=efast',
        //     'username' => 'efastadmin',
        //     'password' => 'Dticaraga13',
        //     'charset' => 'utf8',
        // ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
