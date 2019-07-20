<?php 
$this->title = Yii::t('app', 'Search Results');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \app\components\ListViewWidget::widget(['dataProvider'=>$aData]) ?>
        
