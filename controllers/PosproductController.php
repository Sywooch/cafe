<?php

namespace app\controllers;

use app\models\PosProduct;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class PosproductController extends \yii\web\Controller {

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
                'only' => ['update','delete','updatequantity'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update','delete','updatequantity'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionUpdate() {
        $product_id = \Yii::$app->request->post('product_id');
        $pos_id = \Yii::$app->request->post('pos_id');
        $pos_product_min_quantity = \Yii::$app->request->post('pos_product_min_quantity');

        
        $model=$this->findModel($product_id, $pos_id);
        
        $model->pos_product_min_quantity=$pos_product_min_quantity;
        $model->update();
        return 'OK';
        //return $this->render('update');
    }
    
    public function actionUpdatequantity() {
        $product_id = \Yii::$app->request->post('product_id');
        $pos_id = \Yii::$app->request->post('pos_id');
        $pos_product_quantity = \Yii::$app->request->post('pos_product_quantity');

        
        $model=$this->findModel($product_id, $pos_id);
        $model->pos_product_quantity=$pos_product_quantity;
        $model->update();
        return 'OK';
        //return $this->render('update');
    }
    
    public function actionDelete(){
        $product_id = \Yii::$app->request->post('product_id');
        $pos_id = \Yii::$app->request->post('pos_id');
        $model=$this->findModel($product_id, $pos_id);
        $model->delete();
        return $this->redirect(['/pos/products']);
    }
    
    protected function findModel($product_id, $pos_id) {
        $model = PosProduct::find()->where(['product_id' => ((int) $product_id), 'pos_id' => ((int) $pos_id)])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
