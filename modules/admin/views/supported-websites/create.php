<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupportedWebsites */

$this->title = 'Tạo mới website hỗ trợ';
$this->params['breadcrumbs'][] = ['label' => 'Websie hỗ trợ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supported-websites-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
