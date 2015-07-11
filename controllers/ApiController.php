<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use app\models\Customer;
use app\models\OrderSearch;
use yii\helpers\ArrayHelper;
use app\models\Report;
use app\models\Workingtime;
use yii\db\Query;

class ApiController extends \yii\web\Controller {

    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->enableCsrfValidation = false;
    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionOrderreport() {



        // $post = \Yii::$app->request->queryParams;
        //
        $post = Yii::$app->getRequest()->getBodyParams();
        if (isset($post['pos_pos_title'])) {
            $post['pos.pos_title'] = $post['pos_pos_title'];
        }
        if (isset($post['sysuser_sysuser_fullname'])) {
            $post['sysuser.sysuser_fullname'] = $post['sysuser_sysuser_fullname'];
        }

        // print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }


        $rowsPerPage = 100;
        $page = isset($post['page']) ? max(0, (int) $post['page']) : 0;

        //addOrderBy()

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $post);

        //print_r($dataProvider->query->createCommand()->sql); echo "<hr>";
        // print_r($dataProvider->query->count());



        $json = [];

        $json['posOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(), 'pos_title', 'pos_title');
        $json['sellerOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(), 'sysuser_fullname', 'sysuser_fullname');
        $json['paymentTypeOptions'] = ArrayHelper::map($nOrders = \Yii::$app->db->createCommand("select distinct order_payment_type from `order`", [])->queryAll(), 'order_payment_type', 'order_payment_type');

        $json['n_records'] = $dataProvider->query->count();
        $json['total'] = $searchModel->getOrderTotal($post);
        $pagination = new \yii\data\Pagination(['totalCount' => $json['n_records'], 'pageSize' => $rowsPerPage]);
        $json['page'] = $page;
        $json['pageCount'] = $pagination->getPageCount();



        $dataProvider->query->offset($rowsPerPage * $page);

        $dataProvider->query->limit($rowsPerPage);


        if (isset($post['sort'])) {
            $sort = preg_replace('/[^0-9a-z._-]/i', '', trim($post['sort']));
            //exit('sort='.$sort);

            if (strlen($sort) > 0) {
                if (substr($sort, 0, 1) == '-') {
                    $dataProvider->query->addOrderBy(substr($sort, 1) . " DESC");
                    $json['sort'] = "$sort";
                } else {
                    $dataProvider->query->addOrderBy("$sort ASC");
                    $json['sort'] = "$sort";
                }
            } else {
                $json['sort'] = "";
            }
        } else {
            $json['sort'] = "";
        }

        $json['post'] = $post;
        $json['rows'] = [];
        $rows = $dataProvider->query->all();
        $cnt = count($rows);
        $pos = [];
        for ($i = 0; $i < $cnt; $i++) {
            $json['rows'][$i] = $rows[$i]->attributes;
            if (!isset($pos[$json['rows'][$i]['pos_id']])) {
                $pos[$json['rows'][$i]['pos_id']] = $rows[$i]->getPos()->one();
            }
            $json['rows'][$i]['pos_title'] = $pos[$json['rows'][$i]['pos_id']]->pos_title;
        }

        return json_encode($json);
    }

    public function actionSellerreport() {

        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        // print_r($post);   echo "<hr>";

        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'income' => [],
                'workingtime' => [],
                'post' => $post,
            ];
            return json_encode($json);
        }

        $json = [];
        $json['post'] = $post;

