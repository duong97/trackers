<?php

use yii\helpers\Html;
use app\helpers\MyFormat;
use app\models\SupportedWebsites;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supported Websites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supported-websites-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Supported Websites', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->getLogoUrl(), ['style'=>'max-width:20px;']);
                }
            ],
            [
                'attribute' => 'name',
                'value' => function($model){
                    return $model->name;
                }
            ],
            [
                'attribute' => 'url',
                'format'=>'raw',
                'value' => function($model){
                    return Html::a($model->url, $model->url, ['target'=>'_blank']);
                }
            ],
            [
                'attribute' => 'currency',
                'value' => function($model){
                    return isset(SupportedWebsites::$aCurrency[$model->currency]) ? SupportedWebsites::$aCurrency[$model->currency] : '';
                }
            ],
            [
                'attribute' => 'check_time',
                'value' => function($model){
                    return $model->check_time;
                }
            ],
            [
                'attribute' => 'note',
                'value' => function($model){
                    return empty($model->note) ? '' : $model->note;
                }
            ],
            [
                'attribute' => 'status',
                'format'=>'raw',
                'value' => function($model){
                    $value = isset(SupportedWebsites::$aStatus[$model->status]) ? SupportedWebsites::$aStatus[$model->status] : '';
                    return Html::label($value, null, ['class' => SupportedWebsites::$aStatusCss[$model->status]]);
                },
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
