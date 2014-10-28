<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = Yii::t('app', 'Order {id}',['id'=>$model->order_id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?php /*
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->order_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->order_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
     */?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
            'order_datetime',
            'order_day_sequence_number',
            //'pos_id',
            'pos.pos_title',
            //'seller_id',
            //'sysuser_id',
            'sysuser.sysuser_fullname',
            //'order_total',
            [                    // the owner name of the model
               'label' => Yii::t('app', 'Order Total'),
               'value' => $model->order_total.' '.Yii::$app->params['currency'],
            ],
            //'order_discount',
            [                    // the owner name of the model
               'label' => Yii::t('app', 'Order Discount'),
               'value' => $model->order_discount.' '.Yii::$app->params['currency'],
            ],
            'order_payment_type',
            //'order_hash',
        ],
    ]) ?>

    <h3><?=Yii::t('app', 'Order Items')?></h3>
    
    <table class="table table-striped table-bordered detail-view"><tbody>
    <?php
    $orderItems=$model->getOrderPackagings()->all();
    // print_r($orderItems);
    foreach($orderItems as $item){
        echo "<tr>
                <td>{$item->packaging_title}</td>
                <td>{$item->order_packaging_number} &times; {$item->packaging_price} ".(Yii::$app->params['currency'])."</td>
              </tr>";
    }
    ?>
    </tbody></table>
    

</div>
