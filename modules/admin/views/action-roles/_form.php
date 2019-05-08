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
    $mController    = new Controllers();
    $aAllowAction   = explode(',', $model->actions);
    $aCA            = $mController->getAllCA();
    $aController    = $mController->getAll(true);
    $controllerName = isset($aController[$model->controller_id]) ? $aController[$model->controller_id] : '';
    $aAllAction     = isset($aCA[$controllerName]) ? $aCA[$controllerName] : [];
    ?>
    
    <?= $form->field($model, 'role_id')->dropDownList(Constants::$aRoleAdmin) ?>

    <?php if($model->scenario != Yii::$app->params['SCENARIO_UPDATE']): ?>
        <?= $form->field($model, 'controller_id')->dropDownList($mController->getAll(true)) ?>
    <?php endif; ?>

    <?php // $form->field($model, 'actions')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'actions')->hiddenInput() ?>
    <div class="row container actionArea">
        <?php foreach ($aAllAction as $action): ?>
            <?php 
            $isAllow = array_search($action, $aAllowAction);
            $checked = ($isAllow === false) ? '' : 'checked="checked"'; 
            ?>
            <label class="cbcontainer col-sm-3"><?= $action ?>
                <input type="checkbox" name="ActionRoles[actions][]" value="<?= $action ?>" <?= $checked ?>>
                <span class="checkmark"></span>
            </label>
        <?php endforeach; ?>
    </div>

    <?= $form->field($model, 'can_access')->dropDownList(ActionRoles::$aAccess) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
//    $('#actionroles-actions').on('click', function(){
    $('#actionroles-controller_id').on('change', function(){
        getAction();
    });
    
    function getAction(){
        var url = "<?= Url::to(['action-roles/create', 'getListAction'=>1]) ?>";
        $.ajax({
            type: "post",
            url: url,
            data: {'controller_name': $('#actionroles-controller_id :selected').text()},
            success: function(data){
//                $('#actionroles-actions').val(data);
                var listAction = JSON.parse(data);
                var htmlAdd    = '';
                listAction.forEach(function(item){
                    var temp = '<label class="cbcontainer col-sm-3">'+item+
                                    '<input type="checkbox" name="ActionRoles[actions][]" value="'+item+'">'+
                                    '<span class="checkmark"></span>'+
                                '</label>';
                    htmlAdd += temp;
                });
                
                $('.actionArea').html(htmlAdd);
            },
            error:function(data){
                alert("Error occured!"); //===Show Error Message====
            }
        });     
    }
</script>