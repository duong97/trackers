<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\helpers\Constants;
use app\models\Controllers;
use app\models\ActionRoles;
?>

<div class="api-default-index">
    <h1>Testing API ...</h1>

    <div class="container col-lg-8 col-lg-offset-2">
        <?php $form = ActiveForm::begin([
            'id' => 'form-testing',
            'method'=>'get'
            ]); ?>
        <?= Html::csrfMetaTags() ?>


        <?= Html::label('Url') ?>
        <?= Html::input('text', 'url', '', ['class'=>'form-control', 'placeholder'=>'/module/controller/action']) ?>

        <?= Html::label('Params') ?>
        <?= Html::textarea('params', '', ['class'=>'form-control', 'placeholder'=>'{"name1":"valu1","name2":"valu2"}']) ?>

        <div class="form-group" style="margin: 10px 0">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="container-fluid">
            <pre>
                <?= print_r($data) ?>
            </pre>
        </div>
    </div>
</div>
