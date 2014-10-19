<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pos_product".
 *
 * @property string $pos_id
 * @property string $product_id
 * @property double $pos_product_quantity
 * @property double $pos_product_min_quantity
 *
 * @property Pos $pos
 * @property Product $product
 */
class PosProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id', 'product_id', 'pos_product_quantity', 'pos_product_min_quantity'], 'required'],
            [['pos_id', 'product_id'], 'integer'],
            [['pos_product_quantity', 'pos_product_min_quantity'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_id' => Yii::t('app', 'Pos ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'pos_product_quantity' => Yii::t('app', 'Pos Product Quantity'),
            'pos_product_min_quantity' => Yii::t('app', 'Pos Product Min Quantity'),
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
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }
}
