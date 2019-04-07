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

use app\models\Users;
use app\models\Notifications;

use Yii;

/**
 * Default controller for the `user` module
 */
class NotificationController extends BaseController
{
   
    
    /*
     * Profile of current user
     */
    public function actionRegister(){
        try {
            $subscription  = '';
            if(isset($_GET['subscription'])){
                $jsonSubscription   = $_GET['subscription'];
                $subscription       = json_decode($jsonSubscription, true);
                $notification       = new Notifications();
                $notification->notify($subscription);
                $mUser              = Users::findOne(Yii::$app->user->id);
                if($mUser){
                    $mUser->subscription= $jsonSubscription;
                    $mUser->update();
                }
            } else {
                return $this->goHome();
            }
        } catch (Exception $exc) {
            
        }
    }
    
    
}