        // --------- workingtime - begin ---------------------------------------
        Workingtime::calculateAllWorkingTimes();
        $where = [];
        if (isset($post['order_datetime_min']) && strlen($post['order_datetime_min']) > 0) {
            $timestamp = strtotime($post['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d', $timestamp);
                $where[] = " wt.workingtime_date>='$min_date' ";
            }
        }
        if (isset($post['order_datetime_max']) && strlen($post['order_datetime_max']) > 0) {
            $timestamp = strtotime($post['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d', $timestamp);
                $where[] = " wt.workingtime_date<='$max_date' ";
            }
        }
        if (count($where) > 0) {
            $where = ' WHERE ' . join(' AND ', $where);
        } else {
            $where = '';
        }
        $sql = "SELECT wt.seller_id,
                     SUM(wt.workingtime_seconds)/3600 AS workingtime_hours, 
                     SUM(wt.workingtime_wage) AS workingtime_wage
                FROM `workingtime` wt
                $where
                GROUP BY wt.seller_id
              ";
        $data = \Yii::$app->db->createCommand($sql, [])->queryAll();
        $workingtime = [];
        foreach ($data as $dt) {
            $workingtime[$dt['seller_id']] = $dt;
        }
        // echo '<pre>'; print_r($workingtime); echo '</pre>';
        $json['workingtime'] = $workingtime;
        // --------- workingtime - begin ---------------------------------------
        // --------- seller report - begin -------------------------------------
        $where = [];
        if (isset($post['order_datetime_min']) && strlen($post['order_datetime_min']) > 0) {
            $timestamp = strtotime($post['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $where[] = " o.order_datetime>='$min_date' ";
            }
        }
        if (isset($post['order_datetime_max']) && strlen($post['order_datetime_max']) > 0) {
            $timestamp = strtotime($post['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $where[] = " o.order_datetime<='$max_date' ";
            }
        }
        if (count($where) > 0) {
            $where = ' WHERE ' . join(' AND ', $where);
        } else {
            $where = '';
        }
        $sql = "SELECT o.sysuser_id,o.seller_id, o.sysuser_fullname, 
                     SUM(o.order_total) AS order_total, 
                     COUNT(o.order_id) AS order_count,
                     AVG(if(o.order_total>0,o.order_total,0)) AS order_average
              FROM `order` o
              $where
              GROUP BY o.sysuser_id
              ORDER BY o.sysuser_fullname";
        // echo '<pre>'; print_r($data); echo '</pre>';
        $json['income'] = \Yii::$app->db->createCommand($sql, [])->queryAll();
        // --------- seller report - end ---------------------------------------
        //echo '<pre>'; print_r($json); echo '</pre>';
        return json_encode($json);
    }

    public function actionCustomerincomereport() {


        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        // print_r($post);   echo "<hr>";
        // print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }





        $rowsPerPage = 20;
        $page = isset($post['page']) ? max(0, (int) $post['page']) : 0;

        //print_r($dataProvider->query->createCommand()->sql); echo "<hr>";
        // print_r($dataProvider->query->count());
        // posted data
        $query = new \yii\db\Query;

        $query->select('c.customerId, c.customerMobile, c.customerName, SUM(o.order_total) AS total')
                ->from('customer c')
                ->leftJoin('`order` o', 'o.customerId=c.customerId')
                ->groupBy(['c.customerId'])
        ;
        if (isset($post['order_datetime_min']) && strlen($post['order_datetime_min']) > 0) {
            $timestamp = strtotime($post['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($post['order_datetime_max']) && strlen($post['order_datetime_max']) > 0) {
            $timestamp = strtotime($post['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        if (isset($post['customerMobile']) && strlen($post['customerMobile']) > 0) {
            $query->andWhere(" LOCATE(:customerMobileSubstring, c.customerMobile) ", ['customerMobileSubstring' => $post['customerMobile']]);
        }
        if (isset($post['customerName']) && strlen($post['customerName']) > 0) {
            $query->andWhere(" LOCATE(:customerNameSubstring, c.customerName) ", ['customerNameSubstring' => $post['customerName']]);
        }



        $json = [];

        //$json['posOptions']=ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(),'pos_title','pos_title');
        //$json['sellerOptions']=ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(),'sysuser_fullname','sysuser_fullname');
        //$json['paymentTypeOptions'] = ArrayHelper::map($nOrders=\Yii::$app->db->createCommand("select distinct order_payment_type from `order`", [])->queryAll(),'order_payment_type','order_payment_type');

        $json['n_records'] = $query->count();
        $pagination = new \yii\data\Pagination(['totalCount' => $json['n_records'], 'pageSize' => $rowsPerPage]);
        $json['page'] = $page;
        $json['pageCount'] = $pagination->getPageCount();



        $query->offset($rowsPerPage * $page);

        $query->limit($rowsPerPage);

        $json['sort'] = "";
        if (isset($post['sort'])) {
            $sort = preg_replace('/[^0-9a-z._-]/i', '', trim($post['sort']));
            //exit('sort='.$sort);

            if (strlen($sort) > 0) {
                if (substr($sort, 0, 1) == '-') {
                    $query->addOrderBy(substr($sort, 1) . " DESC");
                    $json['sort'] = "$sort";
                } else {
                    $query->addOrderBy("$sort ASC");
                    $json['sort'] = "$sort";
                }
            }
        }

        $json['post'] = $post;
        $json['rows'] = $query->all();
        //        print_r($rows);
        //        exit('<hr>');
        //        $cnt=count($rows);
        //        for($i=0;$i<$cnt;$i++){
        //            $json['rows'][$i]=$rows[$i]->attributes;
        //        }

        return json_encode($json);
    }

    public function actionProductreport() {
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        // print_r($post);   echo "<hr>";
        // print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }


        $query = Report::productReport($post);

        $rowsPerPage = 100;
        $page = isset($post['page']) ? max(0, (int) $post['page']) : 0;

        $json = [];

        $json['posOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(), 'pos_title', 'pos_title');
        $json['sellerOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(), 'sysuser_fullname', 'sysuser_fullname');
        $json['paymentTypeOptions'] = ArrayHelper::map($nOrders = \Yii::$app->db->createCommand("select distinct order_payment_type from `order`", [])->queryAll(), 'order_payment_type', 'order_payment_type');

        $json['n_records'] = $query->count();
        $pagination = new \yii\data\Pagination(['totalCount' => $json['n_records'], 'pageSize' => $rowsPerPage]);
        $json['page'] = $page;
        $json['pageCount'] = $pagination->getPageCount();


        $query->offset($rowsPerPage * $page);

        $query->limit($rowsPerPage);

        $json['sort'] = "";
        if (isset($post['sort'])) {
            $sort = preg_replace('/[^0-9a-z._-]/i', '', trim($post['sort']));
            //exit('sort='.$sort);

            if (strlen($sort) > 0) {
                if (substr($sort, 0, 1) == '-') {
                    $query->addOrderBy(substr($sort, 1) . " DESC");
                    $json['sort'] = "$sort";
                } else {
                    $query->addOrderBy("$sort ASC");
                    $json['sort'] = "$sort";
                }
            }
        }

        $json['post'] = $post;
        $json['rows'] = $query->all();
        //        print_r($rows);
        //        exit('<hr>');
        //        $cnt=count($rows);
        //        for($i=0;$i<$cnt;$i++){
        //            $json['rows'][$i]=$rows[$i]->attributes;
        //        }

        return json_encode($json);
    }

    public function actionPackagingreport() {
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }


        $query = Report::packagingReport($post);

        $rowsPerPage = 100;
        $page = isset($post['page']) ? max(0, (int) $post['page']) : 0;

        $json = [];
        $json['categoryOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct category_id,category_title from `category`order by category_title", [])->queryAll(), 'category_id', 'category_title');
        $json['posOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(), 'pos_title', 'pos_title');
        $json['sellerOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(), 'sysuser_fullname', 'sysuser_fullname');

        $json['n_records'] = $query->count();
        $pagination = new \yii\data\Pagination(['totalCount' => $json['n_records'], 'pageSize' => $rowsPerPage]);
        $json['page'] = $page;
        $json['pageCount'] = $pagination->getPageCount();

        $json['maxCount'] = Report::packagingReportCount($post);

        $query->offset($rowsPerPage * $page);

        $query->limit($rowsPerPage);

        $json['sort'] = "";
        if (isset($post['sort'])) {
            $sort = preg_replace('/[^0-9a-z._-]/i', '', trim($post['sort']));
            //exit('sort='.$sort);

            if (strlen($sort) > 0) {
                if (substr($sort, 0, 1) == '-') {
                    $query->addOrderBy(substr($sort, 1) . " DESC");
                    $json['sort'] = "$sort";
                } else {
                    $query->addOrderBy("$sort ASC");
                    $json['sort'] = "$sort";
                }
            }
        }


        $json['post'] = $post;

        // echo $query->createCommand()->sql;
        $json['rows'] = $query->all();
        //        print_r($rows);
        //        exit('<hr>');

        return json_encode($json);
    }

    public function actionPosincomereport() {
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }
        $query = Report::posIncomeReport($post);

        $json = [];
        $json['post'] = $post;
        $json['rows'] = $query->all();
        return json_encode($json);
    }
    
    
    
    public function actionHourlyincomereport() {
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }
        $json=[
            'stats' => Report::incomeByHourReport(),
            'profit'=>Report::profitHourly(),
            'count'=>Report::countOrdersHourly(),
        ];
        $json['posOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(), 'pos_title', 'pos_title');
        $json['sellerOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(), 'sysuser_fullname', 'sysuser_fullname');

        return json_encode($json);
    }
    
    
    
    
    public function actionWeekdailyincomereport() {
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }
        $json=[
            'stats' => Report::incomeByWeekday($post),
            'profit'=>Report::profitWeekDaily($post)
        ];
        $json['posOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(), 'pos_title', 'pos_title');
        $json['sellerOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(), 'sysuser_fullname', 'sysuser_fullname');

        return json_encode($json);
    }
    
    
    
    
    public function actionDailyincomereport() {
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }
        
        $json=[
            'stats' => Report::incomeDaily($post),
            'profit'=> Report::profitDaily($post)
        ];
        $json['posOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(), 'pos_title', 'pos_title');
        $json['sellerOptions'] = ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `order`", [])->queryAll(), 'sysuser_fullname', 'sysuser_fullname');

        return json_encode($json);
    }

    
    
    public function actionOrderview(){
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }
        
        
        $json=[];
        $model=Order::findOne(['order_id'=>$post['order_id']]);
        $json['order']=$model->attributes;
        
        $json['pos']=$model->getPos()->one()->attributes;
        $json['sysuser']=$model->getSysuser()->one()->attributes;

        if($json['order']['customerId']>0){
            $json['customer']=$model->getCustomer()->one()->attributes;
        }
        
        $json['items']=$model->getOrderPackagings()->all();
        $cnt=count($json['items']);
        for( $i = 0 ; $i < $cnt ; $i++ ){
            $json['items'][$i]=$json['items'][$i]->attributes;
        }
        // print_r($json);
        return json_encode($json);
    }
    
    
    public function actionOnecustomer(){
        $post = array_merge(Yii::$app->request->queryParams, Yii::$app->getRequest()->getBodyParams());
        //print_r($post);   echo "<hr>";
        //print_r($post);   echo "<hr>";
        $key = md5($post['time'] . Yii::$app->params['apiKey']);
        $time = strtotime(gmdate('Y-m-d H:i:s'));
        // echo "{$post['time']} ".time().' key='.$key.'  '.$post['key'];   echo "<hr>";
        if ($key != $post['key'] || $post['time'] <= $time) {
            // access denied error
            $json = [
                "status" => "error",
                'posOptions' => [],
                'sellerOptions' => [],
                'paymentTypeOptions' => [],
                'n_records' => 0,
                'total' => 0,
                'page' => 0,
                'pageCount' => 0,
                'sort' => '',
                'post' => $post,
                'rows' => []
            ];
            return json_encode($json);
        }
                // get customer info
        $customer = Customer::findOne($post['customerId']);
        
        
        // query to select all customer's orders
        
        $query = new Query;
        $query->select('o.*, pos.pos_title')->from('`order` o')->join('INNER JOIN', 'pos', 'o.pos_id=pos.pos_id') ;
        $query->andFilterWhere(['customerId' => $post['customerId'], ]);
        
        if (isset($post['order_datetime_min']) && strlen($post['order_datetime_min']) > 0) {
            $timestamp = strtotime($post['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($post['order_datetime_max']) && strlen($post['order_datetime_max']) > 0) {
            $timestamp = strtotime($post['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        
        
        $json=[];
        $json['customer']=$customer->attributes;
        $json['orders']=$query->all();
        // echo '<pre>'; print_r($json); echo '</pre>'; 
        return json_encode($json);
    }
}
