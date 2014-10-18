<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
        var createRow=function(row){
           var tr=$('<tr id=\"product'+row.product_id+'\"></tr>');
           var td0=$('<td><a href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></td>');
           tr.append(td0);
           
           var td2=$('<td data-product_id=\"'+row.product_id+'\"></td>');
           td2.html(row.product_title);
           tr.append(td2);
           
           var td3=$('<td></td>');
           td3.html($('<input type=\"text\" class=\"packaging_product_quantity\" data-unit-price=\"'+row.product_unit_price+'\" data-product_id=\"'+row.product_id+'\" value=\"'+row.packaging_product_quantity+'\" size=\"3\">&nbsp;<span>'+row.product_unit+'</span>'));
           tr.append(td3);

           var td5=$('<td class=\"packaging_product_price\"></td>');
           td5.html(row.packaging_product_price);
           tr.append(td5);

           return tr;
        }
        var onQuantityChanged=function(event){
            
            var textFieldQuantity=$(event.target);
            var unitPrice=textFieldQuantity.attr('data-unit-price');
            var productId=textFieldQuantity.attr('data-product_id');
            var priceElement=$('#product'+productId).find('.packaging_product_price');
            
            var newValue=textFieldQuantity.val().replace(/,/,'.');
            var newPrice=newValue*unitPrice;
            //console.log(newPrice);
            priceElement.html(newPrice);
            // send ajax request to update
            
        }        

        var createTable = function () {
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '".Url::toRoute(['/packaging/productlist','id'=>$model->packaging_id])."',
                success: function (response) {
                    // console.log(response);
                    var tableBody=$('#productList')
                    for(var i=0, cnt=response.length; i<cnt; i++){
                        tableBody.append(createRow(response[i]));
                    }
                    
                    // set actions
                    $('.packaging_product_quantity').change(onQuantityChanged).keyup(onQuantityChanged);
                }
            });
        }
        $(window).load(createTable);
       ");
    ?>
    <h3><?=Yii::t('app','Products in packaging')?></h3>
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th><?=Yii::t('app','Product')?></th>
                <th><?=Yii::t('app','Quantity')?></th>
                <th><?=Yii::t('app','Price')?></th>
            </tr>
        </thead>
        <tbody id="productList"></tbody>
    </table>

</div>
