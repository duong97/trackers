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
use app\models\ActionRoles;
use yii\helpers\Url;

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
        $language               = $cookies->getValue('language');
        Yii::$app->language    = $language ? $language : Yii::$app->language;
    }
    
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if(Checks::isRoot() || $this->id == 'site') return true;
            if($this->module->id == 'admin'){
//                $session        = Yii::$app->session;
//                $aAccessAction  = $session->get('listAccessAction');
                
                $mActionRole   = new ActionRoles();
                $aAccessAction  = $mActionRole->getArrayAccess(Yii::$app->user->identity->role);
                
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
    
    public function requireLogin(){
        $urlManager                 = Yii::$app->getUrlManager();
        $url                        = $urlManager->createUrl(['site/login']);
        Yii::$app->user->returnUrl  = Yii::$app->request->getUrl();
        return $this->redirect($url);
    }
    
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    
}
