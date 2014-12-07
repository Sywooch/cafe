<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use app\models\Report;

$this->title = Yii::t('app', 'WeekdailyIncomeReport');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['/report/index']];
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

<?php


$orderSearch=Yii::$app->request->get('OrderSearch');
//print_r($orderSearch);
if(!$orderSearch){
    $orderSearch=Array ( 
        'order_id' =>'',
        'pos.pos_title' => '', 
        'order_datetime_min' =>'', 
        'order_datetime_max' =>'',
        'sysuser.sysuser_fullname' =>''
    ) ;
}



?>

<span class="col1">
    <form method="get" id="filterform">
        <input type="hidden" name="r" value="report/weekdailyincome">
        <div>
        <!-- <label><?=Yii::t('app','Order report')?></label> -->
        <!-- <a class="filter-element width90" href="javascript:void(today())"><?=Yii::t('app','today').' '.date('d.m.Y')?></a> -->
        <!-- <a class="filter-element width90" href="javascript:void(yesterday())"><?=Yii::t('app','yesterday').' '.date('d.m.Y',time()-3600*24)?></a> -->
        <!-- <a class="filter-element width90" href="javascript:void(thisweek())"><?=Yii::t('app','thisweek')?></a> -->
            <a class="filter-element width90" href="javascript:void(lastweek())"><?=Yii::t('app','lastweek')?></a>
            <a class="filter-element width90" href="javascript:void(thismonth())"><?=Yii::t('app','thismonth')?></a>
            <a class="filter-element width90" href="javascript:void(lastmonth())"><?=Yii::t('app','lastmonth')?></a>
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
            <span class="filter-element width90"><label><?=Yii::t('app','Pos')?></label><?=Html::dropDownList('OrderSearch[pos.pos_title]', $orderSearch['pos.pos_title'], array_merge([''=>Yii::t('app','All POSs')],ArrayHelper::map(\Yii::$app->db->createCommand("select distinct pos_title from `pos`", [])->queryAll(),'pos_title','pos_title')), ['class'=>'form-control width100'] )?></span><br/>
            <span class="filter-element width90"><label><?=Yii::t('app','seller')?></label><?=Html::dropDownList('OrderSearch[sysuser.sysuser_fullname]', $orderSearch['sysuser.sysuser_fullname'], array_merge([''=>Yii::t('app','All sellers')],ArrayHelper::map(\Yii::$app->db->createCommand("select distinct sysuser_fullname from `sysuser`", [])->queryAll(),'sysuser_fullname','sysuser_fullname')), ['class'=>'form-control'] )?></span><br/>
            <span class="filter-element"><label>&nbsp;</label><input type="submit" class="btn btn-success" value="<?=Yii::t('app','find')?>"></span>
        </div>
        <?php
        /*
            <span class="filter-element width90"><label><?=Yii::t('app','packaging_title')?></label><?=Html::textInput( 'OrderSearch[packaging_title]', $orderSearch['packaging_title'], ['class'=>'form-control width100'] )?></span><br/>
         */
        ?>

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
    <?php
    if(   strlen($orderSearch['order_datetime_min'])>0
       || strlen($orderSearch['order_datetime_max'])>0
       || strlen($orderSearch['pos.pos_title'])>0
       || strlen($orderSearch['sysuser.sysuser_fullname'])>0){
        ?>
        <div class="itogo breadcrumb">
            <?php
            if($orderSearch['order_datetime_min']==$orderSearch['order_datetime_max']){
                ?><?=$orderSearch['order_datetime_min']?><?php
            }else{
                ?><?=$orderSearch['order_datetime_min']?> &ndash; <?=$orderSearch['order_datetime_max']?><?php
            }
            ?>
            <?=$orderSearch['pos.pos_title']?>
            <?=$orderSearch['sysuser.sysuser_fullname']?>
        </div>
        <?php
    }
    ?>

    
    <canvas id="myChart" width="720" height="400"></canvas>

    <script type="application/javascript">
    var data = {
        labels: ['<?=Yii::t('app','Sun')?>','<?=Yii::t('app','Mon')?>','<?=Yii::t('app','Tue')?>','<?=Yii::t('app','Wed')?>','<?=Yii::t('app','Thu')?>','<?=Yii::t('app','Fri')?>','<?=Yii::t('app','Sat')?>'],
        datasets: [
            {
                label: "---",
                fillColor: "#66FF00",
                strokeColor: "#669900",
                highlightFill: "#99FF66",
                highlightStroke: "#66CC33",
                data: [<?=join(',',array_values($stats))?>]
            }
        ]
    };
    </script>
    <?php
    
    $this->registerJs("
        

        $(document).ready(function(){
            // Get the context of the canvas element we want to select
            var ctx = document.getElementById(\"myChart\").getContext(\"2d\");
            //var myNewChart = new Chart(ctx).Pie(data);
            var myBarChart = new Chart(ctx).Bar(data, {
               barStrokeWidth : 1,
               barValueSpacing : 3,
            });
        });

        ");
    ?>

    <?php
    /*
    <table class="table table-striped table-bordered">
    <tr><th><?=Yii::t('app','Hour')?></th><th><?=Yii::t('app','totalIncome')?></th></tr>
    <?php
    foreach($stats as $hrs=>$total){
        echo "<tr><td>{$hrs}</td><td>{$total}</td></tr>";
    }
    ?>
    </table>
     * 
     */
    ?>
</span>
