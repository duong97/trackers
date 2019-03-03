<?php 
use yii\widgets\ActiveForm;
$url = \Yii::$app->getUrlManager();
?>
<!--Search bar-->
<div class="">
    <?php 
    $form = ActiveForm::begin([
        'id'        => 'search-form',
        'options'   => ['class' => 'form-horizontal'],
        'method'    => 'get',
        'action'    => $url->createUrl(['product/action/search'])
    ]) ?>
        <div class="nav-search-menu">
            <!--<div class="col-sm-3"></div>-->
            <div class="input-group col-sm-8" style="margin: 0 auto;">
                <input type="text" name="search-value" class="form-control" placeholder="<?= Yii::t('app', 'Search') ?>" id="nav-search-product" value="<?= isset($_GET['search-value']) ? $_GET['search-value'] : '' ?>" autocomplete="off"/>
                <div class="input-group-btn">
                    <button class="btn btn-primary" id="search-product-btn" type="submit" style="color: black; background: #e4e4e4; border-color: #d0d0d0;">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </div>
            </div>
            <!--<div class="col-sm-3"></div>-->
        </div>
    <?php ActiveForm::end() ?>
</div>