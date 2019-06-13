<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\MyFormat;
use yii\widgets\Pjax;
use app\models\Products;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Products */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sản phẩm';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tạo mới sản phẩm', ['create'], ['class' => 'btn btn-success']) ?>
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
                },
                'filter' => false
            ],

            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model){
                    return $model->getProductNameWithLink();
                }
            ],
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function($model){
                    return $model->getCategory();
                },
                'filter' => Products::$aCategory
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
                },
                'filter' => \yii\jui\DatePicker::widget([
                    'attribute'     => 'created_date',
                    'language'      => 'vi', 
                    'dateFormat'    => 'dd-MM-yyyy',
                    'model'         => $searchModel,
                    'options' => [
                        'class'=>'form-control',
                        'autocomplete'=>'off'
                    ]
                ]),
                'format' => 'raw',
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
