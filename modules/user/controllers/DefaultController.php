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
use app\models\PriceLogs;

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
        if(Yii::$app->user->isGuest){
            return $this->requireLogin();
        }
        Checks::notFoundExc();
//        return $this->render('index');
    }
    
    /*
     * Profile of current user
     */
    public function actionProfile(){
        try {
            if(Yii::$app->user->isGuest){
                return $this->requireLogin();
            }
            $model      = Users::find()->where(['id' => Yii::$app->user->id])->one();
            $model->scenario = 'editProfile';
            $request    = Yii::$app->request;
            $post       = $request->post();
            if($request->isPost && $post){
                if(isset($post['Users']['newPassword'])){
                    $model->generatePassword($post['Users']['newPassword']);
                }
                $model->attributes = $post['Users'];
                $model->update();
                Yii::$app->session->setFlash('success', "Your profile has been updated successfully.");
                return $this->redirect(['default/profile']);
            }
            return $this->render('profile', ['model' => $model]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /*
     * List of tracking items of current user
     */
    public function actionTrackingItems(){
        try {
            if(Yii::$app->user->isGuest){
                return $this->requireLogin();
            }
            $now   = date('Y-m-d H:i:s');
            $query = UserTracking::find()
                ->where([
                    'user_id' => Yii::$app->user->id, 
                ])
                ->andWhere(['<=', 'start_date', $now])
                ->andWhere([
                    'or',
                    ['is', 'end_date', null],
                    ['>=', 'end_date', $now],
                ]);
            $provider = new ActiveDataProvider([
                'query' => $query,
            ]);
            return $this->render('tracking_items', ['dataProvider' => $provider]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /*
     * Start tracking product
     */
    public function actionStartTracking(){
        try {
            if(Yii::$app->user->isGuest){
                return $this->requireLogin();
            }
            $product              = new Products();
            $product->attributes  = \Yii::$app->request->post('Products');
            $product->handleUrl();
            $models               = Products::find()->where(['url' => $product->url])->one();
            if(!$models){ // Save as New product and price log if product doesn't exists
                $product->save();
                $models = $product; // If model empty
            }
            $mLogExists = PriceLogs::find()
                            ->where([
                                'product_id' => $models->id,
                                'price' => $models->price
                            ])->one();
            // If product doesn exist, save first price
            if(!$mLogExists){
                $pLog             = new PriceLogs();
                $pLog->product_id = $models->id;
                $pLog->price      = $models->price;
                $pLog->save();
            }
            if(!Yii::$app->user->isGuest){ // Save tracking info of user (logged in)
                // Save user tracking info
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
//                        $mTrackingBefore->status   = UserTracking::stt_active;
                        $mTrackingBefore->update();
                    } else { // if dosen tracking before => save new
                        $userTracking->end_date   = $endDate;
//                        $userTracking->status     = UserTracking::stt_active;
                        $userTracking->save();
                    }
                }
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /*
     * stop tracking product by product id
     */
    public function actionStopTracking($id){
        try {
            if(Yii::$app->user->isGuest){
                return $this->requireLogin();
            }
            if(isset($id)){
                $mUserTracking              = new UserTracking();
                $mUserTracking->product_id  = $id;
                $model                      = $mUserTracking->findByProductId();
                if($model){
//                    $model->status      = UserTracking::stt_inactive;
                    $model->end_date    = date('Y-m-d H:i:00');
                    $model->update();
                }
            }
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /*
     * User setting
     */
    public function actionSettings(){
        try {
            if(Yii::$app->user->isGuest){
                return $this->requireLogin();
            }
            $user = Users::find()
                ->where([
                    'id' => Yii::$app->user->id, 
//                    'role' => Constants::USER
                ])
                ->one();
            $post = Yii::$app->request->post('Users');
            if(isset($post)){
                $user->attributes = $post;
                $user->update();
            }
            return $this->render('settings', ['user' => $user]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
}
