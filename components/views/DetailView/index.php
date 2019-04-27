<?php 
use app\helpers\MyFormat;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\UserTracking;

$this->title = $aData['name'];
$this->params['breadcrumbs'][] = $this->title;

$urlManager     = \Yii::$app->getUrlManager();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-4">
            <?php 
            $productUrl = isset($_GET['url']) ? $_GET['url'] : "";
            $imgUrl     = isset($aData['image']) ? $aData['image'] : ""; 
            ?>
            <img src="<?= $imgUrl ?>" style="width: 100%">
        </div>
        <div class="col-sm-6">
            <h3><?= $aData['name'] ?></h3>
            <p class="price"><?= MyFormat::formatCurrency($aData['price']) ?></p>
            <?php 
            $isTracked          = false;
            if(isset($aData['id'])){
                $mUserTracking  = new UserTracking();
                $mUserTracking->product_id = $aData['id'];
                $isTracked      = $mUserTracking->isTracked();
            }
            
            ?>
            <?php if(!$isTracked){ ?>
                <?php 
                $action         = Yii::$app->user->isGuest ?
                                    Url::to(['/site/login']) :
                                    Url::to(['/user/default/start-tracking']);
                $form = ActiveForm::begin([
                    'id'        => 'product-form',
                    'layout'    => 'horizontal',
                    'action'    => $action,
                    'options'   => ['class' => 'form-horizontal'],
                ]) ?>
                    
                    <?php $model = new UserTracking(); ?>
                    <?= $form->field($model, 'end_date')->dropDownList(UserTracking::aTrackingTime()) ?>
            
                    <input type="hidden" name="Products[name]" value="<?= $aData['name'] ?>">
                    <input type="hidden" name="Products[url]" value="<?= $productUrl ?>">
                    <input type="hidden" name="Products[price]" value="<?= $aData['price'] ?>">
                    <input type="hidden" name="Products[image]" value="<?= $imgUrl ?>">

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Start tracking'), ['class' => 'btn btn-primary', Yii::$app->user->isGuest ? 'disabled' : null]) ?>
                        <?php if(Yii::$app->user->isGuest): ?>
                            <?php 
                                $urlLogin = Url::to(['/site/login']);
                            ?>
                            <p><?= "* " . Yii::t('app', 'You need to ') . Html::a(Yii::t('app', 'login'), $urlLogin) . Yii::t('app', ' to track product prices') . "!" ?></p>
                        <?php endif; ?>
                    </div>
                <?php ActiveForm::end() ?>
            <?php } else { ?>
                <?php 
                $userTracking   = new UserTracking();
                $aTrackingItems = $userTracking->getUserTrackingItems();
                $startDate      = isset($aTrackingItems[$aData['id']]) ? $aTrackingItems[$aData['id']]->start_date : "";
                $endDate        = isset($aTrackingItems[$aData['id']]) ? $aTrackingItems[$aData['id']]->end_date : "";
                ?>
                <p class="label label-success">
                    <?= Yii::t('app', 'Products are being tracked') ."!" ?>
                </p><br>
                <p class="label label-default">
                    <?= Yii::t('app', 'Start date') . ": " . MyFormat::formatDatetime($startDate) ?>
                </p><br>
                <p class="label label-default">
                    <?= Yii::t('app', 'End date') . ": " . (empty($endDate) ? Yii::t('app', 'Until canceled') : MyFormat::formatDate($endDate)) ?>
                </p><br><br>
                
                    <?php $url = Url::to(['/user/default/stop-tracking', 'id' => $aData['id']]); ?>
                    <?= Html::a(Yii::t('app', 'Stop tracking'), $url, ['class' => 'btn btn-danger']) ?>
                <?php 
//                $form = ActiveForm::begin([
//                    'id'        => 'product-form',
//                    'layout'    => 'horizontal',
//                    'action'    => $urlManager->createUrl(['product/action/stop-tracking']),
//                ]) ?>
                
<!--                <input type="hidden" name="Products[id]" value="//<?= $aData['id'] ?>">
                <button class="btn btn-danger" type="submit">
                    <?= Yii::t('app', 'Stop tracking') ?>
                </button>-->
                <?php // ActiveForm::end() ?>
            <?php } ?>
            <?= Html::a(Yii::t('app', 'Go to shop'), $productUrl, ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
        </div>
        <div class="col-sm-1"></div>
    </div>
    
    <?php if(!empty($aPriceLog)): ?>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-7">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><?= Yii::t('app', 'Price') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Time') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $hPrice = reset($aPriceLog); $lPrice = reset($aPriceLog); ?>
                    <?php foreach ($aPriceLog as $log): ?>
                    <tr class="text-center">
                            <?php 
                            $hPrice = ($log->price > $hPrice->price) ? $log : $hPrice;
                            $lPrice = ($log->price < $lPrice->price) ? $log : $lPrice;
                            ?>
                        <td><?= $no++; ?></td>
                        <td><?= MyFormat::formatCurrency($log->price) ?></td>
                        <td><?= MyFormat::formatDatetime($log->updated_date) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-3">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><?= Yii::t('app', 'Price') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Time') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= Yii::t('app', 'Current price') ?></td>
                        <td><?= MyFormat::formatCurrency($aData['price']) ?></td>
                        <td><?= date('d/m/Y h:i') ?></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Highest price') ?></td>
                        <td><?= MyFormat::formatCurrency($hPrice->price) ?></td>
                        <td><?= MyFormat::formatDatetime($hPrice->updated_date) ?></td>
                    </tr>
                    <tr>
                        <td><?= Yii::t('app', 'Lowest price') ?></td>
                        <td><?= MyFormat::formatCurrency($lPrice->price) ?></td>
                        <td><?= MyFormat::formatDatetime($lPrice->updated_date) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-1"></div>
    </div>
    <?php count($aPriceLog)>1 ? include('chart.php') : "" ?>
    <?php endif; ?>
</div>