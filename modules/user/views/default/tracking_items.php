<?php 
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use app\helpers\Checks;

$this->title = Yii::t('app', 'Tracking items');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
// FOR ROOT ADMIN
if(Checks::isRoot()){ ?>
    <div style="margin: 15px 0">
        <a href="<?= Url::to(['/admin/root-admin/tracking-all']) ?>" class="btn btn-primary">
            Theo dõi tất cả
        </a>
        <a href="<?= Url::to(['/admin/root-admin/stop-tracking-all']) ?>" class="btn btn-primary">
            Hủy theo dõi tất cả
        </a>
    </div>
<?php } ?>

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
                if(isset($model->rProduct->image))
                    return Html::img($model->rProduct->image, ['alt' => $model->rProduct->name, 'class' => 'thumb img-thumbnail']);
                return '';
            },
        ],
        [
            'attribute' => 'rProduct.name',
            'format' => 'raw',
            'value' => function($model){
                if(isset($model->rProduct)){
                    $url        = Url::to(['/product/action/detail', 'url'=>$model->rProduct->url]);
                    return Html::a($model->rProduct->name, $url);
                }
                return '';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    if(isset($model->rProduct->id)){
                        $url = Url::to(['user/default/stop-tracking', 'id' => $model->rProduct->id]);
                        return Html::a(Yii::t('app', 'Stop tracking'), $url, [
                                    'class' => 'btn btn-danger need-confirm'
                        ]);
                    }
                    return '';
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