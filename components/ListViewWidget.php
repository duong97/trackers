<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii\base\Widget;

class ListViewWidget extends Widget
{
    public $dataProvider;

    public function init()
    {
        parent::init();
        
    }

    public function run()
    {
        return $this->render('ListView/index_listview', ['dataProvider'=> $this->dataProvider]);
    }
}