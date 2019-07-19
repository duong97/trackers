<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Blog;
use dosamigos\ckeditor\CKEditor;
use app\components\UploadFileWidget;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-create',
        'options' => ['enctype' => 'multipart/form-data']
	]); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
    
    <?= UploadFileWidget::widget(['model'=>$model, 'attribute'=>'thumb']) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
    ]); ?>

    <?= $form->field($model, 'type')->dropDownList(Blog::$aType) ?>

    <?= $form->field($model, 'status')->dropDownList(Blog::$aStatus) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
