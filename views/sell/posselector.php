<?php
use yii\helpers\Html;

$this->title = Yii::t('app','Posselector');
?>
<h1><?= Html::encode($this->title) ?></h1>

<ul>
<?php
foreach($posList as $pos){
    echo "<li>".Html::a($pos->pos_title.", ".$pos->pos_address, ['index','pos_id'=>$pos->pos_id])."</li>\n";
}
?>
</ul>