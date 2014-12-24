<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Description of Report
 *
 * @author dobro
 */
class Report extends Model {

    public static function sellerReport() {

        $orderSearch = Yii::$app->request->get('OrderSearch');

        $where = [];

        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $where[] = " o.order_datetime>='$min_date' ";
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
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
        $sql = "SELECT su.sysuser_id,se.seller_id, su.sysuser_fullname, 
                     SUM(o.order_total) AS order_total, 
                     COUNT(o.order_id) AS order_count,
                     AVG(if(o.order_total>0,o.order_total,0)) AS order_average,
                     SUM(IF(o.order_seller_comission=0, se.seller_commission_fee*0.01*o.order_total,o.order_seller_comission)) AS order_seller_comission 
              FROM sysuser su 
                   INNER JOIN seller se USING(sysuser_id)
                   LEFT JOIN `order` o USING(sysuser_id)
              $where
              GROUP BY su.sysuser_id
              ORDER BY su.sysuser_fullname";
        $data = \Yii::$app->db->createCommand($sql, [])->queryAll();
        return $data;
    }

    public static function productReport() {

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        //$sql="SELECT product.product_id, product.product_title, SUM(packaging_product.packaging_product_quantity) AS total_packaging_product_quantity , product.`product_unit`
        //      FROM `order` o
        //           INNER JOIN order_packaging ON (o.order_id=order_packaging.order_id)
        //           INNER JOIN packaging_product ON order_packaging.packaging_id=packaging_product.packaging_id
        //           INNER JOIN `product` ON `product`.product_id=packaging_product.product_id
        //      $where
        //      GROUP BY `product`.product_id
        //";
        $query = new Query;
        $query->select('product.product_id, 
                        product.product_title, 
                        SUM(packaging_product.packaging_product_quantity) AS total_packaging_product_quantity,
                        product.`product_unit`')
                ->from('`order` o')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->innerJoin('order_packaging', 'o.order_id=order_packaging.order_id')
                ->innerJoin('packaging_product', 'order_packaging.packaging_id=packaging_product.packaging_id')
                ->innerJoin('product', '`product`.product_id=packaging_product.product_id')
                ->groupBy(['`product`.product_id'])
        ;

        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['product_title']) && strlen($orderSearch['product_title']) > 0) {
            $query->andWhere(" LOCATE (:product_title_value,product.product_title) ", ['product_title_value' => $orderSearch['product_title']]);
        }

