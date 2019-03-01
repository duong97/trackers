<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

use Yii;

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
        return $ret;
    }
    

}

