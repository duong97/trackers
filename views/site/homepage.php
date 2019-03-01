<?php 
use app\helpers\Constants;
use yii\helpers\Html;

$this->registerCss("
div.main-container{
    margin: 0!important;
    padding: 0;
}
");
?>

<div class="home-background">
    <div class="banner-container">
        <h1><?= 'welcome to ' ?> <span style="font-family: arial;"><?= strtoupper(Constants::website_name) ?></span></h1>
        <p><?= Constants::website_name . Yii::t('app', ' is the place to keep track of the price of a product on e-commerce websites, so you can buy products at the best prices, save costs, and have fun shopping experiences.') ?></p>
        <div class="button-container">
            <?php if(Yii::$app->user->isGuest){ ?>
                <?= Html::a(Yii::t('app', 'Login'), ['/site/login']) ?>
                <?= Html::a(Yii::t('app', 'Register'), ['/site/register']) ?>
            <?php } else { ?>
                <?= Html::a(Yii::t('app', 'GETTING STARTED'), '#', ['class' => 'gt-started-btn']) ?>
            <?php } ?>
        </div>
    </div>
</div>
<div class="list-support-website">
    <h3 class="medium-title color-bold"><?= Yii::t('app', 'SUPPORT WEBSITE') ?></h3>
    <div class="list-support-group">
    <?php foreach (Constants::$aWebsiteName as $key => $wname) { ?>
        <div class="list-support-item">
            <a href="https://<?= Constants::$aWebsiteDomain[$key] ?>" target="_blank">
            <?= Html::img("@web/images/support_website/".strtolower($wname).".png", ['class' => 'img-responsive']) ?>
            </a>
        </div>
    <?php } ?>
    </div>
</div>
<script>
    $(function(){
        var scroll = $(window).scrollTop();
        bindScrollEffect(scroll);
        $(window).scroll(function (event) {
            var scroll = $(window).scrollTop();
            bindScrollEffect(scroll);
        });
        $(document).on('click', '.gt-started-btn', function(){
            $('#nav-search-product').focus();
        });
    });
    
    function transparentNav(){
        $('.nav-container').addClass('nav-transparent');
    }
    
    function defaultNav(){
        $('.nav-container').removeClass('nav-transparent');
    }
    
    function bindScrollEffect(scroll){
        if(scroll <= 0){
            transparentNav();
        } else {
            defaultNav();
        }
    }
</script>