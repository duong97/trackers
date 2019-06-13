<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Controllers */

$this->title = 'Tạo mới Controllers';
$this->params['breadcrumbs'][] = ['label' => 'Controllers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="controllers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
