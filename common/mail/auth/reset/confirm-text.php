<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 17:32
 */

/* @var $this yii\web\View */
/* @var $user \shop\entities\user\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);

?>

Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>


