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
use yii\helpers\Url;

/**
 * This is the model class for table "menus".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $display_order
 * @property int $is_show
 */
class Menus extends BaseModel
{
    public $withOrder;
    
    public static $aShow = [
        STATUS_ACTIVE   => 'Hiện',
        STATUS_INACTIVE => 'Ẩn',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'display_order', 'is_show'], 'safe'],
            [['name', 'url', 'is_show'], 'required', 'on'=>['create', 'update']],
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
            'url' => Yii::t('app', 'Url'),
            'display_order' => Yii::t('app', 'Thứ tự'),
            'is_show' => Yii::t('app', 'Hiện trên menu'),
        ];
    }
    
    public function beforeSave($insert) {
        $this->url = trim($this->url, '/');
        if(empty($this->display_order)){
            $this->display_order = Menus::find()->max('display_order')+1;
        }
        return parent::beforeSave($insert);
    }
    
    public function search($params)
    {
        $query = Menus::find();
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
        $query->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'url', $this->url])
        ->andFilterWhere(['is_show' => $this->is_show])
        ->andFilterWhere(['display_order' => $this->display_order]);
        return $dataProvider;
    }
    
    public function isShow(){
        $aShow = Menus::$aShow;
        return empty($aShow[$this->is_show]) ? '' : $aShow[$this->is_show];
    }
    
    public function getAll($isShow = true){
        $cond = $isShow ? ['is_show'=>STATUS_ACTIVE] : [];
        return Menus::find()->where($cond)->all();
    }
    
     /**
     * @todo get array menu for role
     */
    public function getArrayMenuAdmin(){
        $ret    = [];
        $models = Menus::find()
                ->where(['is_show'=>STATUS_ACTIVE])
                ->orderBy([
                    'display_order'=>SORT_ASC
                ])
                ->all();
        $pre    = empty($this->withOrder) ? '' : '1 - ';
        $ret[]  = [
                'label' => $pre.'Menu',
                'url'   => Url::to(['/admin/menus/index']),
            ];
        foreach ($models as $mMenu) {
            $pre = empty($this->withOrder) ? '' : $mMenu->display_order.' - ';
            $ret[] = [
                'label' => $pre.$mMenu->name,
                'url'   => Url::to(['/'.$mMenu->url]),
            ];
        }
        return $ret;
    }
}
