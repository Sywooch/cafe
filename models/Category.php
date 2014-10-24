<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property string $category_id
 * @property string $category_title
 *
 * @property Packaging[] $packagings
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_title','category_skin'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('app', 'Category ID'),
            'category_title' => Yii::t('app', 'Category Title'),
            'category_skin' => Yii::t('app', 'Category Skin'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagings()
    {
        return $this->hasMany(Packaging::className(), ['category_id' => 'category_id']);
    }
}
