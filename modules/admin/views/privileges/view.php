<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\MyFormat;

/* @var $this yii\web\View */
/* @var $model app\models\Privileges */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Privileges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="privileges-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
                'attribute' => 'module',
                'value' => function($model){
                    return $model->module;
                }
            ],
            [
                'attribute' => 'controller',
                'value' => function($model){
                    return $model->controller;
                }
            ],
            [
                'attribute' => 'action',
                'value' => function($model){
                    return $model->action;
                }
            ],
            [
                'attribute' => 'relate_id',
                'value' => function($model){
                    return $model->relate_id;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->type;
                }
            ],
        ],
    ]) ?>

</div>
