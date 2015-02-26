<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Report;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use app\models\Workingtime;

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
                'only' => ['index', 'seller', 'product','packaging','posincome','sellerincome','onecustomer','customerincome'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'seller', 'product','packaging','posincome','sellerincome','onecustomer','customerincome'],
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

    public function actionSeller() {

        
        Workingtime::calculateAllWorkingTimes();
        // exit('5555');
        $workingtime=Report::sellerWorkingtimeReport();

        $report = Report::sellerReport();
        $query = Report::sellerIncomeReport();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'seller_id',
                    'seller_fullname',
                    'total',
                ],
            ])
        ]);
        
        //print_r($workingtime);
        // print_r($report);
        return $this->render('seller', [
            'report' => $report,
            'provider'=>$provider,
            'query'=>$query,
            'workingtime'=>$workingtime
        ]);
    }

    public function actionProduct() {

        $provider = new ActiveDataProvider(
                [
            'query' => Report::productReport(),
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'product_id',
                    'product_title',
                    'total_packaging_product_quantity',
                ],
            ])

        ]);
        return $this->render('product', [
                    'report' => $provider
        ]);
    }

    public function actionPackaging() {
        // posted data
        $orderSearch = \Yii::$app->request->get('OrderSearch');

        // get categories
        
        $provider = new ActiveDataProvider(
                [
            'query' => Report::packagingReport($orderSearch),
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'packaging_id',
                    'packaging_title',
                    'packaging_number',
                ],
            ])
        ]);
        
        
        return $this->render('packaging', [
            'report' => $provider,
            'maxCount'=>Report::packagingReportCount($orderSearch)
        ]);
    }
    public function actionPosincome() {
        $query = Report::posIncomeReport();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'pos_id',
                    'pos_title',
                    'total',
                ],
            ])
        ]);
        return $this->render('posincome', [
            'report' => $provider,
            'query'  => $query
        ]);
    }
    public function actionSellerincome() {
        $query = Report::sellerIncomeReport();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'seller_id',
                    'seller_fullname',
                    'total',
                ],
            ])
        ]);
        return $this->render('sellerincome', [
            'report' => $provider,
            'query'  => $query
        ]);
    }
    public function actionHourlyincome() {
        return $this->render('hourlyincome', [
            'stats' => Report::incomeByHourReport(),
            'profit'=>Report::profitHourly(),
            'count'=>Report::countOrdersHourly(),
        ]);
    }
    
    public function actionWeekdailyincome() {
        $stats = Report::incomeByWeekday();
        $profit= Report::profitWeekDaily();
        return $this->render('weekdailyincome', [
            'stats' => $stats,
            'profit'=>$profit
        ]);
    }
    public function actionDailyincome() {
        $stats = Report::incomeDaily();
        $profit= Report::profitDaily();
        return $this->render('dailyincome', [
            'stats' => $stats,
            'profit'=> $profit
        ]);
    }    
    
    
    public function actionCustomerincome() {
        $query = Report::customerIncomeReport();
        $data = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'customerMobile',
                    'customerName',
                    'total',
                ],
            ])
        ]);
        return $this->render('customerincome', [
            'data' => $data,
        ]);
    }    
    
    
    public function actionOnecustomer($customerId){
        
        $data=Report::onecustomerReport($customerId);
        $dataprovider = new ActiveDataProvider([
            'query' => $data['query'],
            'pagination' => ['pageSize' => 20,],
            'sort' => new Sort([
                'attributes' => [
                    'order_id',
                    'order_datetime',
                    'order_total',
                    'discount_title',
                    'order_discount',
                ],
            ])
        ]);
        return $this->render('onecustomer', [
            'dataprovider' => $dataprovider,
            'customer'=>$data['customer']
        ]);
    }
}
