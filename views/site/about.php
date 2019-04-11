<?php
$this->title = Yii::t('app', 'About');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reading">
    <h1><?= $this->title ?></h1>
    <p>
        <a href="<?= Yii::$app->homeUrl ?>">Chartcost.com</a> là một trang công cụ 
        dùng để theo dõi giá của những sản phẩm trên các sàn thương mại điện tử và
        các website, qua đó giúp tiết kiệm chi phí cho người mua hàng.
    </p>
</div>