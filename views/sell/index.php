<?php
$this->title=Yii::t('app','Sell');
//var img_root_url="< ? =Yii::$app->homeUrl? >/";
?>
<script type="application/javascript">
var pos_id=<?=($pos?$pos->pos_id:0)?>;
var sysuser_id=<?=$sysuser->sysuser_id?>;
var seller_id=<?=($seller?$seller->seller_id:0)?>;
var currency='<?=Yii::$app->params['currency']?>';
var printerUrl='<?=Yii::$app->params['printerUrl']?>';
</script>