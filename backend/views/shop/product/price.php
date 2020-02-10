<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 18:12
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $product shop\entities\shop\product\Product */
/* @var $model shop\forms\manage\shop\product\PriceForm */

$this->title = 'Price for Product: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Price';
?>

<div class="product-price">

<?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <?= $form->field($model, 'new')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'old')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>



    <?php ActiveForm::end() ?>

</div>
