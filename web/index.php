<?php
// comment out the following two lines when deployed to production
$ipaddress = '';
if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
else if(isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
else
    $ipaddress = 'UNKNOWN';
$IP_ALLOW_DEBUG = [
    '1.52.85.35',
    '::1', // localhost
];
$isDebug = in_array($ipaddress, $IP_ALLOW_DEBUG);
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');

//$x = __DIR__ . '/../../yii2-framework/vendor/autoload.php';
//$x = __DIR__ . '/../../yii2-framework/vendor';

require __DIR__ . '/../../yii2-framework/vendor/autoload.php';
require __DIR__ . '/../../yii2-framework/vendor/yiisoft/yii2/Yii.php';

//$config = require __DIR__ . '/../config/web.php';
$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../config/web.php',
    require __DIR__ . '/../config/bootstrap.php'
);

// Const use for whole project
require __DIR__ . '/../config/const.php';

(new yii\web\Application($config))->run();
