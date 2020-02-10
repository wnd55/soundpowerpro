<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.03.18
 * Time: 10:26
 */

use shop\entities\shop\product\Product;
use yii\helpers\Html;
use yii\grid\GridView;
use shop\helpers\ProductHelper;
use shop\helpers\PriceHelper;


/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
\frontend\assets\FontAwesomeAsset::register($this);
$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">

    <p>
        <?= Html::a('Загрузка трека', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">


            <?= GridView::widget([

                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'rowOptions' => function (Product $model) {
                    return $model->quantity <= 0 ? ['style' => 'background: #fdc'] : [];
                },
                'columns' => [

                    'id',
                    'code',
                    [
                        'attribute' => 'name',
                        'value' => function (Product $model) {
                            return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'category_id',
                        'filter' => $searchModel->categoriesList(),
                        'value' => 'category.name',
                    ],

                    [
                        'attribute' => 'price_new',
                        'value' => function (Product $model) {
                            return PriceHelper::format($model->price_new);
                        },
                        'format' => 'raw',
                    ],
                    'quantity',
                    [
                        'attribute' => 'status',
                        'filter' => $searchModel->statusList(),
                        'value' => function (Product $model) {
                            return ProductHelper::statusLabel($model->status);
                        },
                        'format' => 'raw',
                    ],


                ]


            ]); ?>


        </div>
    </div>
</div>
