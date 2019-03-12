<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Loggers;
use yii\data\ActiveDataProvider;
use app\controllers\BaseController;
use app\helpers\Checks;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LoggersController implements the CRUD actions for Loggers model.
 */
class LoggersController extends BaseController
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
     * Lists all Loggers models.
     * @return mixed
     */
    public function actionIndex()
    {
	try {
            $dataProvider = new ActiveDataProvider([
                'query' => Loggers::find(),
            ]);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
            ]);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Displays a single Loggers model.
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
     * Creates a new Loggers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
	try {
            $model = new Loggers();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
     * Updates an existing Loggers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
	try {
		$model = $this->findModel($id);

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
     * Deletes an existing Loggers model.
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
     * Finds the Loggers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Loggers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Loggers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
