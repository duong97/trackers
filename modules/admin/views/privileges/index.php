<?php

use yii\helpers\Html;
use app\helpers\MyFormat;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Loggers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý quyền';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="privileges-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tạo mới', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n<div class='full-width text-center'>{pager}</div>\n{items}\n<div class='full-width text-center'>{pager}</div>",
        'pager' => [
            'options' => [
                'class' => 'pagination'
            ],
        ],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'value' => function($model){
                    return $model->id;
                }
            ],
            [
                'attribute' => 'module',
                'value' => function($model){
                    return $model->module;
                }
            ],
            [
                'attribute' => 'controller',
                'value' => function($model){
                    return $model->controller;
                }
            ],
            [
                'attribute' => 'action',
                'value' => function($model){
                    return $model->action;
                }
            ],
            [
                'attribute' => 'relate_id',
                'value' => function($model){
                    return $model->relate_id;
                }
            ],
            //'type',


            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function ($model) {
                        return $model->can('update');
                    },
                    'view' => function ($model) {
                        return $model->can('view');
                    },
                    'delete' => function ($model) {
                        return $model->can('delete');
                    },
                ]
            ],
        ],
    ]); ?>
</div>
