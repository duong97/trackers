<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\helpers\Checks;
use app\models\Products;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create'
	]); ?>
    <div class="row">
        <div class="col-sm-6">
            <?php // echo $form->field($model, 'url_redirect')->textInput() ?>

            <?= $form->field($model, 'category_id')->dropDownList(Products::$aCategory) ?>

            <?= $form->field($model, 'status')->dropDownList(Products::$aStatus) ?>

            <?php if( Checks::isRoot() || $model->scenario == Yii::$app->params['SCENARIO_CREATE']): ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?php // echo $form->field($model, 'url')->textarea(['rows' => 3]) ?>
            <?php // echo $form->field($model, 'price')->textInput() ?>
            <?php // echo $form->field($model, 'image')->textarea(['rows' => 3]) ?>
            <?php endif; ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <div class="col-sm-6">
            <?= Html::img($model->image) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
