<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\UserTracking;
use app\models\PriceLogs;
use app\models\GetData;
use app\models\Products;
use app\models\Loggers;
use app\models\Mailer;
use app\models\Notifications;

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
     *  to run: /usr/bin/php /var/www/html/trackers/yii tracking
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
            Loggers::WriteLog("Cron start at: ".date('d/m/Y H:i:s'), Loggers::type_cron);
//            echo "Cron start at: ".date('d/m/Y H:i:s')."\n";
            $timeStart = microtime(true);
            $mUserTracking  = new UserTracking();
            $plog           = new PriceLogs();
            $aProductId     = $mUserTracking->getArrayActive();
            $aLastPrice     = $plog->getArrayLastPrice($aProductId);
            $aProducts      = Products::findAll($aProductId);
            $numProduct     = 0;
            $aProductChange = [];
            foreach ($aProducts as $p) {
                $aData      = GetData::instance()->searchNewUrl($p->url);
                if(!empty($aData['price'])){
                    // If prices are change -> save to PriceLogs
                    if($aData['price'] != $aLastPrice[$p->id]){
                        $pLog               = new PriceLogs();
                        $pLog->product_id   = $p->id;
                        $pLog->price        = $aData['price'];
                        $pLog->updated_date = date('Y-m-d H:i:s');
                        $pLog->save();
                        $numProduct++;
                        $aProductChange[]   = $p->id;
//                        echo "Cron price | product_id:$p->id, new price:{$aData['price']}\n";
                        Loggers::WriteLog("Cron price | product_id: $p->id, new price: {$aData['price']}", Loggers::type_cron);
                    }
                }
            }
            // Notify User by email
            $mailer = new Mailer();
            $mailer->notifyPriceChanged($aProductChange);
            
            // Notify User via browser
            $mNotification = new Notifications();
            $mNotification->notifyPriceChanged($aProductChange);
            
            $timeEnd = microtime(true);
//            echo "Cron end at: ".date('d/m/Y H:i:s')."\n";
//            echo "Cron report | last ".($timeEnd-$timeStart).'(s) | total: '.count($aProducts)." | change: $numProduct\n";
            Loggers::WriteLog("Cron end at: ".date('d/m/Y H:i:s'), Loggers::type_cron);
            Loggers::WriteLog('Cron report | last '.($timeEnd-$timeStart).'(s) | total: '.count($aProducts)." | change: $numProduct\n", Loggers::type_cron);
            return ExitCode::OK;
        } catch (Exception $exc) {
            Loggers::WriteLog("Cron errors: tracking/track-hourly | ".date('d/m/Y H:i:s'), Loggers::type_cron);
        }
    }
}
