<?php

namespace app\models;

use Yii;
use app\models\Seller;
use app\models\Pos;

/**
 * This is the model class for table "workingtime".
 *
 * @property string $seller_id
 * @property string $workingtime_date
 * @property integer $workingtime_seconds
 * @property double $workingtime_hourly_wage
 * @property double $workingtime_wage
 *
 * @property Seller $seller
 */
class Workingtime extends \yii\db\ActiveRecord {

    private static $positiveActions=['sell','login','sellpage'];
    private static $negativeActions=['logout'];
    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'workingtime';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['seller_id', 'workingtime_date'], 'required'],
            [['seller_id', 'workingtime_seconds'], 'integer'],
            [['workingtime_date'], 'safe'],
            [['workingtime_hourly_wage', 'workingtime_wage'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'seller_id' => Yii::t('app', 'Seller ID'),
            'workingtime_date' => Yii::t('app', 'Workingtime Date'),
            'workingtime_seconds' => Yii::t('app', 'Workingtime Seconds'),
            'workingtime_hourly_wage' => Yii::t('app', 'Workingtime Hourly Wage'),
            'workingtime_wage' => Yii::t('app', 'Workingtime Wage'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller() {
        return $this->hasOne(Seller::className(), ['seller_id' => 'seller_id']);
    }

    
    
    // get all available dates
    public static function calculateAllWorkingTimes(){
        $sql = "SELECT MIN(log.log_date) as min_log_date,MAX(log.log_date) as max_log_date FROM `log`";
        $dat = \Yii::$app->db->createCommand($sql, [])->queryOne();
        //print_r($dat);
        //exit('7777');
        self::calculateWorkingTime($dat['min_log_date'], $dat['max_log_date']);
    }
    
    
    public static function calculateWorkingTime($date_min, $date_max){
        $sellers = Seller::find()->all();
        foreach($sellers as $seller){
            // echo "seller_id={$seller['seller_id']}<br>";
            self::calculateSellerWorkingTime($seller['seller_id'],$date_min, $date_max);
        }
    }
    
    
    /**
     * calculate working time for a given interval
     */
    public static function calculateSellerWorkingTime($seller_id,$date_min, $date_max){
        // echo "calculateSellerWorkingTime($seller_id,$date_min, $date_max)<br>";
        $seller = Seller::find()->where(['seller_id' => $seller_id])->one();
        if (!$seller) {
            return false;
        }
        
        $startTime = strtotime($date_min);
        $finishTime = strtotime($date_max);
        $day=3600*24;
        $today=date('Y-m-d',time());
        $yesterday=date('Y-m-d',time()-$day);
        for($timestamp=$startTime; $timestamp <= $finishTime; $timestamp+=$day){
            // check if working time for the day is calculated
            $log_date=date('Y-m-d',$timestamp);
            
            if($log_date==$today || $log_date==$yesterday){
                $workingtime_seconds=self::calculateSellerDayWorkingtime($seller_id, $log_date);
                $wt=Workingtime::findOne(['seller_id' => $seller_id,'workingtime_date'=>$log_date]);
                if ($wt) {
                    // update working time in DB
                    $wt->workingtime_seconds=$workingtime_seconds;
                    $wt->workingtime_hourly_wage=$seller->seller_wage;
                    $wt->workingtime_wage=$workingtime_seconds*$wt->workingtime_hourly_wage/3600.0;
                    $wt->save();
                }else{
                    // add working time to DB
                    $wt=new Workingtime();
                    $wt->seller_id=$seller_id;
                    $wt->workingtime_date=$log_date;
                    $wt->workingtime_seconds=$workingtime_seconds;
                    $wt->workingtime_hourly_wage=$seller->seller_wage;
                    $wt->workingtime_wage=$workingtime_seconds*$wt->workingtime_hourly_wage/3600.0;
                    $wt->save();
                }
            }else{
                // search for working time in DB
                $workingtimeRecord = Workingtime::find()->where(['seller_id' => $seller_id,'workingtime_date'=>$log_date])->one();
                if (!$workingtimeRecord) {
                    // if not found then calculate
                    // update working time in DB
                    $workingtime_seconds=self::calculateSellerDayWorkingtime($seller_id, $log_date);
                    $wt=new Workingtime();
                    $wt->seller_id=$seller_id;
                    $wt->workingtime_date=$log_date;
                    $wt->workingtime_seconds=$workingtime_seconds;
                    $wt->workingtime_hourly_wage=$seller->seller_wage;
                    $wt->workingtime_wage=$workingtime_seconds*($wt->workingtime_hourly_wage/3600.0);
                    $wt->save();
                    //return false;
                }
            }
        }
    }
    
    /**
     * recalculate working time for a given seller and date
     */
    public static function calculateSellerDayWorkingtime($seller_id, $log_date) {
        //$timezone = new \DateTimeZone(\Yii::$app->params['timezone']);
        //echo "calculateSellerDayWorkingtime($seller_id, $log_date)<br>";
        
        
        // load seller record
        $seller = Seller::find()->where(['seller_id' => $seller_id])->one();
        if (!$seller) {
            return false;
        }
        // var_dump($seller);

        // load POS record
        $pos = Pos::find()->where(['pos_id' => $seller->pos_id])->one();
        // var_dump($pos);
        // start time
        $start_time = $pos->pos_worktime_start;
        $finish_time = $pos->pos_worktime_finish;
        // echo "$start_time ... $finish_time;<br/>";

        if ($seller->seller_worktime_start) {
            $start_time = $seller->seller_worktime_start;
        }
        if ($seller->seller_worktime_finish) {
            $finish_time = $seller->seller_worktime_finish;
        }
        // echo "$start_time ... $finish_time;<br/>";


        //echo $log_date.'<hr>';
        //echo date('Y-m-d',strtotime($log_date)).' '.$start_time.'<hr>';
        $startDatetime=new \DateTime(date('Y-m-d',strtotime($log_date)).' '.$start_time);
        
        
        $finishDatetime=new \DateTime(date('Y-m-d',strtotime($log_date)).' '.$finish_time);
        if($startDatetime->getTimestamp()>$finishDatetime->getTimestamp()){
            $finishDatetime->add( new \DateInterval('P1D') );
        }
        $start=$startDatetime->getTimestamp();
        $finish=$finishDatetime->getTimestamp();
        $dt= \Yii::$app->params['workingtime_timeout'];
        
        // echo " $start ".date('Y-m-d H:i:s', $start-$dt)."  $finish ".date('Y-m-d H:i:s', $finish+$dt).';<br>';//exit();
        
        // load log records for the seller and the date
        $log = Log::find()->where("sysuser_id=:value_sysuser_id AND (log_datetime BETWEEN :log_date_from AND :log_date_to ) AND log_action IN ('".join("','",array_merge(self::$negativeActions,self::$positiveActions))."')",
                ['value_sysuser_id' => $seller->sysuser_id,
                 'log_date_from' => date('Y-m-d H:i:s', $start-$dt),
                 'log_date_to' => date('Y-m-d H:i:s', $finish+$dt),
                ])->orderBy('log_datetime')->asArray()->all();
        $cnt = count($log);
        for ($i = 0; $i < $cnt; $i++) {
            $log[$i]['timestamp'] = strtotime($log[$i]['log_datetime']);
        }
        // echo '<pre>'; print_r($log); echo '</pre>'; 

        
        
        
        // search for $b : b.log_action is positive and b.log_datetime gt $start
        for ($i = 0; $i < $cnt; $i++) {
            if ($log[$i]['timestamp'] > $start) {
                $b = &$log[$i];
                $imin=$i;
                // echo "<pre>start:\n"; print_r($b); echo '</pre>'; 
                break;
            }
        }
        // echo "<pre>imin: $i</pre>"; 
        
        if(!isset($b) || $b['timestamp']>$finish){
            return 0;
        }
        
        if($i==0){
            $workingtime = 0;
        }elseif( self::isNegativeAction($log[$i-1]['log_action'])){
            $workingtime = 0;
        }elseif(self::isPositiveAction($log[$i-1]['log_action']) && $log[$i-1]['timestamp']<$start){
            $workingtime = $b['timestamp'] - $start;
            // echo "<pre>initial time $workingtime </pre>";
        }else{
            return 0;
        }
        
        
        for ($i = $imin + 1; $i < $cnt; $i++) {
            // echo "<pre>test=$i</pre>";
            if ($log[$i]['timestamp'] < $finish) {
                if (self::isPositiveAction($log[$i - 1]['log_action'])) {
                    $upd=$log[$i]['timestamp'] - $log[$i - 1]['timestamp'];
                    $workingtime += $upd;
                    // echo "<pre>i=$i upd=$upd </pre>";
                }
            } else {
                if (self::isPositiveAction($log[$i - 1]['log_action'])) {
                    $upd=$finish - $log[$i - 1]['timestamp'];
                    $workingtime += $upd;
                    // echo "<pre>finish $i $upd </pre>";
                }
                break;
            }
        }
        //echo '<pre>'; print_r($log); echo '</pre>'; 
        // echo $workingtime.'<br>';
        return $workingtime;
    }

    
    private static function isPositiveAction($action){
        return in_array($action, self::$positiveActions);
    }
    private static function isNegativeAction($action){
        return in_array($action, self::$negativeActions);
    }
}





//    public static function calculateSellerDayWorkingtime($seller_id, $log_date) {
//        $timeout = \Yii::$app->params['workingtime_timeout'];
//
//        // load seller record
//        $seller = Seller::find()->where(['seller_id' => $seller_id])->one();
//        if (!$seller) {
//            return false;
//        }
//        // var_dump($seller);
//        // 
//        // load POS record
//        $pos = Pos::find()->where(['pos_id' => $seller->pos_id])->one();
//        // var_dump($pos);
//        // start time
//        $start_time = $pos->pos_worktime_start;
//        $finish_time = $pos->pos_worktime_finish;
//        // echo "$start_time ... $finish_time;<br/>";
//
//        if ($seller->seller_worktime_start) {
//            $start_time = $seller->seller_worktime_start;
//        }
//        if ($seller->seller_worktime_finish) {
//            $finish_time = $seller->seller_worktime_finish;
//        }
//        // echo "$start_time ... $finish_time;<br/>";
//
//
//        // load log records for the seller and the date
//        // $log=Log::find()->where(['sysuser_id'=>$seller->sysuser_id,'log_date'=>$log_date])->orderBy('log_datetime')->all();
//        $time_today = strtotime($log_date);
//        $time_yesterday = $time_today - 3600 * 24;
//        $time_tomorrow = $time_today + 3600 * 24;
//        
//        $log = Log::find()->where("
//                   sysuser_id=:value_sysuser_id
//               AND (log_date=:log_date_yesterday OR log_date=:log_date_today OR log_date=:log_date_tomorrow)
//                   ", ['value_sysuser_id' => $seller->sysuser_id,
//                    'log_date_yesterday' => date('Y-m-d', $time_yesterday),
//                    'log_date_today' => $log_date,
//                    'log_date_tomorrow' => date('Y-m-d', $time_tomorrow)
//                ])->orderBy('log_datetime')->all();
//
//
//        
//        $start_timestamp = strtotime($log_date . ' ' . $start_time);
//        $finish_timestamp = strtotime($log_date . ' ' . $finish_time);
//        if ($finish_timestamp < $start_timestamp) {
//            $finish_timestamp+=3600 * 24;
//        }
//        // echo "$start_time ... $finish_time;<br/>";
//        // echo date('Y-m-d H:i:s',$start_timestamp).'  ... '.date('Y-m-d H:i:s',$finish_timestamp).';<br/><br/>';
//
//        $prev_timestamp = false;
//        $curr_timestamp = false;
//        $prev_action=false;
//        
//        $prev_state = 0;
//        $state = 0;
//
//        $workingtime = 0;
//        
//        $lower =  ( $start_timestamp - $timeout );
//        
//        $cnt = count($log);
//
//        // ignore time less than $lower
//        for ($i = 0; $i < $cnt; $i++) {
//            $curr_timestamp = strtotime($log[$i]->log_datetime);
//            if( $curr_timestamp <= $lower){
//                $state = $prev_state = 0;
//                $prev_timestamp = $curr_timestamp;
//                $prev_action = $log[$i]->log_action;
//            }
//            break;
//        }
//        $imin=$i;
//        // echo "$imin {$log[$i]->log_action} {$log[$i]->log_datetime};<br><hr>";
//
//        for ($i = $imin; $i < $cnt; $i++) {
//            $curr_timestamp = strtotime($log[$i]->log_datetime);
//            // echo "$i {$log[$i]->log_action} {$log[$i]->log_datetime} ?;<br>";
//
//            if( $curr_timestamp <= $start_timestamp ){
//
//                $prev_timestamp = $curr_timestamp;
//                $prev_action = $log[$i]->log_action;
//                if ($prev_action == 'login' || $prev_action == 'sellpage' || $prev_action == 'sell') {
//                    // there was an action before $start_timestamp
//                    $state = $prev_state = 1;
//                }else{
//                    $state = $prev_state = 0;
//                }
//                continue;
//            }
//            break;
//        }
//        $imin=$i;
//        // echo "$i {$log[$i]->log_action} {$log[$i]->log_datetime} $prev_action  ... => $state;<br><hr>";
//
//        // $prev_timestamp < $start_timestamp && $start_timestamp<$curr_timestamp
//        $workingtime+=$state*(min($curr_timestamp, $finish_timestamp) - $start_timestamp);
//        $prev_timestamp = $curr_timestamp;
//        
//        // echo ($workingtime).";<br>";
//
//
//        for ($i = $imin; $i < $cnt; $i++) {
//            $curr_timestamp = strtotime($log[$i]->log_datetime);
//            
//            // check if game is over
//            if($curr_timestamp > $finish_timestamp){
//                if($curr_timestamp < $finish_timestamp + $timeout){
//                    $workingtime+=$prev_state*($finish_timestamp - $prev_timestamp);
//                }
//                break;
//            }
//            
//            // get next state
//            switch ($state) {
//                case 0:
//                    if ($log[$i]->log_action == 'login' || $log[$i]->log_action == 'sellpage' || $log[$i]->log_action == 'sell') {
//                        $state = 1;
//                    }
//                    break;
//                case 1:
//                    if ($log[$i]->log_action == 'logout') {
//                        $state = 0;
//                    }
//                    break;
//            }
//            
//            
//            $dt=$prev_state*($curr_timestamp - $prev_timestamp);
//            $workingtime+=$dt;
//            // echo "$i {$log[$i]->log_action} {$log[$i]->log_datetime} | $prev_state =>  $state;<br>";
//            // echo "dt=$dt<br>";
//            // echo "t=".($workingtime).";<br><br>";
//
//            
//            $prev_state = $state;
//            $prev_timestamp = $curr_timestamp;
//        }
//        
//        // if($curr_timestamp > $finish_timestamp){
//        //    $dt = $state * ( $curr_timestamp - $finish_timestamp );
//        //    $workingtime-=$dt;
//        // }
//        // echo "- finish ".date('Y-m-d H:i:s;',$finish_timestamp)."  $state;<br>";
//        // echo "dt=$dt<br>";
//        // echo "t=".($workingtime).";<br><br>";
//        // echo '<pre>'; print_r($log); echo '</pre>';
//
//        // echo "t=".($workingtime/3600).";<br><br>";
//
//        return $workingtime;
//    }