<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Loggers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
//            'url:ntext',
            'price',
//            'image:ntext',
            //'slug',
            //'created_date',

//            [
//                'attribute' => 'type',
//                'value' => function($model){
//                    return $model->type];
//                }
//            ],
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
