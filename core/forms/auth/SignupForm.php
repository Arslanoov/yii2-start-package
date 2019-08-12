<?php

namespace core\forms\auth;

use yii\base\Model;
use core\entities\User\User;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с таким именем уже существует'],
            ['username', 'string', 'min' => 4, 'max' => 32],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с такой почтой уже существует'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 32],
        ];
    }
}
