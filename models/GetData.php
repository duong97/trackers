<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use app\models\simple_html_dom;
use app\helpers\Constants;
use app\helpers\Tools;

class GetData extends BaseModel
{
 
    /*
     * @des detect website and choose methode
     */
    public function getInfo($url){
        $parse          = parse_url($url);
        $domain         = str_replace("www.", "", $parse['host']);
        $aWebsiteDomain = Constants::$aWebsiteDomain;
        $ret            = "";
        switch ($domain) {
            case $aWebsiteDomain[Constants::SHOPEE]:
                $ret = $this->getShopee($url);
                break;
            case $aWebsiteDomain[Constants::SENDO]:
                $ret = $this->getSendo($url);
                break;
            case $aWebsiteDomain[Constants::TIKI]:
                $ret = $this->getTiki($url);
                break;
            case $aWebsiteDomain[Constants::AMAZON]:
                $ret = $this->getAmazon($url);
                break;
            case $aWebsiteDomain[Constants::EBAY]:
                $ret = $this->getEbay($url);
                break;

            default:
                $ret = "Not found";
                break;
        }
        if(gettype($ret) === 'integer'){
            $ret = Yii::$app->formatter->asCurrency($ret);
        }
        return $ret;
    }
    
    /*
     * @des use crawl to get data, use by other functions
     */
    public function getByCrawl($url, $elm_name, $elm_img, $elm_price, $elm_price_2 = ""){
        $html    = new simple_html_dom();
        $html->load_file($url);
        $pr      = $html->find($elm_price, 0);
        if(empty($pr)){
            $pr  = $html->find($elm_price_2, 0);
        }
        $price  = isset($pr->plaintext) ? $pr->plaintext : "";
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
    
    /*
     * @des get data from Shopee by api
     */
    public function getShopee($url){
        $aUrlParams = explode("-i.", $url);
        $aId        = explode(".", $aUrlParams[1]);
        $api        = "https://shopee.vn/api/v2/item/get?itemid={$aId[1]}&shopid={$aId[0]}";
        $result     = file_get_contents($api);
        $aData      = json_decode($result, true);
        $url_img    = "https://cf.shopee.vn/file/";
        $aImage     = [];
        if(isset($aData['item']['images'])){
            foreach ($aData['item']['images'] as $id_img) {
                $aImage[] = [
                    'normal' => $url_img.$id_img,
                    'small' => $url_img.$id_img
                ];
            }
        }
        $price      = isset($aData['item']['price']) ? str_replace("00000", "", $aData['item']['price']) : "";
        $ret        = [
            'name'  => isset($aData['item']['name']) ? $aData['item']['name'] : "",
            'price' => number_format($price , 0 , '.' , ','),
            'image' => $aImage
        ];
        return $ret;
    }
    
    /*
     * @des get price from Sendo by api
     */
    public function getSendo($url){
        $r          = str_replace(["https://www.sendo.vn/",".html"], "", $url);
        $api        = "https://www.sendo.vn/m/wap_v2/full/san-pham/{$r}";
        $result     = file_get_contents($api);
        $aData      = json_decode($result, true);
        $aImage     = [];
        if(isset($aData['result']['data']['media'])){
            foreach ($aData['result']['data']['media'] as $media) {
                if($media['type'] == 'image'){
                    $aImage[] = [
                        'normal' => $media['image'],
                        'small'  => $media['image_50x50']
                    ];
                }
            }
        }
        $price      = isset($aData['result']['data']['final_price']) ? $aData['result']['data']['final_price'] : "";
        $ret        = [
            'name'  => isset($aData['result']['data']['name']) ? $aData['result']['data']['name'] : "",
            'price' => Tools::formatCurrency($price),
            'image' => $aImage
        ];
        return $ret;
    }
    
    /*
     * @des get price from Tiki by api, not often use
     */
    public function getTikiPrice($url){
        $start   = '-p';
        $end     = '.html';
        $pattern = sprintf(
            '/%s(.+?)%s/ims',
            preg_quote($start, '/'), preg_quote($end, '/')
        );
        $product_id = 0;
        if (preg_match($pattern, $url, $matches)) {
            list(, $product_id) = $matches;
        }
        $api        = "https://tiki.vn/api/v2/products/{$product_id}/info";
        $result     = file_get_contents($api);
        $aData      = json_decode($result, true);
        $price      = isset($aData['price']) ? $aData['price'] : "";
        return $price;
    }
    
    /*
     * test again
     * @des get data from Tiki by crawl
     */
    public function getTiki($url){
        $elm_name       = 'h1[id=product-name]';
        $elm_img        = 'img[alt=Product]';
        $elm_price      = 'span[class=price]';
        $elm_price2     = 'span[id=span-price]';
        $aData          = $this->getByCrawl($url, $elm_name, $elm_img, $elm_price, $elm_price2);
        preg_match('/([0-9]+\.[0-9]+)/', $aData['price'], $matches);
        $aData['price'] = $matches[0];
        foreach ($aData['image'] as $key => $img) {
            $aData['image'][$key] = [
                'normal'=> str_replace("/cache/75x75", "", $img),
                'small' => $img
            ];
        }
        return $aData;
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
        $html    = new simple_html_dom();
        $html->load_file($url);
        $pr      = $html->find($elm_price, 0);
        if(empty($pr)){
            $pr  = $html->find($elm_price2, 0);
        }
        $price  = isset($pr->plaintext) ? $pr->plaintext : "";
        $name   = isset($html->find($elm_name, 0)->plaintext) ? $html->find($elm_name, 0)->plaintext : "";
        $aImage     = [];
        $aImageTmp = [];
        foreach($html->find('script') as $element)
        {
            $linkkk = $element->innertext; 
            $regex = "/(https\:\/\/images-na\.ssl-images-amazon\.com\/images\/I\/)[0-9a-zA-Z%._]{10,30}(.jpg)/";
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
                'normal' => end($aImg),
                'small' => reset($aImg)
            ];
        }
        $ret    = [
            'name' => empty($name) ? "" : $name,
            'price' => $price,
            'image' => $aImage
        ];
        return $ret;
    }
    
    /*
     * not working
     * @des get data from Ebay by crawl
     * @link_test:
     * https://www.ebay.com/itm/NOW-Foods-MCT-Oil-Liquid-16-fl-oz-FREE-SHIPPING-MADE-IN-USA/372409979059
     */
    public function getEbay($url){
        $elm = 'span[id=prcIsum]';
        return $this->getByCrawl($url, $elm);
    }
}
