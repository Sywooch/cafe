<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Choose report type');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->product_title, 'url' => ['view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Choose report type');

?>

<h1><?= Html::encode($this->title) ?></h1>
<ul>
<li><?=Html::a( Yii::t('app','Orders'), ['/order/index'], [] )?></li>
<li><?=Html::a( Yii::t('app','Sellers'), ['/report/seller'], [] )?></li>
</ul>