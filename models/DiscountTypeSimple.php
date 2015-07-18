<?php

namespace app\models;

use Yii;
/**
 * Description of DiscountTypeSimple
 *
 * @author dobro
 */
class DiscountTypeSimple implements DiscountType{
    
    private $model;
    private $options;
    const type='simple';
    public function __construct($model) {
        $this->model=$model;
        $this->options = json_decode($this->model->discount_rule, true);
    }
    
    // apply discount to order
    public function apply($order) {
        
        // print_r($order);//exit();
        
        $discountValue=0;
        if (isset($order['discount_id']) && $order['discount_id']>0) {
            
            $condition_ok=false;

            // get transformation rule
            $transformationParameter=(str_replace(',','.',$this->options['discount_value']));
            if(is_numeric($transformationParameter)){
                if($this->options['discount_unit']=='%'){
                    $transformationRule=function($from) use($transformationParameter){ return 0.01*$transformationParameter*$from; };
                }else{
                    $transformationRule=function($from) use($transformationParameter){ return $transformationParameter; };
                }                        
            }else{
                $transformationRule=function($from){return 0;};
            }


            // get order total
            $order_total = 0;
            foreach($order['order_packaging'] as $pack){
                $order_total += $pack['count']*$pack['packaging']['packaging_price'];
            }


            if ($this->options['condition_attribute']) {


                switch ($this->options['condition_attribute']) {
                    case 'order_total':
                        $val=(float)str_replace(',','.',$this->options['condition_value']);
                        switch($this->options['condition_operator']){
                            case '>' : $condition_ok=($order_total >  $val);  break;
                            case '>=': $condition_ok=($order_total >= $val);  break;
                            case '=' : $condition_ok=($order_total == $val);  break;
                            case '<=': $condition_ok=($order_total <= $val);  break;
                            case '<' : $condition_ok=($order_total <  $val);  break;
                            case 'e' : $condition_ok=true;  break;
                        }
                        break;

                    case 'packaging_id':
                    case 'packaging_price':
                        foreach($order['order_packaging'] as $pack){
                            if($condition_ok){ continue; }
                            $num=(float)$pack['packaging_price'];
                            $val=(float)str_replace(',','.',$this->options['condition_value']);
                            switch($this->options['condition_operator']){
                                case '>' : $condition_ok=($num >  $val);  break;
                                case '>=': $condition_ok=($num >= $val);  break;
                                case '=' : $condition_ok=($num == $val);  break;
                                case '<=': $condition_ok=($num <= $val);  break;
                                case '<' : $condition_ok=($num <  $val);  break;
                            }
                        }
                        break;

                    case 'packaging_title':
                        $words = preg_split("/[ ,;-]+/", $this->options['condition_value']);
                        foreach($order['order_packaging'] as $pack){
                            if($condition_ok){ continue; }
                            $packaging_title=$pack['packaging']['packaging_title'];
                            switch($this->options['condition_operator']){
                                case '>' : $condition_ok=($packaging_title >  $this->options['condition_value']); break;
                                case '>=': $condition_ok=($packaging_title >= $this->options['condition_value']); break;
                                case '=' : $condition_ok=($packaging_title == $this->options['condition_value']); break;
                                case '<=': $condition_ok=($packaging_title <= $this->options['condition_value']); break;
                                case '<' : $condition_ok=($packaging_title <  $this->options['condition_value']); break;
                                case '~' : 
                                    $res=true;
                                    foreach($words as $w){
                                        if(mb_stripos($packaging_title, $w, 0 ,'utf-8')===false){
                                            $res=false;
                                        }
                                    }
                                    $condition_ok=$res;
                                    break;
                            }
                        }
                        break;
                }
            }else{
                $condition_ok=true;
            }


            if ($this->options['search_attribute']) {
                //if(verbose){console.log(' matching  search_attribute '+json.search_attribute);}
                switch ($this->options['search_attribute']) {

                    // ========== apply discount to order total = begin ============
                    case 'order_total':
                        $condition_ok=false;
                        $val=(float)str_replace(',','.',$this->options['search_value']);
                        switch($this->options['search_operator']){
                            case '>' : $condition_ok=($order_total >  $val);  break;
                            case '>=': $condition_ok=($order_total >= $val);  break;
                            case '=' : $condition_ok=($order_total == $val);  break;
                            case '<=': $condition_ok=($order_total <= $val);  break;
                            case '<' : $condition_ok=($order_total <  $val);  break;
                            case 'e' : $condition_ok=true;                    break;
                        }
                        if($condition_ok){
                            $discountValue=$transformationRule($order_total);
                        }
                        break;
                    // ========== apply discount to order total = end ==============


                    // == apply discount to packaging_id or packaging_price = begin ==
                    case 'packaging_id':
                    case 'packaging_price':
                        $discountValue=0;
                        $condition_ok=false;
                        $val=(float)str_replace(',','.',$this->options['search_value']);
                        foreach($order->order_packaging as $pack){
                            $num=$pack['packaging']['packaging_price'];
                            switch($this->options['search_operator']){
                                case '>' : $condition_ok=($num >  $val);  break;
                                case '>=': $condition_ok=($num >= $val);  break;
                                case '=' : $condition_ok=($num == $val);  break;
                                case '<=': $condition_ok=($num <= $val);  break;
                                case '<' : $condition_ok=($num <  $val);  break;
                            }
                            if($condition_ok){
                                $discountValue+=$pack['count'] * $transformationRule($num);
                            }
                        }
                        break;
                    // == apply discount to packaging_id or packaging_price = end ====

                    case 'packaging_title':
                        $discountValue=0;
                        $condition_ok=false;
                        $words = preg_split("/[ ,;-]+/", $this->options['search_value']);
                        foreach($order['order_packaging'] as $pack){
                            $packaging_title=$pack['packaging']['packaging_title'];
                            switch($this->options['search_operator']){
                                case '>' : $condition_ok=($packaging_title >  $this->options['search_value']);  break;
                                case '>=': $condition_ok=($packaging_title >= $this->options['search_value']); break;
                                case '=' : $condition_ok=($packaging_title == $this->options['search_value']); break;
                                case '<=': $condition_ok=($packaging_title <= $this->options['search_value']); break;
                                case '<' : $condition_ok=($packaging_title <  $this->options['search_value']);  break;
                                case '~':
                                    $res=true;
                                    foreach($words as $w){
                                        if(mb_stripos($packaging_title, $w, 0,'UTF-8')===false){
                                            $res=false;
                                        }
                                    }
                                    $condition_ok=$res;
                                    break;
                            }
                            if($condition_ok){
                                $discountValue+=$pack['count'] * $transformationRule($pack['packaging']['packaging_price']);
                            }
                        }
                        break;
                }
            }
        }
        if($discountValue>0){
            return ['discount_value'=>$discountValue, 'discount_type'=>self::type,'discount_message'=>'','discount_count'=>1];
        }else{
            return false;
        }
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
            <?=Yii::t('app','condition_attribute')?>:<br/>
            <nobr>
            <select id="condition_attribute" class="form-control">
                <option value=""><?=Yii::t('app','no_condition')?></option>
                <option value="order_total"><?=Yii::t('app','order_total')?></option>
                <option value="packaging_id"><?=Yii::t('app','packaging_id')?></option>
                <option value="packaging_title"><?=Yii::t('app','packaging_title')?></option>
                <option value="packaging_price"><?=Yii::t('app','packaging_price')?></option>
            </select>
            <select id="condition_operator" class="form-control">
                <option value=""></option>
                <option value=">">&gt;</option>
                <option value=">=">&ge;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&le;</option>
                <option value="~"><?=Yii::t('app','contains')?></option>
                <option value="e"><?=Yii::t('app','exists')?></option>
            </select>
            <input type="text" id="condition_value" class="form-control">
            </nobr>
        </span><br>
        <span class="blokformy">
            <?=Yii::t('app','search_attribute')?>:<br/>
            <select id="search_attribute" class="form-control">
                <option value="order_total"><?=Yii::t('app','order_total')?></option>
                <option value="packaging_id"><?=Yii::t('app','packaging_id')?></option>
                <option value="packaging_title"><?=Yii::t('app','packaging_title')?></option>
                <option value="packaging_price"><?=Yii::t('app','packaging_price')?></option>
            </select>
        </span>
        <span class="blokformy">
            <?=Yii::t('app','search_attribute_condition')?>:<br/>
            <select id="search_operator" class="form-control">
                <option value=""></option>
                <option value=">">&gt;</option>
                <option value=">=">&ge;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&le;</option>
                <option value="~"><?=Yii::t('app','contains')?></option>
                <option value="e"><?=Yii::t('app','exists')?></option>
            </select>
            <input type="text" id="search_value" class="form-control">
        </span><br/>
        <span class="blokformy">
            <?=Yii::t('app','discount_value')?>:<br/>
            <input type="text" id="discount_value" class="form-control">
            <select id="discount_unit" class="form-control">
                <option value="%">%</option>
                <option value="abs"><?=Yii::$app->params['currency']?></option>
            </select>
        </span>
    </div>
    <?php
    $view->registerJs("
        var json;
        
        function collectJSON(){
            //alert('collectJSON');
            json.discount_type='simple';
            json.discount_title=$('#discount_title').val();
            json.condition_attribute=$('#condition_attribute').val();
            json.condition_operator=$('#condition_operator').val();
            json.condition_value=$('#condition_value').val();
            json.search_attribute=$('#search_attribute').val();
            json.search_operator=$('#search_operator').val();
            json.search_value=$('#search_value').val();
            json.discount_value=$('#discount_value').val();
            json.discount_unit=$('#discount_unit').val();
            var json_str = JSON.stringify(json);
            \$('#discount_rule').val(json_str);
        }

        function activateForm(){
            try{
              json=jQuery.parseJSON( \$('#discount_rule').val() );
              $('#condition_attribute').val(json.condition_attribute);
              $('#condition_operator').val(json.condition_operator);
              $('#condition_value').val(json.condition_value);
              $('#search_attribute').val(json.search_attribute);
              $('#search_operator').val(json.search_operator);
              $('#search_value').val(json.search_value);
              $('#discount_value').val(json.discount_value);
              $('#discount_unit').val(json.discount_unit);
            }catch(err){
            }
            if(!json){
              json={};
            }
            if(!json.discount_title)      json.discount_title='';
            if(!json.condition_attribute) json.condition_attribute='';
            if(!json.condition_operator)  json.condition_operator='';
            if(!json.condition_value)     json.condition_value='';
            if(!json.search_attribute)    json.search_attribute='';
            if(!json.search_operator)     json.search_operator='';
            if(!json.search_value)        json.search_value='';
            if(!json.discount_value)      json.discount_value='';
            if(!json.discount_unit)       json.discount_unit='';

            $('#condition_attribute').change(collectJSON);
            $('#condition_operator').change(collectJSON);
            $('#condition_value').change(collectJSON);
            $('#search_attribute').change(collectJSON);
            $('#search_operator').change(collectJSON);
            $('#search_value').change(collectJSON);
            $('#discount_value').change(collectJSON);
            $('#discount_unit').change(collectJSON);
            $('#discount_title').change(collectJSON);
            collectJSON();
        }
        $(window).load(activateForm);    
    ");        
    }

}
