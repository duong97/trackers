<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='container-fluid'>
    <div class='row'>
        <div class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4 text-center'>
            <h1><?= Html::encode($this->title) ?></h1>
            <p><?= Yii::t('app', 'Create new account with your email') ?></p>
            <hr class="hr-style">
        </div>
        <div class='col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4'>
            <?php $form = ActiveForm::begin([
                    'id' => 'register-form',
                    'fieldConfig' => [
//                        'options' => ['class' => 'input-group']
                        'template' => "<div class='input-group'>{label}{input}</div>{error}",
                    ],
                ]); ?>

                <?= $form->field($model, 'first_name')
                        ->textInput([
                            'autofocus' => true,
                            'placeholder' => $model->attributeLabels()['first_name']
                        ])
                        ->label('<i class="fas fa-user"></i>', [
                            'class' => 'input-group-addon',
                        ])
                ?>
                <?= $form->field($model, 'last_name') 
                        ->textInput([
                            'autofocus' => true,
                            'placeholder' => $model->attributeLabels()['last_name']
                        ])
                        ->label('<i class="fas fa-user"></i>', [
                            'class' => 'input-group-addon',
                        ])
                ?>
                <?= $form->field($model, 'email')->textInput([
                            'autofocus' => true,
                            'placeholder' => $model->attributeLabels()['email']
                        ])
                        ->label('<i class="fas fa-envelope"></i>', [
                            'class' => 'input-group-addon',
                        ])
                ?>
                <?= $form->field($model, 'password')
                        ->passwordInput([
                            'autofocus' => true,
                            'placeholder' => $model->attributeLabels()['password']
                        ])
                        ->label('<i class="fas fa-unlock"></i>', [
                            'class' => 'input-group-addon',
                        ])
                ?>
                <?= $form->field($model, 'cpassword')
                        ->passwordInput([
                            'autofocus' => true,
                            'placeholder' => Yii::t('app', 'Confirm Password')
                        ])
                        ->label('<i class="fas fa-unlock"></i>', [
                            'class' => 'input-group-addon',
                        ])
                ?>

                <div class='form-group'>
                    <?= Html::submitButton(Yii::t('app', 'Register'), ['class' => 'btn btn-primary btn-block', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
