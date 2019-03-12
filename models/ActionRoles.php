<?php
/**
 * Anivia *
 *	     __.oOo.__
 *	    /'(  _  )`\
 *	   / . \/^\/ . \
 *	  /  _)_`-'_(_  \
 *	 /.-~   ).(   ~-.\
 *	/'     /\_/\     `\
 *	     . "-V-"
 */

namespace app\models;

use app\helpers\Constants;
use Yii;

/**
 * This is the model class for table "action_roles".
 *
 * @property int $id
 * @property int $role_id
 * @property int $controller_id
 * @property string $actions
 * @property int $can_access
 */
class ActionRoles extends BaseModel
{
    const access_allow      = 1;
    const access_deny       = 2;
    
    public static $aAccess = [
        self::access_allow => 'Allow',
        self::access_deny  => 'Deny'
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'controller_id', 'can_access'], 'integer'],
            [['actions'], 'string'],
        ];
    }
    
    /**
     * relation
     */
    public function getRController(){
        return $this->hasOne(Controllers::className(), ['id' => 'controller_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'role_id' => Yii::t('app', 'Roles ID'),
            'controller_id' => Yii::t('app', 'Controller ID'),
            'actions' => Yii::t('app', 'Actions'),
            'can_access' => Yii::t('app', 'Can Access'),
        ];
    }
    
    public function getRole(){
        $aRole = Constants::$aRole;
        return isset($aRole[$this->role_id]) ? $aRole[$this->role_id] : '';
    }
    
    public function getAccess(){
        $aAccess = self::$aAccess;
        return isset($aAccess[$this->can_access]) ? $aAccess[$this->can_access] : '';
    }
    
    /**
     * @todo get array controller and action that current user can access
     * @param type $role_id role id
     */
    public function getArrayAccess($role_id){
        $models = ActionRoles::find()
                ->alias('ar')
                ->joinWith('rController', 'rController.id = controller.id')
                ->where(['ar.role_id' => $role_id])->all();
        $ret    = [];
        foreach ($models as $value) {
            $aAction        = explode(',', $value->actions);
            $cName          = isset($value->rController['controller_name']) ? $value->rController->controller_name : '';
            $ret[$cName]    = array_map('trim', $aAction);
        }
        return $ret;
    }
}
