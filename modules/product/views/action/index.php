<?php 
$this->title = Yii::t('app', 'Search Results');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\components\ProductViewWidget::widget(['aData'=>$aData]) ?>
        
