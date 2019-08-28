<?php

namespace app\models;

use Yii;
//use yii\helpers\Url;
use app\models\simple_html_dom;
use app\models\UserTracking;
use app\models\SupportedWebsites;
use app\models\UserData;
use app\helpers\Constants;
use app\helpers\MyFormat;
use app\helpers\Checks;
use yii\data\ActiveDataProvider;

class GetData extends BaseModel
{
    public $str; // String save when crawl

    public function initCurl($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_REFERER, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $this->str = curl_exec($curl);
        curl_close($curl);
    }
    
    /**
     * @todo get only number from string (remove dot, commas ...)
     * @param type $price
     */
    public function onlyNumber(&$price){
        preg_match_all('!\d+!', $price, $matches);
        // Get only number from price string when crawl
        if(is_array($matches[0])){
            $price = "";
            foreach ($matches[0] as $v) {
                $price .= $v;
            }
        } else {
            $price = $matches[0];
        }
        $price = (int)$price;
    }
    
    /*
     * @des detect website and choose methode
     */
    public function getInfo($searchValue){
        $parse          = parse_url($searchValue);
        if(isset($parse['host'])){
            return $this->searchUrl($searchValue);
        } else {
            return $this->searchKeyword($searchValue);
        }
    }
    
    /*
     * Search for url
     */
    public function searchUrl($url, $forceCrawl = false){
        $ret                = empty($this->searchExistsUrl($url)) ? $this->searchNewUrl($url) : $this->searchExistsUrl($url);
        if( $forceCrawl ){
            $newData        = $this->searchNewUrl($url);
            $ret['name']    = empty($newData['name']) ? '' : $newData['name'];
            $ret['price']   = empty($newData['price']) ? '' : $newData['price'];
            $ret['image']   = empty($newData['image']) ? '' : $newData['image'];
            if( !empty($newData['seller_id']) ){
                $ret['seller_id'] = $newData['seller_id'];
            }
        }
        if( empty($ret['price']) && empty($ret['name']) ){
            Checks::productNotFoundExc();
        }
        return $ret;
    }
    
    /*
     * Search for url in database
     */
    public function searchExistsUrl($url){
        $product    = new Products();
        $urlShort   = $product->handleUrl($url);
        $mProduct   = Products::find()->where(['url' => $urlShort])->one();
        if($mProduct){
            $mPriceLog       = new PriceLogs();
            $mProduct->price = $mPriceLog->getArrayLastPrice($mProduct->id);
            return $mProduct->attributes;
        }
        return [];
    }
    
    /*
     * Search for url by crawl or api
     */
    public function searchNewUrl($url){
        $parse          = parse_url($url);
        $domain         = str_replace("www.", "", $parse['host']);
        $aWebsiteDomain = Constants::$aWebsiteDomain;
        $ret            = [];
        switch ($domain) {
            case $aWebsiteDomain[Constants::LAZADA]:
                $ret    = $this->getLazada($url);
                break;
            case $aWebsiteDomain[Constants::SHOPEE]:
                $ret    = $this->getShopee($url);
                break;
            case $aWebsiteDomain[Constants::SENDO]:
                $ret    = $this->getSendo($url);
                break;
            case $aWebsiteDomain[Constants::TIKI]:
                $ret    = $this->getTiki($url);
                break;
            case $aWebsiteDomain[Constants::TGDD]:
                $ret    = $this->getTgdd($url);
                break;
            case $aWebsiteDomain[Constants::AMAZON]:
                $ret    = $this->getAmazon($url);
                break;
            case $aWebsiteDomain[Constants::EBAY]:
                $ret    = $this->getEbay($url);
                break;

            default:
                $ret    = [];
                break;
        }
        $mSpWebsite     = SupportedWebsites::find()->where(['like', 'url', $domain])->one();
        if( !empty($mSpWebsite) ){
            $ret['seller_id'] = $mSpWebsite->id;
        }
        if(gettype($ret) === 'integer'){
            $ret = Yii::$app->formatter->asCurrency($ret);
        }
        return $ret;
    }
    
