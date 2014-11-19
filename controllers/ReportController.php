<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Report;

/**
 * Description of ReportController
 *
 * @author dobro
 */
class ReportController extends Controller {

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
                'only' => ['index', 'seller'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'seller'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex() {
        return $this->render('index', []);
    }

    public function actionSeller(){
        
        $report=Report::sellerReport();
        // print_r($report);
        return $this->render('seller', [
            'report'=>$report
        ]);
    }
}
