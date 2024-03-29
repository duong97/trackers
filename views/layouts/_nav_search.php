<?php 
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use app\models\UserData;

$mUserData = new UserData();
$session   = \Yii::$app->session;
?>
<!--Search bar-->
<div class="">
    <?php 
    $form = ActiveForm::begin([
        'id'        => 'nav-search-form',
        'options'   => ['class' => 'form-horizontal'],
        'method'    => 'get',
        'action'    => ['/product/action/search']
    ]) ?>
        <div class="nav-search-menu">
            <!--<div class="col-sm-3"></div>-->
            <div class="input-group col-sm-8" style="margin: 0 auto;">
                        <?= 
                        AutoComplete::widget([
                            'name'      => 'search-value',
                            'id'        => 'nav-search-product',
                            'value'     => $session->get('search-nav'),
                            'options'   => [
                                'class'         => 'form-control',
                                'autocomplete'  => 'off',
                                'placeholder'   => Yii::t('app', 'Search'),
                            ],
                            'clientOptions' => [
                                'source'    => $mUserData->getKeywordNav(),
                                'appendTo'  => '#atcl-list-container',
                                'autoFill'  => true,
                                'minLength' => '0',
                                'classes'   => [
                                    "ui-autocomplete"=> "atcl-container"
                                ],
                                'position' => [
                                    'my' => 'center top',
                                    'at' => 'center bottom'
                                ],
//                                'autoFocus' => true,
                                'select'    => new JsExpression("function( event, ui ) {
                                                bindSearchNav();
                                            }")
                                ],
                         ]);
                        ?>
                <div id="atcl-list-container"></div>
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