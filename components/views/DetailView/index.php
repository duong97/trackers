
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-4">
            <?php 
            $imgUrl = isset($aData['image'][0]['normal']) ? $aData['image'][0]['normal'] : ""; 
            ?>
            <img src="<?= $imgUrl ?>" style="width: 100%">
        </div>
        <div class="col-sm-6">
            <h3><?= $aData['name'] ?></h3>
            <p><?= $aData['price'] ?></p>
        </div>
        <div class="col-sm-1"></div>
    </div>
</div>