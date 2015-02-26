<?php

namespace app\controllers;

use app\models\Pos;
use app\models\Order;
use app\models\Packaging;
use app\models\PosPackaging;
use app\models\OrderPackaging;
use app\models\Seller;
use app\models\Sysuser;
use app\models\Discount;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use himiklab\thumbnail\EasyThumbnailImage;
use app\models\Log;

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
                'only' => ['index', 'packaging', 'createorder', 'getorderid', 'posselector', 'return'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'packaging', 'createorder', 'getorderid', 'posselector', 'return'],
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
        $pos = Pos::findOne($pos_id);

        $sysuser = \Yii::$app->user->getIdentity();
        $seller = false;
        if ($pos) {
            // echo "pos ";
            // pos found
            // check access
            $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
            // user muss be seller or admin
            if (isset($roles['admin'])) {
                // admin can do anything
                $seller = Seller::find()->where([
                            'sysuser_id' => ((int) $sysuser->sysuser_id),
                            'pos_id' => ((int) $pos_id)
                        ])->one();
            } elseif (isset($roles['seller'])) {
                // if seller is attached to the POS
                $seller = Seller::find()->where([
                            'sysuser_id' => ((int) $sysuser->sysuser_id),
                            'pos_id' => ((int) $pos_id)
                        ])->one();
                if (!$seller) {
                    // seller cannot access other's POS
                    throw new NotFoundHttpException(Yii::t('app','There is not POS attached to seller'));
                }
            } else {
                // user is not seller nor admin
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        } else {
            //echo "pos not found";
            // pos not found
            $sellers = Seller::find()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->all();
            //var_dump($sellers);
            $cnt = count($sellers);
            //echo "cnt=$cnt";
            if ($cnt == 0) {

                // POS not found but admin can access any POS
                $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
                if (isset($roles['admin'])) {
                    return $this->redirect(['posselector']);
                } else {
                    // throw error
                    throw new NotFoundHttpException(\Yii::t('app','There is not POS attached to seller'));
                }
            } elseif ($cnt == 1) {
                if (isset($sellers[0])) {
                    $seller = $sellers[0];
                    //echo $seller->seller_id;
                    $pos = $seller->getPos()->one();
                    // print_r( $pos );
                } else {
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            } elseif ($cnt > 1) {
                // redirect to POS selector
                return $this->redirect(['posselector']);
            }
        }

        // list of discounts
        $discounts = Discount::find()->all();

        // save login event to log
        $logmodel = new Log();
        $logmodel->sysuser_id = $sysuser->sysuser_id;
        $logmodel->log_action = 'sellpage';
        $logmodel->log_data = "";
        $logmodel->log_date = date('Y-m-d');
        $logmodel->log_datetime = date('Y-m-d H:i:s');
        $logmodel->save();
        
        return $this->render('index', ['pos' => $pos, 'sysuser' => $sysuser, 'seller' => $seller, 'discounts' => $discounts]);
    }

    public function actionPosselector() {

        $sysuser = \Yii::$app->user->getIdentity();

        $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);


        if (isset($roles['admin'])) {
            $posList = Pos::find()->all();
        } elseif (isset($roles['seller'])) {
            $sellers = Seller::find()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->all();
            $posList = [];
            foreach ($sellers as $seller) {
                $posList[] = $seller->getPos()->one();
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $this->render('posselector', ['posList' => $posList,]);
    }

    public function actionPackaging($pos_id) {

        $pos = Pos::findOne($pos_id);
        if (!$pos) {
            $sysuser = \Yii::$app->user->getIdentity();
            $seller = $pos->getSellers()->where(['sysuser_id' => ((int) $sysuser->sysuser_id)])->one();
            $pos_id = $seller->pos_id;
            $pos = Pos::findOne($pos_id);
        }

        $packaging = $pos->getAvailablePackaging();
        //print_r($packaging);

        $cnt = count($packaging['packagingBasic']);
        for ($i = 0; $i < $cnt; $i++) {
            $it = &$packaging['packagingBasic'][$i];
            if (strlen($it['packaging_icon']) > 0) {
                $imageThumb = EasyThumbnailImage::thumbnailImg(
                                (\Yii::$app->params['file_root_dir'] . '/' . $it['packaging_icon']), \Yii::$app->params['icon_width'], \Yii::$app->params['icon_height'], EasyThumbnailImage::THUMBNAIL_OUTBOUND, ['alt' => $it['packaging_title']]
                );
            } else {
                $imageThumb = '';
            }
            $it['imageThumb'] = $imageThumb;
        }
        $cnt = count($packaging['packagingAdditional']);
        for ($i = 0; $i < $cnt; $i++) {
            $it = &$packaging['packagingAdditional'][$i];
            if (strlen($it['packaging_icon']) > 0) {
                $imageThumb = EasyThumbnailImage::thumbnailImg(
                                (\Yii::$app->params['file_root_dir'] . '/' . $it['packaging_icon']), \Yii::$app->params['icon_width'], \Yii::$app->params['icon_height'], EasyThumbnailImage::THUMBNAIL_OUTBOUND, ['alt' => $it['packaging_title']]
                );
            } else {
                $imageThumb = '';
            }
            $it['imageThumb'] = $imageThumb;
        }


        return json_encode($packaging);
    }

    public function actionCreateorder($pos_id) {
        // get userId
        $sysuser = \Yii::$app->user->getIdentity();

        // get pos record
        $pos = Pos::findOne($pos_id);
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

        $sysuser = Sysuser::findOne($sysuser->sysuser_id);

        $orderData = \Yii::$app->request->post('order');

        if(isset($orderData['order_datetime'])){
            $timestamp=strtotime($orderData['order_datetime']);
            if($timestamp!==false){
                $order_datetime = date('Y-m-d H:i:s',$timestamp);
            }else{
                $order_datetime = date('Y-m-d H:i:s');
            }
        }else{
            $order_datetime = date('Y-m-d H:i:s');
        }
        
        

        // check order_day_sequence_number
        //$order_day_sequence_number = Order::countOrders($pos_id, $order_datetime) + 1;
        //if ($order_day_sequence_number != $orderData['order_day_sequence_number']) {
        //    return "{'status':'error', 'message':'" . Yii::t('app', 'Duplicate Order') . "}";
        //}




        // order_packaging is array of ($packaging_id=>$order_packaging_number) pairs
        $tmp = $orderData['order_packaging'];
        $order_packaging = Array();
        $order_total = 0;
        $pos_product_update = Array();
        foreach ($tmp as $packaging_id => $order_packaging_number) {
            //$packaging = Packaging::findOne($packaging_id);
            $posPackaging = PosPackaging::find()->where(['packaging_id' => ((int) $packaging_id), 'pos_id' => ((int) $pos_id)])->one();
            if ($posPackaging) {
                $packaging = $posPackaging->getPackaging()->one();
                $packaging_price = $posPackaging->pos_packaging_price ? $posPackaging->pos_packaging_price : $packaging->packaging_price;
            } else {
                $packaging = Packaging::findOne($packaging_id);
                $packaging_price = $packaging->packaging_price;
            }

            if ($packaging != null) {
                $order_packaging[] = [
                    'packaging' => $packaging,
                    'order_packaging_number' => $order_packaging_number,
                    'packaging_price' => $packaging_price
                ];

                $order_total+=$packaging_price * $order_packaging_number;

                $packagingProducts = $packaging->getPackagingProducts()->all();
                foreach ($packagingProducts as $pp) {
                    if (!isset($pos_product_update[$pp->product_id])) {
                        $pos_product_update[$pp->product_id] = 0;
                    }
                    $pos_product_update[$pp->product_id]+=$pp->packaging_product_quantity * $order_packaging_number;
                }
            }
        }

        // create order
        $order = new Order();
        $order->pos_id = $pos_id;
        $order->seller_id = $seller->seller_id;
        $order->sysuser_id = $sysuser->sysuser_id;
        $order->sysuser_fullname = $sysuser->sysuser_fullname;
        // header('FullName:'.$sysuser->sysuser_fullname);
        // var_dump($sysuser);
        $order->order_datetime = $order_datetime;
        $order->order_day_sequence_number = Order::countOrders($pos_id, $order->order_datetime) + 1;
        $order->order_total = $order_total;
        $order->order_payment_type = $orderData['order_payment_type'];
        $order->order_seller_comission = ($seller->seller_commission_fee) * 0.01 * $order_total;

        if (isset($orderData['discount_id']) && $orderData['discount_id'] > 0) {
            $discount = Discount::findOne($orderData['discount_id']);
            if ($discount) {

                $order->discount_id = $discount->discount_id;
                $order->discount_title = $discount->discount_title;
                // update order_total
                $order->order_discount = Discount::getDiscountValue(
                                [
                            'discount_id' => $discount->discount_id,
                            'order_total' => $order_total,
                            'order_packaging' => $order_packaging
                                ], json_decode($discount->discount_rule));
                $order->order_total-=$order->order_discount;
            }
        }
        $order->customerId = (int)$orderData['customerId'];
        
        $order->order_hash = Order::createOrderHash($order->pos_id, $order->seller_id, $order->order_datetime, $order->order_total, $order->order_discount);

        $order->save();

        // add packagings to order
        $order_id = $order->order_id;
        foreach ($order_packaging as $op) {
            $OP = new OrderPackaging();
            $OP->order_id = $order_id;
            $OP->packaging_id = $op['packaging']->packaging_id;
            $OP->packaging_title = $op['packaging']->packaging_title;
            $OP->packaging_price = $op['packaging_price'];
            $OP->order_packaging_number = $op['order_packaging_number'];
            $OP->save();
        }

        // update pos product quantity
        $posProducts = $pos->getPosProducts()->all();
        foreach ($posProducts as $posProduct) {
            if (isset($pos_product_update[$posProduct->product_id])) {
                $posProduct->pos_product_quantity-=$pos_product_update[$posProduct->product_id];
                $posProduct->save();
            }
        }
        
        
        // save login event to log
        $logmodel = new Log();
        $logmodel->sysuser_id = $sysuser->sysuser_id;
        $logmodel->log_action = 'sell';
        $logmodel->log_data = "order_id={$order_id}\norder_total={$order_total}\norder_payment_type={$orderData['order_payment_type']}";
        $logmodel->log_date = date('Y-m-d');
        $logmodel->log_datetime = date('Y-m-d H:i:s');
        $logmodel->save();

        //return $order;
    }

    public function actionOrdernumber($pos_id) {
        return "{\"id\":" . (Order::countOrders($pos_id, date('Y-m-d H:i:s')) + 1) . "}";
    }

    public function actionSellerstats($seller_id) {
        $seller = Seller::findOne($seller_id);
        $stats = $seller->getSellerStats(date('Y-m-d'));
        //print_r($stats);
        return json_encode($stats);
    }

    public function actionReturn() {
        $this->layout = "fragment";
        // show order
        $orderDaySequenceNumber = \Yii::$app->request->get('orderDaySequenceNumber');
        $pos_id = \Yii::$app->request->get('pos_id');
        $formPosted = \Yii::$app->request->get('formPosted');
        // var_dump($orderDaySequenceNumber);
        if ($orderDaySequenceNumber) {
            $query = Order::find();
            $query->andFilterWhere([
                'order_day_sequence_number' => $orderDaySequenceNumber,
                'pos_id' => $pos_id
            ]);
            $query->andWhere("order_datetime>='".date('Y-m-d')."'");
            $model = $query->one();
        } else {
            $model = false;
        }
        return $this->render('return', [
              'model' => $model,
              'pos_id' => $pos_id,
              'formPosted'=>$formPosted
        ]);
    }

    function actionDoreturn($pos_id){
        // get userId
        $sysuser = \Yii::$app->user->getIdentity();

        // get pos record
        $pos = Pos::findOne($pos_id);
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

        $sysuser = Sysuser::findOne($sysuser->sysuser_id);

        $orderData = \Yii::$app->request->post('order');

        $order_datetime = date('Y-m-d H:i:s');


        // order_packaging is array of ($packaging_id=>$order_packaging_number) pairs
        $tmp = $orderData['order_packaging'];
        $order_packaging = Array();
        $order_total = 0;
        //$pos_product_update = Array();
        foreach ($tmp as $packaging_id => $order_packaging_number) {
            //$packaging = Packaging::findOne($packaging_id);
            $posPackaging = PosPackaging::find()->where(['packaging_id' => ((int) $packaging_id), 'pos_id' => ((int) $pos_id)])->one();
            if ($posPackaging) {
                $packaging = $posPackaging->getPackaging()->one();
                $packaging_price = $posPackaging->pos_packaging_price ? $posPackaging->pos_packaging_price : $packaging->packaging_price;
            } else {
                $packaging = Packaging::findOne($packaging_id);
                $packaging_price = $packaging->packaging_price;
            }

            if ($packaging != null) {
                $order_packaging[] = [
                    'packaging' => $packaging,
                    'order_packaging_number' => $order_packaging_number,
                    'packaging_price' => $packaging_price
                ];

                $order_total-=$packaging_price * $order_packaging_number;

                //$packagingProducts = $packaging->getPackagingProducts()->all();
                //foreach ($packagingProducts as $pp) {
                //    if (!isset($pos_product_update[$pp->product_id])) {
                //        $pos_product_update[$pp->product_id] = 0;
                //    }
                //    $pos_product_update[$pp->product_id]+=$pp->packaging_product_quantity * $order_packaging_number;
                //}
            }
        }

        // create order
        $order = new Order();
        $order->pos_id = $pos_id;
        $order->seller_id = $seller->seller_id;
        $order->sysuser_id = $sysuser->sysuser_id;
        $order->sysuser_fullname = $sysuser->sysuser_fullname;
        // header('FullName:'.$sysuser->sysuser_fullname);
        // var_dump($sysuser);
        $order->order_datetime = $order_datetime;
        $order->order_day_sequence_number = (int)$orderData['order_day_sequence_number'];
        $order->order_total = $order_total;
        $order->order_payment_type = $orderData['order_payment_type'];
        $order->order_seller_comission = ($seller->seller_commission_fee) * 0.01 * $order_total;

        $order->order_hash = Order::createOrderHash($order->pos_id, $order->seller_id, $order->order_datetime, $order->order_total, $order->order_discount);

        $order->save();

        // add packagings to order
        $order_id = $order->order_id;
        foreach ($order_packaging as $op) {
            $OP = new OrderPackaging();
            $OP->order_id = $order_id;
            $OP->packaging_id = $op['packaging']->packaging_id;
            $OP->packaging_title = $op['packaging']->packaging_title;
            $OP->packaging_price = $op['packaging_price'];
            $OP->order_packaging_number = $op['order_packaging_number'];
            $OP->save();
        }

        // update pos product quantity
        //$posProducts = $pos->getPosProducts()->all();
        //foreach ($posProducts as $posProduct) {
        //    if (isset($pos_product_update[$posProduct->product_id])) {
        //        $posProduct->pos_product_quantity-=$pos_product_update[$posProduct->product_id];
        //        $posProduct->save();
        //    }
        //}
        return 'OK';
    }
}
