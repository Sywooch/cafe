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
class Pos extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'pos';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['pos_title', 'pos_address', 'pos_timetable', 'pos_printer_url'], 'string', 'max' => 1024],
            [['pos_printer_template'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'pos_id' => Yii::t('app', 'pos_id'),
            'pos_title' => Yii::t('app', 'pos_title'),
            'pos_address' => Yii::t('app', 'pos_address'),
            'pos_timetable' => Yii::t('app', 'pos_timetable'),
            'pos_printer_url' => Yii::t('app', 'pos_printer_url'),
            'pos_printer_template' => Yii::t('app', 'pos_printer_template'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosProducts() {
        return $this->hasMany(PosProduct::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts() {
        return $this->hasMany(Product::className(), ['product_id' => 'product_id'])->viaTable('{supply}', ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellers() {
        return $this->hasMany(Seller::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplies() {
        return $this->hasMany(Supply::className(), ['pos_id' => 'pos_id']);
    }

    public function getAvailablePackaging() {
        $pos_id = (int) $this->pos_id;

        // get all available packaging
        $sql = "SELECT packaging.*, 
                    MIN(IF(pos_product.product_id IS NULL,-1,pos_product.product_id)) AS packaging_is_available,
                    if(pos_packaging.pos_packaging_price is not null,pos_packaging.pos_packaging_price,packaging.packaging_price) as packaging_price
              FROM packaging_product 
                   INNER JOIN packaging ON packaging.packaging_id=packaging_product.packaging_id
                   LEFT JOIN pos_product 
                   ON (pos_product.product_id=packaging_product.product_id 
                       AND pos_product.pos_id={$pos_id}
                       AND pos_product.pos_product_quantity>packaging_product.packaging_product_quantity)
                   LEFT JOIN pos_packaging
                   ON (pos_packaging.packaging_id=packaging.packaging_id AND pos_packaging.pos_id={$pos_id})
              WHERE packaging.packaging_is_visible
              GROUP BY packaging.packaging_id
              ORDER BY packaging.packaging_ordering ASC
              ";
        if(isset($_REQUEST['v'])){
            echo $sql;
        }
        // HAVING packaging_is_available>0
        $dataBasic = \Yii::$app->db->createCommand($sql, [])->queryAll();

        // no additionalData block
        $dataAdditional = [];

        // get all categories
        $category_ids = Array();
        $category_ids[] = 0;
        foreach ($dataBasic as $item) {
            $category_ids[] = (int) $item['category_id'];
        }
        //
        $sql = "SELECT * FROM category WHERE category_id IN(" . join(',', $category_ids) . ") ORDER BY category_ordering ASC";
        $categories = \Yii::$app->db->createCommand($sql, [])->queryAll();

        return [
            'packagingBasic' => $dataBasic,
            'packagingAdditional' => $dataAdditional,
            'category' => $categories
        ];
    }

    public function getPackaging() {
        $pos_id = (int) $this->pos_id;

        // get all available packaging
        $sql = "SELECT packaging.packaging_id, packaging.packaging_title,
                       packaging.packaging_price,pos_packaging.pos_packaging_price
                FROM packaging
                   LEFT JOIN pos_packaging
                   ON (pos_packaging.packaging_id=packaging.packaging_id AND pos_packaging.pos_id={$pos_id})
              GROUP BY packaging.packaging_id
              ORDER BY packaging.packaging_title ASC
              ";

        return $sql;
    }

    
}
