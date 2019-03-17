<?php 
use yii\helpers\Html;
?>
    <p><b>step 1</b></p>
    <?= Html::input('text', 'string', Yii::$app->request->post('string'), ['class' => 'form-control']) ?>
    