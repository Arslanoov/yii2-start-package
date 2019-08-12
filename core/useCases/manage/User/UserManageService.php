<?php

namespace core\useCases\manage\User;

use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;
use core\repositories\UserRepository;
use core\services\RoleManager;
use core\services\TransactionManager;

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