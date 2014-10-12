<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sysuser".
 *
 * @property string $sysuser_id
 * @property string $sysuser_fullname
 * @property string $sysuser_login
 * @property string $sysuser_password
 * @property integer $sysuser_role_mask
 * @property string $sysuser_telephone
 * @property string $sysuser_token
 *
 * @property Order[] $orders
 * @property Seller[] $sellers
 */
class Sysuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sysuser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysuser_role_mask'], 'integer'],
            [['sysuser_fullname'], 'string', 'max' => 512],
            [['sysuser_login', 'sysuser_telephone', 'sysuser_token'], 'string', 'max' => 64],
            [['sysuser_password'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sysuser_id' => Yii::t('app', 'Sysuser ID'),
            'sysuser_fullname' => Yii::t('app', 'Sysuser Fullname'),
            'sysuser_login' => Yii::t('app', 'Sysuser Login'),
            'sysuser_password' => Yii::t('app', 'Sysuser Password'),
            'sysuser_role_mask' => Yii::t('app', 'Sysuser Role Mask'),
            'sysuser_telephone' => Yii::t('app', 'Sysuser Telephone'),
            'sysuser_token' => Yii::t('app', 'Sysuser Token'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['sysuser_id' => 'sysuser_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellers()
    {
        return $this->hasMany(Seller::className(), ['sysuser_id' => 'sysuser_id']);
    }
}
