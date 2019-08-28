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
use app\models\GetData;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     *  to run: /usr/bin/php /var/www/html/trackers/yii test
     * /usr/bin/php /var/www/clients/client0/web1/web/trackers/yii test
     */
    public function actionIndex()
    {
        try {
            $this->getProductInfo();
        } catch (Exception $exc) {
            Loggers::WriteLog("Cron errors: hello | ".date('d/m/Y H:i:s'), Loggers::type_cron);
        }
    }
    
    /**
     * @todo crawl data from url or product_id
     */
    public function getProductInfo()
    {
        try {
            $id         = 8;
            $mProduct   = Products::findOne($id);
            $aData      = [];
            if( !empty($mProduct) ){
                $aData  = GetData::instance()->searchNewUrl($mProduct->url);
//                https://www.sendo.vn/bo-tay-seo-dvelinil-va-tri-seo-klirvin-chinh-hang-12565826.html
//                https://www.sendo.vn/bo-3-mon-gom-2-kem-tri-seo-klirvin-va-1-serum-tay-seo-dvelinil-12565826.html?platform=web
//                $aData  = GetData::instance()->searchNewUrl('https://www.sendo.vn/bo-3-mon-gom-2-kem-tri-seo-klirvin-va-1-serum-tay-seo-dvelinil-12565826.html?platform=web');
            }
            echo '<pre>';
            print_r($aData);
            echo '</pre>';
            die;
        } catch (Exception $exc) {
            Loggers::WriteLog("Cron errors: hello | ".date('d/m/Y H:i:s'), Loggers::type_cron);
        }
    }
}
