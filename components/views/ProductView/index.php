<?php 
use app\helpers\MyFormat;
use app\models\UserTracking;
?>

<div class="dropdown pull-right">
    <div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <i class="fas fa-filter"></i>
        <?= Yii::t('app', 'Filters') ?>
        <span class="caret"></span>
    </div>
    <ul class="dropdown-menu">
        <li><a href="#"><?= Yii::t('app', 'The most tracking') ?></a></li>
        <li><a href="#"><?= Yii::t('app', 'Prices change the most') ?></a></li>
        <li><a href="#"><?= Yii::t('app', 'Prices change the least') ?></a></li>
    </ul>
</div>
<div class="clearfix"></div>

<div class="row">
    <?php if(!empty($aData)){ ?>
        <?php foreach ($aData as $p) : ?>
            <?php 
            $mUserTracking              = new UserTracking();
            $mUserTracking->product_id  = $p->id;
            $isTracked                  = $mUserTracking->isTracked();
            $urlManager = \Yii::$app->getUrlManager();
            $url        = $urlManager->createUrl(['product/action/detail', 'url' => $p->url]);
            ?>
            <div class="prd-cover col-md-2 col-sm-3 col-xs-4">
                <div class="prd-container card">
                    <?php if($isTracked): ?>
                        <span class="label label-success tracking-label"><?= Yii::t('app', 'Tracking') ?></span>
                    <?php endif; ?>
                    <div class="prd-image">
                        <a href="<?= $url ?>">
                            <img src="<?= $p->image ?>" alt="<?= $p->name ?>">
                        </a>
                    </div>
                    <div class="prd-info">

                        <a href="<?= $url ?>" title="<?= $p->name ?>">
                            <?= MyFormat::shortenName($p->name) ?>
                        </a>
                        <p>
                            <b style="color:#419a13;"><?= MyFormat::formatCurrency($p->price) ?></b>
                            <?php if( !empty($p->numberTracking) ): ?>
                            /
                            <i class="label label-info" title="<?= Yii::t('app', 'Number of people tracking this product') ?>">
                                <i class="fas fa-eye"></i>
                                <?= $p->numberTracking ?>
                            </i>
                            /
                            <?= $p->getSeller() ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php } else { ?>
        <?php 
        $searchValue = isset($_GET['search-value']) ? $_GET['search-value'] : "";
        echo Yii::t('app', 'No results for ') .'"'.$searchValue.'"';
        ?>
    <?php } ?>
</div>