<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.08.19
 * Time: 23:52
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $product shop\entities\shop\product\Product */
/* @var $model  shop\forms\manage\shop\product\StemsForm; */


$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Треки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>

<?= $form->field($model, 'file')->fileInput(['accept' => 'audio/*']) ?>

<div class="form-group">
    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
