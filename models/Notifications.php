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
    
    public function notify($subscription){
        if(empty($subscription)) return;
        $notifications = Subscription::create($subscription);

        $auth = array(
            'VAPID' => array(
                'subject' => Constants::website_name,
                'publicKey' => file_get_contents('http://localhost/trackers/public_key.txt'), // don't forget that your public key also lives in app.js
                'privateKey' => file_get_contents('http://localhost/trackers/private_key.txt'), // in the real world, this would be in a secret file
            ),
        );
        $payload = [
            'title' => Constants::website_name,
            'msg' => 'Thank you for registering to receive notifications via the browser!',
            'icon' => Url::to(['/images/logo/chartcost.png']),
            'data' => [
                'url' => Url::to(['/user/default/settings'])
            ]
        ];
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

