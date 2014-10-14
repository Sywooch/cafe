<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sellers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Seller'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],
            ['attribute' => 'seller_id', 'filterOptions' => ['style' => 'width:100px;'],],
            //'sysuser_id',
            [
                'attribute' => 'sysuser.sysuser_fullname',
                'format' => 'text',
                'label' => Yii::t('app','sysuser_fullname'),
                'filter' => true,
            ],
            //'pos_id',
            [
                'attribute' => 'pos.pos_title',
                'format' => 'text',
                'label' => Yii::t('app','pos_title'),
                'filter' => true,
            ],
            ['attribute' => 'seller_salary', 'filterOptions' => ['style' => 'width:100px;'],],
            ['attribute' => 'seller_commission_fee', 'filterOptions' => ['style' => 'width:100px;'],],
        ],
    ]);
    ?>

</div>
