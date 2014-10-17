<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'product_title')->textInput(['maxlength' => 1024]) ?>

    <?php /* = $form->field($model, 'product_icon')->textInput(['maxlength' => 1024]) */ ?>

    <?php
    if(strlen($model->product_icon)>0){
        ?>
        <div class="file-preview">
            <div class="file-preview-thumbnails">
                <div class="file-preview-frame">
                    <img style="width:auto;height:160px;" class="file-preview-image" src="<?=(Yii::$app->params['file_root_url'].'/'.$model->product_icon) ?>">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
    }
    echo '<label class="control-label">' . Yii::t('app', 'product_icon') . '</label>';
    echo FileInput::widget([
        'name' => 'product_icon_file',
        'options' => ['accept' => 'image/*'],
    ]);
    ?>


<?= $form->field($model, 'product_quantity')->textInput() ?>

    <?= $form->field($model, 'product_unit')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'product_min_quantity')->textInput() ?>

    <?= $form->field($model, 'product_unit_price')->textInput() ?>

    <div class="form-group">
<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
