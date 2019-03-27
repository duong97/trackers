<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

use app\helpers\Checks;
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
 * @property string $social_id
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
    
    const plf_facebook = 1;
    
    public $newPassword, $cnewPassword;
    
    public static $aPlatform = [
        self::plf_facebook => 'facebook'
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
            [['email', 'password', 'salt', 'first_name', 'last_name', 'status', 'role', 'last_access', 'created_date'], 'safe'],
            [['email', 'password', 'first_name', 'last_name'], 'required', 'on' => [Yii::$app->params['SCENARIO_UPDATE'], Yii::$app->params['SCENARIO_CREATE']]],
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
            [['newPassword', 'cnewPassword'], 'safe'],
            ['email', 'unique'],
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
    
    public function createUser(){
        $this->status       = Checks::isAdmin() ? Users::STT_ACTIVE : Users::STT_VERIFYING;
        $this->role         = Checks::isRoot() ? $this->role : Constants::USER;
        $this->created_date = date('Y-m-d H:i:s');
        $this->last_access  = date('Y-m-d H:i:s');
        $this->salt         = md5($this->created_date);
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
        $mActionRole = new ActionRoles();
        $aCA         = $mActionRole->getArrayAccess($this->role);
        $session->set('listAccessAction', $aCA);

        $aMenu       = $mActionRole->getArrayMenu($this->role);
        $session->set('listMenu', $aMenu);

        $this->last_access = date('Y-m-d H:i:s');
        $this->update();
    }
    
    public function search($params)
    {
        $query = Users::find();
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
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // We have to do some search... Lets do some magic
        $query->andFilterWhere(['like', 'first_name', $this->first_name])
        ->andFilterWhere(['like', 'last_name', $this->last_name])
        ->andFilterWhere(['like', 'email', $this->email]);
        return $dataProvider;
    }
}
