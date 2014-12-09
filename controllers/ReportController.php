<?php

namespace app\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Report;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

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
                'only' => ['index', 'seller', 'product','packaging','posincome','sellerincome'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'seller', 'product','packaging','posincome','sellerincome'],
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

        $report = Report::sellerReport();
        // print_r($report);
        return $this->render('seller', [
                    'report' => $report
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
        $provider = new ActiveDataProvider(
                [
            'query' => Report::packagingReport(),
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
                    'report' => $provider
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
        $stats = Report::incomeByHourReport();
        return $this->render('hourlyincome', [
            'stats' => $stats
        ]);
    }
    
    public function actionWeekdailyincome() {
        $stats = Report::incomeByWeekday();
        return $this->render('weekdailyincome', [
            'stats' => $stats
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
}
