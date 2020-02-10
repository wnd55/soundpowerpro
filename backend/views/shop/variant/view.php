<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 15:29
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $tag shop\entities\shop\product\Variant */

$this->title = $variant->variant;
$this->params['breadcrumbs'][] = ['label' => 'Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $variant->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $variant->id], [
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
            'model' => $variant,
            'attributes' => [
                'id',


                [
                    'attribute' => 'characteristic_id',
                    'value' => ArrayHelper::getValue($variant, 'characteristic.name'),
                ],
                'variant',
                'sort'
            ],

        ]) ?>



        </div>
    </div>
</div>
