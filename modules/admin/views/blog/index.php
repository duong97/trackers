<?php

use yii\helpers\Html;
use app\helpers\MyFormat;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Blog;
/* @var $this yii\web\View */
/* @var $searchModel app\models\Loggers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Blog');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php //  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Blog'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'thumb',
                'format' => 'raw',
                'value' => function($model){
                    return $model->getThumbnailHtml();
                }
            ],
            [
                'attribute' => 'title',
                'format'=>'raw',
                'value' => function($model){
                    return Html::a($model->title, $model->getUrlUserView(), ["target"=>'_blank']);
                }
            ],
            [
                'attribute' => 'description',
                'value' => function($model){
                    return $model->description;
                },
                'options' => [
                    'style' => 'width: 300px;'
                ]
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->getType();
                },
                'filter'=>Blog::$aType
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatus();
                },
                'filter'=>Blog::$aStatus
            ],
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return MyFormat::formatDate($model->created_date);
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
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->getCreatedBy();
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
