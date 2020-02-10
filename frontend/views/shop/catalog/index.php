<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $category shop\entities\shop\category */

use yii\helpers\Html;

$this->title = 'Royalty free треки для кино, телевидения, рекламы';
$this->params['breadcrumbs'][] = 'Каталог';
?>

<h1 class="_top"><?= Html::encode($this->title) ?></h1>

<?= $this->render('_subcategories', [
    'category' => $category
]) ?>

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
    'sort' => $sort,
]) ?>


