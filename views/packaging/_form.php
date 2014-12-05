<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use app\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Packaging */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="packaging-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'packaging_title')->textInput(['maxlength' => 32]) ?>

    <?php
    $labels=$model->attributeLabels();
    echo $form->field($model, 'packaging_price')->textInput()->label($labels['packaging_price']);
    ?>
    
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'category_id', 'category_title'),[]) ?>
    
    <?=$form->field($model, 'packaging_ordering')->textInput()?>

    <?= $form->field($model, 'packaging_is_visible')->checkbox() ?>
    
    <?php
    if(strlen($model->packaging_icon)>0){
        ?>
        <div class="file-preview">
            <div class="file-preview-thumbnails">
                <div class="file-preview-frame">
                    <img style="width:auto;height:160px;" class="file-preview-image" src="<?=(Yii::$app->params['file_root_url'].'/'.$model->packaging_icon) ?>">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
    }

    echo '<label class="control-label">' . Yii::t('app','Packaging Icon') . ' '.\Yii::$app->params['icon_width'].'x'.\Yii::$app->params['icon_height'].'px</label>';
    echo FileInput::widget([
        'name' => 'packaging_icon_file',
        'options' => ['accept' => 'image/*'],
    ]);
    ?><br/>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
