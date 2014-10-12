<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pos".
 *
 * @property string $pos_id
 * @property string $pos_title
 * @property string $pos_address
 * @property string $pos_timetable
 *
 * @property Order[] $orders
 * @property PosProduct[] $posProducts
 * @property Product[] $products
 * @property Seller[] $sellers
 * @property Supply[] $supplies
 */
class Pos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_title', 'pos_address', 'pos_timetable'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_id' => Yii::t('app', 'pos_id'),
            'pos_title' => Yii::t('app', 'pos_title'),
            'pos_address' => Yii::t('app', 'pos_address'),
            'pos_timetable' => Yii::t('app', 'pos_timetable'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosProducts()
    {
        return $this->hasMany(PosProduct::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['product_id' => 'product_id'])->viaTable('{supply}', ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellers()
    {
        return $this->hasMany(Seller::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplies()
    {
        return $this->hasMany(Supply::className(), ['pos_id' => 'pos_id']);
    }
}
