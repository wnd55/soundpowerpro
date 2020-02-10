<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

<div class="row">
    <div id="content" class="col-sm-9">
        <?= $content ?>
    </div>
    <aside id="column-right" class="col-sm-3" style="margin-top: 67px">
        <div class="list-group">
            <a href="<?= Html::encode(Url::to(['/cabinet/default/index'])) ?>" class="list-group-item">Личный кабинет</a>
            <a href="<?= Html::encode(Url::to(['/cabinet/order/index'])) ?>" class="list-group-item">История заказов</a>
            <a href="<?= Html::encode(Url::to(['/cabinet/wishlist/index'])) ?>" class="list-group-item">Избранное</a>
            <a href="<?= Html::encode(Url::to(['/auth/reset/request'])) ?>" class="list-group-item">Восстановление пароля</a>




        </div>
    </aside>
</div>

<?php


?>
<?php $this->endContent() ?>
