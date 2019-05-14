<?php 
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\SupportedWebsites;

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
                return Html::img($model->getLogoUrl(), ['style'=>'max-width:20px;']);
            }
        ],
        [
            'attribute' => 'name',
            'value' => 'name'
        ],
        [
            'attribute' => 'status',
            'format'=>'raw',
            'value' => function($model){
                $value = isset(SupportedWebsites::$aStatus[$model->status]) ? SupportedWebsites::$aStatus[$model->status] : '';
                return Html::label($value, null, ['class' => SupportedWebsites::$aStatusCss[$model->status]]);
            },
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
            'attribute' => 'note',
            'value' => function ($model){
                return empty($model->note) ? '' : $model->note;
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
