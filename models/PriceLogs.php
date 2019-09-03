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
use app\helpers\MyFormat;

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
    
    /**
     * @params url: product url on tiki, lazada, ...
     * @params formatData: format price and date
     */
    public function getByUrl($url, $formatData = false) {
        $prd        = new Products();
        $prd->url   = $url;
        $prd->handleUrl();
        $aLogs = PriceLogs::find()
                    ->joinWith('prd')
                    ->where(['products.url' => $prd->url])
                    ->orderBy(['updated_date'=>SORT_DESC])
                    ->all();
        $ret = [];
        foreach ($aLogs as $value) {
            if($formatData){
                $value->price = MyFormat::formatCurrency($value->price);
                $value->updated_date = MyFormat::formatDatetime($value->updated_date);
            }
            $ret[$value->id] = $value;
        }
        return $ret;
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
        $cond = is_array($aProductId) ? ['IN', 'product_id', $aProductId] : ['product_id'=>$aProductId];
        $aAll = PriceLogs::find()
                        ->alias('p')
                        ->where($cond)
                        ->andWhere('updated_date = (SELECT MAX(updated_date)
                                                    FROM price_logs
                                                    WHERE product_id = p.product_id)')
                        ->all();
        $ret = [];
        foreach ($aAll as $value) {
            $ret[$value->product_id] = $onlyPrice ? $value->price : $value;
        }
        return is_array($aProductId) ? $ret : $ret[$aProductId];
    }
    
    /**
     * @todo get data for chart (api chrome extension)
     */
    public function getChartData($aPriceLog){
        $aLabel         = [];
        $aCPrice        = [];
        $aPPrice        = [];
        $cPrice         = null;
        foreach ($aPriceLog as $log) {
            if($cPrice == null){
                $cPrice = $log->price;
            }
            $fDate      = date(MyFormat::date_format, strtotime($log->updated_date));
            $fTime      = date("H:i", strtotime($log->updated_date));
            $aLabel[]   = [$fDate, $fTime];
            $aCPrice[]  = MyFormat::numberOnly($cPrice);
            $aPPrice[]  = MyFormat::numberOnly($log->price);
        }
        $jsonLabel      = json_encode($aLabel);
        $jsonCPrice     = json_encode($aCPrice);
        $jsonPPrice     = json_encode($aPPrice);
        return [
            'label' => $jsonLabel,
            'cPrice' => $jsonCPrice,
            'pPrice' => $jsonPPrice
        ];
    }
    
    public function getRecommend($aPriceLog){
        if(empty($aPriceLog) || !is_array($aPriceLog)) return '';
        
        $result     = '';
        $avgPrice   = 0;
        $avgDays    = 0;
        $prevDate   = '';
        $countLog   = 0;
        foreach ($aPriceLog as $mPriceLogs) {
            $countLog++;
            $avgPrice       += $mPriceLogs->price;
            if( !empty($prevDate) ){
                $avgDays    += MyFormat::countDays($prevDate, $mPriceLogs->updated_date);
            }
            $prevDate        = $mPriceLogs->updated_date;
            
        }
        $avgPrice           /= (empty($countLog) ? 1 : $countLog);
        $avgDays            /= empty($countLog-1) ? 1 : ($countLog-1);
        $avgPrice            = MyFormat::formatCurrency($avgPrice);
        $avgDays             = MyFormat::formatDecimal($avgDays);
        $result             .= Yii::t('app', 'Average price').": $avgPrice<br>";
        $result             .= Yii::t('app', 'The average time changed').": $avgDays ". Yii::t('app', 'days');
        return $result;
    }
    
}
