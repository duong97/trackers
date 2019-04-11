<?php

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;

use app\models\Users;
use app\models\Notifications;
use app\models\UserTracking;
use app\models\Products;
use app\models\PriceLogs;
use app\models\Controllers;
use app\models\Mailer;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;


//$m = new Notifications;
//$aProductId = [23];
//$m->notifyPriceChanged($aProductId);
$aProductId = [20, 22, 23];
$aTracking = UserTracking::find()
                ->select(['count(id) as id', 'product_id'])
                ->where(['in', 'product_id', $aProductId])
                ->groupBy(['product_id'])
                ->all();

        foreach ($aTracking as $value) {
            echo '<pre>';
            print_r($value->attributes);
            echo '</pre>';
    
}