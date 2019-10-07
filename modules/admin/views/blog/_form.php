<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
        'kcfinder' => true,
//        'preset' => 'custom',
        'kcfOptions' => [
            'uploadURL' => '@web/images/upload',
            'uploadDir' => '@webroot/images/upload',
            'access' => [  // @link http://kcfinder.sunhater.com/install#_access
                'files' => [
                    'upload' => true,
                    'delete' => true,
                    'copy' => true,
                    'move' => true,
                    'rename' => true,
                ],
                'dirs' => [
                    'create' => true,
                    'delete' => true,
                    'rename' => true,
                ],
            ],
            'types' => [  // @link http://kcfinder.sunhater.com/install#_types
                'files' => [
                    'type' => '',
                ],
            ],
        ],
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions'=>[
            'filebrowserUploadUrl' => Url::to(['/admin/blog/upload', 'id'=>$model->id]),
        ]
    ]); ?>

    <?= $form->field($model, 'type')->dropDownList(Blog::$aType) ?>

    <?= $form->field($model, 'status')->dropDownList(Blog::$aStatus) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
