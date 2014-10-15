<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Seller */


$pos=$model->getPos()->one();
$sysuser=$model->getSysuser()->one();

$this->title = Yii::t('app', 'Update Seller: ') . ' ' . $sysuser->sysuser_fullname .' @ '.$pos->pos_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $sysuser->sysuser_fullname .' @ '.$pos->pos_title, 'url' => ['view', 'id' => $model->seller_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="seller-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
