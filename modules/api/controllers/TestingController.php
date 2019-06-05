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

namespace app\modules\api\controllers;

use app\controllers\BaseController;
use Yii;
use yii\helpers\Url;

/**
 * Default controller for the `api` module
 */
class TestingController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->get();
        $data = '';
        if($get){
            $url = isset($get['url']) ? $get['url'] : '';
            $params = isset($get['params']) ? json_decode($get['params'], true) : '';
            $params = [
                'url'=>'https://www.sendo.vn/dong-ho-nam-nibosi-2309-chinh-hang-hang-fullbox-day-trang-mat-xanh-11955053.html'
            ];
            $apiUrl = Url::to($url, true);
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close($ch);
        }
        return $this->render('index', ['data'=>$data]);
    }
}
