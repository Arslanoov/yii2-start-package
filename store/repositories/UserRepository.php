<?php

namespace store\repositories;

use store\dispatchers\EventDispatcher;
use store\entities\User\User;
use RuntimeException;

class UserRepository
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function findByUsername(string $username): User
    {
        return $this->getBy([
            'username' => $username
        ]);
    }

    public function findByUsernameOrEmail(string $value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function findByEmail(string $email): User
    {
        return $this->getBy([
            'email' => $email,
        ]);
    }

    public function findByEmailVerificationToken(string $token)
    {
        return $this->getBy([
            'verification_token' => $token
        ]);
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }

    public function findByPasswordResetToken(string $token): User
    {
        return $this->getBy([
            'password_reset_token' => $token
        ]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function get(int $id): User
    {
        return $this->getBy(['id' => $id]);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new RuntimeException('Не получилось сохранить пользователя');
        }

        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    public function remove(User $user): void
    {
        if (!$user->delete()) {
            throw new RuntimeException('Не получилось удалить пользователя');
        }

        $this->dispatcher->dispatchAll($user->releaseEvents());
    }

    private function getBy(array $condition): User
    {
        if (!($user = User::find()->where($condition)->limit(1)->one())) {
            throw new NotFoundException('Пользователь не найден');
        }

        return $user;
    }
}