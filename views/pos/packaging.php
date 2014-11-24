<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pos-packaging {pos_title}',['pos_title'=>$pos->pos_title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pos-list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $pos->pos_title, 'url' => ['view', 'id' => $pos->pos_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            //            [
            //                'class' => 'yii\grid\ActionColumn',
            //                'template' => '{view}&nbsp;{update}{products}{supply}&nbsp;&nbsp;&nbsp;{delete}',
            //                'buttons'=>[
            //                   'products'=>function ($url, $model, $key) {
            //                               return '<b>'.Html::a(' 3 ', ['pos/products','pos_id'=>$model->pos_id],['title'=>Yii::t('app', 'Pos-product-list')]).'</b>';
            //                             },
            //                   'supply'=>function ($url, $model, $key) {
            //                               return '<b>'.Html::a(' П ', ['pos/supply','id'=>$model->pos_id],['title'=>Yii::t('app', 'Pos-product-supply')]).'</b>';
            //                             },
            //                                     
            //                ]
            //            ],
            //['attribute' => 'pos_id','filterOptions'=>['class'=>'numFilter'],],
            //'pos_title',
            //'pos_address',
            //'pos_timetable',
            [
               'label'=>\Yii::t('app','packaging_id'),
               'content'=>function ($model, $key, $index, $column){
                                return $model['packaging_id'];
                           }
            ], 
            ['attribute' => 'packaging_title','label'=>\Yii::t('app','Packaging Title')],
            [
               'label'=>\Yii::t('app','Packaging Price'),
               'content'=>function ($model, $key, $index, $column){
                                return $model['packaging_price'].'&nbsp;'.\Yii::$app->params['currency'];
                           }
            ], 
            [
               'label'=>\Yii::t('app','Pos Packaging Price'),
               'content'=>function ($model, $key, $index, $column) use($pos){
                                return '<nobr>'.Html::textInput('pos_packaging_price', $model['pos_packaging_price'], ['class'=>'pos_packaging_price form-control','data-packaging-id'=>$model['packaging_id'],'data-pos-id'=>$pos->pos_id,'size'=>3]).'&nbsp;'.\Yii::$app->params['currency'].'</nobr>';
                           }
            ], 
        ],
    ]); ?>

<style>    
    .pos_packaging_price{
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
        function update_pos_price(packaging_id, pos_id, pos_packaging_price){
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/pospackaging/update']) . "',
                data:{
                  packaging_id:packaging_id,
                  pos_id:pos_id,
                  pos_packaging_price:pos_packaging_price
                },
                success: function (response) {
                }
            });

        }
        function activateForm(){
            $('.pos_packaging_price').change(function(event){
                var ele=$(event.target);
                var packaging_id=ele.attr('data-packaging-id');
                var pos_id=ele.attr('data-pos-id');
                var pos_packaging_price=ele.val();
                update_pos_price(packaging_id, pos_id, pos_packaging_price);
            });
        }
        $(window).load(activateForm);    
    ");        

    ?>
