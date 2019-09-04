<?php

use app\models\Settings;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'System settings';
$this->params['breadcrumbs'][] = $this->title;
$aTabType = Settings::$aType;
$i = 1;
?>

<div class="container">
    <h2>System settings</h2>

    <ul class="nav nav-tabs" id="myTab">
        <?php foreach ($aTabType as $typeID => $name): ?>
        <li class="<?= $typeID== Settings::TYPE_GENERAL ? 'active' : ''; ?>">
            <a data-toggle="tab" href="#tab-menu-<?= $typeID ?>"><?= $name ?></a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="tab-content">
        <div id="tab-menu-<?= Settings::TYPE_GENERAL ?>" class="tab-pane fade in active">
            <h3><?= $aTabType[Settings::TYPE_GENERAL]; ?></h3>
            <div class="form">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'form-create'
                ]);
                ?>
                <?= $form->field($model, 'type')->hiddenInput(['value'=>Settings::TYPE_GENERAL])->label(false); ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">Người dùng</div>
                            <div class="panel-body">
                                <?= $form->field($model, 'avgDaysRecommend')->textInput(['type'=>'number']) ?>
                                <?= $form->field($model, 'systemMaintenance')->dropdownList(['0'=>'Không', '1'=>'Có']) ?>
                                <?= $form->field($model, 'orderPriceRecommend')->dropdownList(array_combine(range(1,3), range(1,3))) ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div id="tab-menu-<?= Settings::TYPE_SYSTEM ?>" class="tab-pane fade">
            <h3><?= $aTabType[Settings::TYPE_SYSTEM]; ?></h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        </div>
    </div>
</div>
