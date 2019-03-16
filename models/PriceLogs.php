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
 * This is the model class for table "price_logs".
 *
 * @property string $id
 * @property string $link link of products without data ($_GET, ...)
 * @property string $price
 * @property string $updated_date
 */
class PriceLogs extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'price_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['link'], 'string'],
//            [['updated_date'], 'safe'],
//            [['price'], 'string', 'max' => 50],
        ];
    }
    
    /*
     * relations with Products
     */
    public function getPrd()
    {
        return $this->hasMany(Products::className(), ['id' => 'product_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'link' => Yii::t('app', 'Link'),
            'price' => Yii::t('app', 'Price'),
            'updated_date' => Yii::t('app', 'Updated Date'),
        ];
    }
    
    public function getByUrl($url) {
        $prd        = new Products();
        $prd->url   = $url;
        $prd->handleUrl();
        return PriceLogs::find()
                    ->joinWith('prd')
                    ->where(['products.url' => $prd->url])
                    ->all();
    }
    
    /*
     * get all data to array with key = product_id
     */
    public function getAll(){
        $aAll = PriceLogs::find()->all();
        $ret  = [];
        foreach ($aAll as $l) {
            $ret[$l->product_id][] = $l;
        }
        return $ret;
    }
    
    /**
     * @todo get array last price of list product
     * @return [ product_id => price ]
     */
    public function getArrayLastPrice($aProductId, $onlyPrice = true){
        $aAll = PriceLogs::find()
                ->where(['IN', 'product_id', $aProductId])
                ->orderBy('updated_date ASC')
                ->all();
        $ret    = [];
        $aSLogs = [];
        foreach ($aAll as $l) {
            $aSLogs[$l->product_id][] = $l;
        }
        foreach ($aSLogs as $p_id => $aLogs) {
//            $lastLogs = array_slice( $aLogs, -1, 1, TRUE );
            $lastLogs   = end( $aLogs );
            $ret[$p_id] = $onlyPrice ? $lastLogs->price : $lastLogs;
        }
        return $ret;
    }
}
