<?php
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
