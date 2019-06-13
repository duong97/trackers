<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ActionRoles */

$this->title = $model->rController->controller_name;
$this->params['breadcrumbs'][] = ['label' => 'Phân quyền', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="action-roles-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $model->can('update') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'controller_id',
                'value' =>  $model->rController->controller_name
            ],
            [
                'attribute' => 'role_id',
                'value' => $model->getRole()
            ],
            'actions:ntext',
            [
                'attribute' => 'can_access',
                'value' =>$model->getAccess()
            ],
        ],
    ]) ?>

</div>
