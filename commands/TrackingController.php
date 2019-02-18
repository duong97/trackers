<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Products;
use app\models\PriceLogs;
use app\models\GetData;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TrackingController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     *  to run: /usr/bin/php /var/www/html/trackers/yii hello
     */
    public function actionIndex()
    {
        echo "Yes, cron service is running.\n";
        return ExitCode::OK;
    }
    
    /*
     * /usr/bin/php /var/www/html/trackers/yii tracking/track-hourly
     */
    public function actionTrackHourly()
    {
        try {
            $allProducts  = Products::find()->all();
            $plog         = new PriceLogs();
            $allLogs      = $plog->getAll();
            foreach ($allProducts as $p) {
                $aData          = GetData::instance()->getInfo($p->url);
                if(!empty($aData['price'])){
                    // If prices are change -> save to PriceLogs
                    if($aData['price'] != end($allLogs[$p->id])->price){
                        $pLog               = new PriceLogs();
                        $pLog->product_id   = $p->id;
                        $pLog->price        = $aData['price'];
                        $pLog->save();
                    }
                }
            }
            return ExitCode::OK;
        } catch (Exception $exc) {
            
        }
    }
}
