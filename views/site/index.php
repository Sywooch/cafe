<?php
/* @var $this yii\web\View */
$this->title = 'POS-H - учёт продаж';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>POS<span style="color:silver;">H</span></h1>

        <p class="lead">Учет продаж</p>

        <?php 
         if(Yii::$app && Yii::$app->user && Yii::$app->user->identity ){
           ?><p><a class="btn btn-lg btn-success" href="index.php?r=sell%2Findex">Начать продажу</a></p><?php  
         }else{
           ?><p><a class="btn btn-lg btn-success" href="index.php?r=site%2Flogin">Войти</a></p><?php
         }
        ?>
    </div>

</div>
