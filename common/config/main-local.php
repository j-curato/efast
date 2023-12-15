<?php

use yii\symfonymailer\Mailer;



return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=afms',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'cloud_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=163.44.242.9;dbname=vxioebfv_sample',
            'username' => 'vxioebfv_kiotipot',
            'password' => 'fuckthis.l.',
            'charset' => 'utf8',
        ],
        'ryn_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.20.17.26;dbname=afms',
            'username' => 'user1',
            'password' => 'password',
            'charset' => 'utf8',
        ],
        'afms_dev' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=afms_dev',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'restore_db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=restore',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => Mailer::class,
            'transport' => [
                // 'class' => 'Swift_SmtpTransport',
                'scheme' => 'smtps',
                'host' => 'smtp.gmail.com',
                'username' => 'norman.notorious@gmail.com',
                'password' => 'nkifgkkrewukufhd',
                'port' => 465,
                // 'dsn' => 'native://default',
                'encryption' => 'tls'
            ],
            'viewPath' => '@frontend/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],

    ],
];
