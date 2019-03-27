<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

$x = __DIR__ . '/../../yii2-framework/vendor/autoload.php';
$x = __DIR__ . '/../../yii2-framework/vendor';

require __DIR__ . '/../../yii2-framework/vendor/autoload.php';
require __DIR__ . '/../../yii2-framework/vendor/yiisoft/yii2/Yii.php';

//$config = require __DIR__ . '/../config/web.php';
$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../config/web.php',
    require __DIR__ . '/../config/bootstrap.php'
);
(new yii\web\Application($config))->run();
