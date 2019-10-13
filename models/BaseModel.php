<?php

namespace app\models;

use Yii;
use app\models\ActionRoles;
use app\helpers\MyFormat;

class BaseModel extends \yii\db\ActiveRecord
{
    public function beforeSave($insert) {
        if($this->isNewRecord){
            if($this->hasAttribute('created_date') ){
                $this->created_date = empty($this->created_date) ? date('Y-m-d H:i:s') : $this->created_date;
            }
            if($this->hasAttribute('created_by') ){
                $cUid = MyFormat::getCurrentUid();
                $this->created_by = $cUid;
            }
            if($this->hasAttribute('updated_date') ){
                $this->updated_date = empty($this->updated_date) ? date('Y-m-d H:i:s') : $this->updated_date;
            }
        }
        return parent::beforeSave($insert);
    }
    
    public function can($action){
//        $session        = Yii::$app->session;
//        $aAccessAction  = $session->get('listAccessAction');
        $mActionRole    = new ActionRoles();
        $aAccessAction  = $mActionRole->getArrayAccess(Yii::$app->user->identity->role);
        $refClass       = new \ReflectionClass($this->className());
        $ctlName        = $refClass->getShortName() . 'Controller';
        if( isset($aAccessAction[$ctlName]) ){
            return in_array($action, $aAccessAction[$ctlName]);
        }
        return true;
    }
    
    public function getCreatedDate($format = MyFormat::datetime_format){
        if($this->hasAttribute('created_date') ){
            return date($format, strtotime($this->created_date));
        }
        return '';
    }
    
}
