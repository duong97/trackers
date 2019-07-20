<?php 
use yii\widgets\ListView;
use kop\y2sp\ScrollPager;
?>

<?php echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "<div class='container'>{items}\n{pager}</div>",
    'pager' => [
        'class'     => ScrollPager::className(),
        'triggerText' => Yii::t('app', 'Load more'),
    ],
    'itemOptions' => [
        'class'=>'item'
    ],
    'itemView' => function ($model) {
        return $this->render('_item', ['model' => $model]);
    },
]);

