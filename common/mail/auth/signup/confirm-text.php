<?php

/* @var $this yii\web\View */
/* @var $user core\entities\User\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->verification_token]);

?>

Привет, <?= $user->username ?>!

Для подтверждения регистрации на сайте перейдите по ссылке:
<?= $confirmLink ?>

Если вы не отправляли запрос на регистрацию на нашем сайте, то просто проигнорируйте это письмо
