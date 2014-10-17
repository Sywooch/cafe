<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Packaging */

$this->title = Yii::t('app', 'Update Packaging: ') . ' ' . $model->packaging_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packagings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->packaging_title, 'url' => ['view', 'id' => $model->packaging_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="packaging-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
