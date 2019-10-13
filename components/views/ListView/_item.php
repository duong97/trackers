<?php 
use app\helpers\MyFormat;
use yii\helpers\Url;
use app\models\UserTracking;
?>

<?php 
$mUserTracking              = new UserTracking();
$mUserTracking->product_id  = $model->id;
$isTracked                  = $mUserTracking->isTracked();
$url                        = Url::to(['/product/action/detail', 'url' => $model->url]);
?>
<div class="prd-cover">
    <div class="prd-container">
        <?php if($isTracked): ?>
            <span class="label label-success tracking-label"><?= Yii::t('app', 'Tracking') ?></span>
        <?php endif; ?>
        <div class="prd-image">
            <a href="<?= $url ?>">
                <img src="<?= $model->image ?>" alt="<?= $model->name ?>">
            </a>
        </div>
        <div class="prd-info">
            <a href="<?= $url ?>" title="<?= $model->name ?>" class="prd-info-name">
                <?php // echo MyFormat::shortenName($model->name) ?>
                <div class="shorten"><?php echo $model->name; ?></div>
            </a>
            <p class="prd-info-price"><?= MyFormat::formatCurrency($model->price) ?></p>
        </div>
        <hr style="margin: 0 0 10px 0">
        <div class="prd-info">
            <?= $model->getSeller() ?>
            <?php if( !empty($model->numberTracking) ): ?>
            <i class="label label-info" title="<?= Yii::t('app', 'Number of people tracking this product') ?>">
                <i class="fas fa-eye"></i>
                <?= $model->numberTracking ?>
            </i>
            <?php endif; ?>
        </div>
    </div>
</div>