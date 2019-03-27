<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\helpers\MyFormat;
use app\helpers\Constants;
use app\helpers\Checks;

use app\models\Users;
/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= $model->can('update') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'value' => function($model){
                    return $model->id;
                }
            ],
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
                    return Html::label($value, null, ['class' => Users::$aStatusCss[$model->status]]);
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
            [
                'attribute' => 'social_id',
                'value' => function($model){
                    return $model->social_id;
                },
                'visible' => Checks::isRoot()
            ],
            [
                'attribute' => 'is_notify_fb',
                'value' => function($model){
                    return $model->is_notify_fb;
                },
                'visible' => Checks::isRoot()
            ],
            [
                'attribute' => 'is_notify_email',
                'value' => function($model){
                    return $model->is_notify_email;
                },
                'visible' => Checks::isRoot()
            ],
            [
                'attribute' => 'notify_type',
                'value' => function($model){
                    return $model->notify_type;
                },
                'visible' => Checks::isRoot()
            ],
            [
                'attribute' => 'role',
                'value' => function($model){
                    $value = isset(Constants::$aRole[$model->role]) ? Constants::$aRole[$model->role] : '';
                    return $value;
                },
                'visible' => Checks::isRoot()
            ],
        ],
    ]) ?>

</div>
