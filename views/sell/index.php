<?php
$this->title=Yii::t('app','Sell');
//var img_root_url="< ? =Yii::$app->homeUrl? >/";

// printed order template
function parseTemplate($in){
    $from = Array( "'"); //"\\",
    $to = Array("\\'"); //"\\\\", 
    $out = str_replace($from, $to, $in);
    $out=preg_replace_callback(
           "/\\[([^\\]]+)\\/\\]/",
           function($m){ return "'+{$m[1]}+'";},//function($m){ return "'+data.{$m[1]}+'";},
           $out);
    $out=preg_replace_callback(
           "/\\[max_str_size\\]([^\\[]+)\\[\\/max_str_size\\]/",
           function($m){ return "t.max_str_size={$m[1]};";},
           $out);
    $out=preg_replace_callback(
           "/\\[center\\]([^\\[]+)\\[\\/center\\]/",
           function($m){ return "rows.push(t.center('{$m[1]}'));";},
           $out);
    $out=preg_replace_callback(
           "/\\[justify\\]([^\\[]*)\\[\\/\\]([^\\[]*)\\[\\/\\]([^\\[]*)\\[\\/justify\\]/",
           function($m){
               $from=Array("\\");//,"'"
               $to=Array("\\\\");//,"\\'"
               $m1=str_replace($from,$to,$m[1]);
               $m2=str_replace($from,$to,$m[2]);
               $m3=str_replace($from,$to,$m[3]);
               return "rows.push(t.justify('{$m1}','{$m2}','{$m3}'));";
           },
           $out);
    $out=preg_replace_callback(
           "/\\[foreach ([^\\]]+)\\]([^\\[]+)\\[\\/foreach\\]/",
            function($m){
               return "
for(var i in {$m[1]}){
    with({$m[1]}[i]){
        {$m[2]}
    }
}
                   ";
            },
           $out);
    return 
           "
var t=new tpl();
var rows=[];
with(data){
$out
}
return rows.join(\"\\n\");
           "
    ;
}
?>
<script type="application/javascript">
var pos_id=<?=($pos?$pos->pos_id:0)?>;
var sysuser_id=<?=$sysuser->sysuser_id?>;
var seller_id=<?=($seller?$seller->seller_id:0)?>;
var currency='<?=Yii::$app->params['currency']?>';
var printerUrl='<?=($pos?$pos->pos_printer_url:'')?>';
var sysuser_lastname='<?=preg_replace("/[ ,].*$/","",$sysuser->sysuser_fullname)?>';
var org_title='<?=Yii::$app->params['siteTitle']?>';

function processTemplate(data){
    <?=($pos?parseTemplate($pos->pos_printer_template):'')?>
}

var discounts={};
<?php
foreach($discounts as $key=>$discount){
    if(strlen($discount->discount_rule)>0){
       echo "discounts['{$discount->discount_id}']=".$discount->discount_rule.";\n";     
    }
}
?>
</script>
