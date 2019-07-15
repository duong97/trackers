<?php 
use app\helpers\MyFormat;
use yii\helpers\Url;
use app\models\UserTracking;
?>

<?php 
$mUserTracking              = new UserTracking();
$mUserTracking->product_id  = $mProduct->id;
$isTracked                  = $mUserTracking->isTracked();
$url                        = Url::to(['/product/action/detail', 'url' => $mProduct->url]);
?>
<div class="prd-cover col-md-2 col-sm-3 col-xs-4">
    <div class="prd-container card">
        <?php if($isTracked): ?>
            <span class="label label-success tracking-label"><?= Yii::t('app', 'Tracking') ?></span>
        <?php endif; ?>
        <div class="prd-image">
            <a href="<?= $url ?>">
                <img src="<?= $mProduct->image ?>" alt="<?= $mProduct->name ?>">
            </a>
        </div>
        <div class="prd-info">

            <a href="<?= $url ?>" title="<?= $mProduct->name ?>">
                <?= MyFormat::shortenName($mProduct->name) ?>
            </a>
            <p>
                <b style="color:#419a13;"><?= MyFormat::formatCurrency($mProduct->price) ?></b>
                <?php if( !empty($mProduct->numberTracking) ): ?>
                /
                <i class="label label-info" title="<?= Yii::t('app', 'Number of people tracking this product') ?>">
                    <i class="fas fa-eye"></i>
                    <?= $mProduct->numberTracking ?>
                </i>
                /
                <?= $mProduct->getSeller() ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
    