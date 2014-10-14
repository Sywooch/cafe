<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Seller */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seller-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sysuser_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'pos_id')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'seller_salary')->textInput() ?>

    <?= $form->field($model, 'seller_commission_fee')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
