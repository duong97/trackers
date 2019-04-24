<?php

use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tool for root admin';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
$mUsers = new Users();
$aUserNotify = $mUsers->getListUserByNotifyType();
?>

<div class="alert alert-danger fixed-error-notice" id='error-notice' style="display: none;">
    
</div>

<div class="container">
    <?php foreach ($aUserNotify as $notifyType => $aUser) : ?>
        <div class="root-notification-form">
            <?php $form = ActiveForm::begin([
                'id' => 'form-notification'.$notifyType,
                'action' => Url::to(['/admin/root-admin/demo-notify','notifyType'=>$notifyType])
                ]); ?>
            <?php $notifyText = isset(Users::$aNotifyPlatformType[$notifyType]) ? Users::$aNotifyPlatformType[$notifyType] : ''; ?>
            <fieldset style="">
                <legend style=""><?= $notifyText ?></legend>
                
                <?php foreach ($aUser as $value) : ?>
                <div>
                        <label class="r-user-item cbcontainer"><?= $value->first_name ?>
                            <input type="checkbox" name="Users[id][]" value="<?= $value->id ?>">
                            <span class="checkmark"></span>
                        </label>
                        <div class="r-user-item"><b>Email: </b><?= $value->email ?></div>
                        <?php if($notifyType == Users::NOTIFY_ZALO): ?>
                            <div class="r-user-item"><b>Zalo ID: </b><?= $value->zalo_id ?></div>
                        <?php endif; ?>
                        <?php if( !empty($value->subscription) && $notifyType == Users::NOTIFY_BROWSER ): ?>
                            <div>
                                <b>Subscription: 
                                </b>
                                    <span data-toggle="collapse" 
                                         class='btn btn-xs btn-success'
                                         data-target="#toggleSub<?= $notifyType.$value->id ?>">
                                        Toggle Subscription
                                    </span>
                                <div id="toggleSub<?= $notifyType.$value->id ?>" class="collapse">
                                    <pre><?php print_r(json_decode($value->subscription, true)); ?></pre>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="form-group" style="margin: 15px 0;">
                    <?= Html::submitButton('Demo '.$notifyText, ['class' => 'btn btn-primary']) ?>
                </div>
            </fieldset>
            <?php ActiveForm::end(); ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
    $(function(){
        bindCheckBeforeSubmit();
    });
    function bindCheckBeforeSubmit(){
        $("form[id^='form-notification']").on('submit', function(e) {
            var data = $(this).serializeArray();
            var isOk = false;
            data.forEach(function(item){
                if(item.name == "Users[id][]"){
                    isOk = true;
                    console.log('cc');
                }
            });
            if(!isOk){
                console.log(1);
                $('#error-notice').html('Vui lòng chọn User');
//                $('#error-notice').removeClass('hide');
                $('#error-notice').show();
                setTimeout(function() {
                    $("#error-notice").hide('slow');
                }, 3000);
                e.preventDefault();
                return false;
            }
        });
    }
</script>