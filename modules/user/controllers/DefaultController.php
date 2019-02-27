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
use yii\data\ActiveDataProvider;

use app\models\Users;
use app\models\UserTracking;
use app\models\Products;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;
use Yii;

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
        return $this->render('index');
    }
    
    /*
     * Profile of current user
     */
    public function actionProfile(){
        try {
            Checks::requireLogin();
            $model      = Users::find()->where(['id' => Yii::$app->user->id])->one();
            $request    = Yii::$app->request;
            $post       = $request->post();
            if($request->isPost && $post){
                $model->attributes = $post['Users'];
                $model->update();
            }
            return $this->render('profile', ['model' => $model]);
        } catch (Exception $exc) {
            
        }
    }
    
    /*
     * List of tracking items of current user
     */
    public function actionTrackingItems(){
        try {
            Checks::requireLogin();
            $query = UserTracking::find()
                ->where([
                    'user_id' => Yii::$app->user->id, 
                    'status' => UserTracking::stt_active]);
            $provider = new ActiveDataProvider([
                'query' => $query,
            ]);
            return $this->render('tracking_items', ['dataProvider' => $provider]);
        } catch (Exception $exc) {
            
        }
    }
    
    /*
     * Start tracking product
     */
    public function actionStartTracking(){
        try {
            Checks::requireLogin();
            $product              = new Products();
            $product->attributes  = \Yii::$app->request->post('Products');
            $product->handleUrl();
            $models               = Products::find()->where(['url' => $product->url])->one();
            if(!$models){ // Save as New product and price log if product doesn't exists
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
                if(!$userTracking->isTracked()){ // if now doesn tracking
                    $post            = \Yii::$app->request->post('UserTracking');
                    $trackFor        = isset(UserTracking::aTrackingTime()[$post['end_date']]) ? $post['end_date'] : 0;
                    $userTracking->start_date = date('Y-m-d H:i:s');
                    $endDate         = ($trackFor == 0) ? null : MyFormat::modifyDays($userTracking->start_date, $trackFor, '+', 'days', 'Y-m-d H:i:s');
                    $mTrackingBefore = $userTracking->isTrackedBefore();
                    if($mTrackingBefore){ // if tracking before => update 
                        $mTrackingBefore->end_date = $endDate;
                        $mTrackingBefore->status   = UserTracking::stt_active;
                        $mTrackingBefore->update();
                    } else { // if dosen tracking before => save new
                        $userTracking->end_date   = $endDate;
                        $userTracking->status     = UserTracking::stt_active;
                        $userTracking->save();
                    }
                }
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $exc) {
            
        }
    }
    
    /*
     * stop tracking product by product id
     */
    public function actionStopTracking($id){
        try {
            Checks::requireLogin();
            if(isset($id)){
                $mUserTracking              = new UserTracking();
                $mUserTracking->product_id  = $id;
                $model                      = $mUserTracking->findByProductId();
                if($model){
                    $model->status      = UserTracking::stt_inactive;
                    $model->end_date    = date('Y-m-d H:i:s');
                    $model->update();
                }
            }
            return $this->goBack(Yii::$app->request->referrer);
        } catch (Exception $exc) {
            
        }
    }
    
    /*
     * User setting
     */
    public function actionSettings(){
        try {
            Checks::requireLogin();
            $user = Users::find()
                ->where([
                    'id' => Yii::$app->user->id, 
                    'role' => Constants::USER])
                ->one();
            $post = Yii::$app->request->post('Users');
            if(isset($post)){
                $user->attributes = $post;
                $user->update();
            }
            return $this->render('settings', ['user' => $user]);
        } catch (Exception $exc) {
            
        }
    }
    
}
