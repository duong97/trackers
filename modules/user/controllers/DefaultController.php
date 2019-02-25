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
namespace app\modules\user\controllers;

use app\controllers\BaseController;
use app\helpers\Checks;
use app\models\Users;
use Yii;

/**
 * Default controller for the `user` module
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
    
    public function actionProfile(){
        try {
            if(Yii::$app->user->isGuest){
                Checks::notFoundExc();
            }
            $model = Users::find()->where(['id' => Yii::$app->user->id])->one();
            $request = Yii::$app->request;
            $post = $request->post();
            if($request->isPost && $post){
                $model->attributes = $post['Users'];
                $model->update();
            }
            return $this->render('profile', ['model' => $model]);
        } catch (Exception $exc) {
            
        }
    }
}
