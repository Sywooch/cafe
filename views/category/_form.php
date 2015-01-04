<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'category_title')->textInput(['maxlength' => 64]) ?>

    
    <?php
    echo '<label class="control-label">' . Yii::t('app', 'category_icon_file') . '</label>';
    
    $category_icon_url=$model->getImageUrl(\Yii::$app->params['icon_width'].'x'.\Yii::$app->params['icon_height']);
    if(strlen($category_icon_url)>0){
        ?>
        <div class="file-preview">
            <div class="file-preview-thumbnails">
                <div class="file-preview-frame">
                    <img style="width:auto;height:<?=\Yii::$app->params['icon_height']?>px;" class="file-preview-image" src="<?=$category_icon_url?>">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?= Html::a(Yii::t('app', 'category_icon_delete'), ['deleteimage','id'=>$model->category_id]) ?>
        <?php
    }
    echo FileInput::widget([
        'name' => 'category_icon_file',
        'options' => ['accept' => 'image/*'],
    ]);
    ?>

    
    <?php
      $tmp=explode(',',Yii::$app->params['categoryskins']);
      $category_skin_options=array_combine($tmp,$tmp);
    ?>
    <?= $form->field($model, 'category_skin')->dropDownList($category_skin_options,[]) ?>

    <?= $form->field($model, 'category_ordering')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
