<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.19
 * Time: 15:54
 */
/* @var $cart shop\cart\Cart[] */

/* @var $cart shop\cart\Cart */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use shop\entities\shop\product\Photo;

$this->title = 'Загрузки';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">

    <h1 class="_top"><?= Html::encode($this->title) ?></h1>


    <div class="table-responsive">
        <table class="table table-bordered">

            <thead>
            <tr>
                <td class="text-left">Категория</td>
                <td class="text-left">Композиция</td>
                <td class="text-left">Код</td>
                <td class="text-left"></td>
                <td class="text-right">Итого</td>
            </tr>
            </thead>

            <tbody>

            <?php foreach ($cart as $i => $item): ?>

                <?php
                $id = $item['id'];
                $productId = (int)$item['product']->id;
                $quantity = $item['quantity'];
                $product = \shop\entities\shop\product\Product::find()->andWhere(['id' => $productId])->one();
                $photo = \shop\entities\shop\product\AudioFile::findOne(['id' => $product->mainPhoto]);
                $urlCategory = Url::to(['/shop/catalog/category', 'slug' =>$product->category->slug]);
                $urlProduct = Url::to(['/shop/catalog/product', 'category' =>$product->category->slug,  'slug' => $product->slug]);
                ?>
                <tr>


                    <td class="text-left">
                        <a href="<?=$urlCategory ?>"><?= Html::encode($product->category->name) ?></a>
                    </td>

                    <td class="text-left">
                        <a href="<?=$urlProduct ?>"><?= Html::encode($product->name) ?></a>
                    </td>


                    <td class="text-left">
                        <?= Html::encode($product->code) ?>
                    </td>

                    <td class="text-center">


                        <div class="text-center" style="max-width: 50px;">



                        <span>

                            <a title="Удалить" class="btn-sm btn-danger"
                               href="<?= Url::to(['remove', 'id' => $id]) ?>" data-method="post"><i
                                        class="fa fa-times-circle"></i></a>
                        </span>

                        </div>

                    </td>


                    <td class="text-right"><?= PriceHelper::format($product->price_new) ?></td>

                </tr>


            <?php endforeach; ?>
            </tbody>

        </table>
    </div>

    <br/>


    <div class="row">
        <div class="col-sm-4 col-sm-offset-8">
            <table class="table table-bordered">

                <!--                --><?php //foreach ($cost->getDiscounts() as $discount): ?>
                <!--                    <tr>-->
                <!--                        <td class="text-right"><strong>-->
                <? //= Html::encode($discount->getName()) ?><!--:</strong></td>-->
                <!--                        <td class="text-right">-->
                <? //= PriceHelper::format($discount->getValue()) ?><!--</td>-->
                <!--                    </tr>-->
                <!--                --><?php //endforeach; ?>

                </tr>
                <tr>

                <tr>
                    <td class="text-right"><strong>Скидка:</strong></td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Полная стоимость:</strong></td>
                    <td class="text-right"><?= PriceHelper::format($total) ?></td>

                </tr>

            </table>

        </div>

    </div>


    <div class="buttons clearfix">
        <div class="pull-left"><a href="<?= Url::to('/shop/catalog/index') ?>"
                                  class="btn btn-default"><?= 'Продолжить покупки' ?></a></div>


        <div class="pull-right"><a href="<?= Url::to('/shop/checkout/index') ?>"
                                   class="btn btn-primary"><?= 'Оформить заказ' ?> </a></div>

        <div class="pull-right" style="margin-right: 10px"><a href="<?= Url::to('/shop/cart/clear-cart') ?>"
                                                              class="btn btn-default"><?= 'Очистить загрузки' ?> </a>
        </div>

    </div>


</div>
