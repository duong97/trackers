<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

use Yii;
use app\models\Users;
use app\models\LoginForm;

class Htmls{
    
    /*
     * Get array language items for main menu
     */
    public static function getChangeLanguageItems(){
        $urlM       = \Yii::$app->getUrlManager();
        $cLanguage  = \Yii::$app->language;
        $aLanguage  = Constants::$aLanguage;
        $ret        = [
            '<li class="dropdown-header">'. Yii::t('app', 'Select language') .'</li>',
            '<li class="divider"></li>'
        ];
        foreach ($aLanguage as $key => $name) {
            $url = $urlM->createUrl(['site/change-language', 'lang'=>$key]);
            $ret[] = [
                'label' => $name,
                'url' => ($cLanguage == $key) ? "#" : $url,
                'options' => ($cLanguage == $key) ? ['class' => 'active'] : []
            ];
        }
        return $ret;
    }
    
    /*
     * Get array user items for main menu
     */
    public static function getUserItems(){
        $url        = \Yii::$app->getUrlManager();
        $aOption    = [
            'profile' => Yii::t('app', 'Profile'),
            'tracking-items' => Yii::t('app', 'Tracking items'),
            'settings' => Yii::t('app', 'Settings')
        ];
        $ret        = [];
        foreach ($aOption as $action => $name) {
            $ret[] = [
                'label' => $name,
                'url' => $url->createUrl(["user/default/{$action}"]),
            ];
        }
        if(Checks::isAdmin()){ // Menu for admin
            $session     = Yii::$app->session;
            $aCA         = $session->get('listMenu');
            if(empty($aCA)){ // Login by cookie
                $model          = Users::findOne(Yii::$app->user->id);
                $model->initSessionBeforeLogin();
                $aCA            = $session->get('listMenu');
            }
            $sep = [
                '<li class="divider"></li>',
                '<li class="dropdown-header">Chức năng nâng cao</li>',
            ];
            array_push($ret, $sep[0], $sep[1]);
            foreach ($aCA as $value) {
                $ret[]   = [
                            'label' => $value['label'],
                            'url' => $value['url'],
                        ];
            }
        }
        return $ret;
    }
    
    /*
     * @todo convert controller name to controller id
     * SomeThingController -> some-thing
     */
    public static function handleControllerName($name){
        $controller_id  = substr($name, 0, -10);
        $formatedCtl    = preg_replace('/\B([A-Z])/', '-$1', $controller_id);
        return strtolower($formatedCtl);
    }

}

