<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Feedback */

$this->title = $model->getUser();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Feedbacks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($model->can('update')): ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
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
                'attribute' => 'survey_id',
                'value' => function($model){
                    return $model->getQuestion();
                }
            ],
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    return $model->getUser();
                }
            ],
            [
                'attribute' => 'ip',
                'value' => function($model){
                    return $model->ip;
                }
            ],
            [
                'attribute' => 'another_info',
                'value' => function($model){
                    return $model->another_info;
                }
            ],
            [
                'attribute' => 'answer',
                'format'=>'raw',
                'value' => function($model){
                    return $model->getAnswer();
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
