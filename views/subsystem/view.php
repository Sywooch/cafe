<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Subsystem */

$this->title = $model->subsystemTitle;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subsystems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subsystem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->subsystemId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'SubsystemReports'), ['reports', 'subsystemId' => $model->subsystemId], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->subsystemId], [
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
            'subsystemId',
            'subsystemTitle',
            'subsystemUrl:url',
            'subsystemApiKey',
        ],
    ]) ?>

</div>
