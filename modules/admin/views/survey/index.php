<?php

use yii\helpers\Html;
use app\helpers\MyFormat;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Loggers */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Surveys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Survey'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'question',
                'value' => function($model){
                    return $model->question;
                }
            ],
            [
                'attribute' => 'answer',
                'value' => function($model){
                    return $model->getAnswer();
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->getType();
                },
                'filter'=> app\models\Survey::$aType
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatus();
                },
                'filter'=> \app\helpers\Constants::$aStatus
            ],
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return $model->getCreatedDate();
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
</div>
