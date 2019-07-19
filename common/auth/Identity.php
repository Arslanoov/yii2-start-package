<?php

namespace common\auth;

use store\readModels\UserReadRepository;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use store\entities\User\User;
use Yii;

class Identity implements IdentityInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function findIdentity($id)
    {
        $user = self::getRepository()->findActiveById($id);
        return $user ? new self($user): null;
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface|null
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getUsername(): string
    {
        return $this->user->username;
    }

    public function getAuthKey(): string
    {
        return $this->user->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    private static function getRepository()
    {
        return Yii::$container->get(UserReadRepository::class);
    }
}