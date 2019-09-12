<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Controllers;

/* @var $this yii\web\View */
/* @var $model app\models\Controllers */
/* @var $form yii\widgets\ActiveForm */
$aAction = empty($model->actions) ? [] : json_decode($model->actions, true);
$order   = 1;
?>

<div class="controllers-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create',
        'enableAjaxValidation' => true
    ]); ?>

    <?= $form->field($model, 'controller_name')->textInput() ?>
    
    <?= $form->field($model, 'display_name')->textInput() ?>

    <?= $form->field($model, 'module_name')->dropDownList(Controllers::$aModule) ?>

    <?php // echo $form->field($model, 'actions')->textarea(['rows' => 3]) ?>
    <?= Html::activeLabel($model, 'actions') ?>
    <div class="list-action">
        <table class="table table-striped" style="width: 100%">
            <thead>
                <th>STT</th>
                <th>Tên</th>
                <th>Diễn giải</th>
            </thead>
            <tbody>
                <?php foreach ($aAction as $data): ?>
                <tr>
                    <td><?php echo $order++; ?></td>
                    <td><?php echo $data['key']; ?></td>
                    <td>
                        <input type='text' name='Controllers[actions][<?php echo $data['key']; ?>]' value='<?php echo $data['value']; ?>' class='form-control'>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

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
                $('.list-action').html(data);
            },
            error:function(data){
                alert("Error occured!"); //===Show Error Message====
            }
        });     
    }
</script>