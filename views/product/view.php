<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use himiklab\thumbnail\EasyThumbnailImage;

EasyThumbnailImage::$cacheAlias = 'files/thumbnails';

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->product_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ])
        ?>
    </p>


    <?php
    if(strlen($model->product_icon)>0){
        $imageThumb = EasyThumbnailImage::thumbnailImg(
                (Yii::$app->params['file_root_dir'] . '/' . $model->product_icon), Yii::$app->params['icon_width'], Yii::$app->params['icon_height'], 
                EasyThumbnailImage::THUMBNAIL_OUTBOUND, ['alt' => $model->product_title]
        );
    }else{
        $imageThumb = '';
    }
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'product_id',
            'product_title',
            ['label' => Yii::t('app', 'product_icon'), 'format' => 'html', 'value' => $imageThumb],
            'product_quantity',
            'product_unit',
            'product_min_quantity',
            'product_unit_price',
        ],
    ])
    ?>

</div>

