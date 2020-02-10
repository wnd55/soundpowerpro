<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.04.18
 * Time: 20:41
 */


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */

/* @var $category shop\entities\shop\Category */

use yii\helpers\Html;


$this->title = $category->meta_title;

$this->registerMetaTag(['name' => 'description', 'content' => $category->meta_description]);

$this->registerMetaTag(['name' => 'keywords', 'content' => $category->meta_keywords]);


$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];

foreach ($category->parents as $parent) {
    if (!$parent->isRoot()) {
        $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['category', 'id' => $parent->id]];
    }
}

$this->params['breadcrumbs'][] = $category->name;

$this->params['active_category'] = $category;


?>

<section class="category-top">

    <h1 class="_top"> <?= Html::encode($category->getHeadingTile()) ?></h1>


    <?= $this->render('_subcategories', [
        'category' => $category
    ]) ?>


    <?php if (trim($category->description)): ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <?= Yii::$app->formatter->asHtml($category->description, [
                    'Attr.AllowedRel' => array('nofollow'),
                    'HTML.SafeObject' => true,
                    'Output.FlashCompat' => true,
                    'HTML.SafeIframe' => true,
                    'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
                ]) ?>
            </div>
        </div>
    <?php endif; ?>



    <?= $this->render('_list', [
        'dataProvider' => $dataProvider,
        'sort' => $sort,
    ]) ?>


</section>