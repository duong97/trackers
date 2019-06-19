<?php 
use app\helpers\Constants;
use yii\helpers\Html;
use app\models\Products;
use app\models\SupportedWebsites;

$this->registerCss("
div.main-container{
    margin: 0!important;
    padding: 0;
}
div.navbar-default .navbar-brand:hover{
    color: white;
}
");
?>
<?php 
$this->title = Yii::t('app', Constants::website_name);
?>
<!--Home background-->
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
</div> <!--End Home background-->


<?php 
$mProduct = new Products();
$mProduct->category_id = Products::CATEGORY_PHONE;
$aProduct = $mProduct->getByCategory();
?>
<div class="homepage-content">
    <h3 class="medium-title color-bold"><?= Yii::t('app', 'PHONE ACCESSORIES') ?></h3>
    <?= \app\components\SliderMultiWidget::widget(['aData'=>$aProduct]) ?>
</div>

<!--List supported website-->
<div class="list-support-website">
    <h3 class="medium-title color-bold"><?= Yii::t('app', 'SUPPORT WEBSITE') ?></h3>
    <div class="list-support-group">
    <?php 
    $mSpWebsite = new SupportedWebsites();
    $aSite      = $mSpWebsite->getAll();
    ?>
    <?php foreach ($aSite as $key => $item) { ?>
        <div class="list-support-item">
            <a href="<?= $item->url ?>" target="_blank">
            <?= Html::img($item->getHomepageLogoUrl(), ['class' => 'img-responsive']) ?>
            </a>
        </div>
    <?php } ?>
    </div>
</div> <!--End List supported website-->

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