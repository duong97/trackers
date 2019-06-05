<?php 
use app\helpers\MyFormat;
use app\models\UserTracking;
use yii\helpers\Url;
?>
<div class="slider-out-top">
    <div class="h-slide-thumb col-sm-2">
        <img src="<?= Url::to('@web/images/thumb/phone.jpg'); ?>">
    </div>
    <div class="carousel slide col-sm-10" data-ride="carousel" data-type="multi" data-interval="3000" id="myCarousel">
        <div class="carousel-inner">
            <?php if(!empty($aData)): ?>
                <?php $i = 0; ?>
                <?php foreach ($aData as $p) : ?>
                    <?php 
                    $mUserTracking              = new UserTracking();
                    $mUserTracking->product_id  = $p->id;
                    $isTracked                  = $mUserTracking->isTracked();
                    $url                        = Url::to(['/product/action/detail', 'url' => $p->url]);
                    $i++;
                    ?>
                    <div class="item <?= $i==1 ? 'active' : '' ?>" >
                        <div class="col-md-2 col-sm-6 col-xs-12 slider-product">
                            <a href="<?= $url ?>">
                                <img src="<?= $p->image ?>" alt="<?= $p->name ?>" class="img-responsive">
                            </a>
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
            <?php endif; ?>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
    </div>
    <div class="clearfix"></div>
</div>
