<?php

namespace store\useCases\auth;

use store\entities\User\User;
use store\forms\auth\SignupForm;
use store\repositories\UserRepository;
use DomainException;
use yii\mail\MailerInterface;

class SignupService
{
    private $mailer;
    private $users;

    public function __construct(
        UserRepository $users,
        MailerInterface $mailer
    )
    {
        $this->mailer = $mailer;
        $this->users = $users;
    }

    public function signup(SignupForm $form): void
    {
        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->users->save($user);
    }

    public function confirm(string $token): void
    {
        if (empty($token)) {
            throw new DomainException('Пустой токен');
        }

        $user = $this->users->findByEmailVerificationToken($token);
        $user->confirmSignup();
        $this->users->save($user);
    }
}