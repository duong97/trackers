<?php 
/*
 * Chart using HighStock (HighChart JS)
 */
?>
<div class="table-responsive">
    <canvas id="priceChart" ></canvas>
</div>
<?php 
$chartData = [];
foreach ($aPriceLog as $log) {
    $chartData[] = [
        strtotime($log->updated_date)*1000,
        (int)$log->price
    ];
}
// sorting data by time (if not, there will be err(15) in highstock js)
uasort($chartData, function($a, $b){
    return $a[0] > $b[0];
});
$jsData = json_encode(array_values($chartData));
?>
<div id="container"></div>
<script>
    Highcharts.stockChart('container', {
        rangeSelector: {
            selected: 1
        },
        title: {
            text: 'Price Chart'
        },
        tooltip: {
            pointFormat: "Giá: {point.y:,.0f} VND"
        },
        series: [{
            name: 'AAPL',
            data: <?= $jsData ?>,
            tooltip: {
                valueDecimals: 2
            }
        }]
    });

</script>