<?php 
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'The most tracked products');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\components\ProductViewWidget::widget(['aData'=>$aData]) ?>
        
<?= LinkPager::widget([
    'pagination' => $pages,
    'options' => [
        'class'=>'pagination pull-right',
    ]
]); ?>
<div class="clearfix"></div>