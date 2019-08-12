<?php

namespace core\listeners\User;

use core\entities\User\events\UserSignUpRequested;
use yii\mail\MailerInterface;
use RuntimeException;

class UserSignupRequestedListener
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(UserSignUpRequested $event): void
    {
        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/signup/confirm-html', 'text' => 'auth/signup/confirm-text'],
                ['user' => $event->user]
            )
            ->setTo($event->user->email)
            ->setSubject('Signup confirm')
            ->send();

        if (!$sent) {
            throw new RuntimeException('Произошла ошибка');
        }
    }
}