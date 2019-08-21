<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:25
 */

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\shop\TagForm */

$this->title = 'Создать тег';

$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-create">

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
