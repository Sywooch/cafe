<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seller_log".
 *
 * @property string $seller_log_id
 * @property string $seller_id
 * @property string $seller_log_action
 * @property string $seller_log_datetime
 * @property string $seller_log_date
 * @property resource $seller_log_data
 *
 * @property Seller $seller
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysuser_id'], 'integer'],
            [['log_datetime', 'log_date'], 'safe'],
            [['log_data'], 'string'],
            [['log_action'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'sysuser_id' => Yii::t('app', 'sysuser_id'),
            'log_action' => Yii::t('app', 'Log Action'),
            'log_datetime' => Yii::t('app', 'Log Datetime'),
            'log_date' => Yii::t('app', 'Log Date'),
            'log_data' => Yii::t('app', 'Log Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysuser()
    {
        return $this->hasOne(Sysuser::className(), ['sysuser_id' => 'sysuser_id']);
    }
    
    
    
    public function getWorkingTime($sysuser_id, $log_date)
    {
        $tmp=Log::find()->where(['sysuser_id' => $sysuser_id,'log_date'=>$log_date])->orderBy('log_datetime')->all();
        $time=0;
        return $this->hasOne(Sysuser::className(), ['sysuser_id' => 'sysuser_id']);
    }
    // -------------------------------------------------------------------------
    // 
    public static function getCurrentPosSeller($pos_id){
        $result=[];
        $sellers = Seller::find()->where(['pos_id' => $pos_id])->all();
        if($sellers){
            $sysusers = [];
            foreach($sellers as $seller){
                $sysusers[]=$seller['sysuser_id'];
            }
            
            //$day=3600*24;
            //$today=date('Y-m-d',time());
            //$yesterday=date('Y-m-d',time()-$day);
            // get last seller action 
            $last_seller_action_datetime=Log::find()           
                ->select(['sysuser_id', 'MAX(log_datetime) AS last_seller_action_datetime'])
                ->where(['sysuser_id'=>$sysusers])// , 'log_date'=>[$today, $yesterday]
                ->groupBy(['sysuser_id'])
                ->orderBy('last_seller_action_datetime DESC')
                ->asArray()
                ->all();
            if(count($last_seller_action_datetime)>0){
                $where=[];
                foreach($last_seller_action_datetime as $dtt){
                    $where[]=" ( log.sysuser_id={$dtt['sysuser_id']} AND log_datetime='{$dtt['last_seller_action_datetime']}' ) ";
                }
               
                $sql = "SELECT log.*, sysuser.sysuser_fullname, sysuser.sysuser_login
                        FROM `log` inner join sysuser on log.sysuser_id=sysuser.sysuser_id
                        WHERE ".join(' OR ',$where)."
                        ORDER BY log_datetime DESC";
                $result=\Yii::$app->db->createCommand($sql, [])->queryAll();
            }
        }
        return $result;
    }
    

}
