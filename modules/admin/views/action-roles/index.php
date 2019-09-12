<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Phân quyền';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-roles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
            'attribute' => 'controller_id',
                'value' => function ($model){
                    return isset($model->rController) ? $model->rController->display_name : '';
                }
            ],
            [
            'attribute' => 'role_id',
                'value' => function ($model){
                    return $model->getRole();
                }
            ],
            'actions:ntext',
            [
            'attribute' => 'can_access',
                'value' => function ($model){
                    return $model->getAccess();
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
