<?php 
use app\helpers\MyFormat;
use app\models\UserTracking;
use app\models\SupportedWebsites;
use yii\widgets\ActiveForm;
?>

<!--<div class="container" style="padding: 0 20px;">
    <?php $form = ActiveForm::begin([
        'id' => 'form-filter-product',
        'method' =>'get',
        'options' => [
            'class' => 'form-inline'
        ]
        ]); ?>
        <div class="row">
            <div class="col-md-9 col-sm-12" style="border-right: 2px groove #9a9a9a;">
                <?php 
                $mSW = new SupportedWebsites();
                $aSW = $mSW->getAll();
                foreach ($aSW as $item) :
                ?>
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <label class="pure-material-checkbox">
                            <input type="checkbox" name="Products[seller_id][]" value="<?= $item->id ?>">
                            <span><?= $item->name ?></span>
                        </label>
                    </div>
                <?php 
                endforeach;
                ?>
            </div>
            <div class="col-md-3 col-sm-12 text-center">
                <div class="form-group">
                    <label for="sort"><?= Yii::t('app', 'Sort') ?> </label>
                    <select class="form-control" id="sort" name="Products[sort_by]">
                        <option><?= Yii::t('app', 'Price increases') ?></option>
                        <option><?= Yii::t('app', 'Price decreases') ?></option>
                    </select>
                </div>
            </div>
        </div>
    
        <div class="text-right">
            <button type="submit" class="btn btn-primary"><?= Yii::t('app', 'Filters') ?></button>
        </div>
    <?php ActiveForm::end(); ?>
</div>-->

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
                            /
                            <?= $p->getSeller() ?>
                            <?php if( !empty($p->numberTracking) ): ?>
                            /
                            <i class="label label-info" title="<?= Yii::t('app', 'Number of people tracking this product') ?>">
                                <i class="fas fa-eye"></i>
                                <?= $p->numberTracking ?>
                            </i>
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