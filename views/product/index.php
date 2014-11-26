<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Product', [
    'modelClass' => 'Product',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                //'buttons'=>[
                //   'update'=>function ($url, $model, $key) {
                //               //return $model->status == 'editable' ? Html::a('Update', $url) : '';
                //               return Html::a('Update', '#');
                //             }
                //]
            ],
            ['attribute' => 'product_id','filterOptions'=>['style'=>'width:100px;'],],
            'product_title',
            //'product_icon',
            [
                'label' => Yii::t('app','product_unit_price'),
                'filterOptions'=>['style'=>'width:100px;'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->product_unit_price.' '.Yii::$app->params['currency'];
                          }
            ],
            [
                'label' => Yii::t('app','product_min_quantity'),
                'filterOptions'=>['style'=>'width:100px;'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->product_min_quantity.' '.$model->product_unit;
                          }
            ],
            //[
            //    'label' => Yii::t('app','product_quantity'),
            //    'filterOptions'=>['style'=>'width:100px;'],
            //    'content'=>function ($model, $key, $index, $column){
            //                    return $model->product_quantity.' '.$model->product_unit;
            //              }
            //],
            [
               'label'=>\Yii::t('app','product_quantity'),
               'content'=>function ($model, $key, $index, $column){
                                return '<nobr>'.Html::textInput(
                                           'product_quantity',
                                            $model->product_quantity,
                                           ['class'=>'product_quantity form-control',
                                            'data-product_id'=>$model->product_id,
                                            'data-product_min_quantity'=>$model->product_min_quantity,
                                            'size'=>3]).'&nbsp;'.$model->product_unit.'</nobr>';
                           }
            ], 
            [
               'label'=>Yii::t('app', 'Supply-needed'),
               'content'=>function ($model, $key, $index, $column){
                                return '<div id="warning'.$model->product_id.'" style="'.($model->product_quantity<=$model->product_min_quantity?'display:block;':'display:none;').'"><span class="warning-marker">!</span></div>';
                           }
            ],

        ],
    ]); ?>
<style>    
    .product_quantity{
        width:auto;
        display:inline-block;
    }
</style>

</div>
<?php
    /*
     * 'pos_id', 'p.product_id', 'p.product_title', 'p.product_quantity', 'p.product_unit', 'p.product_unit_price',
     * 'pos_product_quantity', 'pos_product_min_quantity', 'supply_quantity','other_pos_supply'  
     */
    $this->registerJs("
        function update_product_quantity(product_id, product_min_quantity, product_quantity){
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/product/updateproductquantity']) . "',
                data:{
                    product_id:product_id,
                    product_quantity:product_quantity
                },
                success: function (response) {
                    if(response.status=='success'){
                        if(parseInt(product_quantity)>parseInt(product_min_quantity)){
                          $('#warning'+product_id).hide();
                        }else{
                          // console.log('show #warning'+product_id);
                          $('#warning'+product_id).show();
                        }
                    }
                }
            });

        }
        function activateForm(){
            $('.product_quantity').change(function(event){
                var ele=$(this);
                var product_id=ele.attr('data-product_id');
                var product_quantity=ele.val();
                var product_min_quantity=ele.attr('data-product_min_quantity');
                update_product_quantity(product_id, product_min_quantity, product_quantity);
            });
        }
        $(window).load(activateForm);    
    ");        

    ?>
