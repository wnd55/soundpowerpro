<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\assets\WavesurferAsset;

AppAsset::register($this);
WavesurferAsset::register($this);

$cart = new shop\cart\Cart();
$amount = $cart->getAmount();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <meta name="yandex-verification" content="d847d96ebb8e61f9"/>
    <?php $this->head() ?>
    <script>


        (function (m, e, t, r, i, k, a) {
            m[i] = m[i] || function () {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(52779739, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });

    </script>
</head>
<body>
<?php $this->beginBody() ?>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/52779739" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandImage' => Yii::getAlias('@web/img/logo.png'),
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'class' => 'img-responsive',
        ],
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);


    $menuItems = [

        ['label' => 'Каталог', 'url' => ['/shop/catalog/index']],
        ['label' => 'Загрузки ' . "{$amount}", 'url' => ['/shop/cart/index']],
        ['label' => 'Контакты', 'url' => ['/contact/index']],


    ];

    if (Yii::$app->user->isGuest) {

        $menuItems[] = [
            'label' => 'Вход / Регистрация',

            'items' => [

                [
                    'label' => 'Вход', 'url' => ['/auth/auth/login'],
                ],
                '<li class="divider"></li>',
                [
                    'label' => 'Регистрация', 'url' => ['/auth/signup/request'],
                ],


            ],

        ];


    } else {


        $menuItems[] = [

            'label' => 'Личный кабинет/Выход',

            'items' => [

                [
                    'label' => 'Личный кабинет', 'url' => ['/cabinet/default/index'],
                ],
                '<li class="divider"></li>',
                [
                    'label' => 'Выход', 'url' => ['/auth/auth/logout'],
                ],


            ],

        ];

    }


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <section class="main-section">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </section>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-right">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="pull-left"><?= 'Обработка персональных данных' ?></p>
    </div>

</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
