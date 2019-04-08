<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Notifications;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     *  to run: /usr/bin/php /var/www/html/trackers/yii hello
     */
    public function actionIndex()
    {
        try {
            $mNotification = new Notifications();
            $aProductId = [23, 27, 28];
            $mNotification->notifyPriceChanged($aProductId);
        } catch (Exception $exc) {
            Loggers::WriteLog("Cron errors: hello | ".date('d/m/Y H:i:s'), Loggers::type_cron);
        }
    }
}
