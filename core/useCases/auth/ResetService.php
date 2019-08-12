<?php

namespace core\useCases\auth;

use RuntimeException;
use core\forms\auth\PasswordResetForm;
use core\forms\auth\ResetPasswordForm;
use Yii;
use DomainException;
use yii\swiftmailer\Mailer;
use core\repositories\UserRepository;

class ResetService
{
    private $users;
    private $mailer;

    public function __construct(UserRepository $users, Mailer $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    public function request(PasswordResetForm $form)
    {
        $user = $this->users->findByEmail($form->email);

        if (!$user->isActive()) {
            throw new DomainException('Пользователь не активирован.');
        }

        $user->requestPasswordReset();
        $this->users->save($user);

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/reset/confirm-html', 'text' => 'auth/reset/confirm-text'],
                ['user' => $user]
            )
            ->setTo($user->email)
            ->setSubject('Восстанавление пароля для ' . Yii::$app->name)
            ->send();

        if (!$sent) {
            throw new RuntimeException('Не получилось отправить подтверждение на почту.');
        }
    }

    public function validateToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new DomainException('Токен не может быть пустым.');
        }

        if (!$this->users->existsByPasswordResetToken($token)) {
            throw new DomainException('Неверный токен.');
        }
    }

    public function reset($token, ResetPasswordForm $form): void
    {
        $user = $this->users->findByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);
    }
}