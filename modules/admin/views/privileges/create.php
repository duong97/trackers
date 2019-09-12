<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Privileges */

$this->title = 'Phân quyền';
$this->params['breadcrumbs'][] = ['label' => 'Quản lý quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="privileges-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
