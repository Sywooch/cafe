<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/report/index']];
$this->params['breadcrumbs'][] = $this->title;
$orderSearch=Yii::$app->request->get('OrderSearch');
//print_r($orderSearch);
if(!$orderSearch){
    $orderSearch=Array ( 
        'order_id' =>'', 'pos.pos_title' => '', 'sysuser.sysuser_fullname' =>'', 
        'order_payment_type' =>'', 'order_total_min' =>'', 
        'order_total_max' =>'', 'order_datetime_min' =>'', 
        'order_datetime_max' =>'' ) ;
}

$data=[
        'order_total_sum'=>((float)$total['order_total_sum']),
        'currency'=>Yii::$app->params['currency'],
        'order_num'=>( (int)$total['order_num'] ),
        'order_total_avg'=>round($total['order_total_avg'],2)
    ];
?>
    <style type="text/css">
        .col1, .col2{
            display:inline-block;
            vertical-align:top;
        }
        .col1{
            width:25%;
        }
        .col2{
            width:75%;
        }
        .width100{
            width:100%;
        }
        .width90{
            width:90%;
        }
        .width50{
            width:43%;
        }
        .itogo{
            margin-top: 20px;
        }
        #filterform{
            padding-top:4px;
        }
        .toggler{
            margin-left:-20px;
            padding-left:20px;
            background-image:url(./img/settings.png);
            background-repeat:no-repeat;
            background-position:left center;
        }
    </style>

