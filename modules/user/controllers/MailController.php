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
use app\models\Mailer;

use app\helpers\Checks;
use app\helpers\MyFormat;
use app\helpers\Constants;
use Yii;

/**
 * Default controller for the `user` module
 */
class MailController extends BaseController
{
   
    
    /*
     * Profile of current user
     */
    public function actionResendRegistration(){
        try {
            $email      = isset($_GET['email']) ? $_GET['email'] : '';
            if(!empty($email)){
                $mailer     = new Mailer();
                $mailer->verifyRegistration($email);
                $urlManager = \Yii::$app->getUrlManager();
                $url        = $urlManager->createUrl(['site/register']);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Email has been resent.'));
                $this->redirect($url);
            } else {
                Checks::notFoundExc();
            }
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    
}
