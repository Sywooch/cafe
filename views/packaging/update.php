<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $model app\models\Packaging */

$this->title = Yii::t('app', 'Update Packaging: ') . ' ' . $model->packaging_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packagings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->packaging_title, 'url' => ['view', 'id' => $model->packaging_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="packaging-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
    <?php
    $this->registerJs("
        var currency='".\Yii::$app->params['currency']."';
        var recalculateTotal=function(){
            //alert('recalc');
            //packaging_product_price
            var summa=0;
            $('.packaging_product_price').each(function(i, el){
              var val=parseFloat($(el).text());
              summa+=val;
            });
            $('#totalPrice').html(summa.toFixed(2)+' '+currency);
        }

        var createRow=function(row){
           var tr=$('<tr id=\"product'+row.product_id+'\"></tr>');
           var td0=$('<td><a href=\"javascript:void(\'delete\')\" class=\"del\" data-id=\"'+row.product_id+'\"><span class=\"glyphicon glyphicon-trash\"></span></a></td>');
           tr.append(td0);
           
           var td2=$('<td data-product_id=\"'+row.product_id+'\"></td>');
           td2.html(row.product_title+', '+row.product_unit_price+' '+currency+'/'+row.product_unit);
           tr.append(td2);
           
           var td3=$('<td></td>');
           td3.html($('<input type=\"text\" class=\"packaging_product_quantity\" data-unit-price=\"'+row.product_unit_price+'\" data-product_id=\"'+row.product_id+'\" value=\"'+row.packaging_product_quantity+'\" size=\"5\">&nbsp;<span>'+row.product_unit+'</span>'));
           tr.append(td3);

           var td5=$('<td class=\"packaging_product_price\"></td>');
           td5.html(row.packaging_product_price+' '+currency);
           tr.append(td5);

           return tr;
        };
        var onQuantityChanged=function(event){
            
            var textFieldQuantity=$(event.target);
            var unitPrice=textFieldQuantity.attr('data-unit-price');
            var productId=textFieldQuantity.attr('data-product_id');
            var priceElement=$('#product'+productId).find('.packaging_product_price');
            
            var newValue=textFieldQuantity.val().replace(/,/,'.');
            var newPrice=newValue*unitPrice;
            newPrice=0.01*Math.round(100*newPrice);
            //console.log(newPrice);
            priceElement.html(newPrice+' '+currency);
            
            recalculateTotal();

            // send ajax request to update
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'html',
                url: '" . Url::toRoute(['/packaging-product/update']) . "',
                data:{
                   packaging_id:'".$model->packaging_id."',
                   product_id:productId,
                   packaging_product_quantity:newValue
                },
                success: function (response) {
                    recalculateTotal();
                }
            });


        };

        var delProduct=function(event){
            var link=$(this);
            var product_id=link.attr('data-id');
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'html',
                url: '" . Url::toRoute(['/packaging-product/delete']) . "',
                data:{
                   packaging_id:'".$model->packaging_id."',
                   product_id:product_id
                },
                success: function (response) {
                    $('#product'+product_id).remove();
                    recalculateTotal();
                }
            });
        }

        var createTable = function () {
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/packaging/productlist', 'id' => $model->packaging_id]) . "',
                success: function (response) {
                    // console.log(response);
                    var tableTotal=$('#productTotal')
                    for(var i=0, cnt=response.length; i<cnt; i++){
                        tableTotal.before(createRow(response[i]));
                    }
                    recalculateTotal();
                    
                    // set actions
                    $('.packaging_product_quantity').change(onQuantityChanged);//.keyup(onQuantityChanged);
                    $('.del').click(delProduct);
                }
            });
        }
        $(window).load(createTable);
       ");
    ?>
    <h3><?= Yii::t('app', 'Products in packaging') ?></h3>
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th><?= Yii::t('app', 'Product') ?></th>
                <th><?= Yii::t('app', 'Quantity') ?></th>
                <th><?= Yii::t('app', 'Price') ?></th>
            </tr>
        </thead>
        <tbody id="productList">
            <tr id="productTotal">
                <th></th>
                <th></th>
                <th></th>
                <th id="totalPrice"></th>
            </tr>            
        </tbody>
    </table>


    <div>
        <h3><?=Yii::t('app', 'Add product to packaging')?></h3>
        <?php
        // $url = Url::toRoute(['/packaging/productlist', 'id' => $model->packaging_id]);
        
        $productModel=new Product();
        $productList=$productModel->find()->all();
        $data=Array();
        foreach($productList as $product){
            //$product=$it->getProduct()->one();
            //var_dump($product);exit();
            $data[$product->product_id]=$product->product_title.', '.$product->product_unit;
        }
        // var_dump($data);
        echo '<label class="control-label">'.Yii::t('app', 'Product').'</label>';
        echo Select2::widget([
            'name' => 'newProduct',
            'data' => $data, 
            'options' => [
                'placeholder' => Yii::t('app', 'Select product ...'),
                'id'=>'newProductId',
                'size'=>'SMALL'
            ]
        ]);
        echo "<div id='newProductIdFeedback'></div>";
        echo '
            <br><div class="form-group field-packaging-packaging_title">
            <label class="control-label">'.Yii::t('app', 'Quantity').'</label>';
        echo Html::textInput(
            'newProductQuantity',
            '',
            [
                'id'=>'newProductQuantity',
                'class'=>"form-control"
            ]
        );
        echo "<div id='newProductQuantityFeedback'></div>";
        echo "</div>";
        
        echo Html::button(
            Yii::t('app', 'Add product to packaging'),
            [
                'id'=>'newProductButton',
                'class'=>"btn btn-primary"
            ]
        );
        
        $this->registerJs("
            $('#newProductButton').click(function(event){
            
                var newProductId=$('#newProductId');
                var newProductIdValue=newProductId.val();
                var newProductIdFeedback=$('#newProductIdFeedback');
                newProductIdFeedback.empty().removeClass('has-error');
                if(isNaN(parseInt(newProductIdValue))){
                    newProductIdFeedback.addClass('has-error').html('<div class=\"help-block\">".Yii::t('app', 'Select a product')."');
                    return;
                }



                var newProductQuantity=$('#newProductQuantity');
                var newProductQuantityValue=newProductQuantity.val();
                var newProductQuantityFeedback=$('#newProductQuantityFeedback');
                newProductQuantityFeedback.empty().removeClass('has-error');
                if(!$.isNumeric( newProductQuantityValue )){
                    newProductQuantityFeedback.addClass('has-error').html('<div class=\"help-block\">".Yii::t('app', 'Product quantity must be a number')."');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    cache: false,
                    dataType:'json',
                    url: '" . Url::toRoute(['/packaging-product/create']) . "',
                    data:{
                       packaging_id:'".$model->packaging_id."',
                       product_id:newProductId.val(),
                       packaging_product_quantity:newProductQuantityValue
                    },
                    success: function (response) {
                        var tableTotal=$('#productTotal')
                        tableTotal.before(createRow(response));
                        recalculateTotal();
                    }
                });
            });
        ");
        ?>
    </div>
    <br><br><br><br>
</div>

