<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use himiklab\thumbnail\EasyThumbnailImage;

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
        ],
    ]) ?>

</div>
