<?php

namespace store\useCases\manage\User;

use store\entities\User\User;
use store\forms\manage\User\UserCreateForm;
use store\forms\manage\User\UserEditForm;
use store\repositories\UserRepository;
use store\services\RoleManager;
use store\services\TransactionManager;

class UserManageService
{
    private $users;
    private $roles;
    private $transaction;

    public function __construct(UserRepository $users, RoleManager $roles, TransactionManager $transaction)
    {
        $this->users = $users;
        $this->roles = $roles;
        $this->transaction = $transaction;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $this->transaction->wrap(function () use ($user, $form) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });

        $this->users->save($user);

        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->users->get($id);

        $user->edit(
            $form->username,
            $form->email
        );

        $this->transaction->wrap(function () use ($user, $form) {
            $this->users->save($user);
            $this->roles->assign($user->id, $form->role);
        });

        $this->users->save($user);
    }

    public function remove($id): void
    {
        $user = $this->users->get($id);
        $this->users->remove($user);
    }

    public function activate($id): void
    {
        $user = $this->users->get($id);
        $user->confirmSignup();
        $this->users->save($user);
    }

    public function draft($id): void
    {
        $user = $this->users->get($id);
        $user->draft();
        $this->users->save($user);
    }
}