<?php

namespace store\entities\User;

use Yii;
use yii\db\ActiveRecord;
use DomainException;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_WAIT = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName(): string
    {
        return '{{%users}}';
    }

    public function rules(): array
    {
        return [
            ['status', 'default', 'value' => self::STATUS_WAIT],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_WAIT]],
        ];
    }

    public static function create(string $username, string $email, string $password): self
    {
        $user = new User();
        $user->created_at = time();
        $user->updated_at = time();
        $user->username = $username;
        $user->email = $email;
        $user->status = self::STATUS_ACTIVE;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user;
    }

    public static function requestSignup(string $username, string $email, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->updated_at = time();
        $user->status = self::STATUS_WAIT;
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user;
    }

    public function edit(string $username, string $email): void
    {
        $this->updated_at = time();
        $this->username = $username;
        $this->email = $email;
    }

    public function confirmSignup(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Пользователь уже активирован');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->verification_token = null;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('Пользователь уже активирован');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->verification_token = null;
    }

    public function draft(): void
    {
        if ($this->isWait()) {
            throw new DomainException('Пользователь уже не активирован');
        }
        $this->status = self::STATUS_WAIT;
    }

    public static function findIdentity($id)
    {
        return self::findOne([
            'id' => $id
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new DomainException('Запрос на восстановление пароля уже был отдан.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function findByPasswordResetToken($token): User
    {
        return self::findOne([
            'password_reset_token' => $token,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function resetPassword($password): void
    {
        $this->setPassword($password);
    }

    public function isActive(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isWait(): bool
    {
        return $this->status == self::STATUS_WAIT;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function editProfile(string $username): void
    {
        $this->username = $username;
        $this->updated_at = time();
    }
}
