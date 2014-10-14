<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seller".
 *
 * @property string $seller_id
 * @property string $sysuser_id
 * @property string $pos_id
 * @property double $seller_salary
 * @property double $seller_commission_fee
 *
 * @property Order[] $orders
 * @property Pos $pos
 * @property Sysuser $sysuser
 */
class Seller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysuser_id', 'pos_id'], 'integer'],
            [['seller_salary', 'seller_commission_fee'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seller_id' => Yii::t('app', 'seller_id'),
            'sysuser_id' => Yii::t('app', 'Sysuser'),
            'pos_id' => Yii::t('app', 'Pos'),
            'seller_salary' => Yii::t('app', 'Seller Salary'),
            'seller_commission_fee' => Yii::t('app', 'Seller Commission Fee'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['seller_id' => 'seller_id']);
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
    public function getSysuser()
    {
        return $this->hasOne(Sysuser::className(), ['sysuser_id' => 'sysuser_id']);
    }
}
