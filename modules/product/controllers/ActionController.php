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
//    public function actionStartTracking(){
//        try {
//            if (Yii::$app->user->isGuest) {
//                Checks::notFoundExc();
//            }
//            $product              = new Products();
//            $product->attributes  = \Yii::$app->request->post('Products');
//            $product->handleUrl();
//            $models               = Products::find()->where(['url' => $product->url])->one();
//            if(!$models){ // Save as New product and price log if product doesn't exists
//                $product->slug = MyFormat::slugify($product->name);
//                $product->save();
//                $models = $product;
//                $pLog             = new PriceLogs();
//                $pLog->product_id = $product->id;
//                $pLog->price      = $product->price;
//                $pLog->save();
//            }
//            if(!Yii::$app->user->isGuest){ // Save tracking info of user (logged in)
//                $userTracking = new UserTracking();
//                $userTracking->user_id        = Yii::$app->user->id;
//                $userTracking->product_id     = $models->id;
//                if(!$userTracking->isTracked()){ // if now doesn tracking
//                    $post            = \Yii::$app->request->post('UserTracking');
//                    $trackFor        = isset(UserTracking::aTrackingTime()[$post['end_date']]) ? $post['end_date'] : 0;
//                    $userTracking->start_date = date('Y-m-d H:i:s');
//                    $endDate         = ($trackFor == 0) ? null : MyFormat::modifyDays($userTracking->start_date, $trackFor, '+', 'days', 'Y-m-d H:i:s');
//                    $mTrackingBefore = $userTracking->isTrackedBefore();
//                    if($mTrackingBefore){ // if tracking before => update 
//                        $mTrackingBefore->end_date = $endDate;
//                        $mTrackingBefore->status   = UserTracking::stt_active;
//                        $mTrackingBefore->update();
//                    } else { // if dosen tracking before => save new
//                        $userTracking->end_date   = $endDate;
//                        $userTracking->status     = UserTracking::stt_active;
//                        $userTracking->save();
//                    }
//                }
//            }
//            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
//        } catch (Exception $exc) {
//            
//        }
//    }
//    
//    public function actionStopTracking(){
//        try {
//            if (Yii::$app->user->isGuest) {
//                Checks::notFoundExc();
//            }
//            $post = \Yii::$app->request->post('Products');
//            if(isset($post['id'])){
//                $mUserTracking              = new UserTracking();
//                $mUserTracking->product_id  = $post['id'];
//                $model                      = $mUserTracking->findByProductId();
//                if($model){
//                    $model->status      = UserTracking::stt_inactive;
//                    $model->end_date    = date('Y-m-d H:i:s');
//                    $model->update();
//                }
//            }
//            return $this->goBack(Yii::$app->request->referrer);
//        } catch (Exception $exc) {
//            
//        }
//    }
}
