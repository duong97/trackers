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
 * This is the model class for table "supported_websites".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $currency
 * @property int $check_time
 * @property string $logo
 * @property int $status
 */
class SupportedWebsites extends BaseModel
{
    const STT_GOOD              = 1;
    const STT_MAINTENANCE       = 2;
    const STT_STOP_SUPPORTING   = 3;
    
    const CURRENCY_VN           = 1;
    const CURRENCY_US           = 2;
    
    public static $aStatus = [
        self::STT_GOOD            => 'Good',
        self::STT_MAINTENANCE     => 'Is maintained',
        self::STT_STOP_SUPPORTING => 'Stop supporting',
    ];
    
    public static $aStatusCss = [
        self::STT_GOOD            => 'label label-success',
        self::STT_MAINTENANCE     => 'label label-warning',
        self::STT_STOP_SUPPORTING => 'label label-danger',
    ];
    
    public static $aCurrency = [
        self::CURRENCY_VN => '₫',
        self::CURRENCY_US => '$',
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supported_websites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url', 'currency', 'check_time', 'status'], 'safe'],
            [['name', 'url', 'currency', 'check_time', 'status'], 'required', 'on' => [Yii::$app->params['SCENARIO_CREATE'], Yii::$app->params['SCENARIO_UPDATE']]],
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
            'currency' => Yii::t('app', 'Currency'),
            'check_time' => Yii::t('app', 'Check Time'),
            'logo' => Yii::t('app', 'Logo'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
    
    public function search($params)
    {
        $query = SupportedWebsites::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
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
        ->andFilterWhere(['like', 'url', $this->url]);
        return $dataProvider;
    }
    
    public function getStatus(){
        $aStatus = SupportedWebsites::$aStatus;
        return isset($aStatus[$this->status]) ? $aStatus[$this->status] : "";
    }
    
    public function getCurrency(){
        $aCurrency = SupportedWebsites::$aCurrency;
        return isset($aCurrency[$this->currency]) ? $aCurrency[$this->currency] : "";
    }
    
    public function getAll(){
        $models = SupportedWebsites::find()->all();
        $ret    = [];
        foreach ($models as $value) {
            $ret[$value->id] = $value;
        }
        return $ret;
    }
}
