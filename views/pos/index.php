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
            [
                'label'=>Yii::t('app', 'pos_timetable'),
                'content'=>function ($model, $key, $index, $column){
                                return preg_replace("/:\\d{2}\$/",'',$model->pos_worktime_start)." - ".preg_replace("/:\\d{2}\$/",'',$model->pos_worktime_finish);
                           }
            ], 
            [
                'label'=>Yii::t('app', 'pos_sellers'),
                'content'=>function ($model, $key, $index, $column){
                                $lst=$model->getLastSellerActions();
                                // var_dump($lst);
                                $sellers='';
                                $date_format=Yii::t('app', 'date_format');
                                $lower=time()-\Yii::$app->params['workingtime_timeout'];//10800; // 3 hours;
                                foreach($lst as $ls){
                                    $tstm=strtotime($ls['log_datetime']);
                                    $dtti=date($date_format, $tstm);
                                    if($tstm > $lower){
                                        $sellers.="<div>{$dtti} {$ls['log_action']} <b>{$ls['sysuser_fullname']}, {$ls['sysuser_login']};</b></div>\n";
                                    }else{
                                        $sellers.="<div class='long-time-ago'>{$dtti} {$ls['log_action']} <b>{$ls['sysuser_fullname']}, {$ls['sysuser_login']};</b></div>\n";
                                    }
                                    
                                }
                                //return preg_replace("/:\\d{2}\$/",'',$model->pos_worktime_start)." - ".preg_replace("/:\\d{2}\$/",'',$model->pos_worktime_finish);
                                return $sellers;
                           }
            ], 

            //'pos_timetable',
            //'pos_worktime_start',
            //'pos_worktime_finish',

        ],
    ]); ?>
<style type="text/css">
    img.ruble-img { height: 1.4ex; margin-bottom: 2px;}
    .long-time-ago{color:silver;}
</style>
</div>
