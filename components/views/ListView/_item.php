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
<div class="prd-cover col-md-2 col-sm-3 col-xs-4">
    <div class="prd-container card">
        <?php if($isTracked): ?>
            <span class="label label-success tracking-label"><?= Yii::t('app', 'Tracking') ?></span>
        <?php endif; ?>
        <div class="prd-image">
            <a href="<?= $url ?>">
                <img src="<?= $model->image ?>" alt="<?= $model->name ?>">
            </a>
        </div>
        <div class="prd-info">

            <a href="<?= $url ?>" title="<?= $model->name ?>">
                <?= MyFormat::shortenName($model->name) ?>
            </a>
            <p>
                <b style="color:#419a13;"><?= MyFormat::formatCurrency($model->price) ?></b>
                /
                <?= $model->getSeller() ?>
                <?php if( !empty($model->numberTracking) ): ?>
                /
                <i class="label label-info" title="<?= Yii::t('app', 'Number of people tracking this product') ?>">
                    <i class="fas fa-eye"></i>
                    <?= $model->numberTracking ?>
                </i>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>
    