        return $query;
    }

    public static function packagingReport() {

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        // SELECT order_packaging.packaging_id, order_packaging.packaging_title, SUM(order_packaging_number) AS packaging_number
        // FROM `order` o INNER JOIN order_packaging ON (o.order_id=order_packaging.order_id)

        $query = new Query;
        $query->select('order_packaging.packaging_id, order_packaging.packaging_title, SUM(order_packaging_number) AS packaging_number')
                ->from('`order` o')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->innerJoin('order_packaging', 'o.order_id=order_packaging.order_id')
                ->groupBy(['order_packaging.packaging_id'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['packaging_title']) && strlen($orderSearch['packaging_title']) > 0) {
            $query->andWhere(" LOCATE (:packaging_title_value,order_packaging.packaging_title) ", ['packaging_title_value' => $orderSearch['packaging_title']]);
        }

        return $query;
    }

    public static function posIncomeReport($orderSearch=false) {
        //SELECT pos.pos_id, pos.pos_title, SUM(o.order_total) AS total
        //FROM `order` o INNER JOIN pos ON o.pos_id=pos.pos_id
        //GROUP BY pos.pos_id
        // posted data
        if(!$orderSearch){
            $orderSearch = Yii::$app->request->get('OrderSearch');
        }
        
        $query = new Query;
        $query->select('pos.pos_id, pos.pos_title, SUM(o.order_total) AS total, count(o.order_id) as n_orders')
                ->from('`order` o')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->groupBy(['pos.pos_id'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
        //    $query->andWhere(" pos.pos_title=:pos_title_value ",['pos_title_value'=>$orderSearch['pos.pos_title']] );
        //}        
        //if (isset($orderSearch['packaging_title']) && strlen($orderSearch['packaging_title']) > 0) {
        //    $query->andWhere(" LOCATE (:packaging_title_value,order_packaging.packaging_title) ",['packaging_title_value'=>$orderSearch['packaging_title']] );
        //}
        return $query;
    }

    public static function sellerIncomeReport() {
        //SELECT sysuser.sysuser_id, sysuser.sysuser_fullname, SUM(o.order_total) AS total
        //FROM `order` o INNER JOIN sysuser ON o.sysuser_id=sysuser.sysuser_id
        //GROUP BY sysuser.sysuser_id
        //ORDER BY total DESC;
        //
        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');
        $query = new Query;
        $query->select('sysuser.sysuser_id, sysuser.sysuser_fullname, SUM(o.order_total) AS total')
                ->from('`order` o')
                ->innerJoin('sysuser', 'o.sysuser_id=sysuser.sysuser_id')
                ->groupBy(['sysuser.sysuser_id'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
        //    $query->andWhere(" pos.pos_title=:pos_title_value ",['pos_title_value'=>$orderSearch['pos.pos_title']] );
        //}        
        //if (isset($orderSearch['packaging_title']) && strlen($orderSearch['packaging_title']) > 0) {
        //    $query->andWhere(" LOCATE (:packaging_title_value,order_packaging.packaging_title) ",['packaging_title_value'=>$orderSearch['packaging_title']] );
        //}
        return $query;
    }

    public static function incomeByHourReport() {

        $t = Array();
        for ($i = 0; $i < 24; $i++) {
            $t[$i] = 0;
        }
        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        // select 

        $query = new Query;
        $query->select('HOUR(o.order_datetime) AS dt, SUM(o.order_total) AS total')
                ->from('`order` o')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->groupBy(['dt'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ", ['sysuser_fullname_value' => $orderSearch['sysuser.sysuser_fullname']]);
        }

        $result = $query->all();
        foreach ($result as $res) {
            $t[$res['dt']] = $res['total'];
        }

        return $t;
    }

    public static function incomeByWeekday() {

        $t = Array();
        for ($i = 1; $i < 8; $i++) {
            $t[$i] = 0;
        }
        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        // select 

        $query = new Query;
        $query->select('DAYOFWEEK(o.order_datetime) AS dt, SUM(o.order_total) AS total')
                ->from('`order` o')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->groupBy(['dt'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ", ['sysuser_fullname_value' => $orderSearch['sysuser.sysuser_fullname']]);
        }

        $result = $query->all();
        foreach ($result as $res) {
            $t[$res['dt']] = $res['total'];
        }

        return $t;
    }

    public static function incomeDaily() {

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        // get date interval
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $fromDate = $timestamp;
            }
        }
        if (!isset($fromDate)) {
            $fromDate = time() - 28 * 24 * 3600;
        }

        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $toDate = $timestamp;
            }
        }
        if (!isset($toDate)) {
            $toDate = time();
        }

        //SELECT MIN(DATE(o.order_datetime)) AS mn, MAX(DATE(o.order_datetime)) AS mx FROM `order` o;
        $query = new Query;
        $query->select('MIN(DATE(o.order_datetime)) AS mn, MAX(DATE(o.order_datetime)) AS mx')
                ->from('`order` o')
        ;
        $minmax = $query->one();

        if ($minmax) {
            $timestamp = strtotime($minmax['mn']);
            if ($fromDate < $timestamp) {
                $fromDate = $timestamp;
            }
            $timestamp = strtotime($minmax['mx']);
            if ($toDate > $timestamp) {
                $toDate = $timestamp;
            }
        }

        $t = Array();
        $ddt = 24 * 3600;
        for ($dt = $fromDate; $dt <= $toDate; $dt+=$ddt) {
            $t[date('Y-m-d', $dt)] = 0;
        }


        // select 
        $query = new Query;
        $query->select('DATE(o.order_datetime) AS dt, SUM(o.order_total) AS total')
                ->from('`order` o')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->innerJoin('sysuser', 'o.sysuser_id=sysuser.sysuser_id')
                ->groupBy(['dt'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ", ['sysuser_fullname_value' => $orderSearch['sysuser.sysuser_fullname']]);
        }

        $result = $query->all();
        foreach ($result as $res) {
            $t[$res['dt']] = $res['total'];
        }

        return $t;
    }

    public static function profitDaily() {

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        // get date interval
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $fromDate = $timestamp;
            }
        }
        if (!isset($fromDate)) {
            $fromDate = time() - 28 * 24 * 3600;
        }

        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $toDate = $timestamp;
            }
        }
        if (!isset($toDate)) {
            $toDate = time();
        }

        //SELECT MIN(DATE(o.order_datetime)) AS mn, MAX(DATE(o.order_datetime)) AS mx FROM `order` o;
        $query = new Query;
        $query->select('MIN(DATE(o.order_datetime)) AS mn, MAX(DATE(o.order_datetime)) AS mx')
                ->from('`order` o')
        ;
        $minmax = $query->one();

        if ($minmax) {
            $timestamp = strtotime($minmax['mn']);
            if ($fromDate < $timestamp) {
                $fromDate = $timestamp;
            }
            $timestamp = strtotime($minmax['mx']);
            if ($toDate > $timestamp) {
                $toDate = $timestamp;
            }
        }

        $t = Array();
        $ddt = 24 * 3600;
        for ($dt = $fromDate; $dt <= $toDate; $dt+=$ddt) {
            $t[date('Y-m-d', $dt)] = 0;
        }


        $sql = "DROP TABLE IF EXISTS packaging_profit;";
        \Yii::$app->db->createCommand($sql, [])->execute();

        $sql = "CREATE TEMPORARY TABLE packaging_profit(
                `packaging_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `packaging_title` VARCHAR(32) DEFAULT NULL,
                `profit` DOUBLE,
                PRIMARY KEY (`packaging_id`)
              ) ENGINE=MEMORY";
        \Yii::$app->db->createCommand($sql, [])->execute();

        $sql = "
            INSERT INTO packaging_profit(packaging_id,packaging_title,profit)
            SELECT packaging_product.packaging_id, packaging.`packaging_title`, packaging.`packaging_price` - SUM(packaging_product.`packaging_product_quantity`*product.`product_unit_price`) AS profit
            FROM packaging_product
            INNER JOIN product ON product.product_id=packaging_product.product_id
            INNER JOIN packaging ON packaging_product.packaging_id=packaging.packaging_id
            GROUP BY packaging_id;
            ";
        \Yii::$app->db->createCommand($sql, [])->execute();

        //SELECT DATE(o.`order_datetime`) AS dt, SUM(packaging_profit.profit*order_packaging.`order_packaging_number`) AS profit
        //FROM packaging_profit
        //     INNER JOIN `order_packaging` ON packaging_profit.packaging_id=order_packaging.packaging_id
        //     INNER JOIN `order` o ON order_packaging.order_id=o.order_id
        //     INNER JOIN pos ON o.pos_id=pos.pos_id
        //GROUP BY dt
        //;
        // select 
        $query = new Query;
        $query->select('DATE(o.order_datetime) AS dt, SUM(packaging_profit.profit*order_packaging.`order_packaging_number`) AS total')
                ->from('packaging_profit')
                ->innerJoin('`order_packaging`', 'packaging_profit.packaging_id=order_packaging.packaging_id')
                ->innerJoin('`order` o', 'order_packaging.order_id=o.order_id')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->innerJoin('sysuser', 'o.sysuser_id=sysuser.sysuser_id')
                ->groupBy(['dt'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ", ['sysuser_fullname_value' => $orderSearch['sysuser.sysuser_fullname']]);
        }

        $result = $query->all();
        foreach ($result as $res) {
            $t[$res['dt']] = $res['total'];
        }

        return $t;
    }

    
    
    
    
    
    
    public static function profitWeekDaily() {

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        
        $t = Array();
        for ($i = 1; $i < 8; $i++) {
            $t[$i] = 0;
        }

        $sql = "DROP TABLE IF EXISTS packaging_profit;";
        \Yii::$app->db->createCommand($sql, [])->execute();

        $sql = "CREATE TEMPORARY TABLE packaging_profit(
                `packaging_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `packaging_title` VARCHAR(32) DEFAULT NULL,
                `profit` DOUBLE,
                PRIMARY KEY (`packaging_id`)
              ) ENGINE=MEMORY";
        \Yii::$app->db->createCommand($sql, [])->execute();

        $sql = "
            INSERT INTO packaging_profit(packaging_id,packaging_title,profit)
            SELECT packaging_product.packaging_id, packaging.`packaging_title`, packaging.`packaging_price` - SUM(packaging_product.`packaging_product_quantity`*product.`product_unit_price`) AS profit
            FROM packaging_product
            INNER JOIN product ON product.product_id=packaging_product.product_id
            INNER JOIN packaging ON packaging_product.packaging_id=packaging.packaging_id
            GROUP BY packaging_id;
            ";
        \Yii::$app->db->createCommand($sql, [])->execute();

        //SELECT DATE(o.`order_datetime`) AS dt, SUM(packaging_profit.profit*order_packaging.`order_packaging_number`) AS profit
        //FROM packaging_profit
        //     INNER JOIN `order_packaging` ON packaging_profit.packaging_id=order_packaging.packaging_id
        //     INNER JOIN `order` o ON order_packaging.order_id=o.order_id
        //     INNER JOIN pos ON o.pos_id=pos.pos_id
        //GROUP BY dt
        //;
        // select 
        $query = new Query;
        $query->select('DAYOFWEEK(o.order_datetime) AS dt, SUM(packaging_profit.profit*order_packaging.`order_packaging_number`) AS total')
                ->from('packaging_profit')
                ->innerJoin('`order_packaging`', 'packaging_profit.packaging_id=order_packaging.packaging_id')
                ->innerJoin('`order` o', 'order_packaging.order_id=o.order_id')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->innerJoin('sysuser', 'o.sysuser_id=sysuser.sysuser_id')
                ->groupBy(['dt'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ", ['sysuser_fullname_value' => $orderSearch['sysuser.sysuser_fullname']]);
        }

        $result = $query->all();
        foreach ($result as $res) {
            $t[$res['dt']] = $res['total'];
        }

        return $t;
    }

    
    
    public static function profitHourly() {

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        $t = Array();
        for ($i = 0; $i < 24; $i++) {
            $t[$i] = 0;
        }

        $sql = "DROP TABLE IF EXISTS packaging_profit;";
        \Yii::$app->db->createCommand($sql, [])->execute();

        $sql = "CREATE TEMPORARY TABLE packaging_profit(
                `packaging_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                `packaging_title` VARCHAR(32) DEFAULT NULL,
                `profit` DOUBLE,
                PRIMARY KEY (`packaging_id`)
              ) ENGINE=MEMORY";
        \Yii::$app->db->createCommand($sql, [])->execute();

        $sql = "
            INSERT INTO packaging_profit(packaging_id,packaging_title,profit)
            SELECT packaging_product.packaging_id, packaging.`packaging_title`, packaging.`packaging_price` - SUM(packaging_product.`packaging_product_quantity`*product.`product_unit_price`) AS profit
            FROM packaging_product
            INNER JOIN product ON product.product_id=packaging_product.product_id
            INNER JOIN packaging ON packaging_product.packaging_id=packaging.packaging_id
            GROUP BY packaging_id;
            ";
        \Yii::$app->db->createCommand($sql, [])->execute();

        //SELECT DATE(o.`order_datetime`) AS dt, SUM(packaging_profit.profit*order_packaging.`order_packaging_number`) AS profit
        //FROM packaging_profit
        //     INNER JOIN `order_packaging` ON packaging_profit.packaging_id=order_packaging.packaging_id
        //     INNER JOIN `order` o ON order_packaging.order_id=o.order_id
        //     INNER JOIN pos ON o.pos_id=pos.pos_id
        //GROUP BY dt
        //;
        // select 
        $query = new Query;
        $query->select('HOUR(o.order_datetime) AS dt, SUM(packaging_profit.profit*order_packaging.`order_packaging_number`) AS total')
                ->from('packaging_profit')
                ->innerJoin('`order_packaging`', 'packaging_profit.packaging_id=order_packaging.packaging_id')
                ->innerJoin('`order` o', 'order_packaging.order_id=o.order_id')
                ->innerJoin('pos', 'o.pos_id=pos.pos_id')
                ->innerJoin('sysuser', 'o.sysuser_id=sysuser.sysuser_id')
                ->groupBy(['dt'])
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ", ['min_date' => $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ", ['max_date' => $max_date]);
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }

        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ", ['pos_title_value' => $orderSearch['pos.pos_title']]);
        }

        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ", ['sysuser_fullname_value' => $orderSearch['sysuser.sysuser_fullname']]);
        }

        $result = $query->all();
        foreach ($result as $res) {
            $t[$res['dt']] = $res['total'];
        }

        return $t;
    }
    
    
    
    public static function getColors() {
        return
        [
        ['#003300', '#003333'],['#003366', '#003399'],['#0033cc',  '#0033ff'],
        ['#006600', '#006633'],['#006666', '#006699'],['#0066cc',  '#0066ff'],
        ['#009900', '#009933'],['#009966', '#009999'],['#0099cc',  '#0099ff'],
        ['#00cc00', '#00cc33'],['#00cc66', '#00cc99'],['#00cccc',  '#00ccff'],
        ['#00ff00', '#00ff33'],['#00ff66', '#00ff99'],['#00ffcc',  '#00ffff'],
        ['#330000', '#330033'],['#330066', '#330099'],['#3300cc',  '#3300ff'],
        ['#333300', '#333333'],['#333366', '#333399'],['#3333cc',  '#3333ff'],
        ['#336600', '#336633'],['#336666', '#336699'],['#3366cc',  '#3366ff']];
    }

}
