<?php
/**
 * Anivia *
 *	     __.oOo.__
 *	    /'(  _  )`\
 *	   / . \/^\/ . \
 *	  /  _)_`-'_(_  \
 *	 /.-~   ).(   ~-.\
 *	/'     /\_/\     `\
 *	     . "-V-"
 */
namespace app\controllers;

use yii\web\Controller;
use Yii;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();
        $this->setLanguage();
    }
    
    public function setLanguage(){
        $cookies                = Yii::$app->request->cookies;
        $language               = $cookies->getValue('language', 'en');
        \Yii::$app->language    = $language;
    }
}
