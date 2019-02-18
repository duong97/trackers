<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii\base\Widget;

class DetailViewWidget extends Widget
{
    public $aData;
    public $aPriceLog;

    public function init()
    {
        parent::init();
        
    }

    public function run()
    {
        $this->aData['name']  = isset($this->aData['name'])  ? $this->aData['name']  : "";
        $this->aData['price'] = isset($this->aData['price']) ? $this->aData['price'] : "";
        $this->aData['image'] = isset($this->aData['image']) ? $this->aData['image'] : "";
        $this->aPriceLog      = isset($this->aPriceLog)      ? $this->aPriceLog : [];
        return $this->render('DetailView/index',[
            'aData'=> $this->aData,
            'aPriceLog'=> $this->aPriceLog,
        ]);
    }
}