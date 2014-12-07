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
    
    
    public static function sellerIncomeReport(){
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

    
    public static function incomeByHourReport(){
        
        $t=Array();
        for($i=0; $i<24;$i++){
            $t[$i]=0;
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
        
        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ",['sysuser_fullname_value'=>$orderSearch['sysuser.sysuser_fullname']] );
        }

        $result=$query->all();
        foreach($result as $res){
            $t[$res['dt']]=$res['total'];
        }
        
        return $t;
    }

    

    public static function incomeByWeekday(){
        
        $t=Array();
        for($i=1; $i<8;$i++){
            $t[$i]=0;
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
        
        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ",['sysuser_fullname_value'=>$orderSearch['sysuser.sysuser_fullname']] );
        }

        $result=$query->all();
        foreach($result as $res){
            $t[$res['dt']]=$res['total'];
        }
        
        return $t;
    }

    

    public static function incomeDaily(){

        // posted data
        $orderSearch = Yii::$app->request->get('OrderSearch');

        // get date interval
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $fromDate = $timestamp;
            }
        }
        if(!isset($fromDate)){
            $fromDate=time()-28*24*3600;
        }
        
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $toDate = $timestamp;
            }
        }
        if(!isset($toDate)){
            $toDate=time();
        }
        
        //SELECT MIN(DATE(o.order_datetime)) AS mn, MAX(DATE(o.order_datetime)) AS mx FROM `order` o;
        $query = new Query;
        $query->select('MIN(DATE(o.order_datetime)) AS mn, MAX(DATE(o.order_datetime)) AS mx')
              ->from('`order` o')
        ;
        $minmax=$query->one();
        
        if($minmax){
            $timestamp=strtotime($minmax['mn']);
            if($fromDate<$timestamp){
                $fromDate=$timestamp;
            }
            $timestamp=strtotime($minmax['mx']);
            if($toDate>$timestamp){
                $toDate=$timestamp;
            }
        }
        
        $t=Array();
        $ddt=24*3600;
        for($dt=$fromDate; $dt<=$toDate;$dt+=$ddt){
            $t[date('Y-m-d',$dt)]=0;
        }


        // select 
        $query = new Query;
        $query->select('DATE(o.order_datetime) AS dt, SUM(o.order_total) AS total')
              ->from('`order` o')
              ->innerJoin('pos', 'o.pos_id=pos.pos_id')
              ->groupBy(['dt']) 
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
        
        if (isset($orderSearch['sysuser.sysuser_fullname']) && strlen($orderSearch['sysuser.sysuser_fullname']) > 0) {
            $query->andWhere(" LOCATE (:sysuser_fullname_value,o.sysuser_fullname) ",['sysuser_fullname_value'=>$orderSearch['sysuser.sysuser_fullname']] );
        }

        $result=$query->all();
        foreach($result as $res){
            $t[$res['dt']]=$res['total'];
        }
        
        return $t;
    }

    
    
    public static function getColors(){
        return
       [["#dddddd","#CCCCCC"],["#999999","#666666"],["#333333","#000000"],
        ["#FFCC00","#FF9900"],["#FF6600","#FF3300"],["#99CC00","#CC9900"],
        ["#FFCC33","#FFCC66"],["#FF9966","#FF6633"],["#CC3300","#CC0033"],
        ["#CCFF00","#CCFF33"],["#333300","#666600"],["#999900","#CCCC00"],
        ["#FFFF00","#CC9933"],["#CC6633","#330000"],["#660000","#990000"],
        ["#CC0000","#FF0000"],["#FF3366","#FF0033"],["#99FF00","#CCFF66"],
        ["#99CC33","#666633"],["#999933","#CCCC33"],["#FFFF33","#996600"],
        ["#993300","#663333"],["#993333","#CC3333"],["#FF3333","#CC3366"],
        ["#FF6699","#FF0066"],["#66FF00","#99FF66"],["#66CC33","#669900"],
        ["#999966","#CCCC66"],["#FFFF66","#996633"],["#663300","#996666"],
        ["#CC6666","#FF6666"],["#990033","#CC3399"],["#FF66CC","#FF0099"],
        ["#33FF00","#66FF33"],["#339900","#66CC00"],["#99FF33","#CCCC99"],
        ["#FFFF99","#CC9966"],["#CC6600","#CC9999"],["#FF9999","#FF3399"],
        ["#CC0066","#990066"],["#FF33CC","#FF00CC"],["#00CC00","#33CC00"],
        ["#336600","#669933"],["#99CC66","#CCFF99"],["#FFFFCC","#FFCC99"],
        ["#FF9933","#FFCCCC"],["#FF99CC","#CC6699"],["#993366","#660033"],
        ["#CC0099","#330033"],["#33CC33","#66CC66"],["#00FF00","#33FF33"],
        ["#66FF66","#99FF99"],["#CCFFCC","#CC99CC"],["#996699","#993399"],
        ["#990099","#663366"],["#660066","#006600"],["#336633","#009900"],
        ["#339933","#669966"],["#99CC99","#FFCCFF"],["#FF99FF","#FF66FF"],
        ["#FF33FF","#FF00FF"],["#CC66CC","#CC33CC"],["#003300","#00CC33"],
        ["#006633","#339966"],["#66CC99","#99FFCC"],["#CCFFFF","#3399FF"],
        ["#99CCFF","#CCCCFF"],["#CC99FF","#9966CC"],["#663399","#330066"],
        ["#9900CC","#CC00CC"],["#00FF33","#33FF66"],["#009933","#00CC66"],
        ["#33FF99","#99FFFF"],["#99CCCC","#0066CC"],["#6699CC","#9999FF"],
        ["#9999CC","#9933FF"],["#6600CC","#660099"],["#CC33FF","#CC00FF"],
        ["#00FF66","#66FF99"],["#33CC66","#009966"],["#66FFFF","#66CCCC"],
        ["#669999","#003366"],["#336699","#6666FF"],["#6666CC","#666699"],
        ["#330099","#9933CC"],["#CC66FF","#9900FF"],["#00FF99","#66FFCC"],
        ["#33CC99","#33FFFF"],["#33CCCC","#339999"],["#336666","#006699"],
        ["#003399","#3333FF"],["#3333CC","#333399"],["#333366","#6633CC"],
        ["#9966FF","#6600FF"],["#00FFCC","#33FFCC"],["#00FFFF","#00CCCC"],
        ["#009999","#006666"],["#003333","#3399CC"],["#3366CC","#0000FF"],
        ["#0000CC","#000099"],["#000066","#000033"],["#6633FF","#3300FF"],
        ["#00CC99","#0099CC"],["#33CCFF","#66CCFF"],["#6699FF","#3366FF"],
        ["#0033CC","#3300CC"],["#00CCFF","#0099FF"],["#0066FF","#0033FF"]];
    }
}

