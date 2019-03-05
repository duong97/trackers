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
            [['currency', 'check_time', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['url', 'logo'], 'string', 'max' => 255],
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
    
    public function getStatus(){
        $aStatus = SupportedWebsites::$aStatus;
        return isset($aStatus[$this->status]) ? $aStatus[$this->status] : "";
    }
    
    public function getCurrency(){
        $aCurrency = SupportedWebsites::$aCurrency;
        return isset($aCurrency[$this->currency]) ? $aCurrency[$this->currency] : "";
    }
}
