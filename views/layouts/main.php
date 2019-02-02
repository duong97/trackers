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
use yii\helpers\Url;
use yii\widgets\ActiveForm;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Constants::website_name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
            'style' => 'border-color:#222;'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => Yii::t('app', 'Home'), 'url' => Yii::$app->homeUrl],
//            ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
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
            [
                'label' => '<span class="glyphicon glyphicon-globe"></span>',
                'items' => [
                     '<li class="dropdown-header">'. Yii::t('app', 'Select language') .'</li>',
                     '<li class="divider"></li>',
//                     ['label' => 'English', 'url' => Url::toRoute(["site/change-language", 'lang'=>'en'],true)],
//                     ['label' => 'Vietnamese', 'url' => Url::toRoute(["site/change-language", 'lang'=>'vi'], true)],
                     ['label' => 'English', 'url' => Url::base() . "/site/change-language?lang=en"],
                     ['label' => 'Vietnamese', 'url' => Url::base() . "/site/change-language?lang=vi"],
                ],
            ],
//            [
//                'label' => '<div class="input-group"><input type="text" class="form-control" placeholder="Search" id="txtSearch"/><div class="input-group-btn"><button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span></button></div></div>',
//            ],
        ],
    ]);
    NavBar::end();
    ?>
    
    
    <!--Search bar-->
    <?php 
    $form = ActiveForm::begin([
        'id' => 'search-form',
        'options' => ['class' => 'form-horizontal'],
        'method' => 'get',
        'action' => Url::base(true) . '/user/default'
    ]) ?>
        <div style="position: fixed; top: 50px; left:0;width: 100%;padding: 5px;background: #222;z-index: 1;">
            <div class="col-sm-3"></div>
            <div class="input-group col-sm-6">
                <input type="text" name="search-value" class="form-control" placeholder="<?= Yii::t('app', 'Search') ?>" id="txtSearch" value="<?= isset($_GET['search-value']) ? $_GET['search-value'] : '' ?>"/>
                <div class="input-group-btn">
                    <button class="btn btn-primary" type="submit" style="color: white; background: #279033; border-color: #279033;">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </div>
            </div>
            <div class="col-sm-3"></div>
        </div>
    <?php ActiveForm::end() ?>
    
    <div class="container" style="margin-top: 25px;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Constants::website_name." ".date('Y') ?></p>

        <!--<p class="pull-right"><?= Yii::powered() ?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
