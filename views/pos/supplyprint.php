<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pos-product-supply-print {pos_title}',['pos_title'=>$model->pos_title]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pos-list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_title, 'url' => ['view', 'id' => $model->pos_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Pos-product-supply-print');
?>
<div class="pos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'columns' => [
            
            'product_title:text:'.\Yii::t('app','Product'),
            //'product_quantity:integer:'.\Yii::t('app','product_quantity'),
            //'product_unit',
            // 'supply_quantity',
            [
               'label'=>Yii::t('app', 'Supplied quantity'),
               'content'=>function ($model, $key, $index, $column){
                                return $model['supply_quantity'].' '.$model['product_unit'];
                          }
            ], 
        ],
    ]); ?>
</div>
