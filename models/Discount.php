<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discount".
 *
 * @property string $discount_id
 * @property string $discount_title
 * @property string $discount_description
 * @property string $discount_rule
 *
 * @property Order[] $orders
 */
class Discount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount_description', 'discount_rule'], 'string'],
            [['discount_title'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'discount_id' => Yii::t('app', 'Discount ID'),
            'discount_title' => Yii::t('app', 'Discount Title'),
            'discount_description' => Yii::t('app', 'Discount Description'),
            'discount_rule' => Yii::t('app', 'Discount Rule'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['discount_id' => 'discount_id']);
    }
    
    
    public static function getDiscountValue($order, $json){
        if (!$order['discount_id']) {
            return 0;
        }

        $condition_ok=false;
        
        // get transformation rule
        $transformationParameter=(str_replace(',','.',$json->discount_value));
        if(is_numeric($transformationParameter)){
            if($json->discount_unit=='%'){
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
            $order_total += $pack['order_packaging_number']*$pack['packaging_price'];
        }
        
        
        if ($json->condition_attribute) {
            

            switch ($json->condition_attribute) {
                case 'order_total':
                    $val=(float)str_replace(',','.',$json->condition_value);
                    switch($json->condition_operator){
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
                        $val=(float)str_replace(',','.',$json->condition_value);
                        switch($json->condition_operator){
                            case '>' : $condition_ok=($num >  $val);  break;
                            case '>=': $condition_ok=($num >= $val);  break;
                            case '=' : $condition_ok=($num == $val);  break;
                            case '<=': $condition_ok=($num <= $val);  break;
                            case '<' : $condition_ok=($num <  $val);  break;
                        }
                    }
                    break;

                case 'packaging_title':
                    foreach($order['order_packaging'] as $pack){
                        if($condition_ok){ continue; }
                        $packaging_title=$pack['packaging']->packaging_title;
                        switch($json->condition_operator){
                            case '>' : $condition_ok=($packaging_title >  $json->condition_value);  break;
                            case '>=': $condition_ok=($packaging_title >= $json->condition_value); break;
                            case '=' : $condition_ok=($packaging_title == $json->condition_value); break;
                            case '<=': $condition_ok=($packaging_title <= $json->condition_value); break;
                            case '<' : $condition_ok=($packaging_title <  $json->condition_value);  break;
                            case '~' : 
                                $words = preg_split("/[ ,;-]+/", $json->condition_value);
                                $res=true;
                                foreach($words as $w){
                                    if(strpos($packaging_title, $w)===false){
                                        $res=false;
                                    }
                                }
                                $condition_ok=$res;
                                break;
                        }
                        //if(verbose){console.log(json.condition_attribute + '  '+condition_ok);}
                    }
                    break;
            }
        }else{
            $condition_ok=true;
        }

        
        if ($json->search_attribute) {
            //if(verbose){console.log(' matching  search_attribute '+json.search_attribute);}
            switch ($json->search_attribute) {
                
                // ========== apply discount to order total = begin ============
                case 'order_total':
                    $condition_ok=false;
                    $val=(float)str_replace(',','.',$json->search_value);
                    switch($json->search_operator){
                        case '>' : $condition_ok=($order_total >  $val);  break;
                        case '>=': $condition_ok=($order_total >= $val);  break;
                        case '=' : $condition_ok=($order_total == $val);  break;
                        case '<=': $condition_ok=($order_total <= $val);  break;
                        case '<' : $condition_ok=($order_total <  $val);  break;
                        case 'e' : $condition_ok=true;                    break;
                    }
                    if($condition_ok){
                        $discountValue=$transformationRule($order_total);
                        return $discountValue;
                    }else{
                        return 0;
                    }
                    break;
                // ========== apply discount to order total = end ==============
                
                
                // == apply discount to packaging_id or packaging_price = begin ==
                case 'packaging_id':
                case 'packaging_price':
                    $discountValue=0;
                    $condition_ok=false;
                    $val=(float)str_replace(',','.',$json->search_value);
                    foreach($order->order_packaging as $pack){
                        $num=$pack['packaging']->packaging_price;
                        switch($json->search_operator){
                            case '>' : $condition_ok=($num >  $val);  break;
                            case '>=': $condition_ok=($num >= $val);  break;
                            case '=' : $condition_ok=($num == $val);  break;
                            case '<=': $condition_ok=($num <= $val);  break;
                            case '<' : $condition_ok=($num <  $val);  break;
                        }
                        if($condition_ok){
                            $discountValue+=$pack['order_packaging_number'] * $transformationRule($num);
                        }
                    }
                    return $discountValue;
                    break;
                // == apply discount to packaging_id or packaging_price = end ====

                case 'packaging_title':
                    $discountValue=0;
                    $condition_ok=false;
                    foreach($order['order_packaging'] as $pack){
                        $packaging_title=$pack['packaging']->packaging_title;
                        switch($json->search_operator){
                            case '>' : $condition_ok=($packaging_title >  $json->search_value);  break;
                            case '>=': $condition_ok=($packaging_title >= $json->search_value); break;
                            case '=' : $condition_ok=($packaging_title == $json->search_value); break;
                            case '<=': $condition_ok=($packaging_title <= $json->search_value); break;
                            case '<' : $condition_ok=($packaging_title <  $json->search_value);  break;
                            case '~':
                                $words = preg_split("/[ ,;-]+/", $json->search_value);
                                $res=true;
                                foreach($words as $w){
                                    if(strpos($packaging_title, $w)===false){
                                        $res=false;
                                    }
                                }
                                $condition_ok=$res;
                                break;
                        }
                        if($condition_ok){
                            $discountValue+=$pack['order_packaging_number'] * $transformationRule($pack['packaging']->packaging_price);
                        }
                    }
                    return $discountValue;
                    break;
            }
        }

        return 0;

    }
}
