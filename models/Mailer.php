<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\models;
use Yii;
use app\models\Users;
use app\models\Products;
use app\models\UserTracking;

class Mailer{
    
    public function verifyRegistration($mail){
        $mUsers     = new Users();
        $user       = $mUsers->getVerifyingUserByEmail($mail);
        if(empty($user)) return null;
        $url        = $user->getUrlConfirm();
        $message    = Yii::$app->mailer->compose('verifyRegistration',['url'=>$url]);
        $message->setFrom([Yii::$app->params['verifyEmail'] => 'ChartCost.com'])
                ->setTo($mail)
                ->setSubject(Yii::t('app', 'Verify Registration'))
                ->send();
    }
    
    public function notifyPriceChanged($aProductId){
        $mUserTracking  = new UserTracking();
        $aUserNotify    = $mUserTracking->getListNotifyUser($aProductId);
        $mUser          = new Users();
        $mProduct       = new Products();
        $aModelUser     = $mUser->getListUserById(array_keys($aUserNotify));
        $aModelProduct  = $mProduct->getListProductById($aProductId);
        foreach ($aUserNotify as $user_id => $aProduct) {
            $aProductOfUser = [];
            foreach ($aProduct as $product_id) {
                $aProductOfUser[$product_id] = isset($aModelProduct[$product_id]) ? $aModelProduct[$product_id] : [];
            }
            $mail       = isset($aModelUser[$user_id]) ? $aModelUser[$user_id]->email : '';
            $message    = Yii::$app->mailer->compose('notifyPriceChanged',['aProductOfUser'=>$aProductOfUser]);
            $message->setFrom([Yii::$app->params['verifyEmail'] => 'ChartCost.com'])
                    ->setTo($mail)
                    ->setSubject(Yii::t('app', 'Notification'))
                    ->send();
        }
    }

}

