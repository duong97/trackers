<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ActionRoles */

$this->title = 'Create Action Roles';
$this->params['breadcrumbs'][] = ['label' => 'Action Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-roles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
