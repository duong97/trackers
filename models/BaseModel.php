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
    
}
