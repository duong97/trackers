<?php

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;
use yii\jui\AutoComplete;

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
use yii\helpers\Html;


//$m = new Notifications;
//$aProductId = [23];
//$m->notifyPriceChanged($aProductId);

//$aProductId = [20, 22, 23];
//$aTracking = UserTracking::find()
//                ->select(['count(id) as id', 'product_id'])
//                ->where(['in', 'product_id', $aProductId])
//                ->groupBy(['product_id'])
//                ->all();
//
//        foreach ($aTracking as $value) {
//            echo '<pre>';
//            print_r($value->attributes);
//            echo '</pre>'; 
//}



    $data = ['Duong', 'hihi', 'do ngoc', 'dadalkadsf'];
    echo AutoComplete::widget([
    'name' => 'Company',
    'id' => 'ddd',
    'clientOptions' => [
        'source' => $data,
        'autoFill'=>true,
        'minLength'=>'0',
        'autoFocus'=>true,
        'select' => "function( event, ui ) {
                alert();
            }"
        ],
     ]);
     ?>
<script>
$("#ddd").bind('focus', function(){ $(this).autocomplete("search"); } );
</script>
