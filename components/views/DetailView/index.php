<?php 
use app\helpers\MyFormat;
use yii\helpers\Html;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-4">
            <?php 
            $imgUrl = isset($aData['image'][0]['normal']) ? $aData['image'][0]['normal'] : ""; 
            ?>
            <img src="<?= $imgUrl ?>" style="width: 100%">
        </div>
        <div class="col-sm-6">
            <h3><?= $aData['name'] ?></h3>
            <p class="price"><?= MyFormat::formatCurrency($aData['price']) ?></p>
            <?= empty($aPriceLog) ? Html::a(
                    Yii::t('app', 'Start tracking'),
                    [
                        'action/start-tracking',
                        'url' => isset($_GET['url']) ? $_GET['url'] : "",
                        'price' => $aData['price']
                    ],
                    ['class'=>'btn btn-primary']) : "" ?>
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
    <?php endif; ?>
</div>