<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 01.04.18
 * Time: 21:46
 */
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<div class="row">

    <div class="col-md-offset-10 col-md-3">
        <span>Сортировать: <?php echo $sort->link('Трек') . ' | ' . $sort->link('Стоимость'); ?></span>
    </div>
</div>

<div class="row">
    <?php foreach ($dataProvider->getModels() as $product): ?>
        <?= $this->render('_product', [
            'product' => $product,
        ]) ?>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-sm-6 text-left">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->getPagination(),
        ]) ?>
    </div>
    <div class="col-sm-6 text-right">Showing <?= $dataProvider->getCount() ?>
        of <?= $dataProvider->getTotalCount() ?></div>
</div>

