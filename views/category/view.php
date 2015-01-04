<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->category_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->category_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->category_id], [
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
            'category_id',
            'category_title',
            'category_skin',
            'category_ordering'
        ],
    ]) ?>
    
    <?php
    
    $category_icon_url=$model->getImageUrl(\Yii::$app->params['icon_width'].'x'.\Yii::$app->params['icon_height']);
    if(strlen($category_icon_url)>0){
        echo '<label class="control-label">' . Yii::t('app', 'category_icon_file') . '</label>';
        ?>
        <div class="file-preview">
            <div class="file-preview-thumbnails">
                <div class="file-preview-frame">
                    <img style="width:auto;height:<?=\Yii::$app->params['icon_height']?>px;" class="file-preview-image" src="<?=$category_icon_url?>">
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
    }
    ?>


</div>
