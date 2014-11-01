<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Discount */

$this->title = Yii::t('app', 'Update Discount: ') . ' ' . $model->discount_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Discounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->discount_title, 'url' => ['view', 'id' => $model->discount_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="discount-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
