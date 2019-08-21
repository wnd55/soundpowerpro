<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 13:50
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \shop\forms\auth\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;



$this->title = 'Вход в личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-6">
        <div class="well">
            <h2 class="_top">Новый покупатель</h2>
            <p><strong>Регистрация на сайте</strong></p>
            <p>Создав учетную запись, вы сможете делать покупки быстрее, быть в курсе статуса заказа и отслеживать заказы, которые вы сделали ранее.</p>
            <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>" class="btn btn-default">Продолжить</a>
        </div>

    </div>
    <div class="col-sm-6">
        <div class="well">
            <h2 class="_top">Постоянный клиент</h2>


            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email')->textInput() ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <div style="color:#999;margin:1em 0">
                Если вы забыли свой пароль, то <?= Html::a('можете обновить', ['auth/reset/request']) ?>.
            </div>

            <div>
                <?= Html::submitButton('Войти', ['class' => 'btn btn-default', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

