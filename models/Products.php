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
use app\helpers\Constants;

/**
 * This is the model class for table "products".
 *
 * @property string $id
 * @property string $name
 * @property string $link
 * @property int $seller
 * @property string $price
 * @property string $image
 * @property int $status
 * @property string $created_date
 */
class Products extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => Yii::t('app', 'Link'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    /*
     * remove trash of get params on url
     */
    public function handleUrl() {
        $parse          = parse_url($this->url);
        $domain         = str_replace("www.", "", $parse['host']);
        $aWebsiteDomain = Constants::$aWebsiteDomain;
        switch ($domain) {
            case $aWebsiteDomain[Constants::SHOPEE]:
                
                break;
            case $aWebsiteDomain[Constants::SENDO]:
                $this->url = strstr($this->url, ".html", true) . ".html";
                break;
            case $aWebsiteDomain[Constants::TIKI]:
                $this->url = strstr($this->url, ".html", true) . ".html";
                break;
            case $aWebsiteDomain[Constants::AMAZON]:
                
                break;
            case $aWebsiteDomain[Constants::EBAY]:
                
                break;

            default:
                
                break;
        }
    }
    
    
    
}
