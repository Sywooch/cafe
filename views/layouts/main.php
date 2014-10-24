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
                'brandLabel' => 'My Company',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            
            $items=[];
            $items[]=['label' => 'Старт', 'url' => ['/site/index']];
            // $items[]=['label' => 'About', 'url' => ['/site/about']];

            if(Yii::$app && Yii::$app->user && Yii::$app->user->identity && Yii::$app->user->identity->sysuser_role == \app\models\Sysuser::ROLE_ADMIN){
                $items[]=['label' => Yii::t('app','Packaging'), 'url' => ['/packaging/index']];
                $items[]=['label' => Yii::t('app','Categories'), 'url' => ['/category/index']];
                
                $items[]=['label' => Yii::t('app','Products'), 'url' => ['/product/index']];
                $items[]=['label' => Yii::t('app','Sellers'), 'url' => ['/seller/index']];
                $items[]=['label' => Yii::t('app','Pos-list'), 'url' => ['/pos/index']];
                $items[]=['label' => Yii::t('app','Sysuser-list'), 'url' => ['/sysuser/index']];
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
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
