<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SupportedWebsites;

/* @var $this yii\web\View */
/* @var $model app\models\SupportedWebsites */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supported-websites-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create',
        'options' => ['enctype' => 'multipart/form-data']
	]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency')->dropDownList(SupportedWebsites::$aCurrency) ?>

    <?= $form->field($model, 'check_time')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(SupportedWebsites::$aStatus) ?>

    <?= $form->field($model, 'icon')->fileInput(['accept' => ".png"]) ?>
    <?php 
    if($model->scenario == Yii::$app->params['SCENARIO_UPDATE']){
        echo Html::img($model->getLogoUrl());
    }
    ?>
    
    <?= $form->field($model, 'homepageLogo')->fileInput(['accept' => ".png"]) ?>
    <?php 
    if($model->scenario == Yii::$app->params['SCENARIO_UPDATE']){
        echo Html::img($model->getHomepageLogoUrl());
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
