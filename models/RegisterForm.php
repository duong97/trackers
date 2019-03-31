<?php

namespace app\models;

use Yii;
use yii\base\Model;
use \app\helpers\Constants;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class RegisterForm extends Model
{
    public $email;
    public $password;
    public $cpassword;
    public $first_name, $last_name;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password', 'cpassword', 'first_name', 'last_name'], 'required'],
            [['email'], 'email'],
            ['cpassword', 'compare', 'compareAttribute' => 'password'],
            ['password', 'string', 'length'=>[6,25], 
                'tooShort' => Yii::t("app", "Password must be between 6 and 25 characters long."),
                'tooLong'  => Yii::t("app", "Password must be between 6 and 25 characters long.")],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'first_name'    => Yii::t('app', 'First name'),
            'last_name'     => Yii::t('app', 'Last name'),
            'email'         => Yii::t('app', 'Email'),
            'password'      => Yii::t('app', 'Password'),
            'cpassword'     => Yii::t('app', 'Confirm Password'),
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function register()
    {
        $message = '';
        if ($this->validateNewUser($message)) {
            $this->createNewUser();
        }
    }

    public function validateNewUser(&$message){
        $model = Users::find()->where(['email' => $this->email])->one();
        
        if(!empty($model)){
            $message = ($model->status == Users::STT_ACTIVE) ? Yii::t('app', 'Email already exists!') : Yii::t('app', 'Email is under verifying!');
            return false;
        }
        return true;
    }
    
    public function createNewUser(){
        $user = new Users();
        $user->email            = $this->email;
        $user->created_date     = date('Y-m-d H:i:s');
        $user->last_access      = date('Y-m-d H:i:s');
        $user->salt             = md5($user->created_date);
//        $user->password         = md5(trim($this->password));
        $user->generatePassword($this->password);
        $user->first_name       = $this->first_name;
        $user->last_name        = $this->last_name;
        $user->role             = Constants::USER;
        $user->status           = Users::STT_VERIFYING;
        $user->ip               = Yii::$app->request->userIP;
        $user->save();
    }
}
