<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Музыкальная библиотека для медиа-контента. Royalty Free треки для кино, телевидения, рекламы.';
$this->registerMetaTag(['name' => 'description', 'content' => 'Royalty Free треки для многократного использования без ограничений.']);
$this->registerMetaTag(['name' => 'keywords', 'content' => 'Royalty Free треки, музыкальная библиотека,треки для кино, телевидения, рекламы ']);
$this->registerJsFile('@web/js/addJS1.js');
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Музыкальная библиотека для медиа-контента</h1>

        <p class="lead">Royalty Free треки для многократного использования без ограничений.</p>


    </div>


    <div class="container">
        <div class="row">
            <div id="waveform">
                <div id="music" data-about="<?= Yii::getAlias(@backend . '/web/music/Орнамент.mp3') ?>">

                </div>
            </div>

            <button class="btn btn-default" onclick="wavesurfer.playPause()">
                <i class="fa fa-play"></i>
                Play
                /
                <i class="fa fa-pause"></i>
                Pause
            </button>


        </div>
    </div>
    <section class="category-content">

        <div class="body-content">


            <div class="row">
                <div class="col-md-4">
                    <div class="category-item">
                        <h3>Комедия</h3>


                    </div>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'lyrics'])) ?>">
                        <div class="category-item">

                            <h3>Лирика</h3>

                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'detective'])) ?>">
                        <div class="category-item">
                            <h3>Детектив</h3>
                        </div>
                    </a>
                </div>


                <div class="col-md-4">

                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'drama'])) ?>">
                        <div class="category-item">
                            <h3>Драма</h3>

                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="category-item">
                        <h3>Фзнтези</h3>


                    </div>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'minimal'])) ?>">
                    <div class="category-item">
                        <h3>Минимал</h3>
                    </div>
                    </a>
                </div>


            </div>

        </div>
    </section>


    <div class="jumbotron">
        <h2>Музыка разных стилей и направлений</h2>
        <p class="lead">Музыкальные треки для кино, телевидения, рекламы.</p>
    </div>

    <section class="category-content">

        <div class="body-content">


            <div class="row">
                <div class="col-md-4">

                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'popular'])) ?>">
                        <div class="category-item">
                            <h3>Популярная</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'dance'])) ?>">
                        <div class="category-item">
                            <h3>Танцевальная</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'electronic'])) ?>">
                    <div class="category-item">
                        <h3>Электронная</h3>
                    </div>
                    </a>
                </div>


                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'rock'])) ?>">
                        <div class="category-item">
                            <h3>Рок</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="category-item">
                        <h3>Этно</h3>
                    </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="category-item">
                        <h3>Авангард</h3>
                    </div>
                    </a>

                </div>


            </div>

        </div>
    </section>
    <section class="category-content">

        <div class="container marketing">

            <div class="row">

                <div class="col-lg-4 lmg">


                    <img class="level" src="<?= Yii::getAlias('@web/img/level.png') ?>">

                    <p class="lead tmp">Современная фоновая музыка для ресторанов, кафе, магазинов</p>
                </div>

                <div class="col-lg-4 lmg">

                    <img class="level" src="<?= Yii::getAlias('@web/img/level.png') ?>">

                    <p class="lead">Оригинальные звуковые эффекты</p>
                </div>

                <div class="col-lg-4 lmg">

                    <img class="level" src="<?= Yii::getAlias('@web/img/level.png') ?>">

                    <p class="lead">Постпродакшн для тв и кино</p>
                </div>
            </div>
        </div>

    </section>
    <section class="category-content">

        <div class="body-content">


            <div class="row">
                <div class="col-md-4">

                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'advertising'])) ?>">
                        <div class="category-item">
                            <h3>Реклама</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'events'])) ?>">
                        <div class="category-item">
                            <h3>События</h3>

                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'sport'])) ?>">
                    <div class="category-item">
                        <h3>Спорт</h3>
                    </div>
                    </a>

                </div>


                <div class="col-md-4">
                    <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => 'rock'])) ?>">
                        <div class="category-item">
                            <h3>Медитация</h3>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="category-item">
                        <h3>Релакс</h3>
                    </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="category-item">
                        <h3>Йога</h3>
                    </div>
                    </a>

                </div>


            </div>

        </div>
    </section>

</div>
