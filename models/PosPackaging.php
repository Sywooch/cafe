<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pos_packaging".
 *
 * @property string $pos_id
 * @property string $packaging_id
 * @property double $pos_packaging_price
 *
 * @property Packaging $packaging
 * @property Pos $pos
 */
class PosPackaging extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pos_packaging';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id', 'packaging_id'], 'required'],
            [['pos_id', 'packaging_id'], 'integer'],
            [['pos_packaging_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_id' => Yii::t('app', 'Pos ID'),
            'packaging_id' => Yii::t('app', 'Packaging ID'),
            'pos_packaging_price' => Yii::t('app', 'Pos Packaging Price'),
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
    public function getPos()
    {
        return $this->hasOne(Pos::className(), ['pos_id' => 'pos_id']);
    }
}
