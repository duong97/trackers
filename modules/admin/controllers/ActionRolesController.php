<?php

namespace app\modules\admin\controllers;

use app\models\ActionRoles;
use app\models\Controllers;
use app\models\Users;

use app\controllers\BaseController;

use app\helpers\Checks;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActionRolesController implements the CRUD actions for ActionRoles model.
 */
class ActionRolesController extends BaseController
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
     * Lists all ActionRoles models.
     * @return mixed
     */
    public function actionIndex()
    {
	try {
            $dataProvider = new ActiveDataProvider([
                'query' => ActionRoles::find(),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Displays a single ActionRoles model.
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
     * Creates a new ActionRoles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	try {
            $this->getListAction();
            $model      = new ActionRoles();
            
            $post       = Yii::$app->request->post();
            $listAction = isset($post['ActionRoles']['actions']) ? $post['ActionRoles']['actions'] : '';
            if( !empty($listAction) ){
                $post['ActionRoles']['actions'] = implode(',', $listAction);
            }

            if ($model->load($post) && $model->save()) {
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
            $cname      = isset($_POST['controller_name']) ? trim($_POST['controller_name']) : '';
            $model      = new Controllers();
            $aCA        = $model->getAllCA();
            $aAction    = isset($aCA[$cname]) ? $aCA[$cname] : [];
            echo json_encode($aAction);
            die;
        }
    }
    
    /**
     * Updates an existing ActionRoles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
	try {
            $model = $this->findModel($id);
            $model->scenario = Yii::$app->params['SCENARIO_UPDATE'];

            $post       = Yii::$app->request->post();
            $listAction = isset($post['ActionRoles']['actions']) ? $post['ActionRoles']['actions'] : '';
            if( !empty($listAction) ){
                $post['ActionRoles']['actions'] = is_array($listAction) ? implode(',', $listAction) : '';
            }
            
            if ($model->load($post) && $model->save()) {
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
     * Deletes an existing ActionRoles model.
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
     * Finds the ActionRoles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActionRoles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActionRoles::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
