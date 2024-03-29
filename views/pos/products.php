<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pos-product-list {pos_title}',['pos_title'=>$model->pos_title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pos-list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_title, 'url' => ['view', 'id' => $model->pos_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            //['class' => 'yii\grid\ActionColumn'],
            [
               'label'=>'',
               'content'=>function ($model, $key, $index, $column){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['/posproduct/delete']), ['class'=>'pos_product_delete','data-product-id'=>$model->product_id,'data-pos-id'=>$model->pos_id]);
                          }
            ], 
            //['attribute' => 'pos_id','filterOptions'=>['style'=>'width:100px;'],],
            //'pos_title',
            'product.product_title',
            //'pos_product_quantity:text:'.Yii::t('app','pos_product_quantity'),
            [
               'label'=>Yii::t('app', 'pos_product_quantity'),
                'content'=>function ($model, $key, $index, $column){
                                $product=$model->getProduct()->one();
                                return Html::textInput('pos_product_quantity', $model->pos_product_quantity, ['class'=>'pos_product_quantity','data-product-id'=>$model->product_id,'data-pos-id'=>$model->pos_id,'size'=>3]).' '.$product->product_unit;
                           }
            ], 
            // 'pos_product_min_quantity:text:'.Yii::t('app','pos_product_min_quantity'),
            [
               'label'=>Yii::t('app', 'pos_product_min_quantity'),
                'content'=>function ($model, $key, $index, $column){
                                $product=$model->getProduct()->one();
                                return Html::textInput('pos_product_min_quantity', $model->pos_product_min_quantity, ['class'=>'pos_product_min_quantity','data-product-id'=>$model->product_id,'data-pos-id'=>$model->pos_id,'size'=>3]).' '.$product->product_unit;
                           }
            ], 
            [
               'label'=>Yii::t('app', 'Supply-needed'),
                'content'=>function ($model, $key, $index, $column){
                                return ($model->pos_product_quantity<=$model->pos_product_min_quantity?"<span class=\"warning-marker\">!</span>":"");
                           }
            ],
                        
        ],
    ]); 
            

            
            
    $this->registerJs("
        function update_min_quantity(product_id, pos_id, pos_product_min_quantity){
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/posproduct/update']) . "',
                data:{
                  product_id:product_id,
                  pos_id:pos_id,
                  pos_product_min_quantity:pos_product_min_quantity
                },
                success: function (response) {
                }
            });

        }
        function update_quantity(product_id, pos_id, pos_product_quantity){
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/posproduct/updatequantity']) . "',
                data:{
                  product_id:product_id,
                  pos_id:pos_id,
                  pos_product_quantity:pos_product_quantity
                },
                success: function (response) {
                }
            });

        }
        function activateForm(){
        
            $('.pos_product_quantity').change(function(event){
                var ele=$(event.target);
                var product_id=ele.attr('data-product-id');
                var pos_id=ele.attr('data-pos-id');
                var pos_product_quantity=ele.val();
                update_quantity(product_id, pos_id, pos_product_quantity);
            });

            $('.pos_product_min_quantity').change(function(event){
                var ele=$(event.target);
                var product_id=ele.attr('data-product-id');
                var pos_id=ele.attr('data-pos-id');
                var pos_product_min_quantity=ele.val();
                update_min_quantity(product_id, pos_id, pos_product_min_quantity);
            });
        }
        $(window).load(activateForm);    
    ");        
            
    ?>

</div>
