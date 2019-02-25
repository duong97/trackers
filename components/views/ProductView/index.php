<?php 
use app\helpers\MyFormat;
?>

<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <div class="row">
        <?php if(isset($aData)): ?>
            <?php foreach ($aData as $p) : ?>
                <div class="prd-container col-md-2 col-sm-3 col-xs-4">
                    <div class="prd-image">
                        <a href="<?= $p->url ?>">
                            <img src="<?= $p->image ?>" alt="<?= $p->image ?>">
                        </a>
                    </div>
                    <div class="prd-info">
                        <a href="<?= $p->url ?>" title="<?= $p->name ?>">
                            <?= MyFormat::shortenName($p->name) ?>
                        </a>
                        <p><?= MyFormat::formatCurrency($p->price) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-1"></div>
</div>