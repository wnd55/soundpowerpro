<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:25
 */

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\shop\product\VariantForm */

$this->title = 'Create Variant';

$this->params['breadcrumbs'][] = ['label' => 'Variants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-create">

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
