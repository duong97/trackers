<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupportedWebsites */

$this->title = 'Cập nhật website hỗ trợ: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Website hỗ trợ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="supported-websites-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
