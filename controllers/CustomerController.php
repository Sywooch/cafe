<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Order;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','delete', 'index',  'update', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update','delete'],
                        'roles' => ['admin'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->customerId]);
            return '{"status":"success"}';
        } else {
            //print_r($model->errors);
            return '{"status":"error"}';
            //return $this->render('create', [
            //    'model' => $model,
            //]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return '{"status":"success"}';
            //return $this->redirect(['view', 'id' => $model->customerId]);
        } else {
            //print_r($model->errors);
            return '{"status":"error"}';
            //return $this->render('update', [
            //    'model' => $model,
            //]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
    public function actionSearch($tel){
        //$query = Customer::find();
        $query = new Query;
        $query->select('c.customerId, c.customerMobile, c.customerName, c.customerNotes, SUM(o.order_total) AS total')
                ->from('customer c')
                ->leftJoin('`order` o', 'o.customerId=c.customerId')
                ->groupBy(['c.customerId'])
        ;
        $query->andWhere(" locate( :telephone, c.customerMobile) ", ['telephone' => $tel]);
        $query->limit(11);
        $result = [];
        $result['list'] = $query->all();//
        if(isset($result['list'][11])){
            $result['etc']=1;
            unset($result['list'][11]);
        }else{
            $result['etc']=0;
        }
                    
        if(count($result['list']) == 1){
            // search for 100 last orders
            $orderquery = Order::find();
            $orderquery->andFilterWhere(['customerId' => $result['list'][0]['customerId'],  ]);
            $orderquery->limit(100);
            $result['orders']=[];
            $tmp = $orderquery->all();
            foreach($tmp as $tm){
                $p=$tm->getPackagings()->all();
                $packaging=[];
                foreach($p as $r){
                    $packaging[]=[
                        'packaging_id' => $r->packaging_id,
                        'packaging_title'=> $r->packaging_title,
                        'packaging_price'=>  $r->packaging_price
                    ];
                }
                $result['orders'][]=[
                    'order_id'=> ($tm->order_id),
                    'order_datetime'=> date(Yii::t('app', 'date_format'), strtotime($tm->order_datetime)),
                    'order_total'=>($tm->order_total),
                    'order_discount'=>($tm->order_discount),
                    'order_currency'=>Yii::$app->params['currency'],
                    'order_payment_type'=>($tm->order_payment_type),
                    'discount_title'=>($tm->discount_title),
                    'packaging'=>$packaging,
                ];
            }

        }
        
        return json_encode($result);
    }

}
