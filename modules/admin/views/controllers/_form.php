<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Controllers;

/* @var $this yii\web\View */
/* @var $model app\models\Controllers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="controllers-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create',
        'enableAjaxValidation' => true
    ]); ?>

    <?= $form->field($model, 'controller_name')->textInput() ?>
    
    <?= $form->field($model, 'display_name')->textInput() ?>

    <?= $form->field($model, 'module_name')->dropDownList(Controllers::$aModule) ?>

    <?= $form->field($model, 'actions')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('#controllers-controller_name').on('blur', function(){
        getAction();
    });
    
    function getAction(){
        var url = "<?= Url::to(['controllers/create', 'getListAction'=>1]) ?>";
        $.ajax({
            type: "post",
            url: url,
            data: $('#form-create').serialize(),
            success: function(data){
                $('#controllers-actions').val(data);
            },
            error:function(data){
                alert("Error occured!"); //===Show Error Message====
            }
        });     
    }
</script>