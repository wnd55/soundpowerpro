<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.06.18
 * Time: 17:46
 */

use shop\entities\Shop\Order\OrderUser;
use shop\helpers\OrderHelper;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\OrderUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Export', ['export'], ['class' => 'btn btn-primary', 'data-method' => 'post', 'data-confirm' => 'Export?']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'id',
                        'value' => function (\shop\entities\shop\Order\OrderUser $model) {
                            return Html::a(Html::encode($model->id), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],

                    'created_at:datetime',
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusUserList(),
                        'value' => function (\shop\entities\shop\Order\OrderUser $model) {
                            return OrderHelper::statusUserLabel($model->current_status);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
