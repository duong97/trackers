<?php 
use app\components\ListViewWidget;

$this->title = Yii::t('app', 'The most tracked products');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= ListViewWidget::widget(['dataProvider'=>$dataProvider]) ?>