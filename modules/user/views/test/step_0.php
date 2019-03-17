<?php 
use yii\widgets\Pjax;
use yii\helpers\Html;
?>

<div class="col-sm-4"
     style="position: relative;">
    <?php Pjax::begin(); ?>
        <?= Html::beginForm(
                ['test/step', 'step' => $step], 
                'post', 
                ['id'=>'hihi','data-pjax' => '', 'class' => '']
            ); ?>

        <?php include($view . '.php') ?>
        <?= Html::submitButton('Next', [
            'class' => 'btn btn-primary', 
            'name' => 'submit1', 
            'value' =>'next',
            'style' => 'position:absolute; right:0; top:10px;'
        ]) ?>
        <?= Html::endForm() ?>

        <!--Prev-->
        <?= Html::beginForm(
                ['test/step', 'step' => $step], 
                'post', 
                ['id'=>'hihi','data-pjax' => '', 'class' => 'form-inline']
            ); ?>
        <?= Html::hiddenInput('action', 'prev') ?>
        <?= Html::submitButton('Prev', [
            'class' => 'btn btn-primary', 
            'name' => 'submit2', 
            'value' =>'prev',
            'style' => 'position:absolute; right:0; top:50px;'
        ]) ?>
        <?= Html::endForm() ?>
    <?php Pjax::end(); ?>
</div>

