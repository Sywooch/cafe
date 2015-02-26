<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $customerId
 * @property string $customerMobile
 * @property string $customerName
 * @property string $customerNotes
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['customerId'], 'required'],
            //[['customerId'], 'integer'],
            [['customerNotes'], 'string'],
            [['customerMobile'], 'string', 'max' => 127],
            [['customerName'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customerId' => Yii::t('app', 'Customer ID'),
            'customerMobile' => Yii::t('app', 'Customer Mobile'),
            'customerName' => Yii::t('app', 'Customer Name'),
            'customerNotes' => Yii::t('app', 'Customer Notes'),
        ];
    }
}
