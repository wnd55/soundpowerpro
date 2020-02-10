<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.06.18
 * Time: 14:07
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use shop\helpers\OrderHelper;
use shop\helpers\PriceHelper;

/* @var $this yii\web\View */
/* @var $order shop\entities\shop\order\OrderUser */

$this->title = 'Заказ  ' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Кабинет', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;



?>

<div class="user-view">

    <h1 class="_top"><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $order,
        'attributes' => [
            'id',
            'created_at:datetime',
            [
                'attribute' => 'current_status',
               'value' => OrderHelper::statusUserLabel($order->current_status),
                'format' => 'raw',
            ],
            'delivery_method_name',
            'cost',
            'note:ntext',
        ],
    ]) ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>

                <td class="text-left">Трек</td>
                <td class="text-left">Код</td>

                <td class="text-right">WAV 48kHz, 24bit</td>
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
                        <a href="<?= \yii\helpers\Url::toRoute(@backend.'/web/uploads/wav/' . $item->product_name.'.wav') ?>">Загрузить</a>
                    </td>
                </tr>

            <?php endforeach; ?>
            </tbody>
        </table>
    </div>




</div>
