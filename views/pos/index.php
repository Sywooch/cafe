<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pos-list');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Pos'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;{update}{products}{supply}{packaging}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons'=>[
                   'products'=>function ($url, $model, $key) {
                               return '<b>'.Html::a(' <span class="glyphicon glyphicon-book"></span> ', ['pos/products','pos_id'=>$model->pos_id],['title'=>Yii::t('app', 'Pos-product-list')]).'</b>';
                             },
                   'supply'=>function ($url, $model, $key) {
                               return '<b>'.Html::a(' <span class="glyphicon glyphicon-import"></span> ', ['pos/supply','id'=>$model->pos_id],['title'=>Yii::t('app', 'Pos-product-supply')]).'</b>';
                             },
                   'packaging'=>function ($url, $model, $key) {
                               return '<b>'.Html::a('<span class="glyphicon rur">c</span>', ['pos/packaging','id'=>$model->pos_id],['title'=>Yii::t('app', 'Pos-packaging-prices')]).'</b>';
                             },
                ]
            ],
            ['attribute' => 'pos_id','filterOptions'=>['class'=>'numFilter'],],
            'pos_title',
            'pos_address',
            'pos_timetable',

        ],
    ]); ?>
<style type="text/css">
    img.ruble-img { height: 1.4ex; margin-bottom: 2px;}
</style>
</div>
