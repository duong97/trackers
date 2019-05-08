<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Loggers;
use app\helpers\MyFormat;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Loggers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loggers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // echo Html::a('Create Loggers', ['create'], ['class' => 'btn btn-success']) ?>
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
        'rowOptions'=>function($model){
            switch ($model->type) {
                case Loggers::type_debug:
                    return ['class' => 'warning'];
                case Loggers::type_cron:
                    return ['class' => 'success'];
                case Loggers::type_app_error:
                    return ['class' => 'danger'];
                case Loggers::type_info:
                    return ['class' => 'info'];

                default:
                    break;
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'message:ntext',
//            'ip',
            [
                'attribute' => 'url',
                'format'=>'raw',
                'value' => function($model){
                    return empty($model->url) ? '' : Html::a('click', $model->url, ['target'=>'_blank']);
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return isset(Loggers::$aLogType[$model->type]) ? Loggers::$aLogType[$model->type] : '';
                },
                'filter'=> Loggers::$aLogType,
            ],
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
                        return false;
                    },
                    'view' => function ($model) {
                        return false;
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
