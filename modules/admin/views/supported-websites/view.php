<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\MyFormat;
use app\models\SupportedWebsites;

/* @var $this yii\web\View */
/* @var $model app\models\SupportedWebsites */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Supported Websites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="supported-websites-view">

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
            'id',
            [
                'attribute' => 'logo',
                'format' => 'raw',
                'value' => function($model){
                    return Html::img($model->logo);
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
                'attribute' => 'status',
                'format'=>'raw',
                'value' => function($model){
                    $value = isset(SupportedWebsites::$aStatus[$model->status]) ? SupportedWebsites::$aStatus[$model->status] : '';
                    return Html::label($value, null, ['class' => SupportedWebsites::$aStatusCss[$model->status]]);
                },
            ],
        ],
    ]) ?>

</div>
