<?php

namespace store\useCases\manage\User;

use store\entities\User\User;
use store\forms\manage\User\UserCreateForm;
use store\forms\manage\User\UserEditForm;
use store\repositories\UserRepository;
use DomainException;

class UserManageService
{
    private $users;

    public function __construct(UserRepository $repository)
    {
        $this->users = $repository;
    }

    public function create(UserCreateForm $form): User
    {
        $user = User::create(
            $form->username,
            $form->email,
            $form->password
        );

        $this->users->save($user);

        return $user;
    }

    public function edit($id, UserEditForm $form): void
    {
        $user = $this->users->get($id);

        $user->edit(
            $form->username
        );

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