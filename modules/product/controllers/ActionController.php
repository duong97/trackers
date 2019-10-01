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
use app\models\PriceLogs;
use app\helpers\Checks;
use app\models\Products;
use yii\helpers\Url;

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
    
    /*
     * Search products by url or keyword
     */
    public function actionSearch(){
        try {
            $aData              = [];
            if(!empty($_GET['search-value'])){
                $searchValue    = $_GET['search-value'];
                $session        = \Yii::$app->session;
                $session->set('search-nav', urldecode($searchValue));
                $mGetData       = new GetData();
                $aData          = $mGetData->getInfo($searchValue);
                if(is_array($aData) && !empty($aData['name'])){
                    return $this->redirect(['action/detail', 'url' => $searchValue]);
                }
            }
            return $this->render('index', [
                'aData' => $aData
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /*
     * View product detail
     */
    public function actionDetail($url, $forceCrawl = false){
        try {
            $mProduct       = new Products();
            $mExistsProduct = $mProduct->getByUrl($url);
            if($mExistsProduct){
                $mExistsProduct->formatNameForSeo();
                return $this->redirect(['action/view', 'name' => $mExistsProduct->name]);
            }
            
            $aData = GetData::instance()->searchUrl($url, $forceCrawl);
            
            if( empty($aData) ){
                Checks::notFoundExc();
            }
            $aPriceLog  = PriceLogs::instance()->getByUrl($url);
            
            $aData['current_url'] = $url;
            return $this->render('detail', [
                'aData'     => $aData,
                'aPriceLog' => $aPriceLog,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    public function actionView($name=''){
        try {
            $mGetData   = new GetData();
            $aData      = $mGetData->searchExistsByName($name);
            if( empty($aData) || empty($aData['id'])){
                Checks::notFoundExc();
            }
            $aPriceLog  = PriceLogs::instance()->getByProductID($aData['id']);
            
            return $this->render('detail', [
                'aData'     => $aData,
                'aPriceLog' => $aPriceLog,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
}
