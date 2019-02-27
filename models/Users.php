<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

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
class Users extends BaseModel
{
    const STT_VERIFYING         = 1;
    const STT_ACTIVE            = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'salt', 'first_name', 'last_name', 'status', 'last_access', 'created_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'password'      => Yii::t('app', 'Password'),
            'first_name'    => Yii::t('app', 'First Name'),
            'last_name'     => Yii::t('app', 'Last Name'),
            'email'         => Yii::t('app', 'Email'),
            'phone'         => Yii::t('app', 'Phone'),
            'role'          => Yii::t('app', 'Role'),
            'ip'            => Yii::t('app', 'Ip'),
            'last_access'   => Yii::t('app', 'Last Access'),
            'created_date'  => Yii::t('app', 'Created Date'),
        ];
    }
    
    public function getUserByEmail($email){
        return Users::find()->where(['email' => $email])->one();
    }
    
    public function getVerifyingUserByEmail($email){
        return Users::find()->where(['email' => $email, 'status'=>Users::STT_VERIFYING])->one();
    }
    
    public function getUrlConfirm(){
        $q = $this->id ."i". md5($this->email);
        return Yii::$app->request->serverName.Url::to(["site/registration-result", 'q'=> $q]);
    }
    
    public function validatePassword($password){
        $pw = md5(trim($password));
        return $pw === $this->password;
    }
    
    public function getFullName(){
        return $this->first_name . " " . $this->last_name;
    }
}
