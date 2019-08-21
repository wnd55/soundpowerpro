<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:48
 */

use shop\entities\shop\Tag;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchTag backend\forms\shop\TagSearchForm */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Теги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']);   ?>


    </p>


    <div class="box">
        <div class="box-body">

<?= GridView::widget(['dataProvider' => $dataProvider,

    'filterModel' => $searchTag,
    'columns' => [
        'id',
        [
            'attribute' => 'name',
            'value' => function (Tag $model) {
                return Html::a(Html::encode($model->name), ['view', 'id' => $model->id]);
            },
            'format' => 'raw',
        ],
        'slug',
        ['class' => ActionColumn::class],
    ],
    ]) ?>


        </div>
    </div>

</div>