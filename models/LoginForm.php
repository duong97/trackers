<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\UserTracking;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'email'         => Yii::t('app', 'Email'),
            'password'      => Yii::t('app', 'Password'),
            'rememberMe'    => Yii::t('app', 'Remember me'),
        ];
    }

//    public function validate(){
//        if (!$this->hasErrors()) {
//            $user = $this->getUser();
//        }
//    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $this->beforeLogin();
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        
        if ($this->_user === false) {
//            $this->_user = (new Users())->getUserByEmail($this->email);
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
    
    /*
     * Update last access of user
     */
    public function beforeLogin(){
        $model                  = Users::find()->where(['id' => $this->_user->id])->one();
        $session                = Yii::$app->session;
        $userTracking           = new UserTracking();
        $userTracking->user_id  = $this->_user->id;
        $aTrackingItems         = $userTracking->getUserTrackingItems();
        $session->set('aTrackingItems', $aTrackingItems);
        if($model){
            $model->last_access = date('Y-m-d H:i:s');
            $model->update();
        }
    }
}
