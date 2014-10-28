<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'pos_id') ?>

    <?= $form->field($model, 'seller_id') ?>

    <?= $form->field($model, 'sysuser_id') ?>

    <?= $form->field($model, 'order_datetime') ?>

    <?php // echo $form->field($model, 'order_day_sequence_number') ?>

    <?php // echo $form->field($model, 'order_total') ?>

    <?php // echo $form->field($model, 'order_discount') ?>

    <?php // echo $form->field($model, 'order_payment_type') ?>

    <?php // echo $form->field($model, 'order_hash') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
