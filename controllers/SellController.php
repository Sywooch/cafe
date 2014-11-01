<?php

namespace app\controllers;

use app\models\Pos;
use app\models\Order;
use app\models\Packaging;
use app\models\OrderPackaging;
use app\models\Seller;
use app\models\Discount;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use himiklab\thumbnail\EasyThumbnailImage;

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
                'only' => ['index', 'packaging', 'createorder', 'getorderid','posselector'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'packaging', 'createorder', 'getorderid','posselector'],
                        'roles' => ['admin', 'seller'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $this->layout = "sell";

        // check pos_id
        $pos_id = (int) \Yii::$app->request->get('pos_id');
        $pos=Pos::findOne($pos_id);
        
        $sysuser = \Yii::$app->user->getIdentity();
        $seller=false;
        if ($pos){
            // pos found
            // check access
            $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
            // user muss be seller or admin
            if(isset($roles['admin'])){
                // admin can do anything
                $seller = Seller::find()->where([
                    'sysuser_id' => ((int) $sysuser->sysuser_id),
                    'pos_id'=>((int)$pos_id)
                    ])->one();
            }elseif(isset($roles['seller'])){
                // if seller is attached to the POS
                $seller = Seller::find()->where([
                    'sysuser_id' => ((int) $sysuser->sysuser_id),
                    'pos_id'=>((int)$pos_id)
                    ])->one();
                if(!$seller){
                    // seller cannot access other's POS
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }else{
                // user is not seller nor admin
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            // pos not found
            $sellers = Seller::find()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->all();
            $cnt=count($seller);
            if($cnt==0){
                // POS not found but admin can access any POS
                $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
                if (isset($roles['admin'])) {
                    return $this->redirect(['posselector']);
                }else{
                    // throw error
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }elseif($cnt==1){
                $seller=$sellers[0];
                $pos = $seller->getPos();
            }elseif($cnt>1){
                // redirect to POS selector
                return $this->redirect(['posselector']);
            }
        }
        return $this->render('index',['pos' => $pos,'sysuser'=>$sysuser,'seller'=>$seller]);
    }

    public function actionPosselector() {

        $sysuser = \Yii::$app->user->getIdentity();
        
        $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
        
        
        if(isset($roles['admin'])){
            $posList=Pos::find()->all();
        }elseif(isset($roles['seller'])){
            $sellers = Seller::find()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->all();
            $posList=[];
            foreach($sellers as $seller){
                $posList[]=$seller->getPos();
            }
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }            
        return $this->render('posselector',['posList' => $posList,]);
    }
    
    public function actionPackaging($pos_id) {

        $pos = Pos::findOne($pos_id);
        if(!$pos){
            $sysuser = \Yii::$app->user->getIdentity();
            $seller = $pos->getSellers()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->one();
            $pos_id = $seller->pos_id;
            $pos = Pos::findOne($pos_id);
        }
        
        $packaging = $pos->getAvailablePackaging();
        //print_r($packaging);

        $cnt=count($packaging['packagingBasic']);
        for($i=0; $i<$cnt; $i++){
            $it = &$packaging['packagingBasic'][$i];
            if(strlen($it['packaging_icon'])>0){
                $imageThumb = EasyThumbnailImage::thumbnailImg(
                        (\Yii::$app->params['file_root_dir'] . '/' . $it['packaging_icon']), \Yii::$app->params['icon_width'], \Yii::$app->params['icon_height'], 
                        EasyThumbnailImage::THUMBNAIL_OUTBOUND, ['alt' => $it['packaging_title']]
                );
            }else{
                $imageThumb = '';
            }
            $it['imageThumb']=$imageThumb;            
        }
        $cnt=count($packaging['packagingAdditional']);
        for($i=0; $i<$cnt; $i++){
            $it = &$packaging['packagingAdditional'][$i];
            if(strlen($it['packaging_icon'])>0){
                $imageThumb = EasyThumbnailImage::thumbnailImg(
                        (\Yii::$app->params['file_root_dir'] . '/' . $it['packaging_icon']), \Yii::$app->params['icon_width'], \Yii::$app->params['icon_height'], 
                        EasyThumbnailImage::THUMBNAIL_OUTBOUND, ['alt' => $it['packaging_title']]
                );
            }else{
                $imageThumb = '';
            }
            $it['imageThumb']=$imageThumb;            
        }
        
        
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
        $pos_id = $seller->pos_id;
        $pos = Pos::findOne($pos_id);

        $orderData = \Yii::$app->request->post('order');

        // order_packaging is array of ($packaging_id=>$order_packaging_number) pairs
        $tmp = $orderData['order_packaging'];
        $order_packaging = Array();
        $order_total = 0;
        $pos_product_update = Array();
        foreach ($tmp as $packaging_id => $order_packaging_number) {
            $packaging = Packaging::findOne($packaging_id);
            if ($packaging != null) {
                $order_packaging[] = [
                    'packaging' => $packaging,
                    'order_packaging_number' => $order_packaging_number
                ];

                $order_total+=$packaging->packaging_price;

                $packagingProducts = $packaging->getPackagingProducts();
                foreach ($packagingProducts as $pp) {
                    if (!isset($pos_product_update[$pp->product_id])) {
                        $pos_product_update[$pp->product_id] = 0;
                    }
                    $pos_product_update[$pp->product_id]+=$pp->packaging_product_quantity;
                }
            }
        }

        // create order
        $order = new Order();
        $order->pos_id = $pos_id;
        $order->seller_id = $seller->seller_id;
        $order->sysuser_id = $sysuser->sysuser_id;
        $order->order_datetime = date('Y-m-d H:i:s');
        $order->order_day_sequence_number = Order::countOrders($pos_id,$order->order_datetime)+1;
        $order->order_total = $order_total;
        $order->order_payment_type = $orderData['order_payment_type'];

        if( isset($orderData['discount_id']) && $orderData['discount_id']>0 ){
            $discount=Discount::findOne($orderData['discount_id']);
            if($discount){
                $order->order_discount = $orderData['order_discount'];
                $order->discount_id = $discount->discount_id;
                $order->discount_title = $discount->discount_title;
            }
        }

        $order->order_hash = Order::createOrderHash($order->pos_id, $order->seller_id, $order->order_datetime, $order->order_total, $order->order_discount);

        $order->save();

        // add packagings to order
        $order_id = $order->order_id;
        foreach ($order_packaging as $op) {
            $OP = new OrderPackaging();
            $OP->order_id = $order_id;
            $OP->packaging_id = $op['packaging']->packaging_id;
            $OP->packaging_title = $op['packaging']->packaging_title;
            $OP->packaging_price = $op['packaging']->packaging_price;
            $OP->order_packaging_number = $op['order_packaging_number'];
            $OP->save();
        }

        // update pos product quantity
        $posProducts=$pos->getPosProducts();
        foreach($posProducts as $posProduct){
            if(isset($pos_product_update[$posProduct->product_id])){
                $posProduct->pos_product_quantity-=$pos_product_update[$posProduct->product_id];
                $posProduct->save();
            }
        }

        // return new order info
    }

    public function actionOrdernumber($pos_id){
        return "{\"id\":".(Order::countOrders($pos_id,date('Y-m-d H:i:s'))+1)."}";
    }
    
    public function actionSellerstats($seller_id){
        $seller=Seller::findOne($seller_id);
        $stats=$seller->getSellerStats(date('Y-m-d'));
        //print_r($stats);
        return json_encode($stats);
    }
}
