<?php 
use yii\helpers\Html;
use app\models\UploadForm;
?>
<div class="form-group">
    <?php echo Html::activeLabel($model, $attribute, ['class' => 'control-label']); ?>
    <?php 
    $attribute = $isMulti ? $attribute.'[]' : $attribute;
    echo Html::activeFileInput($model, $attribute, [
        'accept' => ".". implode(',.', UploadForm::$aImageExtension), //.png,.jpg,.jpeg 
        'multiple' => $isMulti
        ]);
    ?>
</div>