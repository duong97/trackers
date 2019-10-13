<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Feedback');
$this->params['breadcrumbs'][] = $this->title;

$aQuestion = $model->getListActive();
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-feedback'
    ]); ?>
<div class="container">
    <div class="col-sm-8 col-sm-offset-2">
        <h1><?= $this->title ?></h1>
        
        <?php foreach ($aQuestion as $mSurvey) : ?>
        <div>
            <h4><b><?php echo $mSurvey->question; ?></b></h4>
            <div>
                <?php echo $mSurvey->renderAnswer(); ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Send feedback'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>