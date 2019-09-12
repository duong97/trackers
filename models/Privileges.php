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
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "privileges".
 *
 * @property int $id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property int $relate_id user_id or rolle_id
 * @property int $type user, role
 */
class Privileges extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'privileges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['module', 'controller', 'action', 'relate_id', 'type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => 'Module',
            'controller' => 'Chức năng chính',
            'action' => 'Chức năng con',
            'relate_id' => 'ID liên quan',
            'type' => 'Loại',
        ];
    }
    
    public function search($params)
    {
        $query = Privileges::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 
                'pageSize'=> isset(Yii::$app->params['defaultPageSize']) ? Yii::$app->params['defaultPageSize'] : 10,
            ],
        ]);
        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        // We have to do some search... Lets do some magic
        $query->andFilterWhere(['like', 'name', $this->name]);
//        ->andFilterWhere([]);
        return $dataProvider;
    }
}
