<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\MyFormat;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Products */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n<div class='full-width text-center'>{pager}</div>\n{items}\n<div class='full-width text-center'>{pager}</div>",
        'pager' => [
            'options' => [
                'class' => 'pagination'
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function($model){
                    return Html::img($model->image, ['style'=>'width:70px;']);
                }
            ],

            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model){
                    return $model->getProductNameWithLink();
                }
            ],
//            'url:ntext',
            [
                'attribute' => 'price',
                'value' => function($model){
                    return MyFormat::formatCurrency($model->price);
                }
            ],
            //'slug',
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return MyFormat::formatDatetime($model->created_date);
                }
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
    <?php Pjax::end(); ?>
</div>
