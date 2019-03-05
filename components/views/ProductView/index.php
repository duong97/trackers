<?php 
use app\helpers\MyFormat;
use app\models\UserTracking;
?>

<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
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
                <div class="prd-container col-md-2 col-sm-3 col-xs-4">
                    <?php if($isTracked): ?>
                        <span class="label label-success tracking-label"><?= Yii::t('app', 'Tracking') ?></span>
                    <?php endif; ?>
                    <div class="prd-image">
                        <a href="<?= $url ?>">
                            <img src="<?= $p->image ?>" alt="<?= $p->image ?>">
                        </a>
                    </div>
                    <div class="prd-info">
                        
                        <a href="<?= $url ?>" title="<?= $p->name ?>">
                            <?= MyFormat::shortenName($p->name) ?>
                        </a>
                        <p><?= MyFormat::formatCurrency($p->price) ?></p>
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
    </div>
    <div class="col-sm-1"></div>
</div>