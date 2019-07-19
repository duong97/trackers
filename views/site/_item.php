<?php 
use yii\helpers\Url;
use app\helpers\MyFormat;
use app\helpers\Checks;
use yii\helpers\Html;
?>
<li>
    <div>
        <!--Hình ảnh thumbnail--> 
        <?php if( !empty($model->getThumbnailUrl()) ): ?>
        <a href="<?= Url::to(['/site/blog', 'view'=>$model->getSlug()]) ?>">
            <div class="blog-thumb-container">
                <img src="<?= $model->getThumbnailUrl() ?>">
            </div>
        </a>
        <?php endif; ?>

        <!--Tiêu đề-->
        <a href="<?= Url::to(['/site/blog', 'view'=>$model->getSlug()]) ?>">
            <h3><?= $model->title; ?></h3>
        </a>

        <!--Mô tả-->
        <p class="blog-des">
            <?= MyFormat::shortenName($model->description, MyFormat::description_max_length); ?>
            <?php if(Checks::isAdmin()): ?>
                <?= Html::a('<i class="far fa-edit"></i>', ['/admin/blog/update', 'id'=>$model->id], ['target'=>'_blank']) ?>
            <?php endif; ?>
        </p>

        <!--Thời gian-->
        <p class="blog-time-til-now">
            <?php 
            $urlFilter = Url::to(['/site/blog', 'type'=>$model->type]);
            $linkType = "<a href='$urlFilter'>{$model->getType()}</a>";
            ?>
            <?= MyFormat::timeTilNow($model->created_date) . ' - ' . $linkType; ?>
        </p>
        <div class="clearfix"></div>
    </div>
</li>