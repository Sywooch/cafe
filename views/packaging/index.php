<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

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
            [
                'attribute' => 'packaging_price',
                'filterOptions'=>['style'=>'width:100px;'],
                'content'=>function ($model, $key, $index, $column){
                                 return $model->packaging_price.' '.Yii::$app->params['currency'];
                           },
                'label' => Yii::t('app','Packaging Price'),
            ],
            //'packaging_is_additional',
            [
               'label'=>\Yii::t('app','Packaging ordering'),
               'attribute' => 'packaging_ordering',
               'content'=>function ($model, $key, $index, $column){
                                 return '<nobr>'.Html::textInput('packaging_ordering', $model->packaging_ordering, ['class'=>'packaging_ordering form-control','data-packaging-id'=>$model->packaging_id,'size'=>3]).'</nobr>';
                           }
            ], 
        ],
    ]); ?>

</div>


<?php
    $this->registerJs("
        function update_packaging_ordering(packaging_id, packaging_ordering){
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/packaging/updateordering']) . "',
                data:{
                  packaging_id:packaging_id,
                  packaging_ordering:packaging_ordering
                },
                success: function (response) {
                }
            });
        }
        function activateForm(){
            $('.packaging_ordering').change(function(event){
                var ele=$(event.target);
                var packaging_id=ele.attr('data-packaging-id');
                var packaging_ordering=ele.val();
                update_packaging_ordering(packaging_id, packaging_ordering);
            });
        }
        $(window).load(activateForm);    
    ");        

?>
