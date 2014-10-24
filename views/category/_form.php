<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_title')->textInput(['maxlength' => 64]) ?>
    
    <?php
      $tmp=explode(',',Yii::$app->params['categoryskins']);
      $category_skin_options=array_combine($tmp,$tmp);
    ?>
    <?= $form->field($model, 'category_skin')->dropDownList($category_skin_options,[]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
