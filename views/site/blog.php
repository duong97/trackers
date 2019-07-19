<?php 
use yii\widgets\ListView;
use yii\data\Pagination;
use yii\helpers\Url;
use app\models\Blog;

$this->title = 'Blog';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
    <!--LIST POST-->
    <div class="col-sm-9">
        <ul class="list">
            <?php 
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'pager' => [
                    'pagination' => new Pagination([
                                        'defaultPageSize' => DEFAULT_PAGE_SIZE,
                                    ]),
                    'options' => [
                        'class'=>'pagination pull-right',
                    ]
                ],
                'summaryOptions' => [
                    'class' => 'summary text-right'
                ],
                'layout' => "{items}\n<div class='clearfix'></div>{summary}{pager}",
                'itemView' => function ($model, $key, $index, $widget) {
                    return $this->render('_item', ['model' => $model]);
                },
            ]);
            ?>
        </ul>
    </div>
    <!--END LIST POST-->
    
    <!--REPORT SUMARY-->
    <div class="col-sm-3 sticky-top">
        <table class="blog-tbl-report">
            <tr>
                <td class="text-right">Tổng số bài viết</td>
                <td><?= empty($aDataReport['ALL_POST']) ? '' : $aDataReport['ALL_POST'].' '.Blog::UNIT_POST; ?></td>
            </tr>
            <tr>
                <td class="text-right">Bài viết hôm nay</td>
                <td><?= empty($aDataReport['TODAY_POST']) ? '' : $aDataReport['TODAY_POST'].' '.Blog::UNIT_POST; ?></td>
            </tr>
            <?php foreach (Blog::$aType as $key => $name) : ?>
                <?php 
                if( empty($aDataReport['POST_BY_TYPE'][$key]) ) continue;
                $count = $aDataReport['POST_BY_TYPE'][$key].' '.Blog::UNIT_POST;
                $urlFilter = Url::to(['/site/blog', 'type'=>$key]);
                ?>
                <tr>
                    <td class="text-right">
                        <a href="<?= $urlFilter ?>"><?= $name ?></a>
                    </td>
                    <td><?= $count ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <!--END REPORT SUMARY-->
</div>
