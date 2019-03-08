<?php
namespace app\modules\user\controllers;

use app\controllers\BaseController;

class TestController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}