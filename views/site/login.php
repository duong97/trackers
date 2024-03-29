<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

    
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
                        <?= Html::a(Yii::t('app', 'Forgot password?'), ['/site/forgot-password'], ['class' => 'btn btn-default btn-block', 'name' => 'forgot-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        
        <!--Login use social media-->
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="login-social-container">
                
                <?php $authAuthChoice = AuthChoice::begin([
                    'baseAuthUrl' => ['site/auth'],
                    'options' => [
                        'class'=>'social-container'
                    ]
                ]); ?>
                    <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <?php 
                    $client->validateAuthState = false;
                    ?>
                        <?php $text = $client->getName() == 'google' ? Yii::t('app', 'Login with Google') : $text = Yii::t('app', 'Login with Facebook'); ?>
                        <?= $authAuthChoice->clientLink($client, '<i class="fab fa-'.$client->getName().'"></i> '.$text, ['class'=>$client->getName().' login-social']) ?>
                    <?php endforeach; ?>
                <?php AuthChoice::end(); ?>
            </div>
        </div>
    </div>
</div>
            
<script>
//    $(function(){
//        $('.facebook.auth-link').append('<span class="txt">Sign in with facebook</span>');
//        $('.google.auth-link').append('<span class="txt">Sign in with google</span>');
//    });
</script>