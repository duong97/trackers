<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\MyFormat;
use app\models\Blog;

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
                'attribute' => 'thumb',
                'format' => 'raw',
                'value' => function($model){
                    return $model->getThumbnailHtml();
                }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->title, $model->getUrlUserView(), ["target"=>'_blank']);
                }
            ],
            [
                'attribute' => 'description',
                'value' => function($model){
                    return $model->description;
                },
                'options' => [
                    'style' => 'width: 300px;'
                ]
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
                    return MyFormat::formatDatetime($model->created_date);
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return $model->getCreatedBy();
                }
            ],
            [
                'attribute' => 'content',
                'format'=>'raw',
                'value' => function($model){
                    return $model->content;
                }
            ]
        ],
    ]) ?>

</div>
