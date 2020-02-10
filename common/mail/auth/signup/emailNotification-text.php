<?php

/**
 * Created by PhpStorm.
 * User: MaximIV
 * Date: 23.10.2016
 * Time: 14:56
 */

/* @var $this yii\web\View */
/* @var $user \shop\entities\user\User */
?>
Hello <?= $user->username ?>!

Вы успешно зарегистрировались на сайте <?=Yii::$app->name ?>

Ваш email для входа на сайт - <?= $user->email ?>

Пароль на почту не высылается.




