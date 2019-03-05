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
    
    const notify_increase       = 1;
    const notify_decrease       = 2;
    const notify_both           = 3;
    
    public $newPassword, $cnewPassword;
    
    /*
     * get array notify type
     */
    public static function aNotifyType(){
        return [
            self::notify_increase => Yii::t('app', 'Prices increase'),
            self::notify_decrease => Yii::t('app', 'Prices decrease'),
            self::notify_both     => Yii::t('app', 'Both'),
        ];
    }

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
            [['is_notify_fb', 'is_notify_email', 'notify_type'], 'safe'],
            [['first_name', 'last_name'], 'required', 'on' => 'editProfile'],
            ['cnewPassword', 'compare', 'compareAttribute' => 'newPassword'],
            ['newPassword', 'string', 'length' => [6,25], 
                'tooShort' => Yii::t("app", "Password must be between 6 and 25 characters long"),
                'tooLong'  => Yii::t("app", "Password must be between 6 and 25 characters long")],
            ['cnewPassword', 'required', 'when' => function ($model) {
                return $model->newPassword != '';
            }, 'whenClient' => "function (attribute, value) {
                return $('#users-newpassword').val() != '';
            }"],
            [['newPassword', 'cnewPassword'], 'safe']
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
            'newPassword'   => Yii::t('app', 'New password'),
            'cnewPassword'  => Yii::t('app', 'Confirm new password'),
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
    
    public function generatePassword($pw){
        $this->password = md5(trim($pw));
    }
    
    public function validatePassword($password){
        $pw = md5(trim($password));
        return $pw === $this->password;
    }
    
    public function getFullName(){
        return $this->first_name . " " . $this->last_name;
    }
}
