<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

    
<script>
//window.fbAsyncInit = function() {
//    // FB JavaScript SDK configuration and setup
//    FB.init({
//      appId      : '813820762285163', // FB App ID
//      cookie     : true,  // enable cookies to allow the server to access the session
//      xfbml      : true,  // parse social plugins on this page
//      version    : 'v2.8' // use graph api version 2.8
//    });
//    
//    // Check whether the user already logged in
//    FB.getLoginStatus(function(response) {
//        if (response.status === 'connected') {
//            //display user data
//            getFbUserData();
//        }
//    });
//};
//
//// Load the JavaScript SDK asynchronously
//(function(d, s, id) {
//    var js, fjs = d.getElementsByTagName(s)[0];
//    if (d.getElementById(id)) return;
//    js = d.createElement(s); js.id = id;
//    js.src = "//connect.facebook.net/en_US/sdk.js";
//    fjs.parentNode.insertBefore(js, fjs);
//}(document, 'script', 'facebook-jssdk'));
//
//// Facebook login with JavaScript SDK
//function fbLogin() {
//    FB.login(function (response) {
//        if (response.authResponse) {
//            // Get and display the user profile data
//            getFbUserData();
//        } else {
//            document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
//        }
//    }, {scope: 'email'});
//}
//
//function fbLogout(){
//    FB.logout(function(response) {
//        console.log(response);
//    });
//    $.post(
//        '<?= Url::to(['site/fb-logout']) ?>', {
//            'oauth_provider':'facebook'
//        }, function(data){ 
//            return true; 
//        }
//    );
//}
//
//// Fetch the user profile data from facebook
//function getFbUserData(){
//    FB.api('/me', {
//        locale: 'en_US', 
//        fields: 'id,first_name,last_name,email,link,gender,locale,picture'
//    },
//    function (response) {
//        document.getElementById('fbLink').setAttribute("onclick","fbLogout()");
//        document.getElementById('fbLink').innerHTML = 'Logout from Facebook';
//        document.getElementById('status').innerHTML = 'Thanks for logging in, ' + response.first_name + '!';
//        document.getElementById('userData').innerHTML = '<p><b>FB ID:</b> '+response.id+'</p><p><b>Name:</b> '+response.first_name+' '+response.last_name+'</p><p><b>Email:</b> '+response.email+'</p><p><b>Gender:</b> '+response.gender+'</p><p><b>Locale:</b> '+response.locale+'</p><p><b>Picture:</b> <img src="'+response.picture.data.url+'"/></p><p><b>FB Profile:</b> <a target="_blank" href="'+response.link+'">click to view profile</a></p>';
//        
//        // Save user data
//    saveUserData(response);
//    });
//}
//
//// Save user data to the database
//function saveUserData(userData){
//    $.post(
        '<?= Url::to(['site/fb-login']) ?>', {
//            oauth_provider:'facebook',
//            userData: JSON.stringify(userData)
//        }, function(data){ 
//            return true; 
//        }
//    );
//}

</script>
<!-- Display login status -->
<!--<div id="status"></div>

 Facebook login or logout button 
<a href="javascript:void(0);" onclick="fbLogin()" id="fbLink">login</a>
<a href="javascript:void(0);" onclick="fbLogout()" id="fbLink">logout</a>

 Display user profile data 
<div id="userData"></div>-->

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 text-center">
            <h1><?= Html::encode($this->title) ?></h1>
            <p><?= Yii::t('app', 'Login with social media or manually') ?></p>
            <hr class="hr-style">
        </div>
        <!--Login using email and password-->
        <div class="col-sm-6 col-md-4 col-md-offset-2 col-lg-3 col-lg-offset-3">
            <div class="site-login">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
//                    'layout' => 'horizontal',
                    'fieldConfig' => [
//                        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
//                        'labelOptions' => ['class' => 'col-lg-1 control-label'],
                    ],
                ]); ?>

                    <?php echo $form->field($model, 'email')
                                    ->textInput([
                                        'autofocus' => true, 
                                        'placeholder' => $model->attributeLabels()['email'],
                                        'class' => 'square form-control'
                                    ])
                                    ->label(false) ?>

                    <?php echo $form->field($model, 'password')
                                    ->passwordInput([
                                        'placeholder' => $model->attributeLabels()['password'],
                                        'class' => 'square form-control'
                                    ])
                                    ->label(false) ?>

                    <?php 
                    echo $form->field($model, 'rememberMe')->checkbox([
//                        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    ]) 
                    ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-success btn-block', 'name' => 'login-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        
        <!--Login use social media-->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="login-social-container">
                <?php
                echo yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['site/auth'],
                    'popupMode' => true,
                ]); 
                ?>
                <a href="<?= Url::to(['site/auth']) ?>" 
                    class="fb login-social facebook auth-link"
                    data-popup-width="860" data-popup-height="480">
                    <i class="fab fa-facebook"></i> <?= Yii::t('app', 'Login with Facebook') ?>
                </a>
                <a href="#" class="google login-social">
                    <i class="fab fa-google"></i>   <?= Yii::t('app', 'Login with Google+') ?>
                </a>
            </div>
        </div>
    </div>
</div>
            
