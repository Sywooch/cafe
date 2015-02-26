<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property string $order_id
 * @property string $pos_id
 * @property string $seller_id
 * @property string $sysuser_id
 * @property string $order_datetime
 * @property integer $order_day_sequence_number
 * @property double $order_total
 * @property double $order_discount
 * @property string $order_payment_type
 * @property string $order_hash
 *
 * @property Pos $pos
 * @property Seller $seller
 * @property Sysuser $sysuser
 * @property OrderPackaging[] $orderPackagings
 * @property Packaging[] $packagings
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id', 'seller_id', 'sysuser_id'], 'required'],
            [['pos_id', 'seller_id', 'sysuser_id', 'order_day_sequence_number','customerId'], 'integer'],
            [['order_datetime'], 'safe'],
            [['order_total', 'order_discount','order_seller_comission'], 'number'],
            [['order_payment_type'], 'string', 'max' => 32],
            [['order_hash'], 'string', 'max' => 64],
            [['order_notes'], 'string', 'max' => 1024],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'pos_id' => Yii::t('app', 'Pos ID'),
            'seller_id' => Yii::t('app', 'Seller ID'),
            'sysuser_id' => Yii::t('app', 'Sysuser ID'),
            'order_datetime' => Yii::t('app', 'Order Datetime'),
            'order_day_sequence_number' => Yii::t('app', 'Order Day Sequence Number'),
            'order_total' => Yii::t('app', 'Order Total'),
            'order_discount' => Yii::t('app', 'Order Discount'),
            'order_payment_type' => Yii::t('app', 'Order Payment Type'),
            'order_hash' => Yii::t('app', 'Order Hash'),
            'discount_title'=>Yii::t('app', 'Discount Title'),
            'order_seller_comission'=>Yii::t('app', 'Seller Comission'),
            'order_notes'=>Yii::t('app', 'order_notes'),
            'customerId'=>Yii::t('app', 'customerId'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasOne(Pos::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['seller_id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysuser()
    {
        return $this->hasOne(Sysuser::className(), ['sysuser_id' => 'sysuser_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscount()
    {
        return $this->hasOne(Discount::className(), ['discount_id' => 'discount_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPackagings()
    {
        return $this->hasMany(OrderPackaging::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagings()
    {
        return $this->hasMany(Packaging::className(), ['packaging_id' => 'packaging_id'])->viaTable('order_packaging', ['order_id' => 'order_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customerId' => 'customerId']);
    }
    public static function countOrders($pos_id,$date=false){
        if($date===false){
            $date=date('Y-m-d');
        }else{
            $timestamp=strtotime($date);
            if($timestamp!==false){
                $date=date('Y-m-d',$timestamp);
            }else{
                $date=date('Y-m-d');
            }
        }
        
        $pos_id=(int)$pos_id;
        
        //$sql="SELECT COUNT(*) AS nOrders FROM `order` WHERE pos_id={$pos_id} AND order_datetime BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59';";
        //
        $sql="SELECT MAX(order_day_sequence_number) AS nOrders FROM `order` WHERE pos_id={$pos_id} AND order_datetime BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59';";
        $nOrders=\Yii::$app->db->createCommand($sql, [])->queryOne();
        return (int)$nOrders['nOrders'];
    }
    
    public static function createOrderHash($pos_id, $seller_id, $order_datetime, $order_total, $order_discount){
        return sha1("{$pos_id}+{$seller_id}+{$order_datetime}+{$order_total}+{$order_discount}" + Yii::$app->params['salt']);
    }
}
