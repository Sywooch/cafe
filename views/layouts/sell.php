<?php
use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/custom.css" type="text/css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css" />
    <!-- link href="css/jquery-ui/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css"/ -->
    <link href="css/jquery-ui/jquery-ui-1.11.2.custom/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/jquery.toast.css" />
    <?= $content ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="width light" id="sellerPage"><!-- 
    --><div class="leftcolumn"><!-- 
     --><div class="bordercolumn"><div id="cornertop"></div><div id="cornerbottom"></div></div><!--
     --><div class="tipy" id="categories"><!--
     --></div><!-- 
        --><div class="tovary" id="tovaryList"><!-- 
            --><div class="tovar tov_1" id="packagingBasic"></div><!-- 
            --><!-- <div class="tovar tov_2 dobavki" id="packagingAdditional"></div> --><!-- 
        --></div><!-- 
        --><div class="statistika"><!-- 
            --><div class="button_stat" id="button_stat"></div><!--
		--><div class="text_stat"><!-- 
                    --><div class="text_stat_1" id="sellerName">-</div><!--
                    --><div class="text_stat_2">Сегодня: заказов: <span id="ordersCount">-</span></div><!--
                    --><div class="text_stat_3">на сумму: <span id="ordersTotal">-</span>&nbsp;<?=Yii::$app->params['currency']?>,</div><!--
                    --><!-- <div class="text_stat_4">комиссионные: <span id="sellerCommissionFee">-</span>&nbsp;<?=Yii::$app->params['currency']?>.</div> --><!--
		--></div><!-- 
        --></div><!-- 
    --></div><!-- 
    --><div class="rightcolumn"><!-- 
        --><div class="zakaz"><!-- 
            --><h2>Заказ <span id="zakazId"></span></h2><!-- 
            --><div id="zakazScrollUp" class="zakazScrollerUp">&nbsp;</div><!-- 
            --><div id="zakazItems"><div id="zakazItemsPanel"></div></div><!-- 
            --><div id="zakazScrollDown" class="zakazScrollerDown">&nbsp;</div><!-- 
        --></div><!-- 
        --><div class="discounts"><!-- 
           --><h4><span class="col1">Скидка: </span></h4><!-- 
        --></div><!-- 
        --><div class="itogo"><!-- 
           --><div class="new" id="newOrder">!</div><!--
           --><h4><span class="col1">Итого: </span><span id="orderTotal"></span>&nbsp;<?=Yii::$app->params['currency']?></h4><!-- 
        --></div><!-- 
        --><div class="raschet"><!-- 
            --><h4>Сдача:</h4><!-- 
            --><div class="calcRow calcFirstRow"><span class="calcCell">Получено</span><span class="calcCell">Сдача</span></div><!-- 
            --><div class="calcRow" id="calcRow"></div><!-- 
            --><div class="calcRow"><span class="calcCell"><input type=text value="" id="gotCache">&nbsp;<?=Yii::$app->params['currency']?></span><span class="calcCell"><span id="sdacha"></span></span></div><!-- 
        --></div><!-- 
        --><div class="oplata"><!-- 
            --><div id="cachPaid"><div class="cash"><!-- 
                --><!--  <img src=img/cash.png>
                --><h5>Оплачен наличными</h5><!-- 
            --></div></div><!--
            --><div id="cardPaid"><div class="card"><!-- 
                --><!-- <img src=img/card.png>
                --><h5>Оплачен картой</h5><!-- 
            --></div></div><!-- 
        --></div><!-- 
        --><!--               
    --></div><!--
 --></div>
<?php $this->endBody() ?>
<div id="dialog" title="<?=Yii::t('app','Processing the order')?>" style="display:none;"><img src="img/waiting.gif"></div>
<span id='extraLinks' style="display:none;">
<?=Html::a('Стартовая страница',['site/index'])?><br>

</span>
<div id="popupDialog"></div>
<script type="application/javascript" src="js/jquery-ui.min.js"></script>
<script src="js/jquery.mousewheel.min.js"></script>
<script type="application/javascript" src="js/jquery-ui.min.js"></script>
<script src="js/jquery.toast.js" type="text/javascript"></script>
<script src="js/sell.js" type="text/javascript"></script>


    
    

</body>
</html>
<?php $this->endPage() ?>
