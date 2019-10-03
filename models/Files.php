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
 * This is the model class for table "files".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $belong_id
 * @property int $order
 * @property int $status
 * @property int $created_by
 * @property string $created_date
 */
class Files extends BaseModel
{
    const STATUS_ACTIVE  = 1;
    const STATUS_HIDE    = 2;
    const STATUS_DELETED = 3;
    
    const TYPE_BLOG         = 1;
    const TYPE_BLOG_CONTENT = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','type', 'belong_id', 'order', 'status', 'created_by', 'created_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'belong_id' => Yii::t('app', 'Belong ID'),
            'order' => Yii::t('app', 'Order'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    public function beforeSave($insert) {
        $this->status = self::STATUS_ACTIVE;
        return parent::beforeSave($insert);
    }
    
    public function search($params)
    {
        $query = Files::find();
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
        $query->andFilterWhere(['like', 'name', $this->name]);
//        ->andFilterWhere([]);
        return $dataProvider;
    }
}
