<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sysuser */

$this->title = $model->sysuser_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sysusers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sysuser-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->sysuser_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->sysuser_id], [
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
            'sysuser_id',
            'sysuser_fullname',
            'sysuser_login',
            'sysuser_password',
            'sysuser_role_mask',
            'sysuser_telephone',
            'sysuser_token',
        ],
    ]) ?>

</div>
