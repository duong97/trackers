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

use app\helpers\Checks;

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
                $mUser              = Users::findOne(Yii::$app->user->id);
                if($mUser){
                    if(empty($mUser->subscription)){
                        $aSub = [
                            Checks::getDevice() => $jsonSubscription
                        ];
                        $mUser->subscription    = json_encode($aSub);
                        $notification           = new Notifications();
                        $notification->notifyBrowser($subscription); // First notify
                    } else {
                        $aCurSub                        = json_decode($mUser->subscription, true);
                        $aCurSub[Checks::getDevice()]   = $jsonSubscription;
                        $mUser->subscription            = json_encode($aCurSub);
                    }
                    $mUser->update();
                }
            } else {
                return $this->goHome();
            }
        } catch (Exception $exc) {
            
        }
    }
    
    
}
