<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Discount;

/* @var $this yii\web\View */
/* @var $model app\models\Discount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discount-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'discount_title')->textInput(['maxlength' => 32,'id'=>'discount_title']) ?>

    <?= $form->field($model, 'discount_type')->dropDownList($model->getDiscountTypes()) ?>



    <?php
    $discountSubType=Discount::subtypeFactory($model);
    if($discountSubType){
        echo $form->field($model, 'discount_rule')->textarea(['rows' => 6,'id'=>'discount_rule']);
        $discountSubType->form($this);
    }
    ?>

    <?= $form->field($model, 'discount_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'discount_auto')->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
