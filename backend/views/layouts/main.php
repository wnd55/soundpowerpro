<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\widgets\Menu;

AppAsset::register($this);
backend\assets\FontAwesomeAsset::register($this);
backend\assets\WavesurferAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Rubik" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">


    <header class="dark">
        <nav role="navigation">
            <a href="javascript:void(0);" class="ic menu" tabindex="1">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </a>
            <a href="javascript:void(0);" class="ic close"></a>
            <?= Menu::widget([

                'items' => [

                    [
                        'label' => 'Home',
                        'url' => ['/'],
                        'options' => ['class' => 'top-level-link'],
                        'visible' => true,
                        'active' => Yii::$app->controller->id == 'site',


                    ],

                    [

                        'label' => 'Треки',
                        'url' => ['/shop/product'],
                        'visible' => true,
                        'options' => ['class' => 'top-level-link'],
                        'template' => '<a class="mega-menu" href="{url}"><span>{label}</span></a>',
                        'active' => in_array(\Yii::$app->controller->id,['shop/product','shop/category', 'shop/tag', 'shop/delivery']),
                        'submenuTemplate' =>
                            '<div class="sub-menu-block">
                        <div class="row">

                        <div class="col-md-4 col-lg-4 col-sm-4"><h2 class="sub-menu-head">Треки</h2>
                        <ul class="sub-menu-lists">

                         <li>' . Html::a('Треки', ['/shop/product',]).'</li>
                         <li>' . Html::a('Категории', ['/shop/category',]).'</li>
                         <li>' . Html::a('Теги', ['/shop/tag',]) .'</li>


                        </ul>
                       </div>
                        <div class="col-md-4 col-lg-4 col-sm-4"><h2 class="sub-menu-head">Авторы</h2>
                        <ul class="sub-menu-lists">
                           <li>' . Html::a('Авторы', ['/shop/brand',]) . '</li>
                         <li>' . Html::a('Страницы', ['#',]) . '</li>

                        </ul>
                       </div>


                       <div class="col-md-4 col-lg-4 col-sm-4"><h2 class="sub-menu-head">Заказы</h2>
                        <ul class="sub-menu-lists">{items}</ul>
                       </div>
                       </div>
                    </div>',

                        'items' => [


                            ['label' => 'Заказы', 'url' => ['/shop/order-user']],
                            ['label' => 'Доставка', 'url' => ['/shop/delivery']],

                        ],


                    ],

                    [
                        'label' => 'Клиенты',
                        'url' => ['#'],
                        'options' => ['class' => 'top-level-link dropdown'],
                        'active' => in_array(\Yii::$app->controller->id, ['user', 'role' ]),
                        'visible' => true,
                        'template' => '<a class="mega-menu" href="{url}"><span>{label}</span></a>',
                        'submenuTemplate' =>
                            '<div class="sub-menu-block dropdown-content">
                        <div class="row">                              
                       <div class="col-md-4 col-lg-4 col-sm-4">
                        <ul class="sub-menu-lists">{items}</ul>                                            
                       </div>
                     </div>                                              
                    </div>',

                        'items' => [

                            ['label' => 'Клиенты', 'url' => ['/user/index']],
//                            ['label' => 'Роли', 'url' => ['/role/role']],
                          ['label' => 'Роли', 'url' => ['/rbac/default/role']],

                        ],

                    ],


                    [
                        'label' => 'Система',
                        'url' => ['#'],
                        'options' => ['class' => 'top-level-link dropdown'],
                        'active' => in_array(\Yii::$app->controller->id, ['/auth/cache', 'log',]),
                        'visible' => true,
                        'template' => '<a class="mega-menu" href="{url}"><span>{label}</span></a>',
                        'submenuTemplate' =>
                            '<div class="sub-menu-block dropdown-content">
                        <div class="row">                              
                       <div class="col-md-4 col-lg-4 col-sm-4">
                        <ul class="sub-menu-lists">{items}</ul>                                            
                       </div>
                     </div>                                              
                    </div>',

                        'items' => [

                            ['label' => 'Кэш', 'url' => ['/auth/cache']],
                            ['label' => 'Логи', 'url' => ['/log']],


                        ],

                    ],

                    [

                        'label' => 'Выход',
                        'url' => ['/auth/login'],
                        'options' => ['class' => 'top-level-link'],
                        'visible' => !Yii::$app->user->isGuest,
                        'template' => '' . Html::beginForm(['/auth/logout'], 'post')
                            . Html::submitButton(
                                'Выход (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-link logout active']
                            )
                            . Html::endForm() . ''


                    ],

                ],

                'options' => ['class' => 'main-nav'],
                'activateParents'=>true,
            ]);
            ?>

        </nav>
    </header>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
