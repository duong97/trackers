<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\ContactForm;
use app\models\Users;
use app\helpers\Mailer;
use yii\helpers\Url;


class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        Yii::$app->user->returnUrl = Yii::$app->request->referrer;

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        
        if(isset($_POST['RegisterForm'])){
            $model->attributes = $_POST['RegisterForm'];
            if(!$model->hasErrors()){
                $message = '';
                if ($model->validateNewUser($message)) {
                    $model->createNewUser();
                    $mailer = new Mailer();
                    $mailer->verifyRegistration($model->email);
                    Yii::$app->session->setFlash('success', Yii::t('app', 'An email has been sent to')." $model->email, ".Yii::t('app', 'please check your inbox and complete your registration')."!");
                } else {
                    Yii::$app->session->setFlash('error', $message);
                }
            }
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
    
    public function actionRegistrationResult($q)
    {
        $aExplod = explode("i", $q);
        $user = Users::find()->where(['id' => $aExplod[0], 'status'=>Users::STT_VERIFYING])->one();
        if(!empty($user)){
            if(md5($user->email) == $aExplod[1])
            $user->status = Users::STT_ACTIVE;
            $user->update();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Registration successfully, login to continue!'));
        }
        $this->redirect(Url::base() . "/site/login");
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
//        return $this->goBack(Yii::$app->request->referrer);
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionChangeLanguage($lang){
        \Yii::$app->language = $lang;
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'language',
            'value' => $lang,
        ]));
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
//        return $this->goBack(Yii::$app->request->referrer);
    }
}
