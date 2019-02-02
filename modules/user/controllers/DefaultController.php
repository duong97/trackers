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
namespace app\modules\user\controllers;

use app\controllers\BaseController;
use app\models\GetData;

/**
 * Default controller for the `user` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $aData      = [];
        if(!empty($_GET['search-value'])){
            $url    = $_GET['search-value'];
            $aData  = GetData::instance()->getInfo($url);
        }
        return $this->render('index', [
            'aData' => $aData
        ]);
    }
}
