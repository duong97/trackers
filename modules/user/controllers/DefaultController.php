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
use app\models\simple_html_dom;
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
        $aData = [];
        
        if(!empty($_GET['search-value'])){
            $url=$_GET['search-value'];
            $aUrlParams = explode("-i.", $url);
            $aId = explode(".", $aUrlParams[1]);
            $api = "https://shopee.vn/api/v2/item/get?itemid={$aId[1]}&shopid={$aId[0]}";
            $result = file_get_contents($api);
            $aData = json_decode($result, true);
        }
        return $this->render('index', [
            'aData' => $aData
        ]);
    }
}
