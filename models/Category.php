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
class Category extends \yii\db\ActiveRecord {

    public function behaviors() {
        return [
            'image' => [
                'class' => 'app\models\ModelImageBehave',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['category_title', 'category_skin'], 'string', 'max' => 64],
            [['category_ordering'], 'integer'],
            [['category_icon'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'category_id' => Yii::t('app', 'Category ID'),
            'category_title' => Yii::t('app', 'Category Title'),
            'category_skin' => Yii::t('app', 'Category Skin'),
            'category_ordering' => Yii::t('app', 'Category Ordering'),
            'category_icon' => Yii::t('app', 'Category Icon'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackagings() {
        return $this->hasMany(Packaging::className(), ['category_id' => 'category_id']);
    }

    public function getId() {
        return $this->category_id;
    }

    /**
      //retun url to full image
      // echo $img->getUrl();
      //return url to proportionally resized image by width
      //echo $img->getUrl('300x');
      //return url to proportionally resized image by height
      //echo $img->getUrl('x300');
      //return url to resized and cropped (center) image by width and height
      //echo $img->getUrl('200x300');
     */
    public function getImageUrl($sizeString) {
        // echo $sizeString;
        //Returns main model image
        $img = $this->getImage();
        if ($img) {
            return $img->getUrl($sizeString);
        } else {
            return '';
        }
    }

}
