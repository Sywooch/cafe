<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $orderSearch=Yii::$app->request->get('OrderSearch');
    //print_r($orderSearch);
    if(!$orderSearch){
        $orderSearch=Array ( 
            'order_id' =>'', 'pos.pos_title' => '', 'sysuser.sysuser_fullname' =>'', 
            'order_payment_type' =>'', 'order_total_min' =>'', 
            'order_total_max' =>'', 'order_datetime_min' =>'', 
            'order_datetime_max' =>'' ) ;
    }
    ?>
    <div><form method="get" id="filterform">
        <input type="hidden" name="r" value="order/index">
        <input type="hidden" name="sort" value="<?=Yii::$app->request->get('sort')?>">
        <span class="filter-element"><label><?=Yii::t('app', 'Order ID')?></label><?=Html::textInput( 'OrderSearch[order_id]', $orderSearch['order_id'], ['size'=>3, 'class'=>'form-control'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','Pos')?></label><?=Html::textInput( 'OrderSearch[pos.pos_title]', $orderSearch['pos.pos_title'], ['size'=>13, 'class'=>'form-control'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','seller')?></label><?=Html::textInput( 'OrderSearch[sysuser.sysuser_fullname]', $orderSearch['sysuser.sysuser_fullname'], ['size'=>13, 'class'=>'form-control'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','Order Payment Type')?></label><?=Html::dropDownList('OrderSearch[order_payment_type]', $orderSearch['order_payment_type'], array_merge([''=>''],ArrayHelper::map($nOrders=\Yii::$app->db->createCommand("select distinct order_payment_type from `order`", [])->queryAll(),'order_payment_type','order_payment_type')), ['class'=>'form-control'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','Order Total Min')?></label><?=Html::textInput('OrderSearch[order_total_min]', $orderSearch['order_total_min'], ['size'=>3, 'class'=>'form-control'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','Order Total Max')?></label><?=Html::textInput('OrderSearch[order_total_max]', $orderSearch['order_total_max'], ['size'=>3, 'class'=>'form-control'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','Order Datetime Min')?></label><?=Html::textInput('OrderSearch[order_datetime_min]', $orderSearch['order_datetime_min'], ['size'=>10, 'class'=>'form-control','id'=>'datefrom'] )?></span>
        <span class="filter-element"><label><?=Yii::t('app','Order Datetime Max')?></label><?=Html::textInput('OrderSearch[order_datetime_max]', $orderSearch['order_datetime_max'], ['size'=>10, 'class'=>'form-control','id'=>'dateto'] )?></span>
        <span class="filter-element"><label>&nbsp;</label><input type="submit" class="btn btn-success" value="<?=Yii::t('app','find')?>"></span>
        <div>
            <label><?=Yii::t('app','Order Datetime Set')?></label>
            <a href="javascript:void(today())"><?=Yii::t('app','today')?></a>
            <a href="javascript:void(yesterday())"><?=Yii::t('app','yesterday')?></a>
            <a href="javascript:void(thisweek())"><?=Yii::t('app','thisweek')?></a>
            <a href="javascript:void(lastweek())"><?=Yii::t('app','lastweek')?></a>
            <a href="javascript:void(thismonth())"><?=Yii::t('app','thismonth')?></a>
            <a href="javascript:void(lastmonth())"><?=Yii::t('app','lastmonth')?></a>
            <script type="application/javascript">
            function today(){
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();
                if(dd<10) {
                    dd='0'+dd
                } 
                if(mm<10) {
                    mm='0'+mm
                } 
                today = dd+'.'+mm+'.'+yyyy;
                setDates(today, today);
            }
            function yesterday(){
                var today = new Date();
                today.setDate(today.getDate() - 1);
                today = formatDate(today);
                setDates(today, today);
            }
            function getMonday(d) {
              d = new Date(d);
              var day = d.getDay(),
                  diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
              return new Date(d.setDate(diff));
            }
            function thisweek(){
                var monday=getMonday(new Date());
                setDates(formatDate(monday), '');
            }
            function lastweek(){
                var monday=getMonday(new Date());
                monday.setDate(monday.getDate() - 7);

                var sunday=getMonday(new Date());
                sunday.setDate(sunday.getDate() - 1);
                
                setDates(formatDate(monday), formatDate(sunday));
            }
            function thismonth(){
                var today = new Date();
                today.setMonth(today.getMonth());
                today.setDate(1);
                setDates(formatDate(today), '');
            }
            function lastmonth(){
                var start = new Date();
                start.setMonth(start.getMonth()-1);
                start.setDate(1);
                
                var finish=new Date();
                finish.setMonth(finish.getMonth());
                finish.setDate(0);
                
                setDates(formatDate(start), formatDate(finish));
            }
            function formatDate(thedate){
                var dd = thedate.getDate();
                var mm = thedate.getMonth()+1; //January is 0!
                var yyyy = thedate.getFullYear();
                if(dd<10) {
                    dd='0'+dd
                } 
                if(mm<10) {
                    mm='0'+mm
                } 
                return dd+'.'+mm+'.'+yyyy;
            }
            function setDates(d1, d2){
                document.getElementById('datefrom').value=d1;
                document.getElementById('dateto').value=d2;
                document.getElementById('filterform').submit();
            }
            </script>
        </div>
    </form></div><br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view}&nbsp;{update}{products}{supply}&nbsp;&nbsp;&nbsp;{delete}',
                'template' => '{view}',
            ],

            ['attribute' => 'order_id','filterOptions'=>['class'=>'numFilter'],'filter' => false,],

            //'pos_id',
            [
                'attribute' => 'pos.pos_title',
                'format' => 'text',
                'label' => Yii::t('app','Pos'),
                'filter' => false,
            ],
            ['attribute'=>'sysuser.sysuser_fullname','label' => Yii::t('app','seller'),'filter' => false,],
            //'seller_id',
            //'sysuser_id',
            //['attribute' => 'order_datetime', 'format'=>['date', 'php:d.m.Y H:i:s']],
            [
                'attribute' => 'order_datetime',
                'label' => Yii::t('app','Order Datetime'),
                'filter' => false,
                'content'=>function ($model, $key, $index, $column){
                                return date('d.m.Y H:i:s',strtotime($model->order_datetime));
                           }
            ],
            // 'order_day_sequence_number',
            ['attribute' => 'order_payment_type','filterOptions'=>['class'=>'numFilter'],'filter' => false,],
            [
                'label' => Yii::t('app','Order Total'),
                'filterOptions'=>['class'=>'numFilter'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->order_total.' '.Yii::$app->params['currency'];
                           }
            ],
            //[
            //    'label' => Yii::t('app','Seller Commission'),
            //    'filterOptions'=>['class'=>'numFilter'],
            //    'filter' => false,
            //    'content'=>function ($model, $key, $index, $column){
            //                    $seller=$model->getSeller()->one();
            //                    return ($model->order_total * 0.01 * $seller->seller_commission_fee).' '.Yii::$app->params['currency'];
            //               }
            //],
            //[
            //    'label' => Yii::t('app','Order Discount'),
            //    'filterOptions'=>['class'=>'numFilter'],
            //    'content'=>function ($model, $key, $index, $column){
            //                    return $model->order_discount.' '.Yii::$app->params['currency'];
            //               }
            //],
            //'discount_title',
            // 'order_hash',

        ],
    ]); ?>

    
    <p><b><?=Yii::t('app','foundOrdersTotal')?> : <?=$foundOrdersTotal.' '.Yii::$app->params['currency']?></b></p>
</div>
