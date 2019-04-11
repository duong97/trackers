<?php 
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
$data = ['Duong', 'hihi', 'do ngoc', 'dadalkadsf'];
?>
<!--Search bar-->
<div class="">
    <?php 
    $form = ActiveForm::begin([
        'id'        => 'search-form',
        'options'   => ['class' => 'form-horizontal'],
        'method'    => 'get',
        'action'    => ['/product/action/search']
    ]) ?>
        <div class="nav-search-menu">
            <!--<div class="col-sm-3"></div>-->
            <div class="input-group col-sm-8" style="margin: 0 auto;">
<!--                <input type="text" name="search-value" 
                       class="form-control" 
                       placeholder="<?= Yii::t('app', 'Search') ?>" 
                       id="nav-search-product" 
                       value="<?= isset($_GET['search-value']) ? $_GET['search-value'] : '' ?>" 
                       autocomplete="off"/>-->
                        <?= 
                        AutoComplete::widget([
                            'name'      => 'search-value',
                            'id'        => 'nav-search-product',
                            'options'   => [
                                'class'         => 'form-control',
                                'autocomplete'  => 'off',
                                'placeholder'   => Yii::t('app', 'Search'),
                                'value'         => isset($_GET['search-value']) ? $_GET['search-value'] : ''
                            ],
                            'clientOptions' => [
                                'source'    => $data,
                                'appendTo'  => '#atcl-list-container',
                                'autoFill'  => true,
                                'minLength' => '0',
                                'classes'   => [
                                    "ui-autocomplete"=> "atcl-container"
                                ],
                                'position' => [
                                    'my' => 'center top-10%',
                                    'at' => 'center bottom'
                                ],
                                'autoFocus' =>true,
//                                'select'    => ""
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