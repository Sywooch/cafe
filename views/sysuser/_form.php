<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sysuser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sysuser-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sysuser_fullname')->textInput(['maxlength' => 512]) ?>

    <?php if($model->isNewRecord){
           echo $form->field($model, 'sysuser_login')->textInput(['maxlength' => 64]);
          }
    ?>

    <?php /*= $form->field($model, 'sysuser_password')->textInput(['maxlength' => 128]) */ ?>

    <?= $form->field($model, 'sysuser_role_mask')-> dropDownList(\app\models\Sysuser::getRoles()) ?>

    <?= $form->field($model, 'sysuser_telephone')->textInput(['maxlength' => 64]) ?>

    <?php /* $form->field($model, 'sysuser_token')->textInput(['maxlength' => 64]) */?>
    
    <?=($model->isNewRecord?"":'<h3>'.Yii::t('app','Type_password_to_update').'</h3>')?>
    
    <div class="form-group field-sysuser-sysuser_fullname">
    <label class="control-label" for="sysuser-sysuser_fullname"><?=Yii::t('app','sysuser_password1')?></label>
    <?php echo Html::passwordInput('Sysuser[sysuser_password1]', '', array('class'=>'form-control','size'=>60,'maxlength'=>128)); ?>
    <div class="help-block"></div>
    </div>
    <div class="form-group field-sysuser-sysuser_fullname">
    <label class="control-label" for="sysuser-sysuser_fullname"><?=Yii::t('app','sysuser_password2')?></label>
    <?php echo Html::passwordInput('Sysuser[sysuser_password2]', '', array('class'=>'form-control','size'=>60,'maxlength'=>128)); ?>
    <div class="help-block"></div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
