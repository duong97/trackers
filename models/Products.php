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
use app\helpers\MyFormat;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\SupportedWebsites;

/**
 * This is the model class for table "products".
 *
 * @property string $id
 * @property string $name
 * @property string $seller_id
 * @property string $url
 * @property string $url_redirect
 * @property string $price
 * @property string $image
 * @property string $created_date
 */
class Products extends BaseModel
{
    
    public $numberTracking; // Number of people are currently tracking this product
    
    const STT_ACTIVE    = 1;
    const STT_INACTIVE  = 0;
    
    const TYPE_INCREASE = Users::notify_increase;
    const TYPE_DECREASE = Users::notify_decrease;
    
    public static $aStatus = [
        self::STT_ACTIVE   => 'Đang bán',
        self::STT_INACTIVE => 'Ngừng kinh doanh',
    ];
    
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
            [['name', 'url', 'url_redirect', 'price', 'image', 'status'], 'safe'],
            [['name', 'url', 'url_redirect', 'price', 'image'], 'required', 'on'=>'create']
        ];
    }
    
    /**
     * @todo relation seller
     */
    public function getRSeller(){
        return $this->hasOne(SupportedWebsites::className(), ['id' => 'seller_id']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => Yii::t('app', 'Product link'),
            'name' => Yii::t('app', 'Name'),
            'image' => Yii::t('app', 'Images'),
            'created_date' => Yii::t('app', 'Created Date'),
        ];
    }
    
    public function search($params)
    {
//        $query = Products::find()->where(['status'=>self::STT_ACTIVE]);
        $query = Products::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_date' => SORT_DESC,
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
        ->andFilterWhere(['like', 'price', $this->price]);
        return $dataProvider;
    }
    
    public function beforeSave($insert) {
        $this->slug = MyFormat::slugify($this->name);
        if($this->isNewRecord){
            $parse  = parse_url($this->url);
            $domain = str_replace("www.", "", $parse['host']);
            $models = SupportedWebsites::find()
                                ->where([
                                    'like',
                                    'url',
                                    $domain
                                ])->one();
            $this->seller_id = isset($models) ? $models->id : null;
        }
        return parent::beforeSave($insert);
    }
    
    /*
     * remove trash of get params on url
     */
    public function handleUrl($urlOut = "") {
        $url            = empty($urlOut) ? $this->url : $urlOut;
        if(empty($url)) return "";
        $parse          = parse_url($url);
        $domain         = str_replace("www.", "", $parse['host']);
        $aWebsiteDomain = Constants::$aWebsiteDomain;
        switch ($domain) {
            case $aWebsiteDomain[Constants::SHOPEE]:
                $aUrlParams = explode("-i.", $url);
                $shopId     = '';
                $itemId     = '';
                if( count($aUrlParams) < 2){ // https://shopee.vn/product/82239615/1826571737?smtt=0.0.9&fbclid=IwAR1fl1xYSA_WBFDrxSk8fi4EMY5UTIGti0FyY1EMXrjVK_o5kVZ4iH9Wbjo
                    $aUrlParams = explode('/', $url);
                    $shopId     = isset($aUrlParams[4]) ? $aUrlParams[4] : '';
                    $itemId     = isset(explode('?', $aUrlParams[5])[0]) ? explode('?', $aUrlParams[5])[0] : '';
                } else {
                    $aId        = explode(".", $aUrlParams[1]);
                    $shopId     = isset($aId[0]) ? $aId[0] : '';
                    $itemId     = isset($aId[1]) ? $aId[1] : '';
                }
                $url        = "https://shopee.vn/product/{$shopId}/{$itemId}";
                break;
            case $aWebsiteDomain[Constants::SENDO]:
                $url       = strstr($url, ".html", true) . ".html";
                break;
            case $aWebsiteDomain[Constants::TIKI]:
                $url       = strstr($url, ".html", true) . ".html";
                break;
            case $aWebsiteDomain[Constants::AMAZON]:
                
                break;
            case $aWebsiteDomain[Constants::EBAY]:
                
                break;

            default:
                
                break;
        }
        $this->url = $url;
        return $url;
    }
    
    /**
     * @todo get array product by array id
     * @param array $aProductId array product id
     * @return [product_id => model_products]
     */
    public function getListProductById($aProductId){
        $models = Products::find()->where(['in', 'id', $aProductId])->all();
        $ret    = [];
        foreach ($models as $value) {
            $ret[$value->id] = $value;
        }
        return $ret;
    }
    
    public function getAll($returnObject = true){
        $models = Products::find()->where(['status'=>self::STT_ACTIVE])->all();
        $ret    = [];
        foreach ($models as $value) {
            $ret[$value->id] = $returnObject ? $value : $value->id;
        }
        return $ret;
    }
    
    /**
     * @todo get detail url of product
     */
    public function getDetailUrl(){
        return Url::to(['/product/action/detail', 'url'=> $this->url]);
    }
    
    /**
     * @todo get product name with url
     */
    public function getProductNameWithLink(){
        return Html::a($this->name, $this->getDetailUrl());
    }
    
    /**
     * @todo get seller
     */
    public function getSeller(){
        $mSeller = isset($this->rSeller) ? $this->rSeller : null;
        if($mSeller){
            return Html::img($mSeller->getLogoUrl(), ['alt'=>$mSeller->name, 'class'=>'logo']);
        }
        return false;
    }
}
