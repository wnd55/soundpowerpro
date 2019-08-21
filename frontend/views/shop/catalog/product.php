<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.04.18
 * Time: 11:41
 */

/* @var $this yii\web\View */

/* @var $product shop\entities\shop\product\Product */

/* @var $cartForm shop\forms\shop\AddToCartForm */


use yii\helpers\Html;
use yii\helpers\Url;
use shop\helpers\PriceHelper;
use shop\helpers\WeightHelper;
use yii\bootstrap\ActiveForm;


$this->title = $product->meta_title;

$this->registerMetaTag(['name' => 'description', 'content' => $product->meta_description]);

$this->registerMetaTag(['name' => 'keywords', 'content' => $product->meta_keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];

foreach ($product->category->parents as $parent) {

    if (!$parent->isRoot()) {

        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'slug' => $parent->slug]];
    }

}
$this->params['breadcrumbs'][] = ['label' => $product->category->name, 'url' => ['category', 'slug' => $product->category->slug]];

$this->params['breadcrumbs'][] = $product->name;

$this->params['active_category'] = $product->category;

$this->registerJsFile('@web/js/addJS2.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

$path = $product->mainPhoto->getAudioFile($product->id, $product->main_photo_id);
$id = $product->id;
$author = $product->brand->name;
$price = PriceHelper::format($product->price_new);


?>

<div class="row">


    <section class="wave-section">


        <div class="caption">

            <h3 class="_top"><?= Html::encode($product->name) ?></h3>
            <h4>Автор:<a href="<?= Html::encode(Url::to(['brand', 'slug' => $product->brand->slug])) ?>">

                    <?= Html::encode($author) ?>
                </a></h4>


            <p><?= Yii::$app->formatter->asHtml($product->description) ?></p>

            <?php if($product->tags):?>
            <p style="float: right"> Теги: <?php foreach ($product->tags as $tag): ?>

                    <a href="<?= Html::encode(Url::to(['tag', 'id' => $tag->id])) ?>">

                        <?= Html::encode($tag->name) ?>
                    </a>

                <?php endforeach; ?>
            </p>

            <?php endif; ?>

            <p>Код: <?= Html::encode($product->code) ?></p>

        </div>


        <div class="wave" data-path="<?= $path ?>" onclick="return false;">

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
                            data-method="post" data-toggle="tooltip" title="Загрузить"><i
                                class="fa fa-download"></i>
                        <span></span></button>
                    <button type="button" data-toggle="tooltip" title="Добавить в избранное"
                            href="<?= Url::to(['/cabinet/wishlist/add', 'id' => $product->id]) ?>"
                            data-method="post"><i class="fa fa-heart"></i>
                    </button>
                </div>

            </div>
        </div>

        <div class="accordion-info">

            <?= \yii\jui\Accordion::widget(['items' => [

                [
                    'header' => 'Info',
                    'content' => '<div class="accordion-item">Автор<div>' . "{$author}" . '</div></div><div class="accordion-item">Формат<div>WAV
48kHz, 24bit</div></div><div class="accordion-item">Цена<div>' . "{$price}" . '</div></div>',

                    'headerOptions' => [],


                    'options' => [
                    ]
                ],


            ],
                'clientOptions' => ['collapsible' => true, 'active' => false, 'animate' => 200,]
            ]);
            ?>


        </div>
        <hr>
        <div class="stems-section">

            <?php if ($product->stems) : ?>

                <h4>Stems</h4>
                <?php foreach ($product->stems as $stem): ?>

                    <audio controls controlsList="nodownload">

                        <source src="<?= Url::toRoute(@backend .'/web/uploads/mp3/stems/' . $stem->file); ?>"
                                type="audio/mpeg">
                    </audio>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>



    </section>


</div>


