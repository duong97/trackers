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
use app\models\UserTracking;

/**
 * This is the model class for table "products".
 *
 * @property string $id
 * @property string $name
 * @property string $seller_id
 * @property string $category_id
 * @property string $url
 * @property string $url_redirect
 * @property string $price
 * @property string $image
 * @property string $created_date
 */
class Products extends BaseModel
{
    
    public $numberTracking; // Number of people are currently tracking this product
    
    const STT_ACTIVE            = 1;
    const STT_INACTIVE          = 0;
    
    const CATEGORY_FASHION      = 1;
    const CATEGORY_HOUSEWARE    = 2;
    const CATEGORY_ACCESSORIES  = 3;
    const CATEGORY_PHONE        = 4;
    const CATEGORY_ELICTRONIC   = 5;  // Thời trang
    const CATEGORY_LAPTOP       = 6;  // Đồ gia dụng
    const CATEGORY_CAMERA       = 7;  // Phụ kiện
    const CATEGORY_LIFE         = 8;  // Điện thoại - Máy tính bảng
    const CATEGORY_FOOD         = 9;  // Điện tử - Điện lạnh
    const CATEGORY_TOY          = 10; // Máy tính - IT
    const CATEGORY_HEALTH       = 11; // Máy ảnh - Máy quay phim
    const CATEGORY_SPORT        = 12; // Đời sống
    const CATEGORY_VEHICLE      = 13; // Thực phẩm
    const CATEGORY_BOOK         = 14; // Đồ chơi
    const CATEGORY_TRAVEL       = 15; // Du lịch
    
    const TYPE_INCREASE         = Users::notify_increase;
    const TYPE_DECREASE         = Users::notify_decrease;
    
    const LIMIT_RELATED         = 6;
    
    const SEPARATE_NAME_ID_URL  = '__e';
    
    public static $aStatus = [
        self::STT_ACTIVE   => 'Đang bán',
        self::STT_INACTIVE => 'Ngừng kinh doanh',
    ];
    
    public static $aCategory = [
        self::CATEGORY_FASHION      => 'Thời trang',
        self::CATEGORY_HOUSEWARE    => 'Đồ gia dụng',
        self::CATEGORY_ACCESSORIES  => 'Phụ kiện',
        self::CATEGORY_PHONE        => 'Điện thoại - Máy tính bảng',
        self::CATEGORY_ELICTRONIC   => 'Điện tử - Điện lạnh',
        self::CATEGORY_LAPTOP       => 'Máy tính - IT',
        self::CATEGORY_CAMERA       => 'Máy ảnh - Máy quay phim',
        self::CATEGORY_LIFE         => 'Đời sống',
        self::CATEGORY_FOOD         => 'Thực phẩm',
        self::CATEGORY_TOY          => 'Đồ chơi',
        self::CATEGORY_HEALTH       => 'Sức khỏe',
        self::CATEGORY_SPORT        => 'Thể thao',
        self::CATEGORY_VEHICLE      => 'Xe cộ',
        self::CATEGORY_BOOK         => 'Sách',
        self::CATEGORY_TRAVEL       => 'Du lịch - Dã ngoại',
    ];
    
