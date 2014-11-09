<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SysuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sysusers');
$this->params['breadcrumbs'][] = $this->title;

$GLOBALS['rolemap']=\app\models\Sysuser::getRoles();//

?>
<div class="sysuser-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Sysuser'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn'],
            ['attribute' => 'sysuser_id','filterOptions'=>['style'=>'width:100px;'],],
            'sysuser_login',
            'sysuser_fullname',
            'sysuser_telephone',
            // 'sysuser_password',
            [
                'attribute' => 'sysuser_role',
                'format' => 'text',
                'label' => Yii::t('app', 'sysuser_role'),
                'filter'=>$GLOBALS['rolemap'],
                'content'=>function($sysuser) { 
                              if(isset($GLOBALS['rolemap'][$sysuser->sysuser_role])){
                                  return $GLOBALS['rolemap'][$sysuser->sysuser_role];
                              }else{
                                  return '-';
                              }
                           },
             ],
            [
                'format' => 'html',
                'label' => Yii::t('app', 'has_pos_position'),
                'content'=>function($sysuser) { 
                                
                                if($sysuser->sysuser_role=='seller'){
                                    $sellers=$sysuser->getSellers()->all();
                                    return count($sellers)>0?Yii::t('app', 'yes'):'<span class="warning">'.Yii::t('app', 'no').'</span><a href="index.php?r=seller%2Fcreate">'.Yii::t('app', 'assign').'</a>';
                                }else{
                                    return '';
                                }
                                  
                             
                           },
             ]
            // 'sysuser_token',
        ],
    ]); ?>

</div>
<style type="text/css">
    .warning{
        display:inline-block;
        padding:2px;
        background-color:orange;
        color:black;
        margin-right:5px;
    }
</style>