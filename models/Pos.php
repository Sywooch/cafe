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
    
    
    
    public function getAvailablePackaging(){
        $pos_id=(int)$this->pos_id;
        
        // get all available packaging
        //        $sql="SELECT packaging.*, MIN(IF(pos_product.product_id IS NULL,-1,pos_product.product_id)) AS packaging_is_available
        //              FROM packaging_product 
        //                   INNER JOIN packaging ON packaging.packaging_id=packaging_product.packaging_id
        //                   LEFT JOIN pos_product 
        //                   ON (pos_product.product_id=packaging_product.product_id 
        //                       AND pos_product.pos_id={$pos_id}
        //                       AND pos_product.pos_product_quantity>packaging_product.packaging_product_quantity)
        //              WHERE NOT packaging.packaging_is_additional
        //              GROUP BY packaging.packaging_id
        //              HAVING packaging_is_available>0";
        //        $dataBasic = \Yii::$app->db->createCommand($sql, [])->queryAll();
        //        
        //        
        //        $sql="SELECT packaging.*, MIN(IF(pos_product.product_id IS NULL,-1,pos_product.product_id)) AS packaging_is_available
        //              FROM packaging_product 
        //                   INNER JOIN packaging ON packaging.packaging_id=packaging_product.packaging_id
        //                   LEFT JOIN pos_product 
        //                   ON (pos_product.product_id=packaging_product.product_id 
        //                       AND pos_product.pos_id={$pos_id}
        //                       AND pos_product.pos_product_quantity>packaging_product.packaging_product_quantity)
        //              WHERE packaging.packaging_is_additional
        //              GROUP BY packaging.packaging_id
        //              HAVING packaging_is_available>0";
        //        $dataAdditional=\Yii::$app->db->createCommand($sql, [])->queryAll();
        
        // get all available packaging
        $sql="SELECT packaging.*, MIN(IF(pos_product.product_id IS NULL,-1,pos_product.product_id)) AS packaging_is_available
              FROM packaging_product 
                   INNER JOIN packaging ON packaging.packaging_id=packaging_product.packaging_id
                   LEFT JOIN pos_product 
                   ON (pos_product.product_id=packaging_product.product_id 
                       AND pos_product.pos_id={$pos_id}
                       AND pos_product.pos_product_quantity>packaging_product.packaging_product_quantity)
              GROUP BY packaging.packaging_id
              HAVING packaging_is_available>0";
        $dataBasic = \Yii::$app->db->createCommand($sql, [])->queryAll();
        
        // no additionalData block
        $dataAdditional=[];

        // get all categories
        $category_ids=Array();
        $category_ids[]=0;
        foreach($dataBasic as $item){
            $category_ids[]=(int)$item['category_id'];
        }
        //
        $sql="SELECT * FROM category WHERE category_id IN(".join(',',$category_ids).") ORDER BY category_ordering ASC";
        $categories=\Yii::$app->db->createCommand($sql, [])->queryAll();
        
        return [
            'packagingBasic'=>$dataBasic,
            'packagingAdditional'=>$dataAdditional,
            'category'=>$categories
        ];
    }
}
