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

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            xfbml            : true,
            version          : 'v3.3'
        });
    };
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<!-- Your customer chat code -->
<div class="fb-customerchat" attribution=setup_tool page_id="1127264994113778" greeting_dialog_display="hide"></div>
<!-- END Load Facebook SDK for JavaScript -->

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
$aCategory              = Products::$aCategory;
$mProduct               = new Products();
$mProduct->category_id  = Products::$aCategoryHomePage;
$aProduct               = $mProduct->getByCategory();
?>
<?php foreach ($aProduct as $category_id => $aPrd): ?>
    <div class="homepage-content">
        <!--<h3 class="medium-title color-bold"><?php // echo Yii::t('app', 'PHONE ACCESSORIES') ?></h3>-->
        <h3 class="container">
            <i class="fas fa-angle-right"></i>
            <?php echo isset($aCategory[$category_id]) ? $aCategory[$category_id] : '' ?>
        </h3>
        <?= \app\components\SliderMultiWidget::widget(['aData'=>$aPrd]) ?>
    </div>
<?php endforeach; ?>

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