<?php

namespace app\controllers;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PosPackaging;

class PospackagingController extends \yii\web\Controller {

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
                'only' => ['update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionUpdate() {
        $packaging_id = \Yii::$app->request->post('packaging_id');
        $pos_id = \Yii::$app->request->post('pos_id');
        $pos_packaging_price = \Yii::$app->request->post('pos_packaging_price');

        $model = $this->findModel($packaging_id, $pos_id);
        if ($model !== null) {
            $model->pos_packaging_price = $pos_packaging_price;
            $model->update();
        } else {
            $model = new PosPackaging();
            $model->pos_id = $pos_id;
            $model->packaging_id = $packaging_id;
            $model->pos_packaging_price = $pos_packaging_price;
            $model->save();
        }
        return 'OK';
    }
    protected function findModel($packaging_id, $pos_id) {
        $model = PosPackaging::find()->where(['packaging_id' => ((int) $packaging_id), 'pos_id' => ((int) $pos_id)])->one();
        //if ($model !== null) {
        return $model;
        //} else {
        //    throw new NotFoundHttpException('The requested page does not exist.');
        //}
    }

}
