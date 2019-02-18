<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property int $role
 * @property string $ip
 * @property int $status
 * @property string $last_access
 * @property string $created_date
 */
class BaseModel extends \yii\db\ActiveRecord
{
    public function beforeSave($insert) {
        if($this->isNewRecord){
            if($this->hasAttribute('created_date') ){
                $this->created_date = (empty($this->created_date) ? date('Y-m-d H:i:s') : $this->created_date);
            }
            if($this->hasAttribute('updated_date') ){
                $this->updated_date = (empty($this->updated_date) ? date('Y-m-d H:i:s') : $this->updated_date);
            }
        }
        return parent::beforeSave($insert);
    }
}
