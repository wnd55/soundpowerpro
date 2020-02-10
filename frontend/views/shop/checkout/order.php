<?php

use shop\helpers\OrderHelper;
use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $order shop\entities\shop\order\OrderUser */
\frontend\assets\FontAwesomeAsset::register($this);


$this->title = 'Заказ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $order,
                'attributes' => [


                    [
                        'attribute' => 'created_at',
                        'value' => date('Y:d:m H:i:s', $order->created_at),
                        'label' => 'Дата создания',
                    ],

                    [
                        'attribute' => 'current_status',
                        'value' => OrderHelper::statusUserLabel($order->current_status),
                        'format' => 'raw',
                        'label' => 'Статус заказа',
                    ],

                    [
                        'attribute' => 'delivery_method_name',
                        'label' => 'Доставка',
                    ],


                    [
                        'attribute' => 'cost',
                        'value' => function ($order) {
                            return PriceHelper::format($order->cost);
                        },
                        'format' => 'raw',
                        'label' => 'Полная стоимость заказа с доставкой',
                    ],
                    [
                        'attribute' => 'note',
                        'label' => 'Комментарии',
                    ]
                ],
            ]) ?>
        </div>
    </div>


    <div class="box">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom: 0">
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

                                <?= PriceHelper::format($item->price) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
