<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ActionRoles */

$this->title = 'Update Action Roles: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Action Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="action-roles-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
