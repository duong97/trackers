<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <?php if(isset($aData)): ?>
            <?php foreach ($aData as $p) : ?>
                <div class="prd-container">
                    <h4><?= $p->name ?></h4>
                    <a href="<?= $p->link ?>">Di den trang mua</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="col-sm-1"></div>
</div>