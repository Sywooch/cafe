<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SysuserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sysusers');
$this->params['breadcrumbs'][] = $this->title;

$GLOBALS['rolemap']=Array(\app\models\Sysuser::ROLE_ADMIN=>Yii::t('app', 'ROLE_ADMIN'),\app\models\Sysuser::ROLE_SELLER=>Yii::t('app', 'ROLE_SELLER'));

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
                'attribute' => 'sysuser_role_mask',
                'format' => 'text',
                'label' => Yii::t('app', 'sysuser_role_mask'),
                'filter'=>$GLOBALS['rolemap'],
                'content'=>function($sysuser) { 
                              if(isset($GLOBALS['rolemap'][$sysuser->sysuser_role_mask])){
                                  return $GLOBALS['rolemap'][$sysuser->sysuser_role_mask];
                              }else{
                                  return '-';
                              }
                           },
             ]
            // 'sysuser_token',
        ],
    ]); ?>

</div>
