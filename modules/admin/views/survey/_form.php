<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Survey;
use app\helpers\Constants;

/* @var $this yii\web\View */
/* @var $model app\models\Survey */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="survey-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create'
	]); ?>

    <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'type')->dropdownList(Survey::$aType) ?>

    <?= $form->field($model, 'status')->dropdownList(Constants::$aStatus) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
