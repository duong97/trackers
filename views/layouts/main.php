<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\helpers\Constants;
use app\helpers\Htmls;
use app\helpers\Checks;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <!-- top menu -->
    <div class="navbar-fixed-top navbar-default nav-container">
        <?php
        NavBar::begin([
            'brandLabel' => Constants::website_name,
            'brandUrl' => Yii::$app->homeUrl,
            'headerContent' => $this->render('_nav_search'),
            'options' => [
                'class' => 'nav-main-menu',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => [
                ['label' => Yii::t('app', 'Home'), 'url' => Yii::$app->homeUrl],
                ['label' => Yii::t('app', 'The most tracking'), 'url' => ['/product/list/most-tracking']],
                ['label' => Yii::t('app', 'Supported websites'), 'url' => ['/site/supported-websites']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
    //            ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
                Yii::$app->user->isGuest ? (
                    ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']]
                ) : (
                    '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        Yii::t('app', 'Logout').' (' . Yii::$app->user->identity->first_name . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                ),
                Yii::$app->user->isGuest ? (
                    ['label' => Yii::t('app', 'Register'), 'url' => ['/site/register']]
                ) : '',
                Yii::$app->user->isGuest ? '' : ( // If loged in
                    [
                        'label' => '<span class="glyphicon glyphicon-user"></span>',
                        'items' => Htmls::getUserItems()
                    ]
                ),
                [
                    'label' => '<span class="glyphicon glyphicon-globe"></span>',
                    'items' => Htmls::getChangeLanguageItems()
                ],
            ],
        ]);
        NavBar::end();
        ?>
    </div><!-- top menu -->
    
    <div class="main-container">
        <?= Breadcrumbs::widget([
            'homeLink' => [ 
                      'label' => Yii::t('yii', 'Home'),
                      'url' => Yii::$app->homeUrl,
                ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer>
    <div class="footer-info">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-md-3 footer-col">
                    <span><?= Yii::t('app', 'Explode') ?></span>
                    <?= Html::a(Yii::t('app', 'Home'), Yii::$app->homeUrl, ['class' => 'footer-link']) ?>
                    <?= Html::a(Yii::t('app', 'About'), ['/site/about'], ['class' => 'footer-link']) ?>
                </div>
                <div class="col-xs-6 col-md-3 footer-col">
                    <span><?= Yii::t('app', 'Contact') ?></span>
                    <p>Điện Biên Phủ, P.16</p>
                    <p>Q.Bình Thạnh, TP.HCM, Vietnam</p>
                    <p>admin@chartcost.com</p>
                    <p>0935 714 733</p>
                </div>
                <div class="col-xs-6 col-md-3 footer-col">
                    <span><?= Yii::t('app', 'Follow') ?></span>
                    <?= Html::a('Facebook', '#', ['class' => 'footer-link']) ?>
                    <?= Html::a('Instagram', '#', ['class' => 'footer-link']) ?>
                    <?= Html::a('Youtube', '#', ['class' => 'footer-link']) ?>
                </div>
                <div class="col-xs-6 col-md-3 footer-col">
                    <span><?= Yii::t('app', 'Legal') ?></span>
                    <?= Html::a(Yii::t('app', 'Terms'), ['/site/terms'], ['class' => 'footer-link']) ?>
                    <?= Html::a(Yii::t('app', 'Privacy'), ['/site/privacy'], ['class' => 'footer-link']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <p class="ft-copyright">&copy; <?= strtoupper(Constants::website_name)." ".date('Y') . ". ALL RIGHTS RESERVED." ?></p>

        <!--<p class="pull-right"><?= Yii::powered() ?></p>-->
    </div>
</footer>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
