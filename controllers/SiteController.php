<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;
use app\models\Country;


/**
 * Class SiteController
 * @package app\controllers
 *
 *  控制器最佳实践::
 *  1.可访问 请求 数据;
    2.可根据请求数据调用 模型 的方法和其他服务组件;
    3.可使用 视图 构造响应;
    4.不应处理应被模型处理的请求数据;
    5.应避免嵌入HTML或其他展示代码，这些代码最好在 视图中处理.
 */


class SiteController extends Controller
{

    //修改默认的Action
    public $defaultAction = 'login';

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @return string
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
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }





    public function actionEntry(){
        $model = new EntryForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){

            //验证 $model收到的数据
            //做一些有意义的事
            return $this->render('entry-confirm',['model'=>$model]);
        } else {
            return $this->render('entry',['model'=>$model]);
        }


    }



    public function actionSay($message = 'Hello'){
        return $this->render('say',['message'=>$message]);
    }


    public function actionCountry(){

        $countries = Country::find()->orderBy('name')->all();
        $country = Country::findOne('US');
        $country->name = 'U.S.A';
        $country->save();

        return $this->render('country',['countries'=>$countries]);

    }



    /**
     * Displays contact page.
     *
     * @return string
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
}
