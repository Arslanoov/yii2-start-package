<?php

namespace core\useCases\auth;

use core\access\Rbac;
use core\dispatchers\EventDispatcher;
use core\entities\User\events\UserSignUpConfirmed;
use core\entities\User\events\UserSignUpRequested;
use core\entities\User\User;
use core\forms\auth\SignupForm;
use core\repositories\UserRepository;
use DomainException;
use core\services\RoleManager;
use core\services\TransactionManager;
use yii\mail\MailerInterface;

class SignupService
{
    private $users;
    private $roles;
    private $transaction;

    public function __construct(
        UserRepository $users,
        RoleManager $roles,
        TransactionManager $transaction
    )
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    /**
     * @param SignupForm $form
     * @throws \Throwable
     */
    public function signup(SignupForm $form): void
    {
        $user = User::requestSignup(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user) {
            $this->users->save($user);
            $this->roles->assign($user->id, Rbac::ROLE_USER);
        });

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