<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\MyFormat;

/* @var $this yii\web\View */
/* @var $model app\models\Blog */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Blog'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="blog-view">

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
                'attribute' => 'title',
                'value' => function($model){
                    return $model->title;
                }
            ],
            [
                'attribute' => 'description',
                'value' => function($model){
                    return $model->description;
                }
            ],
            [
                'attribute' => 'content',
                'value' => function($model){
                    return $model->content;
                }
            ],
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->type;
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->status;
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->created_by;
                }
            ],
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return $model->created_date;
                }
            ],
        ],
    ]) ?>

</div>
