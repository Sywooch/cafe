<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Packaging */

$this->title = $model->packaging_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Packagings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packaging-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->packaging_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->packaging_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    if(strlen($model->packaging_icon)>0){
        $imageThumb = EasyThumbnailImage::thumbnailImg(
                (Yii::$app->params['file_root_dir'] . '/' . $model->packaging_icon), Yii::$app->params['icon_width'], Yii::$app->params['icon_height'], 
                EasyThumbnailImage::THUMBNAIL_OUTBOUND, ['alt' => $model->packaging_title]
        );
    }else{
        $imageThumb = '';
    }

    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'packaging_id',
            ['label' => Yii::t('app', 'packaging_icon'), 'format' => 'html', 'value' => $imageThumb],
            'packaging_title',
            'packaging_price',
            ['label' => Yii::t('app', 'Packaging is visible'), 'format' => 'html', 'value' => ($model->packaging_is_visible?Yii::t('app','yes'):Yii::t('app','no'))],
            'packaging_ordering',
        ],
    ]) ?>

</div>




    <?php
        $this->registerJs("
        var currency='".\Yii::$app->params['currency']."';
        var recalculateTotal=function(){
            // alert('recalc');
            //packaging_product_price
            var summa=0;
            $('.packaging_product_price').each(function(i, el){
              var val=parseFloat($(el).text());
              summa+=val;
            });
            //summa=0.01*Math.round(100*summa);
            $('#totalPrice').html(summa.toFixed(2)+' '+currency);
        }
        var createRow=function(row){
           var tr=$('<tr id=\"product'+row.product_id+'\"></tr>');
           var td0=$('<td><a href=\"#\"><span class=\"glyphicon glyphicon-trash\"></span></a></td>');
           tr.append(td0);
           
           var td2=$('<td data-product_id=\"'+row.product_id+'\"></td>');
           td2.html(row.product_title+', '+row.product_unit_price+' '+currency+'/'+row.product_unit);
           tr.append(td2);
           
           var td3=$('<td></td>');
           td3.html($('<span>'+row.packaging_product_quantity+'&nbsp;'+row.product_unit+'</span>'));
           tr.append(td3);

           var td5=$('<td class=\"packaging_product_price\"></td>');
           td5.html(row.packaging_product_price+' '+currency);
           tr.append(td5);

           return tr;
        }
        var createTable = function () {
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '".Url::toRoute(['/packaging/productlist','id'=>$model->packaging_id])."',
                success: function (response) {
                    // console.log(response);
                    var tableTotal=$('#productTotal')
                    for(var i=0, cnt=response.length; i<cnt; i++){
                        tableTotal.before(createRow(response[i]));
                    }
                    recalculateTotal();
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
        <tbody id="productList">
            <tr id="productTotal">
                <th></th>
                <th></th>
                <th></th>
                <th id="totalPrice"></th>
            </tr> 
        </tbody>
    </table>