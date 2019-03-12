<?php
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => '../../yii2-framework/vendor',
    'language'=>'en', // Default language
    'components' => [
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
//            'class' => 'yii\swiftmailer\Mailer',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'useFileTransport' => true,
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',  
                'username' => $params['verifyEmail'],
                'password' => $params['verifyEmailPassword'],
                'port' => '25', 
                'encryption' => 'tls', 
            ],
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
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
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
