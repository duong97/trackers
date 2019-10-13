<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

use app\helpers\Checks;
use app\helpers\Constants;
use app\helpers\MyFormat;
use app\models\Menus;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $tmp_password
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $fb_id
 * @property string $zalo_id
 * @property string $is_notify_browser
 * @property string $is_notify_fb
 * @property string $is_notify_zalo
 * @property string $is_notify_email
 * @property string $subscription
 * @property string $zalo_access_token
 * @property string $notify_type
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
    
    const NOTIFY_BROWSER        = 1;
    const NOTIFY_FB             = 2;
    const NOTIFY_ZALO           = 3;
    const NOTIFY_EMAIL          = 4;
    
    const PLATFORM_FACEBOOK = 1;
    const PLATFORM_GOOGLE   = 2;
    const PLATFORM_OTHERS   = 3;
    
    const DEVICE_DESKTOP        = 1;
    const DEVICE_MOBILE         = 2;
    
    public $newPassword, $cnewPassword;
    
    public static $aPlatform = [
        self::PLATFORM_FACEBOOK => 'Facebook',
        self::PLATFORM_GOOGLE   => 'Google',
        self::PLATFORM_OTHERS   => 'Others',
    ];
    
    public static $aDevice = [
        self::DEVICE_DESKTOP => 'Desktop',
        self::DEVICE_MOBILE  => 'Mobile',
    ];
    
    public static $aNotifyPlatformType = [
        self::NOTIFY_BROWSER        => 'Notify Browser',
        self::NOTIFY_FB             => 'Notify Facebook',
        self::NOTIFY_ZALO           => 'Notify Zalo',
        self::NOTIFY_EMAIL          => 'Notify Email'
    ];
    
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
    
    /*
     * get array status of user
     */
    public static $aStatus = [
        self::STT_VERIFYING => 'Verifying',
        self::STT_ACTIVE => 'Active',
    ];
    
    public static $aStatusCss = [
        self::STT_VERIFYING => 'label label-warning',
        self::STT_ACTIVE => 'label label-success',
    ];

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
            [['email', 'password', 'tmp_password', 'first_name', 'last_name', 'status', 'role', 'last_access', 'created_date'], 'safe'],
            [['email', 'password', 'first_name', 'last_name'], 'required', 'on' => [Yii::$app->params['SCENARIO_CREATE']]],
            [['is_notify_browser', 'is_notify_fb', 'is_notify_zalo', 'is_notify_email', 'subscription', 'zalo_access_token', 'notify_type'], 'safe'],
            [['fb_id', 'zalo_id'], 'safe'],
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
            [['newPassword', 'cnewPassword'], 'safe'],
            ['email', 'unique', 'on' => [Yii::$app->params['SCENARIO_CREATE']]],
            ['email', 'required', 'on' => ['forgotPassword']],
            [['email'], 'email'],
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
            'ip'            => Yii::t('app', 'IP'),
            'last_access'   => Yii::t('app', 'Last Access'),
            'created_date'  => Yii::t('app', 'Created Date'),
            'newPassword'   => Yii::t('app', 'New password'),
            'cnewPassword'  => Yii::t('app', 'Confirm new password'),
            'fb_id'         => 'Facebook ID',
            'zalo_id'       => 'Zalo ID',
            'is_notify_fb'  => 'Thông báo qua Facebook',
            'is_notify_zalo'=> 'Thông báo qua Zalo',
            'is_notify_email'=> 'Thông báo qua Email',
            'notify_type'   => 'Loại thông báo',
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
        return Url::to(["/site/registration-result", 'q'=> $q], true);
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
    
    public function createUser(){
        $this->status       = Checks::isAdmin() ? Users::STT_ACTIVE : Users::STT_VERIFYING;
        $this->role         = Checks::isRoot() ? $this->role : Constants::USER;
        $this->created_date = date('Y-m-d H:i:s');
        $this->last_access  = date('Y-m-d H:i:s');
        $this->tmp_password = null;
        $this->ip           = Yii::$app->request->userIP;
        $this->generatePassword($this->password);
        $this->save();
    }
    
    /**
     * @todo init session before login (by form or cookie)
     * @param type $model model Users
     */
    public function initSessionBeforeLogin(){
        $session     = Yii::$app->session;
        $mMenu       = new Menus();
//        $aCA         = $mActionRole->getArrayAccess($this->role);
//        $session->set('listAccessAction', $aCA);

//        $aMenu       = $mActionRole->getArrayMenu($this->role);
        $aMenu       = $mMenu->getArrayMenuAdmin();
        $this->initCookieAdmin();
        $session->set('listMenu', $aMenu);

        $this->last_access = date('Y-m-d H:i:s');
        $this->update();
    }
    
    public function initCookieAdmin(){
        $cookies = Yii::$app->response->cookies;
        if($this->isAdmin()){
            // add a new cookie to the response to be sent
            $cookies->add(new \yii\web\Cookie([
                'name' => COOKIE_ADMIN_NAME,
                'value' => md5($this->id),
            ]));
        } else {
            $cookies->remove(COOKIE_ADMIN_NAME);
            // equivalent to the following
            unset($cookies[COOKIE_ADMIN_NAME]);
        }
    }
    
    public function search($params)
    {
        $query = Checks::isRoot() ? Users::find() : Users::find()->where(['role'=> Constants::USER]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => [
//                'defaultOrder' => [
//                    'created_date' => SORT_DESC,
//                ]
//            ],
            'pagination' => [ 
                'pageSize'=> isset(Yii::$app->params['defaultPageSize']) ? Yii::$app->params['defaultPageSize'] : 10,
            ],
        ]);
        // No search? Then return data Provider
//        if (!($this->load($params) && $this->validate())) {
        if (!($this->load($params))) {
            return $dataProvider;
        }
        // We have to do some search... Lets do some magic
        $query->andFilterWhere(['like', 'first_name', $this->first_name])
                ->andFilterWhere(['like', 'last_name', $this->last_name])
                ->andFilterWhere(['like', 'ip', $this->ip])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['status'=> $this->status])
                ->andFilterWhere(['DATE(last_access)'=> MyFormat::formatSqlDate($this->last_access)])
                ->andFilterWhere(['DATE(created_date)'=> MyFormat::formatSqlDate($this->created_date)]);
        return $dataProvider;
    }
    
    /**
     * @todo get array user by array id
     * @param array $aUserId array user id
     * @return [user_id => model_users]
     */
    public function getListUserById($aUserId){
        $models = Users::find()->where(['in', 'id', $aUserId])->all();
        $ret    = [];
        foreach ($models as $value) {
            $ret[$value->id] = $value;
        }
        return $ret;
    }
    
    /**
     * @todo get model current user
     */
    public function getCurrentUser(){
        if (!Yii::$app->user->isGuest) {
            return Users::findOne(Yii::$app->user->id);
        }
        return null;
    }
    
    /**
     * @todo is link to a zalo account
     */
    public function isLinkToZalo(){
        return !empty($this->zalo_id);
    }
    
    /**
     * @todo check if user is root
     */
    public function isRoot(){
        return ($this->role == Constants::ROOT);
    }
    
    /**
     * @todo check if user is admin
     */
    public function isAdmin(){
        return ($this->role == Constants::ADMIN || $this->role == Constants::ROOT);
    }
    
    /**
     * @todo 
     */
    public function getEmailWithLabel(){
        $ret = $this->email;
        if( $this->isRoot() ){
            $ret .= ' <label class="label label-primary">Root</label>';
        } elseif( $this->isAdmin() ){
            $ret .= ' <label class="label label-info">Admin</label>';
        }
        return $ret;
    }
    
    /**
     * @todo get list user notify by browser, fb, zalo, email...
     */
    public function getListUserByNotifyType($notifyType = ''){
        $aCond      = [];
        switch ($notifyType) {
            case self::NOTIFY_BROWSER:
                $aCond = [
                    'and',
                    ['is_notify_browser' => 1],
                    ['is not', 'subscription', null]
                ];
                break;
            case self::NOTIFY_ZALO:
                $aCond = [
                    'and',
                    ['is_notify_zalo' => 1],
                    ['is not', 'zalo_id', null]
                ];
                break;
            case self::NOTIFY_EMAIL:
                $aCond = ['is_notify_email' => 1];
                break;

            default:
                $aCond = [
                    'or',
                    [
                        'and',
                        ['is_notify_browser' => 1],
                        ['is not', 'subscription', null]
                    ],
                    [
                        'and',
                        ['is_notify_zalo' => 1],
                        ['is not', 'zalo_id', null]
                    ],
                    ['is_notify_email' => 1],
                ];
                break;
        }
        $models = Users::find()->where($aCond)->all();
        $result = [];
        foreach ($models as $value) {
            if($value->is_notify_browser && !empty($value->subscription)){
                $result[self::NOTIFY_BROWSER][$value->id] = $value;
            } 
            if($value->is_notify_zalo && !empty ($value->zalo_id)){
                $result[self::NOTIFY_ZALO][$value->id] = $value;
            }
            if($value->is_notify_email){
                $result[self::NOTIFY_EMAIL][$value->id] = $value;
            }
        }
        return $result;
    }
    
}
