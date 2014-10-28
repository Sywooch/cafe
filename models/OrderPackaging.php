<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_packaging".
 *
 * @property string $order_id
 * @property string $packaging_id
 * @property string $packaging_title
 * @property double $packaging_price
 * @property integer $order_packaging_number
 *
 * @property Order $order
 * @property Packaging $packaging
 */
class OrderPackaging extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_packaging';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'packaging_id'], 'required'],
            [['order_id', 'packaging_id', 'order_packaging_number'], 'integer'],
            [['packaging_price'], 'number'],
            [['packaging_title'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'packaging_id' => Yii::t('app', 'Packaging ID'),
            'packaging_title' => Yii::t('app', 'Packaging Title'),
            'packaging_price' => Yii::t('app', 'Packaging Price'),
            'order_packaging_number' => Yii::t('app', 'Order Packaging Number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackaging()
    {
        return $this->hasOne(Packaging::className(), ['packaging_id' => 'packaging_id']);
    }
}
