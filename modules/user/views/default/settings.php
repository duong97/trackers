<?php 
use yii\bootstrap\ActiveForm;
use app\models\Users;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;

    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'id' => 'settings-form',
        'options' => ['class' => 'form-horizontal'],
        'method' => 'post'
    ]) ?>
<div class="row" style="margin-top: 20px;">
    <div class="col-sm-8 col-sm-offset-2">
        
        <ul class="list-group">
            <label><?= Yii::t('app', 'Notification settings') ?></label>
            <li class="list-group-item">
                <label class="switch">
                    <?= Html::activeCheckbox($user, 'is_notify_browser',['label'=>false]) ?>
                    <span class="slider round"></span>
                </label>
                <div>
                    <p><b><?= Yii::t('app', 'Notify via browser') ?></b></p>
                    <p><?= Yii::t('app', 'Receive notifications via browser (Chrome, Firefox,...)') . "." ?></p>
                    <i><?= Yii::t('app', "To receive notifications via your phone's browser, access this page on your phone, then turn it off and on again") . "." ?></i>
                </div>
            </li>
<!--            <li class="list-group-item">
                <label class="switch">
                    <?= Html::activeCheckbox($user, 'is_notify_fb',['label'=>false]) ?>
                    <span class="slider round"></span>
                </label>
                <div>
                    <p><b><?= Yii::t('app', 'Notify via message') ?></b></p>
                    <p><?= Yii::t('app', 'Receive notifications via Facebook Messenger, require login with Facebook') . "." ?></p>
                </div>
            </li>-->
            <li class="list-group-item">
                <label class="switch">
                    <?= Html::activeCheckbox($user, 'is_notify_zalo',['label'=>false, 'disabled'=>!$user->isLinkToZalo()]) ?>
                    <span class="slider round"></span>
                </label>
                <div>
                    <p><b><?= Yii::t('app', 'Notify via Zalo') ?></b></p>
                    <p><?= Yii::t('app', 'Receive notifications via Zalo message, require to link to a Zalo account') . "." ?></p>
                    <?php 
                    $redirect = 'https://chartcost.com/site/zalo-login';
                    $urlZalo  = "https://oauth.zaloapp.com/v3/auth?app_id=".Yii::$app->params['zalo_app_id']."&redirect_uri=$redirect" 
                    ?>
                    <i>
                        <?php if($user->isLinkToZalo()){ ?>
                            <?= Yii::t('app', "You have linked to a zalo account with ID") . " $user->zalo_id" . ", " ?>
                            <?= Html::a(Yii::t('app', 'link again'), $urlZalo) ?>
                        <?php } else { ?>
                            <?= Yii::t('app', "You have not linked any zalo account yet") . ", " ?>
                            <?= Html::a(Yii::t('app', 'link now'), $urlZalo) ?>
                        <?php } ?>
                    </i>
                </div>
            </li>
            <li class="list-group-item">
                <label class="switch">
                    <?= Html::activeCheckbox($user, 'is_notify_email',['label'=>false]) ?>
                    <span class="slider round"></span>
                </label>
                <div>
                    <p><b><?= Yii::t('app', 'Notify via email') ?></b></p>
                    <p><?= Yii::t('app', 'Receive notifications via your login email') ."." ?></p>
                </div>
            </li>
            <li class="list-group-item">
                <?= Html::activeDropDownList($user, 'notify_type', Users::aNotifyType(),['class'=>'form-control']) ?>
                <div>
                    <p><b><?= Yii::t('app', 'When notify') . "?" ?></b></p>
                    <p><?= Yii::t('app', 'When do you want to receive notifications, prices increase, prices decrease or both') . "?" ?></p>
                </div>
            </li>
        </ul>
        
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

<script src="<?= Yii::getAlias('@web').'/js/notifications.js' ?>"></script>
<script>
    var elm         = $('#users-is_notify_browser');
    var urlHandle   = '<?= Url::to(['/user/notification/register']); ?>';
    var urlSwJs     = '<?= Yii::getAlias('@web').'/js/sw.js' ?>';
//    console.log(urlSwJs);
    bindNotifications(elm, 'change', urlHandle, urlSwJs);
</script>