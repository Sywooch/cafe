<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use app\models\Category;
/**
 * Description of DiscountTypeSimple
 *
 * @author dobro
 */
class DiscountTypeEachNth implements DiscountType{
    
    private $model;
    private $options;
    const type='eachNth';
    public function __construct($model) {
        $this->model=$model;
        $this->options = json_decode($this->model->discount_rule, true);
    }
    
    // apply discount to order
    public function apply($order) {
        
        // check time restriction
        $timefrom=strtotime($this->options['datefrom']);
        $timeto=strtotime($this->options['dateto'])+86400;
        
        $now=time();
        if($now<$timefrom || $now > $timeto){
            return false;
        }
        
        // check customer id
        $customerId=0;
        
        if( isset($order['customerId']) && $order['customerId']>0 ){
        $customer = Customer::findOne((int)$order['customerId']);
            if($customer){
                $customerId=$customer->customerId;
            }
        }
        
        $order['customerTel'] = trim($order['customerTel']);
        if($customerId == 0 && $order['customerTel']){ // 
            $customer = Customer::find()->where('customerMobile = :customerTel', ['customerTel'=>$order['customerTel']])->one();
            if($customer){
                $customerId=$customer->customerId;
            }
        }
        
        // if($customerId <= 0){
        //    return false;
        // }
        

        $datefrom=date('Y-m-d 00:00:00',$timefrom);
        $dateto  =date('Y-m-d 23:59:59',$timeto);
        $category_id=(int)$this->options['eachNth_category'];

        // count order packaging by category (take date limits from discount settings)
        if($customerId > 0){
            $sql=
            "SELECT SUM(op.order_packaging_number) AS n
            FROM order_packaging AS op
                 INNER JOIN `order` AS o ON op.order_id=o.order_id
                 INNER JOIN `packaging` p ON op.packaging_id=p.packaging_id
            WHERE p.category_id={$category_id}
              AND o.order_datetime BETWEEN '{$datefrom}' AND '{$dateto}'
              AND o.customerId={$customerId} AND o.customerId
            ";
            $command = Yii::$app->db->createCommand($sql);
            $n_total = $command->queryOne();
            $n_total = $n_total['n'];            
        }else{
            $n_total = 0;
        }

        // count current order packagings
        if(isset($order['order_packaging'] )){
            foreach($order['order_packaging'] as $pack){
                if($pack['packaging']['category_id']==$category_id){
                    $n_total+=$pack['count'];
                }
            }
        }

        if($customerId > 0){
            $sql=
            "SELECT SUM(o.discount_count) AS n
            FROM `order` AS o
            WHERE o.discount_id=".$this->model->discount_id."
              AND o.order_datetime BETWEEN '{$datefrom}' AND '{$dateto}'
              AND o.customerId={$customerId}  AND o.customerId
            ";
            $command = Yii::$app->db->createCommand($sql);
            $n_discounts = $command->queryOne();
        }else{
            $n_discounts = 0;
        }

        // count discounted packaging including current order (take date limits from discount settings)
        $discounts_available=floor($n_total/$this->options['eachNth_period'])-$n_discounts['n'];
        // 
        // get difference as 
        $discount_value=0;
        $discount_count=0;
        $available=$discounts_available;
        if(isset($order['order_packaging']) && $order['order_packaging']){
            foreach($order['order_packaging'] as $pack){
                if($available>0 && $pack['packaging']['category_id']==$category_id){
                    if($pack['count']<=$available){
                        $discount_count+=$pack['count'];
                        $discount_value+=$pack['packaging']['packaging_price']*$pack['count'];
                        $available-=$pack['count'];
                    }else{
                        $discount_count+=$available;
                        $discount_value+=$pack['packaging']['packaging_price']*$available;
                        $available=0;
                    }
                }else{
                    break;
                }
            }            
        }

        // Category
        $categoryModel=Category::findOne($category_id);
        return [
            'discount_value'=>$discount_value, 
            'discount_type'=>self::type, 
            'discount_count'=>$discount_count,
            'discount_message'=>Yii::t('app', 'Discounted items available',
                                ['discounts_available'=>$discounts_available, 
                                 'category_title'=>$categoryModel->category_title])];
    }

    // draw edit form
    public function form($view) {
        ?>
    
    <style type="text/css">
        #discount_rule{
            display:none;
        }
        .blokformy{
            display:inline-block;
            vertical-align: top;
            width:auto;
            margin-bottom:10px;
        }
        #rule{
            margin-bottom:20px;
        }
        #condition_attribute, #condition_operator, #condition_value,
        #search_attribute, #search_operator, #search_value,
        #discount_value, #discount_unit{
            width:auto;
            display:inline-block;
        }
    </style>
    <div id="rule">
        <span class="blokformy">
            <?=Yii::t('app','eachNth_period')?>:
            <input type="text" id="eachNth_period" class="form-control">
        </span>
        <span class="blokformy">
            <?=Yii::t('app','eachNth_category')?>:<br/>
            <?php echo Html::dropDownList( 'eachNth_category', $selection = null, ArrayHelper::map(Category::find()->all(), 'category_id', 'category_title'), $options = ['class'=>'form-control','id'=>'eachNth_category'] ); ?>
        </span>
            <span class="blokformy"><?=Yii::t('app','Order Datetime Min')?>
            <?=DatePicker::widget([
                'name'  => 'OrderSearch[order_datetime_min]',
                'value'  => null,//($orderSearch['order_datetime_min']?$orderSearch['order_datetime_min']:null),
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'=>['size'=>10, 'class'=>'form-control','id'=>'datefrom']
            ])?>
            </span>
            <span class="blokformy"><?=Yii::t('app','Order Datetime Max')?>
            <?=DatePicker::widget([
                'name'  => 'OrderSearch[order_datetime_max]',
                'value'  => null,//($orderSearch['order_datetime_max']?$orderSearch['order_datetime_max']:null),
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'=>['size'=>10, 'class'=>'form-control','id'=>'dateto']
            ])?>
            </span>

    </div>
    <?php
    $view->registerJs("
        var json;
        
        function collectJSON(){
            //alert('collectJSON');
            json={};
            json.discount_type='eachNth';
            json.discount_title=$('#discount_title').val();
            json.eachNth_period=$('#eachNth_period').val();
            json.eachNth_category=$('#eachNth_category').val();
            json.datefrom=$('#datefrom').val();
            json.dateto=$('#dateto').val();

            var json_str = JSON.stringify(json);
            \$('#discount_rule').val(json_str);
        }

        function activateForm(){
            try{
                json=jQuery.parseJSON( \$('#discount_rule').val() );
                $('#eachNth_period').val(json.eachNth_period);
                $('#eachNth_category').val(json.eachNth_category);
                $('#datefrom').val(json.datefrom);
                $('#dateto').val(json.dateto);
            }catch(err){
            }
            if(!json){
              json={};
            }
            if(!json.discount_title)      json.discount_title='';
            if(!json.eachNth_period)      json.eachNth_period='1000000';
            if(!json.eachNth_category)    json.eachNth_category='0';

            $('#eachNth_period').change(collectJSON);
            $('#eachNth_category').change(collectJSON);
            $('#discount_title').change(collectJSON);
            $('#datefrom').change(collectJSON);
            $('#dateto').change(collectJSON);

            collectJSON();
        }
        $(window).load(activateForm);    
    ");        
    }

}
