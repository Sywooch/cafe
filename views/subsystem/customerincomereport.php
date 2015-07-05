<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'CustomerIncomeReport');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subsystem_reports'), 'url' => ['/subsystem/index']];
$this->params['breadcrumbs'][] = ['label' => $subsystem->subsystemTitle, 'url' => ['/subsystem/reports', 'subsystemId'=>$subsystem->subsystemId]];
$this->params['breadcrumbs'][] = $this->title;

?>
<h1><?= Html::encode($this->title) ?></h1>
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


<span class="col1">
    <form method="get" id="filterform">
        <input type="hidden" name="r" value="subsystem/customerincomereport">
        <input type="hidden" name="sort" value="<?=$post['sort']?>">
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
        

        <br/>
        <br/>
        <a class="filter-element width90 toggler" href="javascript:void(toggleSelector('#otherOptions'))"><b><?=Yii::t('app','Order report flter')?></b></a>
        <div id="otherOptions" style="display:none;">
            <?php /* 
            <span class="filter-element width90">
                <label><?=Yii::t('app', 'Customer ID')?></label>
                <?=Html::textInput( 'OrderSearch[customerId]', $post['customerId'], ['class'=>'form-control width100'] )?>
            </span><br/>
            */?>
            <span class="filter-element width90">
                <label><?=Yii::t('app', 'Customer Mobile')?></label>
                <?=Html::textInput( 'customerMobile', $post['customerMobile'], ['class'=>'form-control width100'] )?>
            </span><br/>
            <span class="filter-element width90">
                <label><?=Yii::t('app', 'Customer Name')?></label>
                <?=Html::textInput( 'customerName', $post['customerName'], ['class'=>'form-control width100'] )?>
            </span><br/>
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
        
        $this->registerJsFile('./js/Chart.min.js');
    
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
    <div class="itogo breadcrumb">
    <?php
    if(strlen($post['order_datetime_min'])>0
            || strlen($post['order_datetime_max'])>0){
        ?>
        
            <?php
            if($post['order_datetime_min']==$post['order_datetime_max']){
                ?><?=$post['order_datetime_min']?><?php
            }else{
                ?><?=$post['order_datetime_min']?> &ndash; <?=$post['order_datetime_max']?><?php
            }
            ?>
        
        <?php
    }
    ?>
    <?=Yii::t('app','Customers found')?>: <?=$data['n_records']?>    
    </div>

<div>
<?=Yii::t('app','Pages')?>:
<?php

$urlParameters = [
    'subsystem/customerincomereport',
    'order_datetime_min' => $post['order_datetime_min'],
    'order_datetime_max' => $post['order_datetime_max'],
    'customerMobile' => $post['customerMobile'],
    'customerName' => $post['customerName'],
    'page' => ( isset($post['page'])?$post['page']:'0'),
    'sort' => ( isset($post['sort'])?$post['sort']:''),
    'subsystemId' => ( isset($post['subsystemId'])?$post['subsystemId']:''),
];

        
for($i=0;$i<$data['pageCount']; $i++){
    if($i==$data['page']){
        echo "<span class=\"pagelink active\">".($i+1)."</span>";
    }else{
        $urlParameters['page']=$i;
        echo "<a href=\"".Url::to($urlParameters)."\" class=\"pagelink\">".($i+1)."</a>";
    }
}

function sortVal($curr,$next){
    if($curr==$next){
        return "-$next";
    }elseif($curr=="-$next"){
        return "";
    }else{
        return $next;
    }
}

?>
</div>
<table class="table table-striped table-bordered">
<tr>
    <th></th>
    <th><a href="<?=Url::to(array_merge($urlParameters,['page'=>0,'sort'=>sortVal($urlParameters['sort'],'customerId'),'page'=>0]))?>"><?=Yii::t('app', 'Customer ID')?></a></th>
    <th><a href="<?=Url::to(array_merge($urlParameters,['page'=>0,'sort'=>sortVal($urlParameters['sort'],'customerMobile')]))?>"><?=Yii::t('app','Customer Mobile')?></a></th>
    <th><a href="<?=Url::to(array_merge($urlParameters,['page'=>0,'sort'=>sortVal($urlParameters['sort'],'customerName')]))?>"><?=Yii::t('app','Customer Name')?></a></th>
    <th><a href="<?=Url::to(array_merge($urlParameters,['page'=>0,'sort'=>sortVal($urlParameters['sort'],'total')]))?>"><?=Yii::t('app','totalIncome')?></a></th>
</tr>

<?php
foreach($data['rows'] as $row){
    ?><tr>
        <td></td>
        <td><?=$row['customerId']?></td>
        <td><?=$row['customerMobile']?></td>
        <td><?=$row['customerName']?></td>
        <td><?=($row['total']?($row['total']."&nbsp;".\Yii::$app->params['currency']):'')?></td>
      </tr><?php
}
?>


</table>
</span>