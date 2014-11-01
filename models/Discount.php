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
}
