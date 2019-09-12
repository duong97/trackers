<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Menus;

/* @var $this yii\web\View */
/* @var $model app\models\Menus */
/* @var $form yii\widgets\ActiveForm */
$mMenu = new Menus();
$mMenu->withOrder = 1;
$aMenu = $mMenu->getArrayMenuAdmin();
?>

<div class="menus-form container">
    <div class="col-sm-5 col-sm-offset-1">
        <?php $form = ActiveForm::begin([
            'id' => 'form-create'
            ]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder'=>'admin/menus/create']) ?>

        <?= $form->field($model, 'display_order')->textInput(['type'=>'number']) ?>

        <?= $form->field($model, 'is_show')->dropdownList(Menus::$aShow) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-sm-5">
        <div class="panel panel-primary">
            <div class="panel-heading">Danh sách menu</div>
            <div class="panel-body">
            <?php foreach ($aMenu as $value): ?>
                <p><?php echo $value['label']; ?></p>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
