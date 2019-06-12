<?php 
/*
 * Chart using Chart JS
 */
use app\helpers\MyFormat;
?>
<div class="table-responsive">
    <canvas id="priceChart" ></canvas>
</div>
<?php 
$aLabelDate     = [];
$aLabelTime     = [];
$aLabel         = [];
$aCPrice        = [];
$aPPrice        = [];
foreach ($aPriceLog as $log) {
    $fDate      = date(MyFormat::date_format, strtotime($log->updated_date));
    $fTime      = date("H:i", strtotime($log->updated_date));
    $aLabel[]   = [$fDate, $fTime];
    $aCPrice[]  = $aData['price'];
    $aPPrice[]  = $log->price;
}
$jsonLabelDate  = json_encode($aLabelDate);
$jsonLabelTime  = json_encode($aLabelTime);
$jsonLabel      = json_encode($aLabel);
$jsonCPrice     = json_encode($aCPrice);
$jsonPPrice     = json_encode($aPPrice);
?>
<script>
var ctx = document.getElementById("priceChart").getContext('2d');
ctx.canvas.style.minWidth   = '100%';
ctx.canvas.style.width      = 'auto';
ctx.canvas.style.height     = '300px';
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= $jsonLabel ?>,
        datasets: [
            {
                label: '<?= Yii::t('app', 'Current price') ?>',
                data: <?= $jsonCPrice ?>,
                backgroundColor: 'rgb(69, 144, 52)',
                borderColor: 'rgb(69, 144, 52)',
                fill: false
            },
            {
                label: '<?= Yii::t('app', 'Previous price') ?>',
                data: <?= $jsonPPrice ?>,
                backgroundColor: 'rgba(251, 229, 170, 1)',
                pointBorderColor: 'rgb(241, 182, 16)',
                borderColor: 'rgba(249, 215, 122, 1)',
                fill: 0
            }
        ]
    },
    options: {
        responsive: false,
        maintainAspectRatio: false,
//        tooltips: {
//            enabled: false
//        },
        animation: {
            onComplete: function () {
                var chartInstance = this.chart,
                ctx = chartInstance.ctx;
                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'left';
                ctx.textBaseline = 'bottom';
                this.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.controller.getDatasetMeta(i);
                    meta.data.forEach(function (bar, index) {
                        var data = dataset.data[index];
                        data = data.substring(0, data.length-3) + "k";
                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                    });
                });
            }
        },
        layout: {
            padding: {
                top: 20
            }
        },
        legend: {
            display: true,
            position: 'bottom',
            labels: {
                fontColor: 'rgb(255, 99, 132)'
            }
        }
//        maintainAspectRatio: false,
//        scales: {
//            yAxes: [{
//                ticks: {
//                    beginAtZero:true
//                }
//            }]
//        }
    },
});
</script>