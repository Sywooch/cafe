<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%subsystem}}".
 *
 * @property string $subsystemId
 * @property string $subsystemTitle
 * @property string $subsystemUrl
 * @property string $subsystemApiKey
 */
class Subsystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subsystem}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subsystemTitle'], 'string', 'max' => 255],
            [['subsystemUrl', 'subsystemApiKey'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subsystemId' => Yii::t('app', 'Subsystem ID'),
            'subsystemTitle' => Yii::t('app', 'Subsystem Title'),
            'subsystemUrl' => Yii::t('app', 'Subsystem Url'),
            'subsystemApiKey' => Yii::t('app', 'Subsystem Api Key'),
        ];
    }
    
        
    public static function download($url,$data) {
        // var_dump($url);
        // var_dump($data);
        //url-ify the data for the POST
	$ch = curl_init();
	$timeout = 15;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_POST, sizeof($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
	$reply = curl_exec($ch);
	curl_close($ch);
	return $reply;
    }

    public static function orderreport($subsystem, $filter){
        
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $filter,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        //unset($postData['r']);
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/orderreport",$postData);
        //echo $json;exit('<hr>');
        return json_decode($json, true);
    }

    
    public static function customerincomereport($subsystem, $filter){
        
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $filter,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        //unset($postData['r']);
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/customerincomereport",$postData);
        //echo $json;exit('<hr>');
        return json_decode($json, true);
    }
    
    
    public static function sellerreport($subsystem, $filter){
        
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $filter,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/sellerreport",$postData);
        //echo $json;exit('<hr>');
        return json_decode($json, true);
    }

    public static function productreport($subsystem, $post){
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $post,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/productreport",$postData);
        //echo $json;exit('<hr>');
        return json_decode($json, true);
    }
    
    

    public static function packagingreport($subsystem, $post){
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $post,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/packagingreport",$postData);
        //echo $json;exit('<hr>');
        return json_decode($json, true);
    }
    
    
    public static function posincomereport($subsystem, $post) {
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $post,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/posincomereport",$postData);
        //echo $json;exit('<hr>');
        return json_decode($json, true);        
    }
    
    
    public static function hourlyincomereport($subsystem, $post) {
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $post,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/hourlyincomereport",$postData);
        // echo $json;exit('<hr>');
        return json_decode($json, true);        
    }
 
    
    public static function weekdailyincomereport($subsystem, $post) {
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $post,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/weekdailyincomereport",$postData);
        // echo $json;exit('<hr>');
        return json_decode($json, true);        
    }
    public static function dailyincomereport($subsystem, $post) {
        $time=strtotime(gmdate('Y-m-d H:i:s'))+300;
        $postData=array_merge(
                    $post,
                    [
                        'time'=>$time,
                        'key'=>md5($time.$subsystem->subsystemApiKey),
                    ]
                );
        // print_r($postData);
        $json=self::download($subsystem->subsystemUrl."?r=api/dailyincomereport",$postData);
        // echo $json;exit('<hr>');
        return json_decode($json, true);        
    }
    
    
}
