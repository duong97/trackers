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
use app\models\UserTracking;
use app\helpers\Checks;
use yii\data\Pagination;

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
            $query = UserTracking::find()
                            ->select(['count(id) as id', 'product_id'])
                            ->groupBy(['product_id'])
                            ->orderBy(['id' => SORT_DESC]);
            $countQuery = clone $query;
            $pages      = new Pagination([
                            'defaultPageSize' => DEFAULT_PAGE_SIZE,
                            'totalCount' => $countQuery->count(),
                        ]);
            $models     = $query->offset($pages->offset)
                                ->limit($pages->limit)
                                ->all();
            $aData      = [];
            foreach ($models as $value) {
                $mProduct = isset($value->rProduct) ? $value->rProduct : null;
                if($mProduct){
                    $mProduct->numberTracking = $value->id;
                    $aData[]                  = $mProduct;
                }
            }
            return $this->render('most_tracking', [
                'aData' => $aData,
                'pages' => $pages,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    
}
