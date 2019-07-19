<?php

namespace store\useCases\auth;

use store\access\Rbac;
use store\dispatchers\EventDispatcher;
use store\entities\User\events\UserSignUpConfirmed;
use store\entities\User\events\UserSignUpRequested;
use store\entities\User\User;
use store\forms\auth\SignupForm;
use store\repositories\UserRepository;
use DomainException;
use store\services\RoleManager;
use store\services\TransactionManager;
use yii\mail\MailerInterface;

class SignupService
{
    private $users;
    private $roles;
    private $transaction;
    private $dispatcher;

    public function __construct(
        UserRepository $users,
        RoleManager $roles,
        TransactionManager $transaction,
        EventDispatcher $dispatcher
    )
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param SignupForm $form
     * @throws \Exception
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

        $this->dispatcher->dispatch(new UserSignUpRequested($user));

        $this->users->save($user);
    }

    public function confirm(string $token): void
    {
        if (empty($token)) {
            throw new DomainException('Пустой токен');
        }

        $user = $this->users->findByEmailVerificationToken($token);
        $user->confirmSignup();

        $this->dispatcher->dispatch(new UserSignUpConfirmed($user));

        $this->users->save($user);
    }
}