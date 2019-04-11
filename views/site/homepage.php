<?php 
use app\helpers\Constants;
use yii\helpers\Html;
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

<!--Slide show-->
<div class="">
    <!--<h2>Carousel Example</h2>-->
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">

            <div class="item active">
                <img src="https://www.w3schools.com/bootstrap/la.jpg" alt="Los Angeles" style="width:100%;">
                <div class="carousel-caption">
                    <h3>Los Angeles</h3>
                    <p>LA is always so much fun!</p>
                </div>
            </div>

            <div class="item">
                <img src="https://www.w3schools.com/bootstrap/chicago.jpg" alt="Chicago" style="width:100%;">
                <div class="carousel-caption">
                    <h3>Chicago</h3>
                    <p>Thank you, Chicago!</p>
                </div>
            </div>

            <div class="item">
                <img src="https://www.w3schools.com/bootstrap/ny.jpg" alt="New York" style="width:100%;">
                <div class="carousel-caption">
                    <h3>New York</h3>
                    <p>We love the Big Apple!</p>
                </div>
            </div>

        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div> <!--End Slide show-->

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
            <?php // echo Html::img("@web/images/support_website/".strtolower($item->name).".png", ['class' => 'img-responsive']) ?>
            <?= Html::img(str_replace('_logo.png', '.png', $item->logo), ['class' => 'img-responsive']) ?>
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