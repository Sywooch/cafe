<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pos */

$this->title = $model->pos_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pos-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->pos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Pos-product-list'), ['products', 'pos_id' => $model->pos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Pos-product-supply'), ['supply', 'id' => $model->pos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Pos-packaging-prices'), ['packaging', 'id' => $model->pos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Pos-product-supply-print'), ['supplyprint', 'id' => $model->pos_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->pos_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pos_id',
            'pos_title',
            'pos_address',
            'pos_timetable',
            'pos_printer_url',
            [
                'attribute'=>'pos_printer_template',
                'label'=>Yii::t('app','pos_printer_template'),
                'value'=> '<pre>'.$model->pos_printer_template.'</pre>',
                'format'=>'html'
            ],
        ],
    ]) ?>

</div>
