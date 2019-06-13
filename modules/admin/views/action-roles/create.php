<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ActionRoles */

$this->title = 'Tạo mới phân quyền';
$this->params['breadcrumbs'][] = ['label' => 'Phân quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-roles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
