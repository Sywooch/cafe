<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Pos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pos_title')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'pos_address')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'pos_timetable')->textInput(['maxlength' => 1024]) ?>

    <?= $form->field($model, 'pos_printer_url')->textInput(['maxlength' => 1024]) ?>
    
    <?= $form->field($model, 'pos_printer_template')->textarea(['id'=>'pos_printer_template']) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

    //var_dump(Yii::$app);
    $this->registerJsFile(dirname(Yii::$app->homeUrl)."/js/jquery.autosize.min.js",['depends'=>'yii\web\YiiAsset']);          
    $this->registerJs("
        \$(window).load(function(){
          \$('#pos_printer_template').autosize();
        });    
    ");          
?>
