<?php

namespace app\controllers;

use app\models\Pos;
use app\models\Order;
use app\models\Packaging;
use app\models\OrderPackaging;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class SellController extends \yii\web\Controller {

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
                'only' => ['index', 'packaging', 'createorder', 'getorderid'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'packaging', 'createorder', 'getorderid'],
                        'roles' => ['admin','seller'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = "sell";

        return $this->render('index');
    }

    public function actionPackaging($pos_id) {
        
        $pos = Pos::findOne($pos_id);
        $packaging = $pos->getAvailablePackaging();
        //print_r($packaging);
        return json_encode($packaging);
    }

    public function actionCreateorder() {
        // get userId
        $sysuser = \Yii::$app->user->getIdentity();
        //print_r($sysuser->sysuser_id);exit();
        
        $seller = $pos->getSellers()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->one();
        // print_r($seller);exit();
        //print_r($role['admin']);exit();
        if ($seller === null) {
            $role = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
            // print_r($role); exit();
            if (!isset($role['admin'])) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        // get pos record
        $pos_id=$seller->pos_id;
        $pos = Pos::findOne($pos_id);

        $orderData=\Yii::$app->request->post('order');
        
        // order_packaging is array of ($packaging_id=>$order_packaging_number) pairs
        $tmp=$orderData['order_packaging'];
        $order_packaging=Array();
        $order_total=0;
        $pos_product_update=Array();
        foreach($tmp as $packaging_id=>$order_packaging_number){
            $packaging = Packaging::findOne($packaging_id);
            if($packaging!=null){
                $order_packaging[]=[
                    'packaging'=>$packaging,
                    'order_packaging_number'=>$order_packaging_number
                ];

                $order_total+=$packaging->packaging_price;
                
                $packagingProducts = $packaging->getPackagingProducts();
                foreach($packagingProducts as $pp){
                    if(!isset($pos_product_update[$pp->product_id])){
                        $pos_product_update[$pp->product_id]=0;
                    }
                    $pos_product_update[$pp->product_id]+=$pp->packaging_product_quantity;
                }
            }
        }
        
        // create order
        $order=new Order();
        $order->pos_id=$pos_id;
        $order->seller_id=$seller->seller_id;
        $order->sysuser_id=$sysuser->sysuser_id;
        $order->order_datetime=date('Y-m-d H:i:s');
        $order->order_day_sequence_number=0;
        $order->order_total=$order_total;
        $order->order_discount=0;
        $order->order_payment_type=$orderData['order_payment_type'];
        $order->order_hash=sha1("{$order->pos_id}+{$order->seller_id}+{$order->order_datetime}+{$order->order_total}+{$order->order_discount}"+Yii::$app->params['salt']);
        $order->save();

        // add packagings to order
        $order_id=$order->order_id;
        foreach($order_packaging as $op){
            $OP=new OrderPackaging();
            $OP->order_id=$order_id;
            $OP->packaging_id=$op['packaging']->packaging_id;
            $OP->packaging_title=$op['packaging']->packaging_title;
            $OP->packaging_price=$op['packaging']->packaging_price;
            $OP->order_packaging_number=$op['order_packaging_number'];
            $OP->save();
        }
        
        
        // update pos product quantity
        
        $orderInfo = $order;
    }

}
