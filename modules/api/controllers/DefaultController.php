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
use app\models\PriceLogs;
use Yii;
use yii\web\Response;

/**
 * Default controller for the `api` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionGetProductHistory($url)
    {
        Yii::$app->response->format = Response:: FORMAT_JSON;
        $get        = Yii::$app->request->get();
        $token      = isset($get['token']) ? $get['token'] : '';
        $appToken   = 'ad8912mJ!alY%1dDXj3j1312akjdD';
        if(empty($token) || $token != $appToken){
            return [
                'status' => 'fail',
                'message'=> 'Missing params',
            ];
        }
        $mPriceLogs = new PriceLogs();
        $aLogs      = $mPriceLogs->getByUrl($url, true);
        $chartData  = $mPriceLogs->getChartData($aLogs);
        if(count($aLogs) > 0 ){
            return [
                'status' => 'success',
                'data'   => $aLogs, 
                'chart'  => $chartData
            ];
        } else {
            return [
                'status' => 'fail',
                'message'=> 'No Logs Found'
            ];
        }
    }
}
