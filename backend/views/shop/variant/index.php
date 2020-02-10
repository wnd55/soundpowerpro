<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:48
 */

use shop\entities\shop\product\Variant;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\shop\VariantSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Variants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']);   ?>


    </p>


    <div class="box">
        <div class="box-body">

            <?= GridView::widget(['dataProvider' => $dataProvider,
                'filterModel' => $search,
                'columns' => [
                    'id',

                    [
                        'attribute' => 'characteristic_id',
                        'filter' => $search->characteristicsList(),
                        'value' => 'characteristic.name',


                    ],

                    [
                        'attribute' => 'variant',
                        'value' => function (Variant $model) {
                            return Html::a(Html::encode($model->variant), ['view', 'id' => $model->id]);
                        },
                        'format' => 'raw',
                    ],
                    'sort',
                    ['class' => ActionColumn::class],
                ],
            ]) ?>


        </div>
    </div>

</div>