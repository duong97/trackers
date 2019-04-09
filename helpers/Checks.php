<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use Yii;
use app\models\Loggers;
use app\models\Users;

class Checks{
    
    public static function notFoundExc() {
        throw new NotFoundHttpException(Yii::t('app', 'The request page does not exists'));
    }
    
    public static function accessExc() {
        throw new UnauthorizedHttpException(Yii::t('app', 'You do not have access to this page!'));
    }
    
    public static function requireLogin(){
        if(Yii::$app->user->isGuest){
            Checks::notFoundExc();
        }
    }
    
    /**
     * @todo catch all exception of controller
     * @param type $exc
     */
    public static function catchAllExeption($exc) {
        $cUid       = isset(Yii::$app->user) ? Yii::$app->user->id : "_";
        $ResultRun  = "Uid: $cUid Exception ".  $exc->getMessage();
        Loggers::WriteLog($ResultRun, Loggers::type_app);
        Checks::notFoundExc();
    }
    
    public static function isAdmin(){
        if(isset(Yii::$app->user->identity)){
            return (Yii::$app->user->identity->role == Constants::ADMIN || Yii::$app->user->identity->role == Constants::ROOT);
        }
        return false;
    }
    
    public static function isRoot(){
        if(isset(Yii::$app->user->identity)){
            return (Yii::$app->user->identity->role == Constants::ROOT);
        }
        return false;
    }
    
    public static function requireAdmin(){
        if(!Checks::isAdmin()){
            Checks::notFoundExc();
        }
    }
    
    public static function requireRoot(){
        if(!Checks::isRoot()){
            Checks::notFoundExc();
        }
    }
    
    public static function isMobileDevice(){
        $aMobileUA = array(
            '/iphone/i'     => 'iPhone', 
            '/ipod/i'       => 'iPod', 
            '/ipad/i'       => 'iPad', 
            '/android/i'    => 'Android', 
            '/blackberry/i' => 'BlackBerry', 
            '/webos/i'      => 'Mobile'
        );
        //Return true if Mobile User Agent is detected
        foreach($aMobileUA as $sMobileKey => $sMobileOS){
            if(preg_match($sMobileKey, Yii::$app->request->getUserAgent())){
                return true;
            }
        }
        //Otherwise return false..  
        return false;
    }
    
    public static function getDevice(){
        return self::isMobileDevice() ? Users::DEVICE_MOBILE : Users::DEVICE_DESKTOP;
    }
}