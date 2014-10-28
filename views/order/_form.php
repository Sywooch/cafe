<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pos_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'seller_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'sysuser_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'order_datetime')->textInput() ?>

    <?= $form->field($model, 'order_day_sequence_number')->textInput() ?>

    <?= $form->field($model, 'order_total')->textInput() ?>

    <?= $form->field($model, 'order_discount')->textInput() ?>

    <?= $form->field($model, 'order_payment_type')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'order_hash')->textInput(['maxlength' => 64]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
