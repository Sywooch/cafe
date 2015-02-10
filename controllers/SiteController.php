<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use app\models\Report;
use yii\data\Sort;
use app\models\Log;

class SiteController extends Controller {

    public function behaviors() {
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
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
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

    public function actionIndex() {
        //$orderSearch=Array ( 
        //    'order_id' =>'',
        //    'pos.pos_title' => '', 
        //    'order_datetime_min' =>date('Y-m-d 00:00:00'), 
        //    'order_datetime_max' =>date('Y-m-d 23:59:59'),
        //    'sysuser.sysuser_fullname' =>'',
        //    'packaging_title'=>''
        //) ;
        //$query = Report::posIncomeReport($orderSearch);
        //$provider = new ActiveDataProvider([
        //    'query' => $query,
        //    'pagination' => ['pageSize' => 20,],
        //]);

        return $this->render('index', [
                        //'report' => $provider,
                        //'query'  => $query
        ]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $sysuser = \Yii::$app->user->getIdentity();

            // save login event to log
            $model = new Log();
            $model->sysuser_id = $sysuser->sysuser_id;
            $model->log_action = 'login';
            $model->log_data = '';
            $model->log_date = date('Y-m-d');
            $model->log_datetime = date('Y-m-d H:i:s');
            $model->save();

            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {

        if (!\Yii::$app->user->isGuest) {
            $sysuser = \Yii::$app->user->getIdentity();
            // save login event to log
            $model = new Log();
            $model->sysuser_id = $sysuser->sysuser_id;
            $model->log_action = 'logout';
            $model->log_data = '';
            $model->log_date = date('Y-m-d');
            $model->log_datetime = date('Y-m-d H:i:s');
            $model->save();
        }


        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAbout() {
        return $this->render('about');
    }

}