<div class="order-index">

    <span class="col1"><h1><?= Html::encode($this->title) ?></h1></span><!-- 
 --><span class="col2"><div class="itogo breadcrumb">
        <span class="filter-element"><?=Yii::t('app','foundOrdersTotal',$data)?></span>
        <span class="filter-element"><?=Yii::t('app','foundOrdersCount',$data)?></span>
        <span class="filter-element"><?=Yii::t('app','foundOrdersAvg',$data)?></span>        
     </div></span>
    <span class="col1"><form method="get" id="filterform">
        <input type="hidden" name="r" value="order/index">
        <input type="hidden" name="sort" value="<?=Yii::$app->request->get('sort')?>">
        <div>
       <!-- <label><?=Yii::t('app','Order report')?></label> -->
            <a class="filter-element width90" href="javascript:void(today())"><?=Yii::t('app','today').' '.date('d.m.Y')?></a>
            <a class="filter-element width90" href="javascript:void(yesterday())"><?=Yii::t('app','yesterday').' '.date('d.m.Y',time()-3600*24)?></a>
       <!-- <a class="filter-element width90" href="javascript:void(thisweek())"><?=Yii::t('app','thisweek')?></a> -->
            <a class="filter-element width90" href="javascript:void(lastweek())"><?=Yii::t('app','lastweek')?></a>
       <!-- <a class="filter-element width90" href="javascript:void(thismonth())"><?=Yii::t('app','thismonth')?></a> -->
       <!-- <a class="filter-element width90" href="javascript:void(lastmonth())"><?=Yii::t('app','lastmonth')?></a> -->
        </div>
        <a class="filter-element width90" href="javascript:void(toggleSelector('#dateselector'))"><?=Yii::t('app','Order Datetime Set')?></a>
        <div id="dateselector" style="display:none;">
            <span class="filter-element width50"><label><?=Yii::t('app','Order Datetime Min')?></label>
            <?=DatePicker::widget([
                'name'  => 'OrderSearch[order_datetime_min]',
                'value'  => ($orderSearch['order_datetime_min']?$orderSearch['order_datetime_min']:null),
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'=>['size'=>10, 'class'=>'form-control','id'=>'datefrom']
            ])?>
            </span>
            <span class="filter-element width50"><label><?=Yii::t('app','Order Datetime Max')?></label>
            <?=DatePicker::widget([
                'name'  => 'OrderSearch[order_datetime_max]',
                'value'  => ($orderSearch['order_datetime_max']?$orderSearch['order_datetime_max']:null),
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'=>['size'=>10, 'class'=>'form-control','id'=>'dateto']
            ])?>
            </span>
            <span class="filter-element"><label>&nbsp;</label><input type="submit" class="btn btn-success" value="<?=Yii::t('app','find')?>"></span>
        </div>
        <br/>
        <br/>
        <a class="filter-element width90 toggler" href="javascript:void(toggleSelector('#otherOptions'))"><b><?=Yii::t('app','Order report flter')?></b></a>
        <div id="otherOptions" style="display:none;">
            <span class="filter-element width90"><label><?=Yii::t('app', 'Order ID')?></label><?=Html::textInput( 'OrderSearch[order_id]', $orderSearch['order_id'], ['class'=>'form-control width100'] )?></span><br/>
            <span class="filter-element width90"><label><?=Yii::t('app','Pos')?></label><?=Html::dropDownList('OrderSearch[pos.pos_title]', $orderSearch['pos.pos_title'], array_merge([''=>Yii::t('app','All POSs')],ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(),'pos_title','pos_title')), ['class'=>'form-control width100'] )?></span><br/>
            <span class="filter-element width90"><label><?=Yii::t('app','seller')?></label><?=Html::dropDownList('OrderSearch[sysuser.sysuser_fullname]', $orderSearch['sysuser.sysuser_fullname'], array_merge([''=>Yii::t('app','All sellers')],ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `sysuser`", [])->queryAll(),'sysuser_fullname','sysuser_fullname')), ['class'=>'form-control'] )?></span><br/>
            <span class="filter-element width90"><label><?=Yii::t('app','Order Payment Type')?></label><?=Html::dropDownList('OrderSearch[order_payment_type]', $orderSearch['order_payment_type'], array_merge([''=>''],ArrayHelper::map($nOrders=\Yii::$app->db->createCommand("select distinct order_payment_type from `order`", [])->queryAll(),'order_payment_type','order_payment_type')), ['class'=>'form-control'] )?></span><br/>
            <span class="filter-element width50"><label><?=Yii::t('app','Order Total Min')?></label><?=Html::textInput('OrderSearch[order_total_min]', $orderSearch['order_total_min'], ['size'=>3, 'class'=>'form-control'] )?></span>
            <span class="filter-element width50"><label><?=Yii::t('app','Order Total Max')?></label><?=Html::textInput('OrderSearch[order_total_max]', $orderSearch['order_total_max'], ['size'=>3, 'class'=>'form-control'] )?></span>
            <span class="filter-element"><label>&nbsp;</label><input type="submit" class="btn btn-success" value="<?=Yii::t('app','find')?>"></span>
        </div>
        <script type="application/javascript">
        function toggleSelector(selector){
            if(typeof(Storage) !== "undefined") {
                localStorage.setItem(selector, ($(selector+':visible').length > 0)?'0':'1');
            } else {
            }
            $( selector ).slideToggle('slow');
        }
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
        <?php
        $this->registerJs("
            $(document).ready(function(){
                if(typeof(Storage) !== \"undefined\") {
                    // Code for localStorage/sessionStorage.
                    if(localStorage.getItem('#otherOptions')=='1'){
                        $('#otherOptions').slideToggle('slow');
                    }
                    if(localStorage.getItem('#dateselector')=='1'){
                        $('#dateselector').slideToggle('slow');
                    }
                } else {
                    // Sorry! No Web Storage support..
                }
            });

            ");
        ?>
    </form>
    </span><!-- 
 --><span class="col2">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view}&nbsp;{update}{products}{supply}&nbsp;&nbsp;&nbsp;{delete}',
                'template' => ($role['admin']?'<nobr>{view}&nbsp;&nbsp;&nbsp;{delete}</nobr>':'{view}'),
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
            ['label' => Yii::t('app','paytype'),'attribute' => 'order_payment_type','filterOptions'=>['class'=>'numFilter'],'filter' => false,],
            [
                'label' => Yii::t('app','Order Total'),
                'filterOptions'=>['class'=>'numFilter'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->order_total.' '.Yii::$app->params['currency'];
                           },
                'format' => 'html',
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
            [
                'label' => Yii::t('app','Order Discount'),
                'filterOptions'=>['class'=>'numFilter'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->order_discount?($model->order_discount.' '.Yii::$app->params['currency']):'';
                           },
                'format' => 'html',
            ],
            //'discount_title',
            // 'order_hash',

        ],
    ]); ?>
    </span>
    
    <p></p>
</div>
