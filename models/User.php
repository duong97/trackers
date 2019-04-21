<?php

namespace app\models;

use app\helpers\Constants;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $email;
    public $password;
    public $salt;
    public $auth_key;
    public $first_name, $last_name;
    public $platform, $fb_id, $zalo_id;
    public $is_notify_browser, $is_notify_fb, $is_notify_zalo,$is_notify_email;
    public $subscription, $zalo_access_token, $notify_type;
    public $role, $status, $ip, $last_access, $created_date;
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = Users::find()->where(['id' => $id])->one();
        return !empty($user) ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
//        $user = Users::find()->where(['email' => $email, 'status'=>Users::STT_ACTIVE])->one();
        $user = Users::find()->where(['email' => $email])->one();
        return empty($user) ? null : new static($user);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        $pw = md5(trim($password));
        return ($pw === $this->password && $this->status = Users::STT_ACTIVE);
    }
    
    /**
     * @todo check if user is admin
     */
    public function isAdmin(){
        return ($this->role == Constants::ADMIN);
    }
}
