<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Menus;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Loggers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Menus', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'name',
                'value' => function($model){
                    return $model->name;
                }
            ],
            [
                'attribute' => 'url',
                'value' => function($model){
                    return $model->url;
                }
            ],
            [
                'attribute' => 'display_order',
                'value' => function($model){
                    return $model->display_order;
                }
            ],
            [
                'attribute' => 'is_show',
                'value' => function($model){
                    return $model->isShow();
                },
                'filter'=> Menus::$aShow,
            ],


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
