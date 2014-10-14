<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SellerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seller-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'seller_id') ?>

    <?= $form->field($model, 'sysuser_id') ?>

    <?= $form->field($model, 'pos_id') ?>

    <?= $form->field($model, 'seller_salary') ?>

    <?= $form->field($model, 'seller_commission_fee') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
