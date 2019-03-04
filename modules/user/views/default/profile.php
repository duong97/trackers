<?php 
use yii\helpers\Html;
use app\helpers\MyFormat;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row" style="margin-top: 20px;">
    <div class="col-sm-12">
        <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
        ]) ?>
        
            <?php 
            $aLabel = $model->attributeLabels()
            ?>
            
            <div class="form-group field-users-created_date">
                <label class="control-label col-sm-3" for="users-email"><?= $aLabel['email'] ?></label>
                <div class="col-sm-6">
                    <p style="margin-top: 8px;"><?= $model->email ?></p>
                </div>
            </div>
        
            <?= $form->field($model, 'first_name') ?>
            <?= $form->field($model, 'last_name') ?>
            
            <div class="form-group field-users-created_date">
                <label class="control-label col-sm-3" for="users-created_date"><?= $aLabel['last_access'] ?></label>
                <div class="col-sm-6">
                    <p style="margin-top: 8px;"><?= MyFormat::formatDatetime($model->last_access) ?></p>
                </div>
            </div>
        
            <div class="form-group field-users-created_date">
                <label class="control-label col-sm-3" for="users-created_date"><?= $aLabel['created_date'] ?></label>
                <div class="col-sm-6">
                    <p style="margin-top: 8px;"><?= MyFormat::formatDatetime($model->created_date) ?></p>
                </div>
            </div>

            <div style="border: 1px solid black; margin: 20px; padding: 20px;">
                <p class="col-sm-9 col-sm-offset-3"><i>* <?= Yii::t('app', 'Leave blank if you want to keep the old password') ?></i></p>

                <?= $form->field($model, 'newPassword')->passwordInput() ?>

                <?= $form->field($model, 'cnewPassword')->passwordInput() ?>
            </div>
        
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>