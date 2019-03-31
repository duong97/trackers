<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\models;
use Yii;
use app\models\Users;

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
    
    

}