    /*
     * Search for keyword
     */
    public function searchKeyword($keyword){
        if (!Yii::$app->user->isGuest) {
            $mUserData = new UserData();
            $mUserData->handleUserKeyword($keyword);
        }
        $slugKeyword    = MyFormat::slugify($keyword);
        $query = Products::find()
                    ->alias('p')
                    ->select(['p.*', 'count(u.id) as numberTracking'])
                    ->innerJoin('user_tracking u', 'p.id=u.product_id')
                    ->where(['like', 'slug', $slugKeyword])
                    ->groupBy(['p.id'])
                    ->orderBy(['numberTracking' => SORT_DESC]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => DEFAULT_PAGE_SIZE,
            ],
        ]);
        return $dataProvider;
    }

    /*
     * @des use crawl to get data, use by other functions
     */
    public function getByCrawl($url, $aElement){
        $elm_name       = empty($aElement['name']) ? '' : $aElement['name'];
        $elm_img        = empty($aElement['image']) ? '' : $aElement['image'];
        $elm_price      = empty($aElement['price']) ? '' : $aElement['price'];
        $this->initCurl($url);
        $html    = new simple_html_dom();
//        $html->load_file($url);
        $html->load($this->str);
        
        $objPrice       = null;
        if( is_array($elm_price) ){
            foreach ($elm_price as $price_class) :
                $objPrice = $html->find($price_class, 0);
                if(!empty($objPrice)) break;
            endforeach;
        } else {
            $objPrice   = $html->find($elm_price, 0);
        }
        
        $price  = isset($objPrice->plaintext) ? $objPrice->plaintext : 0;
        $name   = isset($html->find($elm_name, 0)->plaintext) ? $html->find($elm_name, 0)->plaintext : "";
        $img    = $html->find($elm_img);
        $aImage = [];
        if(count($img) > 0){
            foreach ($img as $img) {
                $aImage[] = $img->src;
            }
        }
        $ret    = [
            'name' => empty($name) ? "" : $name,
            'price' => $price,
            'image' => $aImage
        ];
        return $ret;
    }
    
    /**
     * get shop id and item id of shopee url
     */
    public function getParamsShopee($url){
        $aUrlParams = explode("-i.", $url);
        $shopId     = '';
        $itemId     = '';
        
        if( count($aUrlParams) < 2){ // https://shopee.vn/product/82239615/1826571737?smtt=0.0.9&fbclid=IwAR1fl1xYSA_WBFDrxSk8fi4EMY5UTIGti0FyY1EMXrjVK_o5kVZ4iH9Wbjo
            $aUrlParams = explode('/', $url);
            $shopId     = isset($aUrlParams[4]) ? $aUrlParams[4] : '';
            $itemId     = isset($aUrlParams[5]) && isset(explode('?', $aUrlParams[5])[0]) ? explode('?', $aUrlParams[5])[0] : '';
        } else { // https://shopee.vn/-KM-TH%C3%81NG-5-Balo-cao-c%E1%BA%A5p-%C4%91%E1%BB%B1ng-Laptop-Nam-phong-c%C3%A1ch-H%C3%A0n-Qu%E1%BB%91c-(%E1%BA%A2nh-SP-th%E1%BA%ADt)-i.82239615.1826571737
            $aId        = explode(".", $aUrlParams[1]);
            $shopId     = isset($aId[0]) ? $aId[0] : '';
            $itemId     = isset($aId[1]) ? $aId[1] : '';
        }
        if( empty($shopId) && empty($itemId) ) return [];
        return [
            'shop_id' => $shopId,
            'item_id' => $itemId
        ];
    }
    
    /*
     * @des get data from Shopee by api
     */
    public function getShopee($url){
        $aParams    = $this->getParamsShopee($url);
        $api        = "https://shopee.vn/api/v2/item/get?itemid={$aParams['item_id']}&shopid={$aParams['shop_id']}";
        $result     = file_get_contents($api);
        $aData      = json_decode($result, true);
        $url_img    = "https://cf.shopee.vn/file/";
        $aImage     = [];
        if(isset($aData['item']['images'])){
            foreach ($aData['item']['images'] as $id_img) {
                $aImage[] = $url_img.$id_img;
//                        [
//                    'normal' => $url_img.$id_img,
//                    'small' => $url_img.$id_img
//                ];
            }
        }
        $price      = isset($aData['item']['price']) ? substr($aData['item']['price'], 0, -5) : "";
        $this->onlyNumber($price);
        $ret        = [
            'name'  => isset($aData['item']['name']) ? $aData['item']['name'] : "",
            'price' => empty($price) ? Yii::t('app', 'Stop trading') : $price,
            'image' => isset($aImage[0]) ? $aImage[0] : ""
        ];
        return $ret;
    }
    
    /*
     * @des get data from Sendo by api
     */
    public function getSendo($url){
        $limitLoop      = 2; // Chạy vòng lặp tối đa 2 lần
        $aData          = [];
        $tmpUrl         = $url;
        $limitLoop     -= 1;
        do{ // xử lý nếu url sp redirect sang url khác 
            echo $limitLoop;
            $r          = str_replace(["https://www.sendo.vn/",".html"], "", $tmpUrl);
            $api        = "https://www.sendo.vn/m/wap_v2/full/san-pham/{$r}";
            $result     = file_get_contents($api);
            $aData      = json_decode($result, true);
            $tmpUrl     = empty($aData['result']['meta_data']['redirect']) ? '' : $aData['result']['meta_data']['redirect'];
        } while ( empty($aData['result']['data']) && $limitLoop-- );
        
        $aImage         = [];
        if(isset($aData['result']['data']['media'])){
            foreach ($aData['result']['data']['media'] as $media) {
                if($media['type'] == 'image'){
                    $aImage[] = $media['image'];
//                            [
//                        'normal' => $media['image'],
//                        'small'  => $media['image_50x50']
//                    ];
                }
            }
        }
        $price      = isset($aData['result']['data']['final_price']) ? $aData['result']['data']['final_price'] : "";
        $ret        = [
            'name'  => isset($aData['result']['data']['name']) ? $aData['result']['data']['name'] : "",
            'price' => empty($price) ? Yii::t('app', 'Stop trading') : $price,
            'image' => isset($aImage[0]) ? $aImage[0] : ""
        ];
        return empty($ret['name']) ? [] : $ret;
    }
    
    /*
     * @des get data from Lazada by crawl
     */
    public function getLazada($url){
        $this->initCurl($url);
        $html       = new simple_html_dom();
        $html->load($this->str);
        $script     = $html->find('script[type=application/ld+json]',0);
        
        // Crawl data from <script> tag
        if(empty($script)) {
            return [];
        }
        $json               = htmlspecialchars_decode($script->innertext);
        $aJsonData          = json_decode($json, true);
        $aData['image']     = isset($aJsonData['image']) ? $aJsonData['image'] : "";
        $aData['name']      = isset($aJsonData['name']) ? $aJsonData['name'] : "";
        $aData['price']     = isset($aJsonData['offers']['price']) ? $aJsonData['offers']['price'] : "";
        $this->onlyNumber($aData['price']);
        unset($html);
        return $aData;
    }
    
    /*
     * @des get price from Tiki by api, not often use
     */
