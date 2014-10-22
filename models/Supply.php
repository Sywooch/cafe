<?php

namespace app\models;

use Yii;
use app\models\Product;
use yii\data\ArrayDataProvider;
/**
 * This is the model class for table "supply".
 *
 * @property string $pos_id
 * @property string $product_id
 * @property double $supply_quantity
 *
 * @property Pos $pos
 * @property Product $product
 */
class Supply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id', 'product_id'], 'required'],
            [['pos_id', 'product_id'], 'integer'],
            [['supply_quantity'], 'number']
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
            'supply_quantity' => Yii::t('app', 'Supply Quantity'),
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
    
    public static function getSupply($pos_id){
        // выбрать список всех товаров
        //    для каждого товара показать кол-во, которое осталось от поставок в другие точки
        //    кол-во, которое есть в текущей точке
        //    кол-во запланированной поставки
        

        $pos_id*=1;
        $query="SELECT  {$pos_id} as pos_id, p.product_id, p.product_title, p.product_quantity, p.product_unit, p.product_unit_price,
                IF(pp.`pos_product_quantity` IS NULL, 0, pp.`pos_product_quantity`) pos_product_quantity, 
                IF(pp.`pos_product_min_quantity` IS NULL,0,pp.`pos_product_min_quantity`) pos_product_min_quantity, 
                IF(s.`supply_quantity` IS NULL,0,s.`supply_quantity`) supply_quantity,
                SUM(IF(os.supply_quantity IS NULL, 0,os.supply_quantity)) AS other_pos_supply
                FROM product p
                     LEFT JOIN pos_product pp ON (pp.pos_id={$pos_id} AND p.product_id=pp.product_id)
                     LEFT JOIN supply s ON (s.pos_id={$pos_id} AND p.product_id=s.product_id)
                     LEFT JOIN supply os  ON (os.pos_id<>{$pos_id} AND p.product_id=s.product_id)
                GROUP BY p.product_id
                ORDER BY p.product_title";
        $data = \Yii::$app->db->createCommand($query, [])->queryAll();
                     
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['pos_id', 'p.product_id', 'p.product_title', 'p.product_quantity', 'p.product_unit', 'p.product_unit_price','pos_product_quantity', 'pos_product_min_quantity', 'supply_quantity','other_pos_supply'],
            ],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $dataProvider;
    }
}
