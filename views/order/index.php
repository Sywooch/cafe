<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    /*
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Order',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    */
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view}&nbsp;{update}{products}{supply}&nbsp;&nbsp;&nbsp;{delete}',
                'template' => '{view}',
            ],

            ['attribute' => 'order_id','filterOptions'=>['class'=>'numFilter'],],

            //'pos_id',
            [
                'attribute' => 'pos.pos_title',
                'format' => 'text',
                'label' => Yii::t('app','pos_title'),
                'filter' => true,
            ],
            //'seller_id',
            //'sysuser_id',
            ['attribute' => 'order_datetime', 'format'=>['date', 'php:d.m.Y H:i:s']],
            // 'order_day_sequence_number',
            ['attribute' => 'order_payment_type','filterOptions'=>['class'=>'numFilter'],],
            [
                'label' => Yii::t('app','Order Total'),
                'filterOptions'=>['class'=>'numFilter'],
                'content'=>function ($model, $key, $index, $column){
                                return $model->order_total.' '.Yii::$app->params['currency'];
                           }
            ],
            'sysuser.sysuser_fullname',
            [
                'label' => Yii::t('app','Seller Commission'),
                'filterOptions'=>['class'=>'numFilter'],
                'content'=>function ($model, $key, $index, $column){
                                $seller=$model->getSeller()->one();
                                return ($model->order_total * 0.01 * $seller->seller_commission_fee).' '.Yii::$app->params['currency'];
                           }
            ],
            //[
            //    'label' => Yii::t('app','Order Discount'),
            //    'filterOptions'=>['class'=>'numFilter'],
            //    'content'=>function ($model, $key, $index, $column){
            //                    return $model->order_discount.' '.Yii::$app->params['currency'];
            //               }
            //],
            //'discount_title',
            // 'order_hash',

        ],
    ]); ?>

</div>
