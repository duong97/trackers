<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupportedWebsites */

$this->title = 'Update Supported Websites: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Supported Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supported-websites-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
