<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->params['siteTitle'],
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            
            $items=[];
            // $items[]=['label' => 'Старт', 'url' => ['/site/index']];
            // $items[]=['label' => 'About', 'url' => ['/site/about']];

            if(Yii::$app && Yii::$app->user && Yii::$app->user->identity && Yii::$app->user->identity->sysuser_role == \app\models\Sysuser::ROLE_ADMIN){
                //http://localhost/cafe/web/index.php?r=sell%2Findex&pos_id=1
                
                $items[]=['label' => Yii::t('app','Sell'), 'url' => ['/sell/index']];
                //$items[]=['label' => Yii::t('app','Orders'), 'url' => ['/order/index','sort'=>'-order_datetime']];
                $items[]=[
                    'label' => Yii::t('app','Reports'), 
                    //'items'=>[
                    //    ['label'=>Yii::t('app','Orders'), 'url'=>['/order/index','sort'=>'-order_id']],
                    //    ['label'=>Yii::t('app','Sellers'), 'url'=>['/report/seller']],
                    //    ['label'=>Yii::t('app','ProductReport'), 'url'=>['/report/product']],
                    //    ['label'=>Yii::t('app','PackagingReport'), 'url'=>['/report/packaging']],
                    //    ['label'=>Yii::t('app','PosIncomeReport'), 'url'=>['/report/posincome']],
                    //    ['label'=>Yii::t('app','SellerIncomeReport'), 'url'=>['/report/sellerincome']],
                    //    ['label'=>Yii::t('app', 'HourlyIncomeReport'), 'url'=>['/report/hourlyincome']],
                    //    ['label'=>Yii::t('app', 'WeekdailyIncomeReport'), 'url'=>['/report/weekdailyincome']],
                    //    ['label'=>Yii::t('app', 'DailyIncomeReport'), 'url'=>['/report/dailyincome']],
                    //]
                    'url' => ['/report/index']
                ];
                
                

                $items[]=['label' => Yii::t('app','Pos-list'), 'url' => ['/pos/index']];

                $items[]=[
                    'label' => Yii::t('app','Products'),
                    'items' => [
                        ['label' => Yii::t('app','Products'), 'url' => ['/product/index']],
                        ['label' => Yii::t('app','Discounts'), 'url' => ['/discount/index']],
                        ['label' => Yii::t('app','Packaging'), 'url' => ['/packaging/index']],
                        ['label' => Yii::t('app','Categories'), 'url' => ['/category/index']],
                    ]
                ];
                $items[]=[
                    'label'=>Yii::t('app','Personnel'),
                    'items'=>[
                        ['label' => Yii::t('app','Sysuser-list'),'url' => ['/sysuser/index']],
                        ['label' => Yii::t('app','Sellers'), 'url' => ['/seller/index']]
                    ]
                ];
            }
            
            if(Yii::$app->user->isGuest){
                $items[]=['label' => Yii::t('app','Login'), 'url' => ['/site/login']];
            }else{
                $items[]=['label' => Yii::t('app','Logout ({login})',['login'=>Yii::$app->user->identity->sysuser_login]),
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']];
            }

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $items
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?=Yii::$app->params['siteTitle']?> <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
