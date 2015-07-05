<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SubsystemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subsystem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'subsystemId') ?>

    <?= $form->field($model, 'subsystemTitle') ?>

    <?= $form->field($model, 'subsystemUrl') ?>

    <?= $form->field($model, 'subsystemApiKey') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
