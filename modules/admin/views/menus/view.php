<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\MyFormat;

/* @var $this yii\web\View */
/* @var $model app\models\Menus */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="menus-view">

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
                'attribute' => 'name',
                'value' => function($model){
                    return $model->name;
                }
            ],
            [
                'attribute' => 'url',
                'value' => function($model){
                    return $model->url;
                }
            ],
            [
                'attribute' => 'display_order',
                'value' => function($model){
                    return $model->display_order;
                }
            ],
            [
                'attribute' => 'is_show',
                'value' => function($model){
                    return $model->isShow();
                }
            ],
        ],
    ]) ?>

</div>
