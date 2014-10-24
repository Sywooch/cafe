<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pos-product-supply {pos_title}',['pos_title'=>$model->pos_title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pos-list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_title, 'url' => ['view', 'id' => $model->pos_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a(Yii::t('app', 'Pos-product-supply-print',['pos_title'=>$model->pos_title]), ['supplyprint','id'=>$model->pos_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => [
            [
               'label'=>Yii::t('app', 'Supply-needed'),
               'content'=>function ($model, $key, $index, $column){
                              return ($model['pos_product_quantity']<=$model['pos_product_min_quantity']?"<span class=\"warning-marker\">!</span>":"");
                          }
            ],
            'product_title:text:'.\Yii::t('app','Product'),
            //'product_quantity:integer:'.\Yii::t('app','product_quantity'),
            //'product_unit',
            [
               'label'=>\Yii::t('app','Product quantity'),
                'content'=>function ($model, $key, $index, $column){
                                return $model['product_quantity'].' '.$model['product_unit'];
                           }
            ], 
            [
               'label'=>Yii::t('app', 'Product quantity available'),
               'content'=>function ($model, $key, $index, $column){
                              return ($model['product_quantity']-$model['other_pos_supply']).' '.$model['product_unit'];
                          }
            ],
            //'pos_product_quantity:text:'.Yii::t('app', 'Supply.pos_product_quantity'),
            [
               'label'=>Yii::t('app', 'Supply.pos_product_quantity'),
               'content'=>function ($model, $key, $index, $column){
                              return $model['pos_product_quantity'].' '.$model['product_unit'];
                          }
            ],
            // 'pos_product_min_quantity:text:'.Yii::t('app', 'Supply.pos_product_min_quantity'),
            [
               'label'=>Yii::t('app', 'Supply.pos_product_min_quantity'),
               'content'=>function ($model, $key, $index, $column){
                              return $model['pos_product_min_quantity'].' '.$model['product_unit'];
                          }
            ],

            // 'supply_quantity',
            [
               'label'=>Yii::t('app', 'Supplied quantity'),
               'content'=>function ($model, $key, $index, $column){
                                return Html::textInput('supply_quantity', $model['supply_quantity'], ['class'=>'supply_quantity','data-product-id'=>$model['product_id'],'data-pos-id'=>$model['pos_id'],'size'=>3]).' '.$model['product_unit'];
                          }
            ], 
            //'other_pos_supply'
            // ['class' => 'yii\grid\SerialColumn'],
            //['class' => 'yii\grid\ActionColumn'],
            //            [
            //               'label'=>'',
            //               'content'=>function ($model, $key, $index, $column){
            //                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['/posproduct/delete']), ['class'=>'pos_product_delete','data-product-id'=>$model->product_id,'data-pos-id'=>$model->pos_id]);
            //                          }
            //            ], 
            //            //['attribute' => 'pos_id','filterOptions'=>['style'=>'width:100px;'],],
            //            //'pos_title',
            //            'product.product_title',
            //            //'pos_product_quantity:text:'.Yii::t('app','pos_product_quantity'),
            //            [
            //               'label'=>Yii::t('app', 'pos_product_quantity'),
            //                'content'=>function ($model, $key, $index, $column){
            //                                return Html::textInput('pos_product_quantity', $model->pos_product_quantity, ['class'=>'pos_product_quantity','data-product-id'=>$model->product_id,'data-pos-id'=>$model->pos_id,'size'=>3]);
            //                           }
            //            ], 
            //            // 'pos_product_min_quantity:text:'.Yii::t('app','pos_product_min_quantity'),
            //            [
            //               'label'=>Yii::t('app', 'pos_product_min_quantity'),
            //                'content'=>function ($model, $key, $index, $column){
            //                                return Html::textInput('pos_product_min_quantity', $model->pos_product_min_quantity, ['class'=>'pos_product_min_quantity','data-product-id'=>$model->product_id,'data-pos-id'=>$model->pos_id,'size'=>3]);
            //                           }
            //            ], 
                        
                        
        ],
    ]); ?>
            
    <?php
    /*
     * 'pos_id', 'p.product_id', 'p.product_title', 'p.product_quantity', 'p.product_unit', 'p.product_unit_price',
     * 'pos_product_quantity', 'pos_product_min_quantity', 'supply_quantity','other_pos_supply'  
     */
    $this->registerJs("
        function update_supply_quantity(product_id, pos_id, supply_quantity){
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/supply/update']) . "',
                data:{
                  product_id:product_id,
                  pos_id:pos_id,
                  supply_quantity:supply_quantity
                },
                success: function (response) {
                }
            });

        }
        function activateForm(){
            $('.supply_quantity').change(function(event){
                var ele=$(event.target);
                var product_id=ele.attr('data-product-id');
                var pos_id=ele.attr('data-pos-id');
                var supply_quantity=ele.val();
                update_supply_quantity(product_id, pos_id, supply_quantity);
            });
        }
        $(window).load(activateForm);    
    ");        

    ?>

</div>
