<?php
return [
    'id' => 'app-console',
    'class' => 'yii\web\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'bootstrap' => [],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'banana',
            'enableCookieValidation' => false,
            'isConsoleRequest' => false,
            'hostInfo' => 'http://localhost',
            'baseUrl' => '/',

        ],

        'urlManager' => [
            'class' => \bl\locale\UrlManager::className(),
            'baseUrl' => '/',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'languageProvider' => [
                'class' => '\bl\locale\provider\ConfigLanguageProvider',
                'languages' => [
                    'ru-ru',
                    'en-us',
                    'en-UK',
                    'uk-UA',
                    'uk',
                ]
            ],
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
