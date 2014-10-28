<?php

namespace app\controllers;

class SellController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout="sell";
        
        return $this->render('index');
    }

}
