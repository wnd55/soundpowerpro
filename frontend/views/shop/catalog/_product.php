<?php

/* @var $this yii\web\View */

/* @var $product shop\entities\Shop\Product\Product */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;



$this->registerJsFile('@web/js/addJS2.js', ['depends' => [\yii\web\JqueryAsset::className()]]);


$url = Url::to(['product', 'category' =>$product->category->slug,  'slug' => $product->slug]);
$path = $product->mainPhoto->getAudioFile($product->id, $product->main_photo_id);
$id = $product->id;
$author = $product->brand->name;
$price = PriceHelper::format($product->price_new);

?>


<section class="wave-section">

    <div class="container">
        <div class="row">
            <div class="caption">

                <h4 class="_top"><a href="<?= Html::encode($url) ?>"><?= Html::encode($product->name) ?></a></h4>

                <h5>Категория: <a href="<?= Html::encode(Url::to(['/shop/catalog/category', 'slug' => $product->category->slug])) ?>"><?= Html::encode($product->category->name) ?></a></h5>
            </div>


            <div class="wave" data-path="<?=$path ?>" onclick="return false;">

                <div class="wave-container"></div>
                <button class="btn  btn-default btn-bloc " type="button"><i class="fa fa-play"></i>
                    Play
                    /
                    <i class="fa fa-pause"></i>
                    Pause
                </button>



            <div class="button-order">

                <div class="button-group" style="margin-top: 10px">
                    <button type="button" href="<?= Url::to(['/shop/cart/add', 'id' => $product->id]) ?>"
                            data-method="post" data-toggle="tooltip" title="Загрузить"><i class="fa fa-download"></i>
                        <span></span></button>
                    <button type="button" data-toggle="tooltip" title="Добавить в избранное" href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>"
                            data-method="post"><i class="fa fa-heart"></i>
                    </button>
                </div>

            </div>
            </div>

            <div class="accordion-info">

                <?= \yii\jui\Accordion::widget(['items' => [

                    [
                        'header' => 'Info',


                        'content'=>'<div class="accordion-item">Автор<div>'."{$author}".'</div></div><div class="accordion-item">Формат<div>WAV
48kHz, 24bit</div></div><div class="accordion-item">Цена<div>'."{$price}".'</div></div>',

                        'headerOptions' => [],


                        'options' => [
                        ]
                    ],


                ],
                    'clientOptions' => ['collapsible' => true, 'active'=> false, 'animate' =>200, ]
                ]);
                ?>

            </div>

        </div>
    </div>
    <hr>


</section>


