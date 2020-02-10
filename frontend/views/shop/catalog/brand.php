<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $brand shop\entities\Shop\Brand */

use yii\helpers\Html;

$this->title = $brand->meta_title;

$this->registerMetaTag(['name' =>'description', 'content' => $brand->meta_description]);
$this->registerMetaTag(['name' =>'keywords', 'content' => $brand->meta_keywords]);

$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $brand->name;
?>

<h1><?= Html::encode($brand->name) ?></h1>

<hr />

<?= $this->render('_list', [
    'dataProvider' => $dataProvider,
    'sort' => $sort,
]) ?>


