<?php

namespace app\controllers;

use Yii;
use app\models\Subsystem;
use app\models\SubsystemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SubsystemController implements the CRUD actions for Subsystem model.
 */
class SubsystemController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','delete', 'index',  'update', 'view','reports','orderreport','sellerreport'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update','delete','reports','orderreport','sellerreport'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Subsystem models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SubsystemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subsystem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subsystem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Subsystem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->subsystemId]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subsystem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->subsystemId]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionReports($subsystemId) {
        $model = $this->findModel($subsystemId);
        return $this->render('reports', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Subsystem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Subsystem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Subsystem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Subsystem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionOrderreport() {
        $post = Yii::$app->request->queryParams;
        // var_dump($post);echo '<hr>';
        if(!isset($post['order_id'])){ $post['order_id']='';  }
        if(!isset($post['pos.pos_title'])){ $post['pos.pos_title']='';  }
        if( isset($post['pos_pos_title'])){ $post['pos.pos_title']=$post['pos_pos_title'];  }
        if(!isset($post['sysuser.sysuser_fullname'])){ $post['sysuser.sysuser_fullname']='';  }
        if( isset($post['sysuser_sysuser_fullname'])){ $post['sysuser.sysuser_fullname']=$post['sysuser_sysuser_fullname'];  }
        if(!isset($post['order_payment_type'])){ $post['order_payment_type']='';  }
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['order_total_min'])){ $post['order_total_min']='';  }
        if(!isset($post['order_total_max'])){ $post['order_total_max']='';  }
        //var_dump($post);echo '<hr>';
        
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::orderreport($subsystem, $post);
        //echo '<pre>';print_r($data);echo '</pre><hr>';exit();

        $sysuser = \Yii::$app->user->getIdentity();
        $role = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);

        return $this->render('orderreport', [
                    'data' => $data,
                    'role' => $role,
                    'post' => $post,
                    'subsystem'=>$subsystem
        ]);
    }

    
    
    public function actionSellerreport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::sellerreport($subsystem, $post);
        return $this->render('sellerreport', [
                    'data' => $data,
                    'post' => $post,
                    'subsystem'=>$subsystem
        ]);
    }
    
    
    public function actionCustomerincomereport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['customerMobile'])){ $post['customerMobile']='';  }
        if(!isset($post['customerName'])){ $post['customerName']='';  }
        if(!isset($post['sort'])){ $post['sort']='';  }
        
        
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::customerincomereport($subsystem, $post);
        return $this->render('customerincomereport', [
                    'data' => $data,
                    'post' => $post,
                    'subsystem'=>$subsystem
        ]);
    }
    
    public function actionProductreport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['sort'])){ $post['sort']='';  }
        
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['pos.pos_title'])){ $post['pos.pos_title']='';  }
        if( isset($post['pos_pos_title'])){ $post['pos.pos_title']=$post['pos_pos_title'];  }
        if(!isset($post['product_title'])){ $post['product_title']='';  }
        if(!isset($post['sysuser.sysuser_fullname'])){ $post['sysuser.sysuser_fullname']='';  }
        if( isset($post['sysuser_sysuser_fullname'])){ $post['sysuser.sysuser_fullname']=$post['sysuser_sysuser_fullname'];  }

        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::productreport($subsystem, $post);
        return $this->render('productreport', [
                    'data' => $data,
                    'post' => $post,
                    'subsystem'=>$subsystem
        ]);
    }
    

    public function actionPackagingreport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['sort'])){ $post['sort']='';  }
        
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['pos.pos_title'])){ $post['pos.pos_title']='';  }
        if( isset($post['pos_pos_title'])){ $post['pos.pos_title']=$post['pos_pos_title'];  }
        if(!isset($post['product_title'])){ $post['product_title']='';  }
        if(!isset($post['sysuser.sysuser_fullname'])){ $post['sysuser.sysuser_fullname']='';  }
        if( isset($post['sysuser_sysuser_fullname'])){ $post['sysuser.sysuser_fullname']=$post['sysuser_sysuser_fullname'];  }
        if(!isset($post['packaging_title'])){ $post['packaging_title']='';  }
        if(!isset($post['category'])){ $post['category']='0';  }
        
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::packagingreport($subsystem, $post);
        
        return $this->render('packagingreport', [
                    'data' => $data,
                    'post' => $post,
                    'subsystem'=>$subsystem
        ]);
    }
    
    
    public function actionPosincomereport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::posincomereport($subsystem, $post);
        return $this->render('posincomereport', [
                    'data' => $data,
                    'post' => $post,
                    'subsystem'=>$subsystem
        ]);
    }
    
    
    
    public function actionHourlyincomereport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['pos.pos_title'])){ $post['pos.pos_title']='';  }
        if( isset($post['pos_pos_title'])){ $post['pos.pos_title']=$post['pos_pos_title'];  }
        if(!isset($post['sysuser.sysuser_fullname'])){ $post['sysuser.sysuser_fullname']='';  }
        if( isset($post['sysuser_sysuser_fullname'])){ $post['sysuser.sysuser_fullname']=$post['sysuser_sysuser_fullname'];  }
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::hourlyincomereport($subsystem, $post);
        return $this->render('hourlyincomereport', [
            'data' => $data,
            'post' => $post,
            'subsystem'=>$subsystem
        ]);
    }
        
    
    public function actionWeekdailyincomereport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['pos.pos_title'])){ $post['pos.pos_title']='';  }
        if( isset($post['pos_pos_title'])){ $post['pos.pos_title']=$post['pos_pos_title'];  }
        if(!isset($post['sysuser.sysuser_fullname'])){ $post['sysuser.sysuser_fullname']='';  }
        if( isset($post['sysuser_sysuser_fullname'])){ $post['sysuser.sysuser_fullname']=$post['sysuser_sysuser_fullname'];  }
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::weekdailyincomereport($subsystem, $post);
        return $this->render('weekdailyincomereport', [
            'data' => $data,
            'post' => $post,
            'subsystem'=>$subsystem
        ]);
    }
    
    
    public function actionDailyincomereport(){
        $post = array_merge(\Yii::$app->request->queryParams, \Yii::$app->getRequest()->getBodyParams());
        if(!isset($post['order_datetime_min'])){ $post['order_datetime_min']='';  }
        if(!isset($post['order_datetime_max'])){ $post['order_datetime_max']='';  }
        if(!isset($post['pos.pos_title'])){ $post['pos.pos_title']='';  }
        if( isset($post['pos_pos_title'])){ $post['pos.pos_title']=$post['pos_pos_title'];  }
        if(!isset($post['sysuser.sysuser_fullname'])){ $post['sysuser.sysuser_fullname']='';  }
        if( isset($post['sysuser_sysuser_fullname'])){ $post['sysuser.sysuser_fullname']=$post['sysuser_sysuser_fullname'];  }
        $subsystemId = $post['subsystemId'];
        $subsystem = $this->findModel($subsystemId);
        $data = Subsystem::dailyincomereport($subsystem, $post);
        return $this->render('dailyincomereport', [
            'data' => $data,
            'post' => $post,
            'subsystem'=>$subsystem
        ]);
    }
}
