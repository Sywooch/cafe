<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pos */

$this->title = Yii::t('app', 'Update Pos: ') . ' ' . $model->pos_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_title, 'url' => ['view', 'id' => $model->pos_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
