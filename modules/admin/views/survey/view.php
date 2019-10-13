<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\MyFormat;

/* @var $this yii\web\View */
/* @var $model app\models\Survey */

$this->title = $model->question;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="survey-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
                'attribute' => 'question',
                'value' => function($model){
                    return $model->question;
                }
            ],
            [
                'attribute' => 'answer',
                'contentOptions' => [
                    'style'=>'white-space:pre-wrap;'
                ],
                'value' => function($model){
                    return $model->getAnswer();
                },
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->getType();
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatus();
                }
            ],
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return $model->getCreatedDate();
                }
            ],
        ],
    ]) ?>

</div>
