<?php

/* @var $this yii\web\View */
/* @var $cart \shop\cart\Cart */

/* @var $model \shop\forms\Shop\Order\OrderUserForm */

use shop\helpers\PriceHelper;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/shop/catalog/index']];
$this->params['breadcrumbs'][] = ['label' => 'Корзина', 'url' => ['/shop/cart/index']];
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
                <td class="text-right">Стоимость</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($cart as $i => $item): ?>

                <?php
                $id = $item['id'];
                $productId = (int)$item['product']->id;
                $quantity = $item['quantity'];
                $product = \shop\entities\shop\product\Product::find()->andWhere(['id' => $productId])->one();
                $url = Url::to(['/shop/catalog/product', 'id' => $product->id]);
                ?>
                <tr>
                    <td class="text-left">
                        <?= Html::encode($product->category->name) ?>
                    </td>
                    <td class="text-left">
                        <?= Html::encode($product->name) ?>
                    </td>
                    <td class="text-left">

                        <?= Html::encode($product->code) ?>

                    </td>
                    <td class="text-right">

                        <?= PriceHelper::format($product->price_new) ?></td>
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

                <!--                </tr>-->


                <tr>
                    <td class="text-right"><strong>Скидка:</strong></td>
                    <td class="text-right"
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>Полная стоимость :</strong></td>
                    <td class="text-right"><?= PriceHelper::format($totalCount) ?></td>
                </tr>
            </table>
        </div>
    </div>


    <?php $form = ActiveForm::begin() ?>

    <div class="panel panel-default">
        <div class="panel-heading">Покупатель</div>
        <div class="panel-body">


            <?= $form->field($model, 'name')->textInput() ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Доставка</div>
        <div class="panel-body">
            <?= $form->field($model, 'delivery')->dropDownList($model->deliveryMethodsList(), ['prompt' => '--- Доставка ---']) ?>


        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Комментарии</div>
        <div class="panel-body">
            <?= $form->field($model, 'note')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Оформить заказ', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
    </div>

    <?php ActiveForm::end() ?>

</div>
    
