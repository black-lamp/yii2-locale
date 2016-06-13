<?php
return [
    'id' => 'app-console',
    'class' => 'yii\web\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'bootstrap' => [],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'isConsoleRequest' => false,
            'hostInfo' => 'http://localhost',
            'baseUrl' => '/',

        ],
        'urlManager' => [
            'class' => \common\components\locale\UrlManager::className(),
            'baseUrl' => '/',
            'showScriptName' => true,
            'rules' => [

            ],
        ],
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'sqlite:' . \Yii::getAlias('@tests/_output/temp.db'),
            'username' => '',
            'password' => '',
        ]
    ]
];
