<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Seller */


$pos=$model->getPos()->one();
$sysuser=$model->getSysuser()->one();

$this->title = $sysuser->sysuser_fullname.' @ '.($pos?$pos->pos_title:"");
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sellers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->seller_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->seller_id], [
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
            'seller_id',
            'sysuser.sysuser_fullname',
            'pos.pos_title',
            'seller_salary',
            'seller_commission_fee',
            'seller_wage',
            'seller_worktime_start',
            'seller_worktime_finish'
        ],
    ]) ?>

</div>
