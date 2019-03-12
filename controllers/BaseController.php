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
use app\helpers\Checks;

class BaseController extends Controller
{
    public function init()
    {
        parent::init();
        $this->setAccess();
        $this->setLanguage();
    }
    
    public function setLanguage(){
        $cookies                = Yii::$app->request->cookies;
        $language               = $cookies->getValue('language', 'en');
        \Yii::$app->language    = $language;
    }
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if(Checks::isRoot() || $this->id == 'site') return true;
            if($this->module->id == 'admin'){
                $session        = Yii::$app->session;
                $aAccessAction  = $session->get('listAccessAction');
                $refClass       = new \ReflectionClass($this->className());
                $ctlName        = $refClass->getShortName();
                if( isset($aAccessAction[$ctlName]) ){
                    if( in_array($this->action->id, $aAccessAction[$ctlName]) ){
                        return true;
                    }
                }
                Checks::accessExc();
                return false;
            }
            return true;
        } else {
            Checks::accessExc();
            return false;
        }
    }
    
    protected function setAccess()
    {
        if($this->module->id == 'admin'){
            if($this->id == 'controllers'){
                Checks::requireRoot();
            } else {
                Checks::requireAdmin();
            }
        }
        return false;
    }
}
