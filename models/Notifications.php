<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\models;

use Yii;
use app\models\Users;
use app\helpers\Constants;
use yii\helpers\Url;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Notifications{
    
    /**
     * @todo notify for user when price is changed via browser
     */
    public function notifyPriceChanged($aProductId){
        $mUserTracking  = new UserTracking();
        $aUserNotify    = $mUserTracking->getListNotifyUser($aProductId);
        $mUser          = new Users();
        $mProduct       = new Products();
        $aModelUser     = $mUser->getListUserById(array_keys($aUserNotify));
        $aModelProduct  = $mProduct->getListProductById($aProductId);
        foreach ($aUserNotify as $user_id => $aProduct) {
            if( !$aModelUser[$user_id]->is_notify_browser ) continue;
            $subscription       = isset($aModelUser[$user_id]) ? $aModelUser[$user_id]->subscription : '';
            if($subscription){
                $aSub           = json_decode($subscription, true); //[device_id => json_subscription]
                foreach ($aSub as $device => $sub) {
                    foreach ($aProduct as $product_id) {
                        $product    = isset($aModelProduct[$product_id]) ? $aModelProduct[$product_id] : [];
                        $urlDetail  = Url::to(['/product/action/detail', 'url'=> $product->url]);
                        $payload    = [
                            'title' => Yii::t('app', 'Notification'),
                            'msg'   => Yii::t('app', 'The price of the product you are tracking has been changed'),
                            'icon'  => Url::to(['/images/logo/chartcost.png']),
                            'data'  => [
                                'url' => $urlDetail
                            ]
                        ];
                        $this->notify(json_decode($sub, true), $payload);
                        Loggers::WriteLog('Notify via browser | User ID: '.$user_id.' - '.Users::$aDevice[$device].' | '.date('d/m/Y'), Loggers::type_info);
                    }
                }
            }
            
        }
    }
    
    /**
     * @todo notify for user via browser, each user have one $subscription
     * @param type $subscription array subsription
     */
    public function notify($subscription, $payload = []){
        if(empty($subscription)) return;
        $notifications          = Subscription::create($subscription);

        $auth = array(
            'VAPID' => array(
                'subject'       => Constants::website_name,
                'publicKey'     => file_get_contents(Yii::getAlias('@root').'/public_key.txt'), // don't forget that your public key also lives in app.js
                'privateKey'    => file_get_contents(Yii::getAlias('@root').'/private_key.txt'), // in the real world, this would be in a secret file
            ),
        );
        if( empty($payload) ){
            $payload = [
                'title' => Yii::t('app', 'Welcome'),
                'msg' => Yii::t('app', 'You have successfully registered to receive browser notifications!'),
                'icon' => Url::to(['/images/logo/chartcost.png']),
                'data' => [
                    'url' => Yii::$app->homeUrl
                ]
            ];
        }
        $webPush = new WebPush($auth);
            $webPush->sendNotification(
                $notifications,
                json_encode($payload)
            );
        // handle eventual errors here, and remove the subscription from your server if it is expired
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            if ($report->isSuccess()) {
                echo "[v] Message sent successfully for subscription {$endpoint}.";
            } else {
                echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
            }
        }
    }

}

