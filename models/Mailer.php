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
     * @param $aProductId [ type(increase or decrease) => [id1, id2, ...] ]
     */
    public function notifyPriceChanged($aProductId){
        if(empty($aProductId)) return;
        $listProdId     = array_reduce($aProductId, 'array_merge',[]);
        $mUserTracking  = new UserTracking();
        $aUserNotify    = $mUserTracking->getListNotifyUser($listProdId);
        $mUser          = new Users();
        $mProduct       = new Products();
        $aModelUser     = $mUser->getListUserById(array_keys($aUserNotify));
        $aModelProduct  = $mProduct->getListProductById($listProdId);
        
        foreach ($aUserNotify as $user_id => $aProduct) {
            $user = $aModelUser[$user_id];
            if( empty($user) || !$user->is_notify_email ) continue;
            
            $aProductOfUser = [];
            foreach ($aProduct as $product_id) {
                // Check user notify type
                if($user->notify_type == Products::TYPE_DECREASE && !in_array($product_id, $aProductId[Products::TYPE_DECREASE])) continue;
                if($user->notify_type == Products::TYPE_INCREASE && !in_array($product_id, $aProductId[Products::TYPE_INCREASE])) continue;
                
                $aProductOfUser[$product_id] = isset($aModelProduct[$product_id]) ? $aModelProduct[$product_id] : [];
            }
            
            $mail       = $user->email;
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
    
    /**
     * @todo send mail reset password
     */
    public function forgotPassword($mail, $tmpPass){
        $mUsers     = new Users();
        $user       = $mUsers->getUserByEmail($mail);
        if(empty($user)) return null;
        $user->generatePassword($tmpPass);
        $user->update();
        $message    = Yii::$app->mailer->compose('forgotPassword',['tempPass'=>$tmpPass]);
        $message->setFrom([Yii::$app->params['verifyEmail'] => 'ChartCost.com'])
                ->setTo($mail)
                ->setSubject(Yii::t('app', 'Reset password'))
                ->send();
    }

}

