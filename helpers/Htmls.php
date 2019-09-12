<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\helpers;

use Yii;
use app\models\Users;
use yii\helpers\Url;

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
        $aOption    = [
            'profile'           => Yii::t('app', 'Profile'),
            'tracking-items'    => Yii::t('app', 'Tracking items'),
            'settings'          => Yii::t('app', 'Settings')
        ];
        $ret        = [];
        foreach ($aOption as $action => $name) {
            $ret[] = [
                'label' => $name,
                'url' => Url::to(["/user/default/{$action}"]),
            ];
        }
        return $ret;
    }
    
    /*
     * Get array admin items for main menu
     */
    public static function getAdminItems(){
        $ret                = [];
        if(Checks::isAdmin()){ // Menu for admin
            $session        = Yii::$app->session;
            $aCA            = $session->get('listMenu');
            if(empty($aCA)){ // Login by cookie
                $model      = Users::findOne(Yii::$app->user->id);
                $model->initSessionBeforeLogin();
                $aCA        = $session->get('listMenu');
            }
            
            // test live menu
//            $mMenu = new \app\models\Menus();
//            $aCA = $mMenu->getArrayMenuAdmin();
            
//            $sep = [
//                '<li class="divider"></li>',
//                '<li class="dropdown-header">Chức năng nâng cao</li>',
//            ];
//            array_push($ret, $sep[0], $sep[1]);
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
    
    /**
     * 
     * @param type $aData ['header'=>[], 'body'=>[0=>[], 1=>[]]]
     * @param type $tableClass class of <table> tag
     * @param $custom 
     * [
     *    'input'=>[
     *        $index_column(0,1,2..) => ['name'=>'Model[name]', 'column_key'=>1]
     *    ]
     * ]
     */
    public static function createTabelFromArray($aData, $tableClass='table table-striped', $custom=[]){
        $result  = "<table class='$tableClass'>";
        if(empty($aData['header'])){
            return $result;
        }
        $aHead   = $aData['header'];
        $aBody   = empty($aData['body']) ? [] : $aData['body'];
        $result .= '<thead>';
        $result .=      '<tr>';
        foreach ($aHead as $value) {
            $result .=      "<th>$value</th>";
        }
        $result .=      '</tr>';
        $result .= '<thead>';
        $result .= '<tbody>';
        foreach ($aBody as $row) {
            $result .= '<tr>';
            foreach ($aHead as $head_key => $head_name) {
                $cell_value     = empty($row[$head_key]) ? '' : $row[$head_key];
                if( isset($custom['input'][$head_key]) ){
                    $inputName  = empty($custom['input'][$head_key]['name']) ? '' : $custom['input'][$head_key]['name'];
                    $columnKey  = empty($custom['input'][$head_key]['column_key']) ? '' : $custom['input'][$head_key]['column_key'];
                    $key        = empty($row[$columnKey]) ? '' : $row[$columnKey];
                    $cell_value = "<input type='text' name='{$inputName}[$key]' value='{$cell_value}' class='form-control'>";
                }
                $result .=      "<td>$cell_value</td>";
            }
            $result .= '</tr>';
        }
        $result .= '</tbody>';
        return $result;
    }

}

