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
                'template' => '{view}&nbsp;{update}{products}{supply}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons'=>[
                   'products'=>function ($url, $model, $key) {
                               return '<b>'.Html::a(' 3 ', $url,['title'=>Yii::t('app', 'Pos-product-list')]).'</b>';
                               //return "<span>X</span>";
                             },
                   'supply'=>function ($url, $model, $key) {
                               return '<b>'.Html::a(' П ', $url,['title'=>Yii::t('app', 'Pos-product-supply')]).'</b>';
                               //return "<span>X</span>";
                             },
                                     
                ]
            ],
            ['attribute' => 'pos_id','filterOptions'=>['class'=>'numFilter'],],
            'pos_title',
            'pos_address',
            'pos_timetable',

        ],
    ]); ?>

</div>
