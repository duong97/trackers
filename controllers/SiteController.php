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
use app\models\SupportedWebsites;
use app\models\Mailer;

use app\helpers\Constants;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;


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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
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
        return $this->render('homepage');
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
            if(Yii::$app->user->returnUrl != '/'){
                return $this->redirect(Yii::$app->user->returnUrl);
            }else{
                return $this->goBack();
            }
        }
        Yii::$app->user->returnUrl = (Yii::$app->user->returnUrl == '/') ? Yii::$app->request->referrer : Yii::$app->user->returnUrl;
        
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
                    $urlManager = \Yii::$app->getUrlManager();
                    $urlResend = $urlManager->createUrl(['user/mail/resend-registration', 'email' => $model->email]);
                    $linkResend = Html::a('Resend email.', $urlResend);
                    Yii::$app->session->setFlash('success', Yii::t('app', 'An email has been sent to')." $model->email, ".Yii::t('app', 'please check your inbox and complete your registration')."! ".$linkResend);
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
        return $this->goBack(Yii::$app->request->referrer);
//        return $this->goHome();
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
    
    /**
     * Displays terms page.
     *
     * @return string
     */
    public function actionTerms()
    {
        return $this->render('terms');
    }
    
    /**
     * Displays privacy page.
     *
     * @return string
     */
    public function actionPrivacy()
    {
        return $this->render('privacy');
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
    
    /*
     * List supported websites
     */
    public function actionSupportedWebsites(){
        try {
            $dataProvider = new ActiveDataProvider([
                'query' => SupportedWebsites::find(),
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            return $this->render('supported_websites', [
                'dataProvider' => $dataProvider,
            ]);
        } catch (Exception $exc) {
            
        }
    }
    
    /**
     * @todo login with facebook (using fb sdk)
     */
    public function actionFbLogin(){
        $post = Yii::$app->request->post('userData');
        if(!empty($post)){
            $data               = json_decode($post);
            $aPlatForm          = Users::$aPlatform;
            $platform           = array_search(Yii::$app->request->post('oauth_provider'), $aPlatForm);
            if($platform === FALSE) $platform = null;
            $models = Users::find()->where([
                                    'fb_id' => $data->id,
                                    'platform' => $platform
                                ])->all();
            if(!$models){
                $user               = new Users();
                $user->fb_id        = $data->id;
                $user->first_name   = $data->first_name;
                $user->last_name    = $data->last_name;
                $user->email        = $data->email;
                $user->platform     = $platform;
                $user->role         = Constants::USER;
                $user->status       = Users::STT_ACTIVE;
                $user->last_access  = date('Y-m-d H:i:s');
                $user->ip           = Yii::$app->request->userIP;
                $user->save();
            }
            $mLoginForm = new LoginForm();
            $mLoginForm->email = $data->email;
            return Yii::$app->user->login($mLoginForm->getUser(), 0);
        }
    }
    
    /*
     * uncomment this when using login with fb using fb sdk (if errors)
     */
//    public function beforeAction($action) 
//    { 
//        $this->enableCsrfValidation = false; 
//        return parent::beforeAction($action); 
//    }
    
    /**
     * @todo logout with facebook (using fb sdk)
     */
    public function actionFbLogout(){
        $post = Yii::$app->request->post('confirm');
        if(!empty($post)){
            
        }
    }
    
    /**
     * @todo login with fb (using AuthClient Yii2)
     */
    public function oAuthSuccess($client) {
        $userData = $client->getUserAttributes();
        $platform = Users::plf_facebook;
        $models   = Users::find()->where([
                                'email' => $userData['email'],
                            ])->one();
        if(!$models){
            $user               = new Users();
            $user->fb_id        = $userData['id'];
            $user->first_name   = $userData['first_name'];
            $user->last_name    = $userData['last_name'];
            $user->email        = $userData['email'];
            $user->platform     = $platform;
            $user->role         = Constants::USER;
            $user->status       = Users::STT_ACTIVE;
            $user->last_access  = date('Y-m-d H:i:s');
            $user->ip           = Yii::$app->request->userIP;
            $user->save();
        }
        $mLoginForm             = new LoginForm();
        $mLoginForm->email      = $userData['email'];
        return Yii::$app->user->login($mLoginForm->getUser(), 0);
    }
    
    public function actionZaloLogin(){
        if(isset($_GET['code'])){
            $appId          = Yii::$app->params['zalo_app_id'];
            $appScret       = Yii::$app->params['zalo_app_secret'];
            $urlGetAT       = "https://oauth.zaloapp.com/v3/access_token?app_id={$appId}&app_secret={$appScret}&code={$_GET['code']}";
            $jAccessToken   = file_get_contents($urlGetAT);
            $aAccessToken   = json_decode($jAccessToken, true);
            $accessToken    = $aAccessToken['access_token'];
            $urlGetUD       = "https://graph.zalo.me/v2.0/me?access_token=".$accessToken;
            $jUserData      = file_get_contents($urlGetUD);
            $aUserData      = json_decode($jUserData, true);
            $mUser          = new Users();
            $cUser          = $mUser->getCurrentUser();
            if($cUser){
                $cUser->zalo_access_token   = $accessToken;
                $cUser->zalo_id             = $aUserData['id'];
                $cUser->is_notify_zalo      = 1;
                $cUser->update();
            }
        }
        return $this->redirect(['/user/default/settings']);
    }
    
    // test
//    public function actionTest(){
//        $mNotification = new \app\models\Notifications();
//        $aProductChange = [20, 21, 22];
//        $mNotification->notifyPriceChangedViaZalo($aProductChange);
//    }
    
}
