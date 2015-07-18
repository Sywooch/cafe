<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Discount;

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
            'discount_type',
            'discount_description:ntext',
            'discount_auto:boolean',
            //'discount_rule:ntext',
        ],
    ]) ?>
    
    <label for="discount_rule" class="control-label"><?=Yii::t('app','Discount Rule')?></label>
    
    <?php
    $discountSubType=Discount::subtypeFactory($model);
    if($discountSubType){
        echo '<textarea style="display:none;" id="discount_rule">'.htmlspecialchars($model->discount_rule).'</textarea>';
        $discountSubType->form($this);
        $this->registerJs("
        $(window).load(function (){
            $('#eachNth_period').attr('disabled','true');
            $('#eachNth_category').attr('disabled','true');
            $('#discount_title').attr('disabled','true');
            $('#datefrom').attr('disabled','true');
            $('#dateto').attr('disabled','true');
        });    
    ");        
    }
    ?>

</div>
