<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Privileges */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="privileges-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create'
	]); ?>

    <?= $form->field($model, 'module')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'controller')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'relate_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
