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
use app\models\Products;

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
     * /usr/bin/php /var/www/clients/client0/web1/web/trackers/yii hello
     */
    public function actionIndex()
    {
        try {
            $aProductChange[Products::TYPE_INCREASE]=[
                69,70,71
            ];
            $mNotification = new Notifications();
            $mNotification->notifyPriceChangedViaZalo($aProductChange);
        } catch (Exception $exc) {
            Loggers::WriteLog("Cron errors: hello | ".date('d/m/Y H:i:s'), Loggers::type_cron);
        }
    }
}
