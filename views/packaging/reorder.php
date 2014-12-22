<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PackagingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Reorder Packagings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Packagings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="css/jquery-ui/jquery-ui-1.11.2.custom/jquery-ui.min.css" rel="stylesheet" type="text/css"/>

<div class="packaging-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php

//print_r($data);
echo '<p>'.Yii::t('app','Drag to Reorder Packagings').'</p>';
echo "<ol id=\"sortablePacks\">";
foreach($data as $packaging){
    $category=$packaging->getCategory()->one();
    echo "<li data-id=\"{$packaging->packaging_id}\" >&nbsp; / {$category->category_title} / {$packaging->packaging_title}</li>";
}
echo "</ol>";
?>
<style type="text/css">
    #sortablePacks li{
        cursor:move;
        padding:3px 0;
        margin-bottom:3px;
        background-color:#eee;
    }
    #sortablePacks li:hover{
        background-color:#ddd;
    }
</style>
</div>



<?php
    //$path=Yii::$app->assetManager->publish(Yii::$app->basePath."/web/js/");
    //var_dump(Yii::getAlias('@web')."/js/");exit();
    $this->registerJsFile(Yii::getAlias('@web/js/jquery-ui.min.js'),['depends'=>'yii\web\YiiAsset']);          

    $this->registerJs("
        
        function update_packaging_ordering(){
            var ids=[];
            $('#sortablePacks li').each(function(key,val){
                ids.push($(val).attr('data-id'));
            });
            
            $.ajax({
                type: 'POST',
                cache: false,
                dataType:'json',
                url: '" . Url::toRoute(['/packaging/batchupdateordering']) . "',
                data:{
                  ids:ids.join()
                },
                success: function (response) {
                }
            });
        }
        
        function activateForm(){
            jQuery( \"#sortablePacks\" ).sortable({
              update:update_packaging_ordering
            });
            jQuery( \"#sortablePacks\" ).disableSelection();
        }
        jQuery(window).load(activateForm);    
    ");        

?>
