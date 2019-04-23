<?php

namespace app\modules\admin\controllers;

use app\controllers\BaseController;
use app\helpers\Checks;
use app\models\UserTracking;

/**
 * ProductsController implements the CRUD actions for Products model.
 * This controller is used for ROOT ADMIN only
 */
class RootAdminController extends BaseController
{
    /**
     * @todo tracking all product with end_date = null
     */
    public function actionTrackingAll(){
        try {
            if(Checks::isRoot()){
                $mUserTracking = new UserTracking();
                $mUserTracking->trackingAll();
                $this->redirect(['/user/default/tracking-items']);
            } else {
                $this->redirect(['/site/index']);
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
                $this->redirect(['/user/default/tracking-items']);
            } else {
                $this->redirect(['/site/index']);
            }
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
}
