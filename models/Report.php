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
class Report  extends Model{
     
    
    public static function sellerReport(){
        
        $orderSearch = Yii::$app->request->get('OrderSearch');
        
        $where=[];
        
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $where[]=" o.order_datetime>='$min_date' ";
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $where[]=" o.order_datetime<='$max_date' ";
            }
        }

        if(count($where)>0){
            $where=' WHERE '.join(' AND ', $where);
        }else{
            $where='';
        }
        $sql="SELECT su.sysuser_id,se.seller_id, su.sysuser_fullname, 
                     SUM(o.order_total) AS order_total, 
                     COUNT(o.order_id) AS order_count,
                     AVG(o.order_total) AS order_average,
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
    

    public static function productReport(){
        
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
                $query->andWhere(" o.order_datetime>=:min_date ",['min_date'=>$min_date] );
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ",['max_date'=>$max_date] );
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }
        
        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ",['pos_title_value'=>$orderSearch['pos.pos_title']] );
        }
        
        if (isset($orderSearch['product_title']) && strlen($orderSearch['product_title']) > 0) {
            $query->andWhere(" LOCATE (:product_title_value,product.product_title) ",['product_title_value'=>$orderSearch['product_title']] );
        }

        return $query;        
    }
    
    
    public static function packagingReport(){
        
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
                $query->andWhere(" o.order_datetime>=:min_date ",['min_date'=>$min_date] );
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ",['max_date'=>$max_date] );
            }
        }
        //        if (isset($orderSearch['order_payment_type']) && strlen($orderSearch['order_payment_type']) > 0) {
        //            $query->andWhere(" o.order_payment_type=':order_payment_type' ",['order_payment_type'=>$orderSearch['order_payment_type']] );
        //        }
        
        if (isset($orderSearch['pos.pos_title']) && strlen($orderSearch['pos.pos_title']) > 0) {
            $query->andWhere(" pos.pos_title=:pos_title_value ",['pos_title_value'=>$orderSearch['pos.pos_title']] );
        }
        
        if (isset($orderSearch['packaging_title']) && strlen($orderSearch['packaging_title']) > 0) {
            $query->andWhere(" LOCATE (:packaging_title_value,order_packaging.packaging_title) ",['packaging_title_value'=>$orderSearch['packaging_title']] );
        }

        return $query;
    }
    
    
    public static function posIncomeReport(){
        //SELECT pos.pos_id, pos.pos_title, SUM(o.order_total) AS total
        //FROM `order` o INNER JOIN pos ON o.pos_id=pos.pos_id
        //GROUP BY pos.pos_id
        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');
        $query = new Query;
        $query->select('pos.pos_id, pos.pos_title, SUM(o.order_total) AS total')
              ->from('`order` o')
              ->innerJoin('pos', 'o.pos_id=pos.pos_id')
              ->groupBy(['pos.pos_id']) 
        ;
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andWhere(" o.order_datetime>=:min_date ",['min_date'=>$min_date] );
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andWhere(" o.order_datetime<=:max_date ",['max_date'=>$max_date] );
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

}

