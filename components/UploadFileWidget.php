<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use yii\base\Widget;

class UploadFileWidget extends Widget
{
    public $model, $attribute, $isMulti = false;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('UploadFile/form_upload',[
                    'model'     => $this->model,
                    'attribute' => $this->attribute,
                    'isMulti'   => $this->isMulti
                ]);
    }
}