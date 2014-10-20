<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "packaging".
 *
 * @property string $packaging_id
 * @property string $packaging_icon
 * @property string $packaging_title
 * @property double $packaging_price
 * @property string $category_id
 * 
 * @property OrderPackaging[] $orderPackagings
 * @property Order[] $orders
 * @property PackagingProduct[] $packagingProducts
 * @property Product[] $products
 * @property Category $category
 */
class Packaging extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'packaging';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['packaging_price'], 'number'],
            [['packaging_icon'], 'string', 'max' => 1024],
            [['category_id'], 'integer'],
            [['packaging_is_additional'], 'boolean'],
            [['packaging_title'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'packaging_id' => Yii::t('app', 'Packaging ID'),
            'packaging_icon' => Yii::t('app', 'Packaging Icon'),
            'packaging_title' => Yii::t('app', 'Packaging Title'),
            'packaging_price' => Yii::t('app', 'Packaging Price') . ', ' . \Yii::$app->params['currency'],
            'category_id' => Yii::t('app', 'Packaging Category'),
            'packaging_is_additional'=>Yii::t('app', 'Packaging is additional'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPackagings() {
        return $this->hasMany(OrderPackaging::className(), ['packaging_id' => 'packaging_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['order_id' => 'order_id'])->viaTable('order_packaging', ['packaging_id' => 'packaging_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagingProducts() {
        return $this->hasMany(PackagingProduct::className(), ['packaging_id' => 'packaging_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasMany(Product::className(), ['product_id' => 'product_id'])->viaTable('packaging_product', ['packaging_id' => 'packaging_id']);
    }

}
