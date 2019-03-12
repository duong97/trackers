<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Controllers;
use app\helpers\Checks;
use app\controllers\BaseController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * ControllersController implements the CRUD actions for Controllers model.
 */
class ControllersController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Controllers models.
     * @return mixed
     */
    public function actionIndex()
    {
        try {
            $dataProvider = new ActiveDataProvider([
                'query' => Controllers::find(),
            ]);
            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Displays a single Controllers model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        try {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Creates a new Controllers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try {
            $this->getListAction();
            $model = new Controllers();
            // Validate ajax (unique controller name)
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                $model->actions = $_POST['Controllers']['actions'];
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render('create', [
                'model' => $model,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
    
    /**
     * @todo get list action of given controller for ajax
     */
    public function getListAction(){
        if( isset($_GET['getListAction']) ) {
            $cname      = isset($_POST['Controllers']['controller_name']) ? $_POST['Controllers']['controller_name'] : '';
            $model      = new Controllers();
            $aCA        = $model->getAllCA();
            $aAction    = isset($aCA[$cname]) ? $aCA[$cname] : [];
            echo implode(', ', $aAction);
            die;
        }
    }

    /**
     * Updates an existing Controllers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        try {
            $model = $this->findModel($id);
            // Validate ajax (unique controller name)
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Deletes an existing Controllers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Finds the Controllers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Controllers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Controllers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
