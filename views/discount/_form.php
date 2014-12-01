<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Discount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="discount-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'discount_title')->textInput(['maxlength' => 32,'id'=>'discount_title']) ?>

    <?= $form->field($model, 'discount_rule')->textarea(['rows' => 6,'id'=>'discount_rule']) ?>

    <style type="text/css">
        #discount_rule{
            display:none;
        }
        .blokformy{
            display:inline-block;
            vertical-align: top;
            width:auto;
            margin-bottom:10px;
        }
        #rule{
            margin-bottom:20px;
        }
        #condition_attribute, #condition_operator, #condition_value,
        #search_attribute, #search_operator, #search_value,
        #discount_value, #discount_unit{
            width:auto;
            display:inline-block;
        }
    </style>
    <div id="rule">
        <span class="blokformy">
            <?=Yii::t('app','condition_attribute')?>:<br/>
            <nobr>
            <select id="condition_attribute" class="form-control">
                <option value=""><?=Yii::t('app','no_condition')?></option>
                <option value="order_total"><?=Yii::t('app','order_total')?></option>
                <option value="packaging_id"><?=Yii::t('app','packaging_id')?></option>
                <option value="packaging_title"><?=Yii::t('app','packaging_title')?></option>
                <option value="packaging_price"><?=Yii::t('app','packaging_price')?></option>
            </select>
            <select id="condition_operator" class="form-control">
                <option value=""></option>
                <option value=">">&gt;</option>
                <option value=">=">&ge;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&le;</option>
                <option value="~"><?=Yii::t('app','contains')?></option>
                <option value="e"><?=Yii::t('app','exists')?></option>
            </select>
            <input type="text" id="condition_value" class="form-control">
            </nobr>
        </span><br>
        <span class="blokformy">
            <?=Yii::t('app','search_attribute')?>:<br/>
            <select id="search_attribute" class="form-control">
                <option value="order_total"><?=Yii::t('app','order_total')?></option>
                <option value="packaging_id"><?=Yii::t('app','packaging_id')?></option>
                <option value="packaging_title"><?=Yii::t('app','packaging_title')?></option>
                <option value="packaging_price"><?=Yii::t('app','packaging_price')?></option>
            </select>
        </span>
        <span class="blokformy">
            <?=Yii::t('app','search_attribute_condition')?>:<br/>
            <select id="search_operator" class="form-control">
                <option value=""></option>
                <option value=">">&gt;</option>
                <option value=">=">&ge;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&le;</option>
                <option value="~"><?=Yii::t('app','contains')?></option>
                <option value="e"><?=Yii::t('app','exists')?></option>
            </select>
            <input type="text" id="search_value" class="form-control">
        </span><br/>
        <span class="blokformy">
            <?=Yii::t('app','discount_value')?>:<br/>
            <input type="text" id="discount_value" class="form-control">
            <select id="discount_unit" class="form-control">
                <option value="%">%</option>
                <option value="abs"><?=Yii::$app->params['currency']?></option>
            </select>
        </span>
    </div>
<?php
    $this->registerJs("
        var json;
        
        function collectJSON(){
            //alert('collectJSON');
            json.discount_title=$('#discount_title').val();
            json.condition_attribute=$('#condition_attribute').val();
            json.condition_operator=$('#condition_operator').val();
            json.condition_value=$('#condition_value').val();
            json.search_attribute=$('#search_attribute').val();
            json.search_operator=$('#search_operator').val();
            json.search_value=$('#search_value').val();
            json.discount_value=$('#discount_value').val();
            json.discount_unit=$('#discount_unit').val();
            var json_str = JSON.stringify(json);
            \$('#discount_rule').val(json_str);
        }

        function activateForm(){
            try{
              json=jQuery.parseJSON( \$('#discount_rule').val() );
              $('#condition_attribute').val(json.condition_attribute);
              $('#condition_operator').val(json.condition_operator);
              $('#condition_value').val(json.condition_value);
              $('#search_attribute').val(json.search_attribute);
              $('#search_operator').val(json.search_operator);
              $('#search_value').val(json.search_value);
              $('#discount_value').val(json.discount_value);
              $('#discount_unit').val(json.discount_unit);
            }catch(err){
            }
            if(!json){
              json={};
            }
            if(!json.discount_title)      json.discount_title='';
            if(!json.condition_attribute) json.condition_attribute='';
            if(!json.condition_operator)  json.condition_operator='';
            if(!json.condition_value)     json.condition_value='';
            if(!json.search_attribute)    json.search_attribute='';
            if(!json.search_operator)     json.search_operator='';
            if(!json.search_value)        json.search_value='';
            if(!json.discount_value)      json.discount_value='';
            if(!json.discount_unit)       json.discount_unit='';

            $('#condition_attribute').change(collectJSON);
            $('#condition_operator').change(collectJSON);
            $('#condition_value').change(collectJSON);
            $('#search_attribute').change(collectJSON);
            $('#search_operator').change(collectJSON);
            $('#search_value').change(collectJSON);
            $('#discount_value').change(collectJSON);
            $('#discount_unit').change(collectJSON);
            $('#discount_title').change(collectJSON);
            collectJSON();
        }
        $(window).load(activateForm);    
    ");        
?>

    <?= $form->field($model, 'discount_description')->textarea(['rows' => 6]) ?>

    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
