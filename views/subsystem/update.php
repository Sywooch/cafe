<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Subsystem */

$this->title = Yii::t('app', 'Update Subsystem:') . ' ' . $model->subsystemTitle;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subsystems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subsystemTitle, 'url' => ['view', 'id' => $model->subsystemId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="subsystem-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
