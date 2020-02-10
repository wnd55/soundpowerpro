<?php

/* @var $this yii\web\View */
/* @var $model shop\forms\user\ProfileEditForm */
/* @var $user shop\entities\user\User */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Cabinet', 'url' => ['cabinet/default/index']];
$this->params['breadcrumbs'][] = 'Profile';
?>
<div class="user-update">

    <div class="row">
        <div class="col-sm-6">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
