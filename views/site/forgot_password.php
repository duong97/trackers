<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Forgot password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4 text-center'>
            <h1><?= Html::encode($this->title) ?></h1>
            <p><?= Yii::t('app', 'We will send a temporary password to your email, you must change your password immediately for security reasons.') ?></p>
            <hr class="hr-style">
        </div>
        <div class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4'>
            <?php $form = ActiveForm::begin([
                    'id' => 'register-form',
                    'fieldConfig' => [
//                        'options' => ['class' => 'input-group']
//                        'template' => "<div class='input-group'>{label}{input}</div>{error}",
                    ],
                ]); ?>

                <?php echo $form->field($model, 'email')
                                ->textInput([
                                    'autofocus' => true, 
                                    'placeholder' => Yii::t('app', 'Enter your email'),
                                    'class' => 'form-control'
                                ]) ?>

                <div class='form-group' style="margin-top: 10px;">
                    <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary btn-block', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