    public static $aCategoryHomePage = [
        self::CATEGORY_HOUSEWARE,
        self::CATEGORY_ACCESSORIES,
        self::CATEGORY_PHONE,
        self::CATEGORY_TRAVEL,
        self::CATEGORY_FASHION,
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
            [['name', 'category_id', 'url', 'url_redirect', 'price', 'image', 'status', 'created_date'], 'safe'],
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
            'category_id' => Yii::t('app', 'Category'),
            'image' => Yii::t('app', 'Images'),
            'status' => Yii::t('app', 'Status'),
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
        $slugKeyword    = MyFormat::slugify($this->name);
        $query->andFilterWhere(['like', 'slug', $slugKeyword])
                ->andFilterWhere(['category_id' => $this->category_id])
                ->andFilterWhere(['status' => $this->status])
                ->andFilterWhere(['DATE(created_date)' => MyFormat::formatSqlDate($this->created_date)])
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
        if($this->status == self::STT_INACTIVE){
            $mUserTracking = new UserTracking();
            $mUserTracking->product_id = $this->id;
            $mUserTracking->untrackStopTrading();
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
                    $itemId     = isset($aUrlParams[5]) && isset(explode('?', $aUrlParams[5])[0]) ? explode('?', $aUrlParams[5])[0] : '';
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
     * @todo format name display on url with slugify name and product id
     */
    public function formatNameForSeo(){
        $slugName  = MyFormat::slugify($this->name);
        $slugName .= Products::SEPARATE_NAME_ID_URL;
        $slugName .= $this->id;
        $this->name = $slugName;
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
    
    /**
     * @todo get category name
     */
    public function getCategory(){
        $aCategory = self::$aCategory;
        return isset($aCategory[$this->category_id]) ? $aCategory[$this->category_id] : '';
    }
    
    /**
     * @todo get product by category
     */
    public function getByCategory(){
        $aCategory  = array_keys(self::$aCategory);
        $ret        = [];
        $models     = null;
        if( !empty($this->category_id) ){
            if( is_array($this->category_id) ){
                $models = Products::find()->where(['IN', 'category_id', $this->category_id])->all();
            } else {
                $models = Products::find()->where(['category_id' => $this->category_id])->all();
            }
            foreach ($models as $value) {
                $ret[$value->category_id][$value->id] = $value;
            }
        }
        return $ret;
    }
    
    /*
     * Search for url in database
     */
    public function getByUrl($url){
        $product    = new Products();
        $urlShort   = $product->handleUrl($url);
        $mProduct   = Products::find()->where(['url' => $urlShort])->one();
        if($mProduct){
            $mPriceLog       = new PriceLogs();
            $mProduct->price = $mPriceLog->getArrayLastPrice($mProduct->id);
            return $mProduct;
        }
        return null;
    }
    
    /**
     * @todo get related product (suggest for user)
     */
    public function getRelated(){
        $ret = [];
        if(!empty($this->name)){
//            Extract word from string
//            $vnChar  = 'áạàảãăắặằẳẵâấậầẩẫéẹèẻẽêếệềểễíịìỉĩóọòỏõôốộồổỗơớợờởỡúụùủũưứựừửữýỵỳỷỹđ';
//            $vnChar .= 'ÁẠÀẢÃĂẮẶẰẲẴÂẤẬẦẨẪÉẸÈẺẼÊẾỆỀỂỄÍỊÌỈĨÓỌÒỎÕÔỐỘỒỔỖƠỚỢỜỞỠÚỤÙỦŨƯỨỰỪỬỮÝỴỲỶỸĐ';
//            echo '<pre>';
//            print_r(str_word_count($this->name, 1, $vnChar));
//            echo '</pre>';
            
            $fmtName        = MyFormat::slugify($this->name, ' ');
            $aKeyword       = MyFormat::$aKeyword;
            $kwSuggest      = '';
            $category       = $this->category_id;
            foreach ($aKeyword as $kw) {
                if(stripos($fmtName, $kw) !== FALSE){
                    $kwSuggest = $kw;
                }
            }
            $kwSuggestSlug  = str_replace(' ', '-', $kwSuggest);
            $cond           = empty($this->id) ? 
                                ['like', 'slug', $kwSuggestSlug] :
                                [
                                    'and',
                                    ['like', 'slug', $kwSuggestSlug],
                                    ['<>', 'id', $this->id]
                                ];
            $models         = Products::find()->where($cond)->limit(self::LIMIT_RELATED)->all();
            foreach ($models as $value) {
                $ret[$value->id] = $value;
            }
            // If not enough, find same type, not in id,...
            while(count($ret) < self::LIMIT_RELATED){
                $remain     = self::LIMIT_RELATED-count($models);
                $aIdNotIn   = array_keys($ret);
                $aIdNotIn[] = empty($this->id) ? null : $this->id;
                $cond2      = empty($category) ?
                                ['not in', 'id', array_filter($aIdNotIn)] :
                                [
                                    'and',
                                    ['category_id' => $this->category_id],
                                    ['not in', 'id', array_filter($aIdNotIn)]
                                ];
                $adtModels  = Products::find()->where($cond2)->limit($remain)->all();
                foreach ($adtModels as $value) {
                    $ret[$value->id] = $value;
                }
                $category = null; // if same type still not enough, find id not in
            }
        }
        return $ret;
    }
}
