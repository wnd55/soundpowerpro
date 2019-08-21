<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 14:34
 */

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $product shop\entities\shop\product\Product */
/* @var $model shop\forms\manage\shop\product\ProductEditForm */

$this->title = 'Update Product: ' . $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $product->name, 'url' => ['view', 'id' => $product->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border">Common</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'brandId')->dropDownList($model->brandList()) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <?= $form->field($model, 'description')->widget(CKEditor::class) ?>
        </div>
    </div>


    <div class="box box-default">
        <div class="box-header with-border">Warehouse</div>
        <div class="box-body">
            <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border">Рекомендованные и популярные товары</div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'recommended')->checkbox() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'popular')->checkbox() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Categories</div>
                <div class="box-body">
                    <?= $form->field($model->categories, 'main_category')->dropDownList($model->categories->categoriesList(), ['prompt' => '']) ?>
                    <?= $form->field($model->categories, 'additional_categories')->checkboxList($model->categories->categoriesList()) ?>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">Tags</div>
                <div class="box-body">
                    <?= $form->field($model->tags, 'existing')->checkboxList($model->tags->tagsList()) ?>
                    <?= $form->field($model->tags, 'textNew')->textInput() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-default">
        <div class="box-header with-border">SEO</div>
        <div class="box-body">
            <?= $form->field($model, 'meta_title')->textInput() ?>
            <?= $form->field($model, 'meta_description')->textarea(['rows' => 2]) ?>
            <?= $form->field($model, 'meta_keywords')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>


</div>
