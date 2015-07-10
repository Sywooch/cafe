<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use app\models\Report;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "{$subsystem->subsystemTitle} - ".Yii::t('app', 'PosIncomeReport');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subsystem_reports'), 'url' => ['/subsystem/index']];
$this->params['breadcrumbs'][] = ['label' => $subsystem->subsystemTitle, 'url' => ['/subsystem/reports', 'subsystemId'=>$subsystem->subsystemId]];
$this->params['breadcrumbs'][] = $this->title;

//print_r($post);
//print_r($data);

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
        .pagelink{
            padding:3px 7px;
            border:1px solid gray;
            display:inline-block;
            vertical-align: baseline;
        }
        .pagelink:hover,
        .pagelink.active{
            background-color:silver;
        }
    </style>
<div class="order-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <span class="col1">
    <form method="get" id="filterform">
        <input type="hidden" name="r" value="subsystem/posincomereport">
        <input type="hidden" name="subsystemId" value="<?=$post['subsystemId']?>">
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
                'name'  => 'order_datetime_min',
                'value'  => ($post['order_datetime_min']?$post['order_datetime_min']:null),
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'=>['size'=>10, 'class'=>'form-control','id'=>'datefrom']
            ])?>
            </span>
            <span class="filter-element width50"><label><?=Yii::t('app','Order Datetime Max')?></label>
            <?=DatePicker::widget([
                'name'  => 'order_datetime_max',
                'value'  => ($post['order_datetime_max']?$post['order_datetime_max']:null),
                'language' => 'ru',
                'dateFormat' => 'dd.MM.yyyy',
                'options'=>['size'=>10, 'class'=>'form-control','id'=>'dateto']
            ])?>
            </span>
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


    </form></span><!--
 --><span class="col2">
     
         <?php
    if(strlen($post['order_datetime_min'])>0 || strlen($post['order_datetime_max'])>0){
        ?>
        <div class="itogo breadcrumb">
            <?php
            if($post['order_datetime_min']==$post['order_datetime_max']){
                ?><?=$post['order_datetime_min']?><?php
            }else{
                ?><?=$post['order_datetime_min']?> &ndash; <?=$post['order_datetime_max']?><?php
            }
            ?>
           
        </div>
        <?php
    }
    ?>  
     
    <span style="float:left;margin-right:20px;display:inline-block;"><b><?=Yii::t('app','Order Total')?></b><br><canvas id="myChart" width="250" height="250" ></canvas></span>
    <script type="application/javascript">
        var data=[];
    <?php
      // $tmp=$query->all();
      $colors=Report::getColors();
      // foreach($tmp as $ke=>$tm){
      foreach($data['rows'] as $ke=>$row){
          $colorId=$ke%count($colors);
          echo "data.push({ value: {$row['total']},color:\"{$colors[$colorId][0]}\", highlight: \"{$colors[$colorId][1]}\", label: \"{$row['pos_title']}\"});";
      }
    ?>
    </script>
    <?php
    
        $this->registerJsFile('./js/Chart.min.js');
    
        $this->registerJs("


            $(document).ready(function(){
                // Get the context of the canvas element we want to select
                var ctx = document.getElementById(\"myChart\").getContext(\"2d\");
                var myNewChart = new Chart(ctx).Pie(data);

            });

            ");

    ?>
 
         <table class="table table-striped table-bordered">
            <tr><thead>
                <th><?=Yii::t('app','pos_id')?></th>
                <th><?=Yii::t('app','pos_title')?></th>
                <th><?=Yii::t('app','totalIncome')?></th>
                <th><?=Yii::t('app','n_orders')?></th>
                <tbody>
            <?php
            foreach($data['rows'] as $row){
                ?>
                <tr>
                    <td><?=$row['pos_id']?></td>
                    <td><?=$row['pos_title']?></td>
                    <td><?=((double)$row['total']).'&nbsp;'.Yii::$app->params['currency']?></td>
                    <td><?=$row['n_orders']?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </span>
</div>