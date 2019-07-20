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

namespace app\modules\product\controllers;

use app\controllers\BaseController;
use yii\data\ActiveDataProvider;
use Yii;
use app\helpers\Checks;
use app\models\Products;

/**
 * Default controller for the `product` module
 */
class ListController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /*
     * Search products by url or keyword
     */
    public function actionMostTracking(){
        try {
            $query = Products::find()
                    ->alias('p')
                    ->select(['p.*', 'count(u.id) as numberTracking'])
                    ->innerJoin('user_tracking u', 'p.id=u.product_id')
                    ->groupBy(['p.id'])
                    ->orderBy(['numberTracking' => SORT_DESC]);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => DEFAULT_PAGE_SIZE,
                ],
            ]);
            
            return $this->render('most_tracking', [
                'dataProvider' => $dataProvider,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    
}
