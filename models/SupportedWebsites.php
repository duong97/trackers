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
use yii\helpers\Html;

/**
 * This is the model class for table "supported_websites".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int $currency
 * @property int $check_time
 * @property string $note
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
    
    public $homepageLogo, $icon;
    
    public static $aStatus = [
        self::STT_GOOD            => 'Tốt',
        self::STT_MAINTENANCE     => 'Đang bảo trì',
        self::STT_STOP_SUPPORTING => 'Ngừng hỗ trợ',
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
            [['name', 'url', 'currency', 'check_time', 'status', 'logo', 'note', 'homepageLogo', 'icon'], 'safe'],
            [['name', 'url', 'currency', 'check_time', 'status'], 'required', 'on' => [Yii::$app->params['SCENARIO_CREATE'], Yii::$app->params['SCENARIO_UPDATE']]],
            [['icon', 'homepageLogo'], 'required', 'on' => [Yii::$app->params['SCENARIO_CREATE']]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'name'          => Yii::t('app', 'Name'),
            'url'           => Yii::t('app', 'Url'),
            'currency'      => Yii::t('app', 'Currency'),
            'check_time'    => Yii::t('app', 'Check Time'),
            'note'          => Yii::t('app', 'Note'),
            'logo'          => Yii::t('app', 'Logo'),
            'status'        => Yii::t('app', 'Status'),
        ];
    }
    
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $logo           = $this->getFullPath().$this->logo;
        $homepageLogo   = $this->getFullPath().str_replace('_logo', '', $this->logo);
        file_exists($logo) ? unlink($logo):'';
        file_exists($homepageLogo) ? unlink($homepageLogo):'';
        return true;
    }
    
    /**
     * @todo get full path /var/www/html/trackers/web/images/support_website
     */
    public function getFullPath(){
        return Yii::getAlias('@root').'/web/images/support_website/';
    }
    
    /**
     * @todo get relative path for image src /web/images/support_website/tiki_logo.png
     */
    public function getRelativePath(){
        return Yii::getAlias('@web').'/images/support_website/';
    }
    
    public function getLogoUrl(){
        return $this->getRelativePath() . $this->logo;
    }
    
    public function getHomepageLogoUrl(){
        return $this->getRelativePath() . str_replace('_logo', '', $this->logo);
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
        // If use where only, query cant filter empty condition
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'check_time', $this->check_time])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['currency' => $this->currency])
            ->andFilterWhere(['status' => $this->status]);
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
    
    /**
     * @todo get array logo of seller
     */
    public static function getArraySeller(){
        $mSW = new SupportedWebsites();
        $aSeller = $mSW->getAll();
        $ret = [];
        foreach ($aSeller as $seller_id => $item) {
            $ret[$seller_id] = Html::img($item->getLogoUrl(), ['alt'=>$item->name, 'class'=>'logo']);
        }
        return $ret;
    }
}
