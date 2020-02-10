<?php

use shop\helpers\OrderHelper;
use shop\helpers\PriceHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $order shop\entities\shop\order\OrderUser */
\frontend\assets\FontAwesomeAsset::register($this);
$this->title = 'OrderUser ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $order->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $order->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $order,
                'attributes' => [
                    'id',
                    'user_id',
                    'customer_name',


                    'created_at:datetime',


                    [
                        'attribute' => 'current_status',
                        'value' => OrderHelper::statusUserLabel($order->current_status),
                        'format' => 'raw',
                        'label' => 'Статус заказа',
                    ],

                    'delivery_method_name',

                    'cost',
                    'note:ntext',
                    [
                        'attribute' => 'cancel_reason',

                        'format' => 'raw',

                    ],
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
                        <th class="text-left">Трэк</th>
                        <th class="text-left">Код</th>
                        <th class="text-right">Стоимость</th>
                        <th class="text-right">Загрузить WAV</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($order->items as $item): ?>
                        <tr>
                            <td class="text-left">
                                <a href="<?= Html::encode(\yii\helpers\Url::to(['/shop/product/view', 'id' => $item->product_id])) ?>"> <?= Html::encode($item->product_name) ?>
                                </a>
                            </td>
                            <td class="text-left">
                                <?= Html::encode($item->product_code) ?>
                            </td>
                            <td class="text-right">

                                <?= PriceHelper::format($item->price) ?>

                            </td>

                            <td class="text-right">
                                <?= Html::beginForm('add-wav', 'post', ['enctype' => 'multipart/form-data', 'accept' => 'audio/wav']) ?>
                                <?= Html::fileInput('wav') ?>
                                <?= Html::submitButton('Загрузить', ['class' => 'btn btn-default']) ?>
                                <?= Html::endForm() ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
