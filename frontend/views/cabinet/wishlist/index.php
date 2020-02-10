<?php

/* @var $this yii\web\View */

/* @var $dataProvider yii\data\ActiveDataProvider */

use shop\entities\Shop\Product\Product;
use shop\helpers\PriceHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <h1 class="_top"><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [


            [
                'attribute' => 'category',
                'value' => function (Product $model) {
                    return Html::a(Html::encode($model->category->name), ['/shop/catalog/category', 'slug' =>$model->category->slug]);
                },
                'format' => 'raw',
                'label' => 'Категория'
            ],


            [
                'attribute' => 'name',
                'value' => function (Product $model) {
                    return Html::a(Html::encode($model->name), ['/shop/catalog/product', 'category' =>$model->category->slug,  'slug' => $model->slug]);
                },
                'format' => 'raw',
                'label' => 'Название'
            ],

            'code',


            [
                'attribute' => 'description',
                'value' => function (Product $model) {
                    return Yii::$app->formatter->asHtml($model->description);
                },
                'label' => 'Описание',
                'format' => 'raw',
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',


            ],
        ],
    ]); ?>

</div>
