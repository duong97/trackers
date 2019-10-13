<?php 
use yii\widgets\ListView;
use kop\y2sp\ScrollPager;
?>

<?php echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "<div class='container'><div class='row'>{items}\n{pager}</div></div>",
    'pager' => [
        'class'     => ScrollPager::className(),
        'triggerText' => Yii::t('app', 'Load more'),
    ],
    'itemOptions' => [
        'class'=>'item col-md-3 col-sm-4 col-xs-6 p-0'
    ],
    'itemView' => function ($model) {
        return $this->render('_item', ['model' => $model]);
    },
]);

