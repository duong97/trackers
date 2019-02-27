<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

use yii\web\NotFoundHttpException;
use Yii;

class Checks{
    
    public static function notFoundExc() {
        throw new NotFoundHttpException(Yii::t('app', 'The request page does not exists'));
    }
    
    public static function requireLogin(){
        if(Yii::$app->user->isGuest){
            Checks::notFoundExc();
        }
    }
}