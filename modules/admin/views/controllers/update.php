<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Controllers */

$this->title = 'Cập nhật chức năng: ' . $model->display_name;
$this->params['breadcrumbs'][] = ['label' => 'Controllers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->controller_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="controllers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
