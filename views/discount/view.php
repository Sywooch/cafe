<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Discount */

$this->title = $model->discount_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Discounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discount-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->discount_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->discount_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'discount_id',
            'discount_title',
            'discount_description:ntext',
            //'discount_rule:ntext',
        ],
    ]) ?>
    
    <label for="discount_rule" class="control-label"><?=Yii::t('app','Discount Rule')?></label>
    
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
            <select id="condition_attribute" class="form-control" disabled="true">
                <option value=""><?=Yii::t('app','no_condition')?></option>
                <option value="order_total"><?=Yii::t('app','order_total')?></option>
                <option value="packaging_id"><?=Yii::t('app','packaging_id')?></option>
                <option value="packaging_title"><?=Yii::t('app','packaging_title')?></option>
                <option value="packaging_price"><?=Yii::t('app','packaging_price')?></option>
            </select>
            <select id="condition_operator" class="form-control" disabled="true">
                <option value=""></option>
                <option value=">">&gt;</option>
                <option value=">=">&ge;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&le;</option>
                <option value="~"><?=Yii::t('app','contains')?></option>
                <option value="e"><?=Yii::t('app','exists')?></option>
            </select>
            <input type="text" id="condition_value" class="form-control" disabled="true">
            </nobr>
        </span><br>
        <span class="blokformy">
            <?=Yii::t('app','search_attribute')?>:<br/>
            <select id="search_attribute" class="form-control" disabled="true">
                <option value="order_total"><?=Yii::t('app','order_total')?></option>
                <option value="packaging_id"><?=Yii::t('app','packaging_id')?></option>
                <option value="packaging_title"><?=Yii::t('app','packaging_title')?></option>
                <option value="packaging_price"><?=Yii::t('app','packaging_price')?></option>
            </select>
        </span>
        <span class="blokformy">
            <?=Yii::t('app','search_attribute_condition')?>:<br/>
            <select id="search_operator" class="form-control" disabled="true">
                <option value=""></option>
                <option value=">">&gt;</option>
                <option value=">=">&ge;</option>
                <option value="=">=</option>
                <option value="<">&lt;</option>
                <option value="<=">&le;</option>
                <option value="~"><?=Yii::t('app','contains')?></option>
                <option value="e"><?=Yii::t('app','exists')?></option>
            </select>
            <input type="text" id="search_value" class="form-control" disabled="true">
        </span><br/>
        <span class="blokformy">
            <?=Yii::t('app','discount_value')?>:<br/>
            <input type="text" id="discount_value" class="form-control" disabled="true">
            <select id="discount_unit" class="form-control" disabled="true">
                <option value="%">%</option>
                <option value="abs"><?=Yii::$app->params['currency']?></option>
            </select>
        </span>
    </div>
<?php
    $this->registerJs("
        var json=".$model->discount_rule.";
        function activateForm(){
            if(!json){
              json={};
            }
            if(!json.condition_attribute) json.condition_attribute='';
            if(!json.condition_operator)  json.condition_operator='';
            if(!json.condition_value)     json.condition_value='';
            if(!json.search_attribute)    json.search_attribute='';
            if(!json.search_operator)     json.search_operator='';
            if(!json.search_value)        json.search_value='';
            if(!json.discount_value)      json.discount_value='';
            if(!json.discount_unit)       json.discount_unit='';
            try{
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
        }
        $(window).load(activateForm);    
    ");        
?>

</div>
