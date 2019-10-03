<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use app\models\Blog;
use app\models\UploadForm;
use app\models\Files;
use app\controllers\BaseController;
use app\helpers\Checks;

/**
 * BlogController implements the CRUD actions for Blog model.
 */
class BlogController extends BaseController
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
    
    public function beforeAction($action)
    {            
        if ($action->id == 'upload') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Blog models.
     * @return mixed
     */
    public function actionIndex()
    {
        try {
            $searchModel = new Blog();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		} catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }

    /**
     * Displays a single Blog model.
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
     * Creates a new Blog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        try {
            $model = new Blog();
            $model->scenario = Yii::$app->params['SCENARIO_CREATE'];
            
            if ($model->load(Yii::$app->request->post())) {
                $model->save();
                $mUpload        = new UploadForm();
                $mUpload->handleUpload($model, 'thumb', Files::TYPE_BLOG);
                $model->thumb   = $mUpload->pathAfterUpload;
                $model->update(); // update thumb
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
     * Updates an existing Blog model.
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
            
            if ($model->load(Yii::$app->request->post())) {
                $mUpload        = new UploadForm();
                $mUpload->handleUpload($model, 'thumb', Files::TYPE_BLOG);
                if( !empty($mUpload->pathAfterUpload) ){
                    $model->thumb   = $mUpload->pathAfterUpload;
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
     * Deletes an existing Blog model.
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
     * Finds the Blog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    public function actionUpload($id = ''){
        try {
            $model      = Blog::findOne($id);
            $mUpload    = new UploadForm();
            if(empty($model)){
                $model  = new Blog();
            }
            $post       = Yii::$app->request->post();
            if (!empty($post)):
                $mUpload->aImageFile = [];
                $file   = isset($_FILES['upload']) ? $_FILES['upload'] : '';
                if(!empty($file)){
                    $mUploadedFile              = new UploadedFile();
                    $mUploadedFile->name        = $file['name'];
                    $mUploadedFile->tempName    = $file['tmp_name'];
                    $mUploadedFile->type        = $file['type'];
                    $mUploadedFile->size        = $file['size'];
                    $mUploadedFile->error       = $file['error'];
                    $mUpload->aImageFile[]      = $mUploadedFile;
                    $mUpload->handleUpload($model, 'aContentImage', Files::TYPE_BLOG_CONTENT);
                }
            endif;
            $res = [
                'uploaded'  => 1,
                'fileName'  => $mUpload->pathAfterUpload,
                'url'       => $mUpload->fullPathAfterUpload,
            ];
            echo json_encode($res);
        } catch (Exception $exc) {
            Checks::catchAllExeption($exc);
        }
    }
}
