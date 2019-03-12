<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Loggers */

$this->title = 'Create Loggers';
$this->params['breadcrumbs'][] = ['label' => 'Loggers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loggers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
