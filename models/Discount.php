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
            [['discount_description', 'discount_rule', 'discount_type'], 'string'],
            [['discount_auto'], 'boolean'],
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
            'discount_auto' => Yii::t('app', 'Discount Auto'),
            'discount_type' => Yii::t('app', 'Discount Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['discount_id' => 'discount_id']);
    }
    
    
    public function getDiscountTypes(){
        return [
            'simple'=>Yii::t('app', 'Discount Type Simple'),
            'eachNth'=>Yii::t('app', 'Discount Type Nth'),
        ];
    }
    
    public static function subtypeFactory($model){
        switch($model->discount_type){
            case 'eachNth':
                return new DiscountTypeEachNth($model);
            case 'simple':
                return new DiscountTypeSimple($model);                
        }
    }
    
    
    public function getDiscountValue($order){
        //, $json
        // check if order has discount_id
        $discountModel=null;
        if(isset($order['discount_id']) && $order['discount_id']>0){
            $discountModel = Discount::findOne($order['discount_id']);
        }
        if($discountModel !== null){
            // get one discount
            $discountSubtype = Discount::subtypeFactory($discountModel);
            $tmp=$discountSubtype->apply($order);
            if($tmp){
                return $tmp['discount_value'];
            }
        }
        return 0;
    }
    
}
