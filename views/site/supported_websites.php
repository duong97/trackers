<?php 
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'List of supported websites');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'logo',
            'format' => 'raw',
            'value' => function ($model){
                return Html::img($model->logo, ['class'=>'xx']);
            }
        ],
        [
            'attribute' => 'name',
            'value' => 'name'
        ],
        [
            'attribute' => 'status',
            'value' => function ($model){
                return $model->getStatus();
            }
        ],
        [
            'attribute' => 'check_time',
            'value' => 'check_time'
        ],
        [
            'attribute' => 'currency',
            'value' => function ($model){
                return $model->getCurrency();
            }
        ],
        [
            'attribute' => 'url',
            'format' => 'raw',
            'value' => function($model){
                return Html::a($model->url, $model->url);
            },
        ],
    ],
]) ?>
