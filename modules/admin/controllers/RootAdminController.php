<?php

namespace app\modules\admin\controllers;

use yii\helpers\Url;
use app\controllers\BaseController;
use app\helpers\Checks;
use app\models\UserTracking;
use app\models\Notifications;
use app\models\Mailer;
use app\models\Users;
use Yii;

/**
 * ProductsController implements the CRUD actions for Products model.
 * This controller is used for ROOT ADMIN only
 */
class RootAdminController extends BaseController
{
    /**
     * @todo list all tool for root admin
     */
    public function actionIndex(){
        try {
            if(Checks::isRoot()){
                return $this->render('index');
            } else {
                return $this->redirect(['/site/index']);
            }
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }


    /**
     * @todo tracking all product with end_date = null
     */
    public function actionTrackingAll(){
        try {
            if(Checks::isRoot()){
                $mUserTracking = new UserTracking();
                $mUserTracking->trackingAll();
                return $this->redirect(['/user/default/tracking-items']);
            } else {
                return $this->redirect(['/site/index']);
            }
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /**
     * @todo tracking all product with end_date = null
     */
    public function actionStopTrackingAll(){
        try {
            if(Checks::isRoot()){
                $mUserTracking = new UserTracking();
                $mUserTracking->stopTrackingAll();
                return $this->redirect(['/user/default/tracking-items']);
            } else {
                return $this->redirect(['/site/index']);
            }
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /**
     * @todo demo notify
     */
    public function actionDemoNotify($notifyType){
        try {
            if(Checks::isRoot()){
                $mNotification  = new Notifications();
                $mailer         = new Mailer();
                $post           = Yii::$app->request->post('Users');
                $aUserId        = isset($post['id']) ? $post['id'] : [];
                $aModelUser     = Users::find()->where(['id' => $aUserId])->all();
                $listUser       = '';
                switch ($notifyType) {
                    case Users::NOTIFY_BROWSER:
                        $payload = [
                            'title' => 'Demo',
                            'msg' => 'Demo function notify via browser!',
                            'icon' => Url::to(['/images/logo/chartcost.png']),
                            'data' => [
                                'url' => Yii::$app->homeUrl
                            ]
                        ];
                        foreach ($aModelUser as $value) {
                            $listUser   .= $value->email.', ';
                            $aSub       = json_decode($value->subscription, true);
                            foreach ($aSub as $device => $sub) {
                                $mNotification->notifyBrowser(json_decode($sub, true), $payload);
                            }
                        }
                        break;
                        
                    case Users::NOTIFY_ZALO:
                        $message = [
                            'attachment' => [
                                'type' => 'template',
                                'payload' => [
                                    'template_type' => 'list',
                                    'elements' => [
                                        [
                                            "title" => "Demo Zalo Message",
                                            "subtitle" => "Demo thông báo qua tin nhắn Zalo | ".date('d/m/Y H:i:s'),
                                            "image_url" => Yii::$app->params['homeUrl'] . Url::to(['/images/logo/chartcost.png']),
                                            "default_action" => [
                                                "type" => "oa.open.url",
                                                "url" => Yii::$app->params['homeUrl']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ];
                        foreach ($aModelUser as $value) {
                            $listUser   .= "<b>$value->email</b> - ";
                            $mNotification->notifyZalo($value->zalo_id, json_encode($message));
                        }
                        break;
                        
                    case Users::NOTIFY_EMAIL:
                        foreach ($aModelUser as $value) {
                            $mailer->demoSendmail($value->email);
                            $listUser   .= "<b>$value->email</b> - ";
                        }
                        break;

                    default:
                        
                        break;
                }
                Yii::$app->session->setFlash('success', "Notify successful for ".rtrim($listUser, "- "));
                return $this->redirect(['/admin/root-admin/index']);
            } else {
                return $this->redirect(['/site/index']);
            }
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
        
    }
}
