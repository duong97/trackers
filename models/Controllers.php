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
            ['controller_name', 'unique', 'targetAttribute' => 'controller_name']
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
            'id' => Yii::t('app', 'ID'),
            'controller_name' => Yii::t('app', 'Controller Name'),
            'display_name' => Yii::t('app', 'Display Name'),
            'module_name' => Yii::t('app', 'Module Name'),
            'actions' => Yii::t('app', 'Actions'),
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
}
