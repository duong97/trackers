<?php

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;

use app\models\Users;
use app\models\UserTracking;
use app\models\Products;
use app\models\PriceLogs;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;

$mPriceLogs = new PriceLogs;
$mUserTracking = new UserTracking;
$aProductId     = $mUserTracking->getArrayActive();
$array = $mPriceLogs->getArrayLastPrice($aProductId);
echo '<pre>';
print_r(microtime(true));
echo '</pre>';
die;
$prev = strtotime(date('Y-m-d 23:29:00'));
$now = strtotime(date('Y-m-d H:i:s'));
echo $now - $prev;