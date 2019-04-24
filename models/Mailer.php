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
    
    /**
     * @todo notify for user when price is changed via email
     */
    public function notifyPriceChanged($aProductId){
        $mUserTracking  = new UserTracking();
        $aUserNotify    = $mUserTracking->getListNotifyUser($aProductId);
        $mUser          = new Users();
        $mProduct       = new Products();
        $aModelUser     = $mUser->getListUserById(array_keys($aUserNotify));
        $aModelProduct  = $mProduct->getListProductById($aProductId);
        foreach ($aUserNotify as $user_id => $aProduct) {
            if( !$aModelUser[$user_id]->is_notify_email ) continue;
            
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
            Loggers::WriteLog('Notify via email | User ID: '.$user_id.' - '.$mail.' | '.date('d/m/Y'), Loggers::type_info);
        }
    }
    
    /**
     * @todo demo sendmail for root admin
     */
    public function demoSendmail($userEmail){
        if(empty($userEmail)) return;
        $mProduct           = new Products();
        $aProductId         = [10,11];
        $aProductOfUser     = $mProduct->getListProductById($aProductId);
        $message            = Yii::$app->mailer->compose('notifyPriceChanged',['aProductOfUser'=>$aProductOfUser]);
        $message->setFrom([Yii::$app->params['verifyEmail'] => 'ChartCost.com'])
                ->setTo($userEmail)
                ->setSubject(Yii::t('app', 'Notification'))
                ->send();
    }

}

