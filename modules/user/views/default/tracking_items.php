<?php 
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Tracking items');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'rProduct.image',
            'format' => 'raw',
            'contentOptions' => [
                'class' => 'text-center'
            ],
            'value' => function($model){
                return Html::img($model->rProduct->image, ['alt' => $model->rProduct->name, 'class' => 'thumb img-thumbnail']);
            },
        ],
        [
            'attribute' => 'rProduct.name',
            'value' => 'rProduct.name'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $urlManager  = \Yii::$app->getUrlManager();
                    $url = $urlManager->createUrl(['user/default/stop-tracking', 'id' => $model->rProduct->id]);
                    return Html::a(Yii::t('app', 'Stop tracking'), $url, [
                                'class' => 'btn btn-danger need-confirm'
                    ]);
                }
            ]
        ],
    ],
]) ?>

<script>
    $(function(){
        bindConfirm('<?= Yii::t('app', 'Are you sure') . "?" ?>');
    });
</script>