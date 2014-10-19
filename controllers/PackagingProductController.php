<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\PackagingProduct;


class PackagingProductController extends \yii\web\Controller {

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
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate() {
        $product_id=\Yii::$app->request->post('product_id');
        $packaging_id=\Yii::$app->request->post('packaging_id');
        $packaging_product_quantity=\Yii::$app->request->post('packaging_product_quantity');

        $model=new PackagingProduct();
        $model->product_id=$product_id;
        $model->packaging_id=$packaging_id;
        $model->packaging_product_quantity=str_replace(',','.',$packaging_product_quantity);
        $model->save();
        
        
        
        $item=Array();
        $product=$model->getProduct()->one();
        //var_dump($product);exit();
        $item['product_id']=$product->product_id;
        $item['product_title']=$product->product_title;
        $item['product_unit']=$product->product_unit;
        $item['product_unit_price']=$product->product_unit_price;
        $item['packaging_product_quantity']=$model->packaging_product_quantity;
        $item['packaging_product_price']=$model->packaging_product_quantity*$product->product_unit_price;

        //var_dump($result);
        return json_encode($item);
    }

    public function actionDelete() {
        $product_id=\Yii::$app->request->post('product_id');
        $packaging_id=\Yii::$app->request->post('packaging_id');
        $model=$this->findModel($product_id, $packaging_id);
        $model->delete();
        return 'OK';
    }

    public function actionIndex() {
        return 'OK';
    }

    public function actionUpdate() {
        $product_id=\Yii::$app->request->post('product_id');
        $packaging_id=\Yii::$app->request->post('packaging_id');
        $packaging_product_quantity=\Yii::$app->request->post('packaging_product_quantity');
        $model=$this->findModel($product_id, $packaging_id);
        $model->packaging_product_quantity=$packaging_product_quantity;
        $model->update();
        return 'OK';
    }

    protected function findModel($product_id, $packaging_id) {
        $model = PackagingProduct::find()->where(['product_id' => ((int) $product_id), 'packaging_id' => ((int) $packaging_id)])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
