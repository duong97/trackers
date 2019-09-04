<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii\base\Component;
use app\models\Settings;
use app\helpers\MyFormat;

class SettingsComponent extends Component
{
    public $m;
    
    public function init()
    {
        parent::init();
        $this->initAllSettings();
    }

    public function initAllSettings(){
        $mSetting   = new Settings();
        $aSetting   = $mSetting->getAll();
        $this->m    = new Settings();
        foreach ($aSetting as $mSetting) {
            $var    = MyFormat::toCamelCase($mSetting->code, '_');
            if( property_exists($this->m, $var) ){
                $this->m->{$var} = $mSetting->value;
            }
        }
    }
    
}