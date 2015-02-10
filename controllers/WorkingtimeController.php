<?php

namespace app\controllers;

use app\models\Workingtime;

class WorkingtimeController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTest3($pos_id){
        print_r(Workingtime::getCurrentPosSeller($pos_id));
        return 'OK';
    }

    public function actionTest2($log_date_from,$log_date_to){
        Workingtime::calculateWorkingTime($log_date_from,$log_date_to);
        return 'OK';
    }
    
    public function actionTest1($seller_id, $log_date){
        //echo "$seller_id, $log_date<hr>";
        Workingtime::calculateSellerDayWorkingtime($seller_id, $log_date);
        return 'OK';
    }
}
