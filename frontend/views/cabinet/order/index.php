<?php

use shop\entities\Shop\Order\OrderUser;
use shop\helpers\OrderHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'value' => function (OrderUser $model) {
                            return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'created_at:datetime',
                    [
                        'attribute' => 'status',
                        'value' => function (OrderUser $model) {
                            return OrderHelper::statusUserLabel($model->current_status);
                        },
                        'format' => 'raw',
                    ],
                    [
                            'class' => ActionColumn::class,
                        'template' => '{view}'],
                ],
            ]); ?>
        </div>
    </div>
</div>
