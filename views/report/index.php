<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Choose report type');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->product_title, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Choose report type');

?>

<h1><?= Html::encode($this->title) ?></h1>
<ul>
<li><?=Html::a( Yii::t('app','Orders'), ['/order/index','sort'=>'-order_id'], [] )?></li>
<li><?=Html::a( Yii::t('app','Sellers'), ['/report/seller'], [] )?></li>
<li><?=Html::a( Yii::t('app','ProductReport'), ['/report/product'], [] )?></li>
<li><?=Html::a( Yii::t('app','PackagingReport'), ['/report/packaging'], [] )?></li>
<li><?=Html::a( Yii::t('app', 'PosIncomeReport'), ['/report/posincome'], [] )?></li>
<li><?=Html::a( Yii::t('app', 'SellerIncomeReport'), ['/report/sellerincome'], [] )?></li>
<li><?=Html::a( Yii::t('app', 'HourlyIncomeReport'), ['/report/hourlyincome'], [] )?></li>
<li><?=Html::a( Yii::t('app', 'WeekdailyIncomeReport'), ['/report/weekdailyincome'], [] )?></li>
<li><?=Html::a( Yii::t('app', 'DailyIncomeReport'), ['/report/dailyincome'], [] )?></li>
</ul>

