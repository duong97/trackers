<?php

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;

use app\models\Users;
use app\models\UserTracking;
use app\models\Products;
use app\models\PriceLogs;
use app\models\Controllers;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;

$mPriceLogs = new PriceLogs;
$mUserTracking = new UserTracking;
$mC = new app\models\ActionRoles;
echo '<pre>';
print_r($mUserTracking->getArrayActive());
echo '</pre>';
die;
