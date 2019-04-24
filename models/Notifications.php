<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\models;

use Yii;
use app\models\Users;
use app\models\Loggers;
use app\helpers\Constants;
use yii\helpers\Url;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Notifications{
    
    /**
     * @todo notify for user when price is changed via browser
     */
    public function notifyPriceChangedViaBrowser($aProductId){
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
                        $this->notifyBrowser(json_decode($sub, true), $payload);
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
    public function notifyBrowser($subscription, $payload = []){
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
    
    /**
     * @todo notify via zalo message
     */
    public function notifyPriceChangedViaZalo($aProductId){
        $mUserTracking  = new UserTracking();
        $aUserNotify    = $mUserTracking->getListNotifyUser($aProductId);
//        $aUserNotify = [
//            25 => [
////                20, 21, 22
//            ]
//        ];//test
        $mUser          = new Users();
        $mProduct       = new Products();
        $aModelUser     = $mUser->getListUserById(array_keys($aUserNotify));
        $aModelProduct  = $mProduct->getListProductById($aProductId);
        foreach ($aUserNotify as $user_id => $aProduct) {
            if( !$aModelUser[$user_id]->is_notify_zalo ) continue;
            $message = [
                    'attachment' => [
                        'type' => 'template',
                        'payload' => [
                            'template_type' => 'list',
                            'elements' => [
                                [
                                    "title" => "Thông báo",
                                    "subtitle" => "Chào ".$aModelUser[$user_id]->first_name.", dưới đây là danh sách những sản phẩm bạn đang theo dõi có sự biến động giá!",
//                                    "image_url" => Yii::$app->params['homeUrl'] . Url::to(['/images/logo/chartcost.png']), // open when run on web
                                    "image_url" => Url::to(['/images/logo/chartcost.png']), // open when run cron
                                    "default_action" => [
                                        "type" => "oa.open.url",
                                        "url" => Yii::$app->params['homeUrl']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            foreach ($aProduct as $product_id) {
                $product    = isset($aModelProduct[$product_id]) ? $aModelProduct[$product_id] : [];
//                $urlDetail  = Yii::$app->params['homeUrl'] . Url::to(['/product/action/detail', 'url'=> $product->url]); // open when run on web
                $urlDetail  = Url::to(['/product/action/detail', 'url'=> $product->url]); // open when run cron
                if(substr( $product->image, 0, 2 ) === "//"){ // validate image url (http / https)
                    $shortImgUrl    = substr($product->image, 2);
                    $product->image = str_pad($shortImgUrl, strlen($shortImgUrl)+7,'http://',STR_PAD_LEFT);
                }
                $bodyMessage = [
                    "title"             => $product->name,
                    "subtitle"          => "chi tiết",
                    "image_url"         => $product->image,
                    "default_action"    => [
                        "type"  => "oa.open.url",
                        "url"   => $urlDetail
                    ]
                ];
                $message['attachment']['payload']['elements'][] = $bodyMessage;
            }
            $this->notifyZalo($aModelUser[$user_id]->zalo_id, json_encode($message));
        }
    }
    
    /**
     * @todo notify via zalo for each user
     * @param $message json
     * @ref https://developers.zalo.me/docs/api/official-account-api/api/gui-tin-nhan-post-2343
     */
    public function notifyZalo($zaloId, $message){
        if(empty($zaloId) || empty($message)) return;
        $accessToken    = Yii::$app->params['zalo_oa_access_token'];
        $ch             = curl_init('https://openapi.zalo.me/v2.0/oa/message?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt(
            $ch, 
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $data   = '{"recipient":{"user_id":"'.$zaloId.'" },"message":'.$message.'}}';
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $jRes   = curl_exec($ch);
        $aRes   = json_decode($jRes, true);
        if($aRes['error'] == 0){
            Loggers::WriteLog('Notify Zalo successful: '.$jRes, Loggers::type_info);
        } else {
            Loggers::WriteLog('Notify Zalo error: '.$jRes, Loggers::type_app_error);
        }
        curl_close($ch);
    }
    
}

