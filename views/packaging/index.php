<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PackagingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Packagings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packaging-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app','Create Packaging'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],

            ['attribute' => 'packaging_id','filterOptions'=>['style'=>'width:100px;'],],
            // 'packaging_icon',
            'packaging_title',
            ['attribute' => 'packaging_price','filterOptions'=>['style'=>'width:100px;'],],
            //'packaging_is_additional',

        ],
    ]); ?>

</div>
