<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\helpers\Constants;
use app\models\Controllers;
use app\models\ActionRoles;

/* @var $this yii\web\View */
/* @var $model app\models\ActionRoles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="action-roles-form">

    <?php $form = ActiveForm::begin([
	'id' => 'form-create'
	]); ?>

    <?php 
    $mController = new Controllers();
    ?>
    
    <?= $form->field($model, 'role_id')->dropDownList(Constants::$aRoleAdmin) ?>

    <?= $form->field($model, 'controller_id')->dropDownList($mController->getAll(true)) ?>

    <?= $form->field($model, 'actions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'can_access')->dropDownList(ActionRoles::$aAccess) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('#actionroles-actions').on('click', function(){
        getAction();
    });
    
    function getAction(){
        var url = "<?= Url::to(['action-roles/create', 'getListAction'=>1]) ?>";
        $.ajax({
            type: "post",
            url: url,
            data: {'controller_name': $('#actionroles-controller_id :selected').text()},
            success: function(data){
                $('#actionroles-actions').val(data);
            },
            error:function(data){
                alert("Error occured!"); //===Show Error Message====
            }
        });     
    }
</script>