<?php

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;

use app\models\Users;
use app\models\UserTracking;
use app\models\Products;
use app\models\PriceLogs;
use app\models\Controllers;
use app\models\Mailer;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;

$mPriceLogs = new PriceLogs;
$mUserTracking = new UserTracking;
$mMailer = new Mailer();
$aProductId = [23, 20];
$mMailer->notifyPriceChanged($aProductId);
$mC = new app\models\ActionRoles;
