<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "{$subsystem->subsystemTitle} - ".Yii::t('app', 'Orderview')." {$data['order']['order_id']}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subsystem_reports'), 'url' => ['/subsystem/index']];
$this->params['breadcrumbs'][] = ['label' => $subsystem->subsystemTitle, 'url' => ['/subsystem/reports', 'subsystemId'=>$subsystem->subsystemId]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['/subsystem/orderreport','sort'=>'-order_id','subsystemId'=>$subsystem->subsystemId]];
$this->params['breadcrumbs'][] = $this->title;

//print_r($post);
//print_r($data);

?>
<style type="text/css">
        
    .attrView{
        
    }
    .attrView .attrViewLabel{
        display:inline-block;
        width:200px;
        vertical-align:top;
    }
    .attrView .attrViewValue{
        display:inline-block;
        width:400px;
        vertical-align:top;
    }
</style>
<div class="order-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'Order ID')?></span>
        <span class="attrViewValue"><?=$data['order']['order_id']?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'Order Datetime')?></span>
        <span class="attrViewValue"><?=date(Yii::t('app', 'datetime_format'),strtotime($data['order']['order_datetime']))?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'Order Day Sequence Number')?></span>
        <span class="attrViewValue"><?=$data['order']['order_day_sequence_number']?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'pos_title')?></span>
        <span class="attrViewValue"><?=$data['pos']['pos_title']?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'seller')?></span>
        <span class="attrViewValue"><?=$data['sysuser']['sysuser_fullname']?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'Order Total')?></span>
        <span class="attrViewValue"><?=$data['order']['order_total'].' '.Yii::$app->params['currency']?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'Order Discount')?></span>
        <span class="attrViewValue">
        <?=($data['order']['discount_id']>0
            ? "{$data['order']['discount_title']} {$data['order']['order_discount']} ".Yii::$app->params['currency']
            : '-----' )?>
        </span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'Order Payment Type')?></span>
        <span class="attrViewValue"><?=$data['order']['order_payment_type']?></span>
    </div>
    <div class="attrView">
        <span class="attrViewLabel"><?=Yii::t('app', 'customerId')?></span>
        <span class="attrViewValue"><?=(
                isset($data['customer'])
                ?("{$data['customer']['customerName']} {$data['customer']['customerMobile']} {$data['customer']['customerNotes']}")
                :'-----'
                )?></span>
    </div>

    <h3><?=Yii::t('app', 'Order Items')?></h3>
    <table class="table table-striped table-bordered detail-view"><tbody>
    <?php
    foreach($data['items'] as $item){
        echo "<tr>
                <td>{$item['packaging_title']}</td>
                <td>{$item['order_packaging_number']} &times; {$item['packaging_price']} ".(Yii::$app->params['currency'])."</td>
              </tr>";
    }
    ?>
    </tbody></table>
</div>
