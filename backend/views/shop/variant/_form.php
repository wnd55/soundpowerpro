<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:30
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model shop\forms\manage\shop\product\VariantForm */
/* @var $form yii\widgets\ActiveForm */

?>


<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">

            <?= $form->field($model, 'characteristicId')->dropDownList($model->characteristicVariantList()) ?>
            <?= $form->field($model, 'variant')->textInput()?>


        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

