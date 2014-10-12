<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property string $product_id
 * @property string $product_title
 * @property string $product_icon
 * @property double $product_quantity
 * @property string $product_unit
 * @property double $product_min_quantity
 * @property double $product_unit_price
 *
 * @property PackagingProduct[] $packagingProducts
 * @property Packaging[] $packagings
 * @property PosProduct[] $posProducts
 * @property Pos[] $pos
 * @property Supply[] $supplies
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_quantity', 'product_min_quantity', 'product_unit_price'], 'number'],
            [['product_title', 'product_icon'], 'string', 'max' => 1024],
            [['product_unit'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'product_id'),
            'product_title' => Yii::t('app', 'product_title'),
            'product_icon' => Yii::t('app', 'product_icon'),
            'product_quantity' => Yii::t('app', 'product_quantity'),
            'product_unit' => Yii::t('app', 'product_unit'),
            'product_min_quantity' => Yii::t('app', 'product_min_quantity'),
            'product_unit_price' => Yii::t('app', 'product_unit_price'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagingProducts()
    {
        return $this->hasMany(PackagingProduct::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagings()
    {
        return $this->hasMany(Packaging::className(), ['packaging_id' => 'packaging_id'])->viaTable('{packaging_product}', ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosProducts()
    {
        return $this->hasMany(PosProduct::className(), ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasMany(Pos::className(), ['pos_id' => 'pos_id'])->viaTable('{supply}', ['product_id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplies()
    {
        return $this->hasMany(Supply::className(), ['product_id' => 'product_id']);
    }
}
