<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SysuserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sysuser-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'sysuser_id') ?>

    <?= $form->field($model, 'sysuser_fullname') ?>

    <?= $form->field($model, 'sysuser_login') ?>

    <?= $form->field($model, 'sysuser_password') ?>

    <?= $form->field($model, 'sysuser_role_mask') ?>

    <?php // echo $form->field($model, 'sysuser_telephone') ?>

    <?php // echo $form->field($model, 'sysuser_token') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
