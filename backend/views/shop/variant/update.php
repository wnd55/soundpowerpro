<?php

/* @var $this yii\web\View */
/* @var $tag shop\entities\Shop\Tag */
/* @var $model shop\forms\manage\Shop\TagForm */

$this->title = 'Update Variant: ' . $variant->variant;
$this->params['breadcrumbs'][] = ['label' => 'Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $variant->variant, 'url' => ['view', 'id' => $variant->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tag-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
