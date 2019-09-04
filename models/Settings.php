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
use app\helpers\MyFormat;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property int $type
 * @property string $code
 * @property string $value
 * @property string $created_date
 */
class Settings extends BaseModel
{
    const TYPE_GENERAL  = 1;
    const TYPE_SYSTEM   = 2;
    
    const AVG_DAYS_RECOMMEND    = 'AVG_DAYS_RECOMMEND'; // Mua sp 10/11.. ngày thay đổi giá 1 lần
    const ORDER_PRICE_RECOMMEND = 'ORDER_PRICE_RECOMMEND'; // Mua lúc giá thấp nhất, nhì
    const SYSTEM_MAINTENANCE    = 'SYSTEM_MAINTENANCE'; // Bảo trì hệ thống
    
    public $avgDaysRecommend;
    public $orderPriceRecommend;
    public $systemMaintenance;
    
    public static $aCode = [
        Settings::AVG_DAYS_RECOMMEND,
        Settings::SYSTEM_MAINTENANCE,
        Settings::ORDER_PRICE_RECOMMEND,
    ];
    
    public static $aType = [
        Settings::TYPE_GENERAL => 'Tổng quát',
        Settings::TYPE_SYSTEM  => 'Hệ thống',
    ];

        /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'value', 'type'], 'safe'],
            [['avgDaysRecommend','systemMaintenance','orderPriceRecommend'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'code' => Yii::t('app', 'Code'),
            'value' => Yii::t('app', 'Value'),
            'created_date' => Yii::t('app', 'Created Date'),
            'avgDaysRecommend' => 'Ngày trung bình khuyến nghị',
            'orderPriceRecommend' => 'Giá khuyến nghị (thấp nhất, nhì,..)',
            'systemMaintenance' => 'Bảo trì hệ thống',
        ];
    }
    
    public function search($params)
    {
        $query = Settings::find();
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
    
    public function getAll(){
        $aSettings  = Settings::find()->all();
        $result     = [];
        foreach ($aSettings as $mSetting) {
            $result[$mSetting->id] = $mSetting;
        }
        return $result;
    }
    
    /**
     * save all setting
     */
    public function saveSettings(){
        $aSettings  = Settings::find()->all();
        $aFSetting  = [];
        foreach ($aSettings as $mSetting) {
            $aFSetting[$mSetting->code] = $mSetting;
        }
        foreach (Settings::$aCode as $code) {
            $mSetting           = '';
            if( empty($aFSetting[$code]) ){ // Nếu là setting mới (chưa lưu)
                $mSetting       = new Settings();
                $mSetting->code = $code;
            } else { // Nếu setting đã lưu rồi thì update value
                $mSetting       = $aFSetting[$code];
            }
            $varName            = MyFormat::toCamelCase($code, '_');
            $formValue          = isset($this->{$varName}) ? $this->{$varName} : '';
            $mSetting->type     = empty($this->type) ? Settings::TYPE_GENERAL : $this->type;
            $mSetting->value    = $formValue;
            $mSetting->isNewRecord ? $mSetting->save() : $mSetting->update();
        }
    }
}
