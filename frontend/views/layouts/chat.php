<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 27.08.19
 * Time: 21:54
 */

/* @var $this \yii\web\View */

/* @var $content string */


use yii\widgets\ActiveForm;
use yii\helpers\Html;
use shop\forms\ChatForm;

$this->registerJsFile('@web/js/chat.js');
$this->registerCssFile('@web/css/chat.css');
$model = new ChatForm();
?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<?= $content ?>

<button class="open-button" onclick="openForm()">Чат</button>

<div class="chat-popup" id="myForm" style="display: none;">

    <div class="form-container">

        <div id="chatbox">
            <h3>Чат</h3>
            <div id="chattext"></div>

            <?= Html::button('Connect', ['id' => 'conx_btn', 'onclick' => 'connect()', 'disabled' => 'disabled', 'class' => 'btn-success']); ?>

            <?= Html::button('Disconnect', ['id' => 'disconx_btn', 'onclick' => 'disconnect()', 'disabled' => 'disabled', 'class' => 'btn-warning']); ?>
        </div>

        <br>


        <div class="method-form">
            <?php $form = ActiveForm::begin([
                'id' => 'chatForm',
                'enableAjaxValidation' => true,
            ]) ?>
            <div class="box box-default">
                <div class="box-body">

                    <div id="userName">
                        <?= $form->field($model, 'name')->textInput(['id' => 'name',]) ?>

                        <?= Html::button('Ok', ['id' => 'btn_ok', 'class' => 'btn btn-default']); ?>

                    </div>


                    <?= $form->field($model, 'text')->textarea(['id' => 'text'])->label(false) ?>

                </div>

                <div class="form-group">
                    <?= Html::button('Отправить', ['id' => 'chat_btn', 'onclick' => 'chatPost()', 'disabled' => 'disabled', 'class' => 'btn btn-default']) ?>
                </div>

                <button type="button" class="btn cancel" onclick="closeForm()">Закрыть</button>
                <?php ActiveForm::end(); ?>

            </div>

        </div>


    </div>
</div>


<?php $this->endContent() ?>
