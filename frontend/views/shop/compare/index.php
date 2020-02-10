<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.02.19
 * Time: 0:23
 */

/* @var $compare shop\compare\Compare[] */

use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use shop\entities\shop\product\Photo;

$this->title = 'Сравнение товаров';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cabinet-index">

    <h1 class="_top"><?= Html::encode($this->title) ?></h1>


    <div class="table-responsive">
        <table class="table table-bordered">

            <thead>
            <tr>
                <td class="text-center" style="width: 100px">Изображение</td>
                <td class="text-left">Выбранные товары</td>

                <td class="text-left">Описание</td>
                <td class="text-right">Цена</td>

            </tr>
            </thead>

            <tbody>

            <?php foreach ($compare as $i => $item):?>

                <?php
                $id = $item['id'];
                $productId =(int)$item['product']->id;
                $product = \shop\entities\shop\product\Product::find()->andWhere(['id' => $productId])->one();
                $photo = Photo::findOne(['id' => $product->mainPhoto]);
                $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);



                ?>
                <tr>

                    <td class="text-center">
                        <a href="<?= $url ?>">

                            <?php if ($product->mainPhoto): ?>
                                <?= Html::img($photo->getCartListPhotoFileUrl($product->id, $photo->id), ['style' => 'width: 50%']) ?>
                            <?php endif; ?>

                        </a>
                    </td>

                    <td class="text-left">
                        <a href=""><?= Html::encode($product->name) ?></a>
                    </td>

                    <td class="text-left">
                     <?=Yii::$app->formatter->asHtml($product->description) ?>

                    </td>
                    <td class="text-right"><?= PriceHelper::format($product->price_new) ?></td>



                </tr>


            <?php endforeach;?>
            </tbody>

        </table>
    </div>

    <br/>





    <div class="buttons clearfix">
        <div class="pull-left"><a href="<?= Url::to('/shop/catalog/index') ?> " class="btn btn-default"><?= Yii::t('app', 'Continue Shopping')?></a></div>
        <div class="pull-right"><a href="<?= Url::to('/shop/compare/clear-compare') ?> " class="btn btn-default"><?= Yii::t('app', 'Empty compare')?></a></div>
    </div>




</div>

