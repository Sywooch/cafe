<?php

namespace app\controllers;

use app\models\Supply;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Pos;
use app\models\PosProduct;
use app\models\Product;

class SupplyController extends \yii\web\Controller {

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
                'only' => ['update', 'accept'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['accept'],
                        'roles' => ['admin', 'seller'],
                    ],
                ],
            ],
        ];
    }

    public function actionUpdate() {

        $product_id = \Yii::$app->request->post('product_id');
        $pos_id = \Yii::$app->request->post('pos_id');
        $supply_quantity = \Yii::$app->request->post('supply_quantity');


        $model = $this->findModel($product_id, $pos_id);
        if ($model !== null) {
            $model->supply_quantity = $supply_quantity;
            $model->update();
        } else {
            $model = new Supply();
            $model->pos_id = $pos_id;
            $model->product_id = $product_id;
            $model->supply_quantity = $supply_quantity;
            $model->save();
        }
        return 'OK';
    }

    public function actionAccept($pos_id) {

        //$pos_id = \Yii::$app->request->post('pos_id');
        $pos = Pos::findOne($pos_id);
        if ($pos === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
        // exit();
        // $this->findModel($id);

        $supplyList = Supply::find()->where(['pos_id' => ((int) $pos_id)])->all();
        foreach ($supplyList as $supply) {
            
            // increase PosProduct quantity
            $posproduct = PosProduct::find()->where(['product_id' => $supply->product_id, 'pos_id' => ((int) $pos_id)])->one();
            if ($posproduct !== null) {
                $posproduct->pos_product_quantity+=$supply->supply_quantity;
                $posproduct->update();
            } else {
                $posproduct=new PosProduct;
                $posproduct->product_id=$supply->product_id;
                $posproduct->pos_id=$supply->pos_id;
                $posproduct->pos_product_quantity=$supply->supply_quantity;
                $posproduct->pos_product_min_quantity=0;
                $posproduct->save();
            }

            // decrease product quantity
            $product=Product::findOne($supply->product_id);
            $product->product_quantity-=$supply->supply_quantity;
            $product->update();
            
            $supply->delete();
        }
        return 'OK';
    }

    protected function findModel($product_id, $pos_id) {
        $model = Supply::find()->where(['product_id' => ((int) $product_id), 'pos_id' => ((int) $pos_id)])->one();
        //if ($model !== null) {
        return $model;
        //} else {
        //    throw new NotFoundHttpException('The requested page does not exist.');
        //}
    }

}
