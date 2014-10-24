<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
            //['attribute' => 'product_quantity','filterOptions'=>['style'=>'width:100px;'],],
            [
                'label' => Yii::t('app','product_quantity'),
                'filterOptions'=>['style'=>'width:100px;'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->product_quantity.' '.$model->product_unit;
                          }
            ],
            // ['attribute' => 'product_unit','filterOptions'=>['style'=>'width:100px;'],],
            //['attribute' => 'product_min_quantity','filterOptions'=>['style'=>'width:100px;'],],
            [
                'label' => Yii::t('app','product_min_quantity'),
                'filterOptions'=>['style'=>'width:100px;'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->product_min_quantity.' '.$model->product_unit;
                          }
            ],
            //['attribute' => 'product_unit_price','filterOptions'=>['style'=>'width:100px;'],],
            [
                'label' => Yii::t('app','product_unit_price'),
                'filterOptions'=>['style'=>'width:100px;'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->product_unit_price.' '.Yii::$app->params['currency'];
                          }
            ],
            
            [
               'label'=>Yii::t('app', 'Supply-needed'),
                'content'=>function ($model, $key, $index, $column){
                                return ($model->product_quantity<=$model->product_min_quantity?"<span class=\"warning-marker\">!</span>":"");
                           }
            ],
            
        ],
    ]); ?>

</div>
