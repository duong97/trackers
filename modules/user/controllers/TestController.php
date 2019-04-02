<?php
namespace app\modules\user\controllers;

use app\controllers\BaseController;
use Yii;
use app\helpers\Checks;

class TestController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if(!Checks::isRoot()){
            Checks::notFoundExc();
        }
        return $this->render('index');
    }
    
    public function actionStep($step)
    {
        if(!Checks::isRoot()){
            Checks::notFoundExc();
        }
        $maxStep = 3;
        $minStep = 1;
        if($step < 1){
            return $this->redirect(['test/step', 'step' => 1]);
        }
        $view = 'step_'.$step;
        if ( Yii::$app->request->isPost ) {
            if( Yii::$app->request->post('action') ){
                $step--;
            } else {
                $step++;
            }
            $step = ($step > $maxStep) ? $maxStep : (($step < $minStep) ? $minStep : $step);
            $view = 'step_'.$step;
            $this->redirect(['test/step', 'step' => $step]);
         }
        
        return $this->render('step_0', [
            'view' => $view,
            'step' => $step
        ]);
    }

}