//    public function getTikiPrice($url){
//        $start   = '-p';
//        $end     = '.html';
//        $pattern = sprintf(
//            '/%s(.+?)%s/ims',
//            preg_quote($start, '/'), preg_quote($end, '/')
//        );
//        $product_id = 0;
//        if (preg_match($pattern, $url, $matches)) {
//            list(, $product_id) = $matches;
//        }
//        $api        = "https://tiki.vn/api/v2/products/{$product_id}/info";
//        $result     = file_get_contents($api);
//        $aData      = json_decode($result, true);
//        $price      = isset($aData['price']) ? $aData['price'] : "";
//        return $price;
//    }
    
    /*
     * @des get data from Tiki by crawl
     * @link test:
     * https://tiki.vn/may-anh-canon-750d-lens-18-55-is-stm-le-bao-minh-p440702.html?spid=146473&src=deal-hot&2hi=0
     * https://tiki.vn/quat-thap-tiross-ts9180-den-p519278.html?src=view-together&2hi=0
     * https://tiki.vn/man-hinh-benq-gw2270-22inch-fullhd-5ms-60hz-va-hang-chinh-hang-p551192.html?src=recently-viewed&2hi=0
     */
    public function getTiki($url){
        $aElement       = [
            'name'      => 'h1[id=product-name]',
            'image'     => 'img',
            'price'     => [
                            'span[class=price]>span[class=red]', // nhập mã giảm giá
                            'span#span-price', // normal
                            'span[class=price]' // if not normal
                        ],
        ];
        $aData          = $this->getByCrawl($url, $aElement);
        if( empty($aData['price']) ){
            $aData['price'] = Yii::t('app', 'Stop trading');
        } else {
            $this->onlyNumber($aData['price']);
        }
        $regexImg       = "/(https\:\/\/salt\.tikicdn\.com\/cache\/)[0-9a-zA-Z-_.\/]{30,80}(.jpg)/";
        $aImgTemp       = [];
        foreach ($aData['image'] as $img) {
            preg_match_all($regexImg, $img, $aMatchImage);
            if(!empty($aMatchImage[0])){
                $aImgTemp[] = array_unique($aMatchImage[0])[0];
            }
        }
//        foreach ($aData['image'] as $key => $img) {
//            $aData['image'][$key] = [
//                'normal'=> str_replace("/cache/75x75", "", $img),
//                'small' => $img
//            ];
//        }
        $aData['image'] = isset($aImgTemp[0]) ? $aImgTemp[0] : "";
        return empty($aData['name']) ? [] : $aData;
    }
    
    /**
     * @todo get data form thegioididong
     */
    public function getTgdd($url){
        $aElement       = [
            'name'      => '.rowtop h1',
            'image'     => 'aside.picture img',
            'price'     => [
                            'aside.price_sale .area_price strong',
                            '.boxshock .boxshockheader div label strong'
                        ],
        ];
        $aData          = $this->getByCrawl($url, $aElement);
        if( empty($aData['price']) ){
            $aData['price'] = Yii::t('app', 'Stop trading');
        } else {
            $this->onlyNumber($aData['price']);
        }
        $aData['image'] = isset($aData['image'][0]) ? $aData['image'][0] : '';
        return empty($aData['name']) ? [] : $aData;
    }
    
    /* //fixing
     * @des get data from Amazon by crawl
     * @link_test:
     * https://www.amazon.com/Funcl-AI-Bluetooth-SweatProof-Microphone/dp/B07LBSWV36/ref=sr_1_3?s=electronics&ie=UTF8&qid=1549096945&sr=1-3
     */
    public function getAmazon($url){
        $elm_name       = 'span[id=productTitle]';
        $elm_price      = 'span[id=priceblock_ourprice]';
        $elm_price2     = 'span[class="a-size-base a-color-price a-color-price"]';
        $html           = new simple_html_dom();
        $html->load_file($url);
        $pr             = $html->find($elm_price, 0);
        if(empty($pr)){
            $pr         = $html->find($elm_price2, 0);
        }
        $price  = isset($pr->plaintext) ? $pr->plaintext : "";
        $name   = isset($html->find($elm_name, 0)->plaintext) ? $html->find($elm_name, 0)->plaintext : "";
        $aImage         = [];
        $aImageTmp      = [];
        foreach($html->find('script') as $element)
        {
            $linkkk = $element->innertext; 
            $regex  = "/(https\:\/\/images-na\.ssl-images-amazon\.com\/images\/I\/)[0-9a-zA-Z%._]{10,30}(.jpg)/";
            preg_match_all($regex, $linkkk, $matches);
            if(!empty($matches[0])){
                $aImageTmp[] = $matches[0];
            }
        }
        $aIdImage       = [];
        $aImageTmp2     = [];
        $url_img        = "https://images-na.ssl-images-amazon.com/images/I/";
        foreach ($aImageTmp[0] as $value) {
            $sId        = str_replace([$url_img, ".jpg"], "", $value);
            $aId        = explode("._", $sId);
            $idImg      = $aId[0];
            $key_of_each= 0;
            if(isset($aId[1])){
                preg_match_all('!\d+!', $aId[1], $matches);
                $key_of_each = $matches[0][0];
            }
            $aImageTmp2[$idImg][$key_of_each] = $value;
            if(!in_array($idImg, $aIdImage)){
                $aIdImage[$idImg] = $idImg;
            }
        }
        $canShorten = false;
        foreach ($aImageTmp2 as $key => $aImg) {
            if(count($aImg) > 1){
                $canShorten = true;
            }
        }
        if($canShorten){
            foreach ($aImageTmp2 as $key => $aImg) {
                if(count($aImg) < 3){
                    unset($aImageTmp2[$key]);
                } else {
                    ksort($aImageTmp2[$key]);
                }
            }
        }
        foreach ($aImageTmp2 as $key => $aImg) {
            $aImage[] = [
                end($aImg)
//                'normal' => end($aImg),
//                'small' => reset($aImg)
            ];
        }
        $ret    = [
            'name' => empty($name) ? "" : $name,
            'price' => $price,
            'image' => $aImage[0]
        ];
        return $ret;
    }
    
    /*
     * @des get data from Ebay by crawl
     * @link_test:
     * https://www.ebay.com/itm/NOW-Foods-MCT-Oil-Liquid-16-fl-oz-FREE-SHIPPING-MADE-IN-USA/372409979059
     */
    public function getEbay($url){
        $aElement       = [
            'name'      => 'h1[id=itemTitle]',
            'image'     => 'img[id=icImg]',
            'price_code'=> '',
            'price'     => [
                            'span[id=convbinPrice]',
                            'span[id=prcIsum]'
                        ],
        ];
        $aData          = $this->getByCrawl($url, $aElement);
        $aImg           = array_unique($aData['image']);
        $aNewImage      = [];
        if(!empty($aImg)){
            foreach ($aImg as $img) {
                $aNewImage[] = [
                    $img
//                    'normal' => $img,
//                    'small'  => $img
                ];
            }
        }
        unset($aData['image']);
        $aData['image'] = $aNewImage[0];
        return $aData;
    }
}
