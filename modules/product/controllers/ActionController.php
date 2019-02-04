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

namespace app\modules\product\controllers;

use app\controllers\BaseController;
use app\models\GetData;

/**
 * Default controller for the `product` module
 */
class ActionController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionSearch(){
        $aData              = [];
        if(!empty($_GET['search-value'])){
            $searchValue    = $_GET['search-value'];
            $aData          = GetData::instance()->getInfo($searchValue);
            if(!empty($aData['name'])){
                return $this->redirect(['action/detail','url'=>$searchValue]);
            }
        }
        
        return $this->render('index', [
            'aData' => $aData
        ]);
    }
    
    public function actionDetail($url){
        $aData = GetData::instance()->searchUrl($url);
        return $this->render('detail', [
            'aData' => $aData
        ]);
    }
}
