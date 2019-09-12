<?php

use yii\helpers\Html;
use app\helpers\MyFormat;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Loggers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Phân quyền';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-primary">
    <div class="panel-heading">Thông tin</div>
    <div class="panel-body">
        <p><b>Module:</b> <?= $mControllers->module_name; ?></p>
        <p><b>Chức năng:</b> <?= $mControllers->display_name; ?></p>
    </div>
</div>
