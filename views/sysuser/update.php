<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sysuser */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Sysuser',
]) . ' ' . $model->sysuser_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sysusers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sysuser_id, 'url' => ['view', 'id' => $model->sysuser_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="sysuser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
