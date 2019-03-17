<?php 
use yii\helpers\Html;
?>

    <p><b>step 3</b></p>
    <?= Html::input('text', 'string', Yii::$app->request->post('string'), ['class' => 'form-control']) ?>
    <?= Html::input('text', 'string2', Yii::$app->request->post('string2'), ['class' => 'form-control']) ?>
    <?= Html::input('text', 'string2', Yii::$app->request->post('string2'), ['class' => 'form-control']) ?>
    