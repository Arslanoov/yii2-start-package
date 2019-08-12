<?php

/* @var $this yii\web\View */
/* @var $user core\entities\User\User */

$resetLink = Yii::$app->get('frontendUrlManager')->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);

?>

<p>Привет, <?= $user->username ?>!</p>

<p>Для восстановления пароля на сайте необходимо перейти по ссылке:</p>

<?= $resetLink ?>

Если вы не отправляли запрос на восстановление пароля, то просто проигнорируйте это письмо