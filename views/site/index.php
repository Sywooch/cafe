<?php
/* @var $this yii\web\View */
use yii\grid\GridView;

?>
<div class="site-index">
<?php
if(Yii::$app && Yii::$app->user && ($sysuser = \Yii::$app->user->getIdentity()) ){
?>

    <div class="jumbotron" style="margin-bottom:0;">
        <h1>POS<span style="color:silver;">H</span></h1>
        <p class="lead">Учет продаж</p>
        <p><a class="btn btn-lg btn-success" href="index.php?r=sell%2Findex">Начать продажу</a></p>
    </div>
    
    <?php
    /*
    $roles = \Yii::$app->authManager->getRolesByUser($sysuser->sysuser_id);
    if (isset($roles['admin'])) {
    ?>
    
    
    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <h1>Сегодня</h1>
                <p>
                <?= GridView::widget([
                    'dataProvider' => $report,
                    //'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
                        //            [
                        //                'class' => 'yii\grid\ActionColumn',
                        //                //'template' => '{view}&nbsp;{update}{products}{supply}&nbsp;&nbsp;&nbsp;{delete}',
                        //                'template' => ($role['admin']?'<nobr>{view}&nbsp;&nbsp;&nbsp;{delete}</nobr>':'{view}'),
                        //            ],
                        //
                        //[
                        //    'attribute' => 'pos_id',
                        //    'label' => Yii::t('app','pos_id'),
                        //],
                        [
                            'attribute' => 'pos_title',
                            'label' => Yii::t('app','pos_title'),
                        ],
                        [
                            'attribute' => 'total',
                            'label' => Yii::t('app','totalIncome'),
                            'content'=>function ($model, $key, $index, $column){
                                            return round($model['total'],5).' '.Yii::$app->params['currency'];
                                       }
                        ],
                        [
                            'attribute' => 'n_orders',
                            'label' => Yii::t('app','n_orders'),
                            'content'=>function ($model, $key, $index, $column){
                                            return $model['n_orders'];
                                       }
                        ],



                    ],
                ]); ?>

            </div>
            <div class="col-lg-6">
                <?php
                   //<h2>Другие отчёты</h2>
                   //include('../report/index.php');
                   //echo dirname(__FILE__).'/../report/index.php';
                   include(dirname(__FILE__).'/../report/index.php');
                   $this->params['breadcrumbs']=[];
                ?>

                <!-- <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p> -->
            </div>
            <!-- 
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
            -->
        </div>

    </div>
    <?php
    }*/
    ?>
<?php
}else{
?>
    <div class="jumbotron">
        <h1>POS<span style="color:silver;">H</span></h1>
        <p class="lead">Учет продаж</p>
        <p><a class="btn btn-lg btn-success" href="index.php?r=site%2Flogin">Войти</a></p>
    </div>
<?php
}
?>
</div>
<?php

$this->title = 'POS-H - учёт продаж';

?>