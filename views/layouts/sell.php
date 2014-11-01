<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
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
    <?= $content ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="width light"><!-- 
    --><div class="leftcolumn"><!-- 
        --><div class="tipy" id="categories"></div><!-- 
        --><div class="tovary" id="tovaryList"><!-- 
            --><div class="tovar tov_1" id="packagingBasic"></div><!-- 
            --><div class="tovar tov_2" id="packagingAdditional"></div><!-- 
        --></div><!-- 
        --><div class="statistika"><!-- 
            --><div class="button_stat"></div><!--
		--><div class="text_stat"><!-- 
                    --><div class="text_stat_1" id="sellerName">-</div><!--
                    --><div class="text_stat_2">заказов: <span id="ordersCount">-</span></div><!--
                    --><div class="text_stat_3">на сумму: <span id="ordersTotal">-</span>&nbsp;<?=Yii::$app->params['currency']?>,</div><!--
                    --><div class="text_stat_4">комиссионные: <span id="sellerCommissionFee">-</span>&nbsp;<?=Yii::$app->params['currency']?>.</div><!--
		--></div><!-- 
        --></div><!-- 
    --></div><!-- 
    --><div class="rightcolumn"><!-- 
        --><div class="zakaz"><!-- 
            --><h2>Заказ №<span id="zakazId"></span></h2><!-- 
            --><div id="zakazItems"></div><!-- 
        --></div><!-- 
        --><div class="itogo"><!-- 
           --><h4>Итого: <span id="orderTotal"></span>&nbsp;<?=Yii::$app->params['currency']?></h4><!-- 
        --></div><!-- 
        --><div class="raschet"><!-- 
            --><h4>Получено: <input type=text value="" id="gotCache">&nbsp;<?=Yii::$app->params['currency']?></h4><!-- 
            --><h4>Сдача: <span id="sdacha"></span>&nbsp;<?=Yii::$app->params['currency']?></h4><!-- 
        --></div><!-- 
        --><div class="oplata"><!-- 
            --><div class="cash"><!-- 
                --><img src=img/cash.png><!-- 
                --><h5>Оплачено наличными</h5><!-- 
            --></div><!--
            --><div class="card"><!-- 
                --><img src=img/card.png><!-- 
                --><h5>Оплачено картой</h5><!-- 
            --></div><!-- 
        --></div><!-- 
        --><div class="new" id="newOrder"><h4>Новый заказ</h4></div><!--               
    --></div><!--
 --></div>
<?php $this->endBody() ?>
<script src="js/sell.js" type="text/javascript"></script>
</body>
</html>
<?php $this->endPage() ?>
