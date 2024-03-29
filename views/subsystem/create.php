<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Subsystem */

$this->title = Yii::t('app', 'Create Subsystem');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subsystems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subsystem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
