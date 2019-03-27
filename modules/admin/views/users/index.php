<?php

use app\helpers\MyFormat;
use app\helpers\Checks;

use app\models\Users;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Users', ['create'], ['class' => 'btn btn-success']) ?>
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
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'email',
                'value' => function($model){
                    return $model->email;
                }
            ],
            [
                'attribute' => 'first_name',
                'value' => function($model){
                    return $model->first_name;
                }
            ],
            [
                'attribute' => 'last_name',
                'value' => function($model){
                    return $model->last_name;
                }
            ],
            [
                'attribute' => 'status',
                'format'=>'raw',
                'value' => function($model){
                    $value = isset(Users::$aStatus[$model->status]) ? Users::$aStatus[$model->status] : '';
                    $css   = isset(Users::$aStatusCss[$model->status]) ? Users::$aStatusCss[$model->status] : '';
                    return Html::label($value, null, ['class' => $css]);
                }
            ],
            [
                'attribute' => 'ip',
                'value' => function($model){
                    return $model->ip;
                },
                'visible' => Checks::isRoot()
            ],
            [
                'attribute' => 'last_access',
                'value' => function($model){
                    return MyFormat::formatDatetime($model->last_access);
                }
            ],
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return MyFormat::formatDatetime($model->created_date);
                }
            ],
            //'first_name',
            //'last_name',
            //'social_id',
            //'is_notify_fb',
            //'is_notify_email:email',
            //'notify_type',
            //'role',
            //'status',
            //'ip',
            //'last_access',
            //'created_date',


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
