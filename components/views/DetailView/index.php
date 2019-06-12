<?php 
use app\helpers\MyFormat;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\UserTracking;
use app\models\Products;

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
            $status     = isset($aData['status']) ? $aData['status'] : Products::STT_ACTIVE;
            $cPrice     = ($status == Products::STT_ACTIVE) ? $aData['price'] : Products::$aStatus[$status];
            ?>
            <img src="<?= $imgUrl ?>" style="width: 100%">
        </div>
        <div class="col-sm-6">
            <h3><?= $aData['name'] ?></h3>
            <p class="price"><?= is_numeric($cPrice) ? MyFormat::formatCurrency($cPrice) : $cPrice ?></p>
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
                    <input type="hidden" name="Products[price]" value="<?= is_numeric($cPrice) ? $cPrice : 0 ?>">
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
<!--                <p class="label label-default">
                    <?php // Yii::t('app', 'Start date') . ": " . MyFormat::formatDatetime($startDate) ?>
                </p><br>-->
                <p class="label label-default">
                    <?= Yii::t('app', 'End date') . ": " . (empty($endDate) ? Yii::t('app', 'Until canceled') : MyFormat::formatDate($endDate)) ?>
                </p><br><br>
                
                    <?php $url = Url::to(['/user/default/stop-tracking', 'id' => $aData['id']]); ?>
                    <?= Html::a(Yii::t('app', 'Stop tracking'), $url, ['class' => 'btn btn-danger']) ?>

            <?php } ?>
            <?= Html::a(Yii::t('app', 'Go to shop'), $productUrl, ['class' => 'btn btn-success', 'target'=>'_blank']) ?>
        </div>
        <div class="col-sm-1"></div>
    </div>
    
    <?php if(!empty($aPriceLog)): ?>
        <?php 
        $hPrice = reset($aPriceLog); 
        $lPrice = reset($aPriceLog);
        foreach ($aPriceLog as $log){
            $hPrice = ($log->price > $hPrice->price) ? $log : $hPrice;
            $lPrice = ($log->price < $lPrice->price) ? $log : $lPrice;
        }
        ?>
    <div class="row">
        <div class="col-sm-8">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><?= Yii::t('app', 'Price') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Time') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($aPriceLog as $log): ?>
                        <?php 
                        $class = ($log->price == $hPrice->price) ? 'danger' :
                                (($log->price == $lPrice->price) ? 'success' : 'active');
                        ?>
                        <tr class="text-center <?= $class ?>">
                            <td><?= $no++; ?></td>
                            <td><?= MyFormat::formatCurrency($log->price) ?></td>
                            <td><?= MyFormat::formatDatetime($log->updated_date) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><?= Yii::t('app', 'Price') ?></th>
                        <th class="text-center"><?= Yii::t('app', 'Time') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="active">
                        <td><?= Yii::t('app', 'Current price') ?></td>
                        <td><?= is_numeric($cPrice) ? MyFormat::formatCurrency($cPrice) : ''; ?></td>
                        <td><?= date('d/m/Y h:i') ?></td>
                    </tr>
                    <tr class="danger">
                        <td><?= Yii::t('app', 'Highest price') ?></td>
                        <td><?= MyFormat::formatCurrency($hPrice->price) ?></td>
                        <td><?= MyFormat::formatDatetime($hPrice->updated_date) ?></td>
                    </tr>
                    <tr class="success">
                        <td><?= Yii::t('app', 'Lowest price') ?></td>
                        <td><?= MyFormat::formatCurrency($lPrice->price) ?></td>
                        <td><?= MyFormat::formatDatetime($lPrice->updated_date) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php count($aPriceLog)>1 ? include('chart_highstock.php') : "" ?>
    <?php endif; ?>
</div>