<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sysuser */

$this->title = Yii::t('app', 'Create Sysuser', [
    'modelClass' => 'Sysuser',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sysusers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sysuser-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
