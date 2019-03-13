<?php

use yii\helpers\Html;
use app\helpers\MyFormat;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

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
            'name',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->url, $model->url, ["target"=>'_blank']);
                }
            ],
            [
                'attribute' => 'url_redirect',
                'format' => 'raw',
                'value' => function($model){
                    return Html::a($model->url_redirect, $model->url_redirect, ["target"=>'_blank']);
                }
            ],
            [
                'attribute' => 'price',
                'value' => function($model){
                    return MyFormat::formatCurrency($model->price);
                }
            ],
            [
                'attribute' => 'slug',
                'value' => function($model){
                    return $model->slug;
                }
            ],
            [
                'attribute' => 'created_date',
                'value' => function($model){
                    return MyFormat::formatDatetime($model->created_date);
                }
            ],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function($model){
                    return Html::img($model->image);
                }
            ],
        ],
    ]) ?>

</div>
