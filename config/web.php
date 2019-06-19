<?php
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
//    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => '../../yii2-framework/vendor',
    'language'=>'vi', // Default language
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '813820762285163',
                    'clientSecret' => 'd7d3116ab1aa1d5ab6b58ba45b78b544',
                    'attributeNames' => ['name', 'email', 'first_name', 'last_name'],
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => '583166492010-fdd95ibejqh7dgelcb6m88gjcoc9nkib.apps.googleusercontent.com',
                    'clientSecret' => 'dkWMt5ddraNBUEL3N0mmuYAI',
                ],
            ],
        ],
        // Translation
        'i18n' => [
//            'class' => 'app\components\NewI18N',
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@app/messages',
                    'basePath' => __DIR__ . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR . 'messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'arM8OCffi4JaycUtbOkJSQK1VqYMY_8c',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.gmail.com',  
                'host' => 'pro43.emailserver.vn',  
                'username' => $params['verifyEmail'],
                'password' => $params['verifyEmailPassword'],
//                'port' => '25', 
//                'encryption' => 'tls', 
                'port' => '465', 
                'encryption' => 'ssl', 
            ],
//            'messageConfig' => [
//                'charset' => 'UTF-8',
//                'from' => ['admin@chartcost.com' => 'ChartCost.Com'],
//            ],
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
        'db' => $db,
        // Rewrite URL
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
//                '' => 'user/default',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>' => '<module>/<controller>',
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\UserModule',
        ],
        'product' => [
            'class' => 'app\modules\product\ProductModule',
        ],
        'admin' => [
            'class' => 'app\modules\admin\AdminModule',
        ],
        'api' => [
            'class' => 'app\modules\api\ApiModule',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => [
            '127.0.0.1', '::1', // local
//            '115.78.14.143' // ktx
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
        'generators' => [ //here
            'module' => [ // generator name
                'class' => 'yii\gii\generators\module\Generator', // generator class
                'templates' => [ //setting for out templates
                    'anivia' => '@app/templates/module/default', // template name => path to template
                ]
            ],
            'model' => [ // generator name
                'class' => 'yii\gii\generators\model\Generator', // generator class
                'templates' => [ //setting for out templates
                    'anivia' => '@app/templates/model/default', // template name => path to template
                ]
            ],
            'crud' => [ // generator name
                'class' => 'yii\gii\generators\crud\Generator', // generator class
                'templates' => [ //setting for out templates
                    'anivia' => '@app/templates/crud/default', // template name => path to template
                ]
            ]
        ],
    ];
}

return $config;
