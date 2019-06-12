<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath    = '@webroot';
    public $baseUrl     = '@web';
    public $jsOptions   = ['position' => \yii\web\View::POS_HEAD];
    public $css = [
        'css/site.css',
        'css/main.css',
        'css/material.css',
        'css/responsive.css',
        'fontawesome-5.7.2/css/all.css',
        'css/highcharts.css'
    ];
    public $js = [
        'js/main.js',
//        'js/Chart.min.js',
        'js/highstock.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
