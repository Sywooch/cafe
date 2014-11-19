<?php
namespace app\models;

use Yii;
use yii\base\Model;

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
    

}

