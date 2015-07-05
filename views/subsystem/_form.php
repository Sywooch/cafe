<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Subsystem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subsystem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subsystemTitle')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'subsystemUrl')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'subsystemApiKey')->textInput(['maxlength' => 1024]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
