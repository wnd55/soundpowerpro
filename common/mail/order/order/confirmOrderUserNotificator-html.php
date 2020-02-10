z<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.06.18
 * Time: 23:04
 */

/* @var $this yii\web\View */
/* @var $order \shop\entities\shop\order\OrderUser */

/* @var $items \shop\entities\shop\order\OrderUserItem */

use yii\helpers\Html;

?>
<section>
<div class="logo">
    <a href="https://www.green-acres.ru"><img src="<?= $message->embed($logo); ?>"/>  </a>
    </div>

</section>
<section>
    <div>
        <p>Здравствуйте,</p>
        <p>Вы сделали заказ на сайте www.soundpowerpro.ru</p>
        <p>Статус заказа и полную информацию можно узнать в личном кабинете.</p>
        <p>Реквизиты для оплаты Вам перешлёт на почту администратор сайта. После оплаты, Вы сожете скачать трек в личном кабинете.</p>
    </div>

    <div class="user-view">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= \yii\widgets\DetailView::widget([
            'model' => $order,
            'attributes' => [
                'id',
                'created_at:datetime',
                [
                    'attribute' => 'current_status',
                    'value' => \shop\helpers\OrderHelper::statusUserLabel($order->current_status),
                    'format' => 'raw',
                ],
                'delivery_method_name',

                [
                    'attribute' => 'cost',
                    'value' => $order->cost . Html::img($message->embed($ruble),  ['style' => ['width' => '10px']]),
                    'format' => 'raw',

                ],
                'note:ntext',
            ],
        ]) ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td class="text-left">Трек</td>
                    <td class="text-left">Код</td>
                    <td class="text-right">Стоимость</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order->items as $item): ?>
                    <tr>
                        <td class="text-left">

                            <?= Html::encode($item->product_name) ?>
                        </td>
                        <td class="text-left">
                            <?= Html::encode($item->product_code) ?>
                        </td>
                        <td class="text-right">

                            <?= $item->price . Html::img($message->embed($ruble),  ['style' => ['width' => '10px']]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>


    </div>

</section>