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
use app\models\Products;
use app\models\UserTracking;
use app\helpers\Checks;
use \app\helpers\MyFormat;
use Yii;

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
                $mGetData       = new GetData();
                $aData          = $mGetData->getInfo($searchValue);
                if(!empty($aData['price'])){
                    return $this->redirect(['action/detail', 'url' => $searchValue]);
                }
            }
            return $this->render('index', [
                'aData' => $aData
            ]);
        } catch (Exception $exc) {
            
        }
    }
    
    /*
     * View product detail
     */
    public function actionDetail($url){
        try {
            $aData      = GetData::instance()->searchUrl($url);
            $aPriceLog  = PriceLogs::instance()->getByUrl($url);
            return $this->render('detail', [
                'aData'     => $aData,
                'aPriceLog' => $aPriceLog,
            ]);
        } catch (Exception $exc) {
            
        }
    }
    
    /*
     * Start tracking product
     */
    public function actionStartTracking(){
        try {
            if (Yii::$app->user->isGuest) {
                Checks::notFoundExc();
            }
            $product              = new Products();
            $product->attributes  = \Yii::$app->request->post('Products');
            $product->handleUrl();
            $models               = Products::find()->where(['url' => $product->url])->one();
            if(!$models){ // Save if product doesn't exists
                $product->slug = MyFormat::slugify($product->name);
                $product->save();
                $models = $product;
                $pLog             = new PriceLogs();
                $pLog->product_id = $product->id;
                $pLog->price      = $product->price;
                $pLog->save();
            }
            if(!Yii::$app->user->isGuest){ // Save tracking info of user (logged in)
                $userTracking = new UserTracking();
                $userTracking->user_id        = Yii::$app->user->id;
                $userTracking->product_id     = $models->id;
                if(!$userTracking->isTracked()){
                    $userTracking->start_date = date('Y-m-d H:i:s');
                    $post                     = \Yii::$app->request->post('UserTracking');
                    $trackFor                 = isset(UserTracking::aTrackingTime()[$post['end_date']]) ? $post['end_date'] : 0;
                    $userTracking->end_date   = ($trackFor == 0) ? null : MyFormat::modifyDays($userTracking->start_date, $trackFor, '+', 'days', 'Y-m-d H:i:s');
                    $userTracking->save();
                }
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $exc) {
            
        }
    }
}
