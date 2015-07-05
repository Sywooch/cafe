<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubsystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Subsystems');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subsystem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Subsystem'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;{update}&nbsp;{reports}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons'=>[
                   'reports'=>function ($url, $model, $key) {
                               return '<b>'.Html::a(' <span class="glyphicon glyphicon-book"></span> ', ['subsystem/reports','subsystemId'=>$model->subsystemId],['title'=>Yii::t('app', 'Subsystem_reports')]).'</b>';
                             }
                ]
            ],


            ['attribute' => 'subsystemId','filterOptions'=>['style'=>'width:100px;'],],
            'subsystemTitle',
            //'subsystemUrl:url',
            //'subsystemApiKey',
        ],
    ]); ?>

</div>
