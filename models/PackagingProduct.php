<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "packaging_product".
 *
 * @property string $packaging_id
 * @property string $product_id
 * @property double $packaging_product_quantity
 *
 * @property Packaging $packaging
 * @property Product $product
 */
class PackagingProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'packaging_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packaging_id', 'product_id'], 'required'],
            [['packaging_id', 'product_id'], 'integer'],
            [['packaging_product_quantity'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'packaging_id' => Yii::t('app', 'Packaging ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'packaging_product_quantity' => Yii::t('app', 'Packaging Product Quantity'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackaging()
    {
        return $this->hasOne(Packaging::className(), ['packaging_id' => 'packaging_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }
}
