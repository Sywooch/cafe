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

    public function actionCreate($product_id, $packaging_id, $packaging_product_quantity) {
        $model=new PackagingProduct();
        $model->product_id=$product_id;
        $model->packaging_id=$packaging_id;
        $model->packaging_product_quantity=$packaging_product_quantity;
        $model->save();
        return 'OK';
    }

    public function actionDelete($product_id, $packaging_id) {
        $model=$this->findModel($product_id, $packaging_id);
        $model->delete();
        return 'OK';
        //return $this->render('delete');
    }

    public function actionIndex() {
        return 'OK';
    }

    public function actionUpdate($product_id, $packaging_id, $packaging_product_quantity) {
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
