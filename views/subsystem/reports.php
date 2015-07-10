<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubsystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->subsystemTitle.' - '.Yii::t('app', 'Subsystem_reports');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subsystem-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <ul>
        <ul>
            <li><?=Html::a( Yii::t('app', 'Orders'), ['/subsystem/orderreport','sort'=>'-order_id','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'Sellers'), ['/subsystem/sellerreport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'CustomerIncomeReport'), ['/subsystem/customerincomereport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'ProductReport'), ['/subsystem/productreport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'PackagingReport'), ['/subsystem/packagingreport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'PosIncomeReport'), ['/subsystem/posincomereport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'HourlyIncomeReport'), ['/subsystem/hourlyincomereport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'WeekdailyIncomeReport'), ['/subsystem/weekdailyincomereport','subsystemId'=>$model->subsystemId], [] )?></li>
            <li><?=Html::a( Yii::t('app', 'DailyIncomeReport'), ['/subsystem/dailyincomereport','subsystemId'=>$model->subsystemId], [] )?></li>
        </ul>
    </ul>

</div>
