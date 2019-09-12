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

use Yii;

/**
 * This is the model class for table "controllers".
 *
 * @property int $id
 * @property string $controller_name
 * @property string $display_name
 * @property string $module_name
 * @property string $actions
 */
class Controllers extends BaseModel
{
    
    public static $aModule = [
        'admin' => 'Admin'
    ];
    
    public static $aDefaultActionName = [
        'index'     => 'Trang list',
        'view'      => 'Xem',
        'create'    => 'Tạo mới',
        'update'    => 'Cập nhật',
        'delete'    => 'Xóa',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'controllers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['controller_name', 'display_name', 'module_name'], 'required'],
            ['controller_name', 'validateController'],
            ['controller_name', 'unique', 'targetAttribute' => 'controller_name'],
            [['controller_name', 'display_name', 'module_name', 'actions'], 'safe'],
        ];
    }
    
    /*
     * Custom Validate
     */
    public function validateController($attribute, $params)
    {
        if ( !in_array($this->$attribute, array_keys($this->getAllCA())) ) {
            $this->addError($attribute, 'This controller does not exists!');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'controller_name' => 'Controller Name',
            'display_name' => 'Tên chức năng',
            'module_name' => 'Module',
            'actions' => 'Các chức năng con',
        ];
    }
    
    /**
     * @todo get all controller and action
     * @return array [ CNameController => [action1, action2] ]
     */
    public function getAllCA($module = 'admin')
    {
        $controllerlist = [];
        $controllerPath = "../modules/{$module}/controllers/";
        $handle         = opendir($controllerPath);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                    $controllerlist[] = $file;
                }
            }
            closedir($handle);
        }
        asort($controllerlist);
        $fulllist = [];
        foreach ($controllerlist as $controller):
            $handle = fopen($controllerPath . $controller, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (preg_match('/public function action(.*?)\(/', $line, $display)):
                        if (strlen($display[1]) > 2):
                            $fulllist[substr($controller, 0, -4)][] = strtolower($display[1]);
                        endif;
                    endif;
                }
            }
            fclose($handle);
        endforeach;
        return $fulllist;
    }
    
    /**
     * @todo get array all controller in db
     * @return [controller_id => value]
     * @param $onlyName if true -> [controller_id => name], else [controller_id => object]
     */
    public function getAll($onlyName = false) {
        $models = Controllers::find()->all();
        $ret    = [];
        foreach ($models as $value) {
            $ret[$value->id] = $onlyName ? $value->controller_name : $value;
        }
        return $ret;
    }
    
    /**
     * @todo handle save list action on create, update
     */
    public function handleBeforeSave(){
        $aActionWithoutKey = [];
        foreach ($this->actions as $key => $value) {
            $aActionWithoutKey[] = [
                'key'   => $key,
                'value' => $value,
            ];
        }
        $this->actions = json_encode($aActionWithoutKey); // encode with key cần decode 2 lần ?
    }
    
    /**
     * @todo get array action, key is controller name
     * @return string
     */
    public function getArrayAction(){
        $result         = [];
        $aController    = Controllers::find()->all();
        foreach ($aController as $mController) {
            $aAction    = json_decode($mController->actions, true);
            foreach ($aAction as $data) {
                $key    = empty($data['key']) ? '' : $data['key'];
                $value  = empty($data['value']) ? '' : $data['value'];
                $result[$mController->controller_name][] = $key.' - '.$value;
            }
        }
        return $result;
    }
}
