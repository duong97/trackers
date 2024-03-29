<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\SupportedWebsites;
use app\models\UploadForm;
use app\controllers\BaseController;
use app\helpers\Checks;
use app\helpers\MyFormat;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SupportedWebsitesController implements the CRUD actions for SupportedWebsites model.
 */
class SupportedWebsitesController extends BaseController
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
     * Lists all SupportedWebsites models.
     * @return mixed
     */
    public function actionIndex()
    {
        try {
            $searchModel  = new SupportedWebsites();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		} catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Displays a single SupportedWebsites model.
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
     * Creates a new SupportedWebsites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try {
            $model           = new SupportedWebsites();
            $model->scenario = Yii::$app->params['SCENARIO_CREATE'];
            
            if ($model->load(Yii::$app->request->post())) {
                $model->name                        = MyFormat::removeSlash($model->name);
                $mUpload                            = new UploadForm();
                $mUpload->imageFile['icon']         = UploadedFile::getInstances($model, 'icon')[0];
                $mUpload->imageFile['homepageLogo'] = UploadedFile::getInstances($model, 'homepageLogo')[0];
                $filename['icon']                   = $model->name.'_logo';
                $filename['homepageLogo']           = $model->name;
                $model->logo                        = $filename['icon'].'.'.$mUpload->imageFile['icon']->extension;
                $model->icon                        = $model->logo;
                $model->homepageLogo                = $filename['homepageLogo'].'.'.$mUpload->imageFile['homepageLogo']->extension;
                if ( !$mUpload->upload($model->getFullPath(), $filename) ) {
                    Yii::$app->session->setFlash('error', 'Lỗi upload file.');
                    return $this->redirect(['create']);
                }
                if ( $model->hasErrors() ) {
                    Yii::$app->session->setFlash('error', 'System error.');
                    return $this->redirect(['create']);
                }
                $model->save();
                Yii::$app->session->setFlash('success', 'Create successful.');
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
     * Updates an existing SupportedWebsites model.
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
            
            if ( $model->load(Yii::$app->request->post()) ) {
                $model->name                        = MyFormat::removeSlash($model->name);
                $mUpload                            = new UploadForm();
                $mUpload->imageFile['icon']         = isset(UploadedFile::getInstances($model, 'icon')[0]) ? UploadedFile::getInstances($model, 'icon')[0] : '';
                $mUpload->imageFile['homepageLogo'] = isset(UploadedFile::getInstances($model, 'homepageLogo')[0]) ? UploadedFile::getInstances($model, 'homepageLogo')[0] : '';
                $filename['icon']                   = $model->name.'_logo';
                $filename['homepageLogo']           = $model->name;
                $model->logo                        = isset($mUpload->imageFile['icon']->extension) ? $filename['icon'].'.'.$mUpload->imageFile['icon']->extension : $model->logo;
                if ( !$mUpload->upload($model->getFullPath(), $filename) ) {
                    Yii::$app->session->setFlash('error', 'Lỗi upload file.');
                    return $this->redirect(['create']);
                }
                if ( !$model->save() ) {
                    Yii::$app->session->setFlash('error', 'System error.');
                    return $this->redirect(['create']);
                }
                
                $model->save();
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
     * Deletes an existing SupportedWebsites model.
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
     * Finds the SupportedWebsites model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SupportedWebsites the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SupportedWebsites::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
