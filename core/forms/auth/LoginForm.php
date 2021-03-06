<?php

namespace core\forms\auth;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Имя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня'
        ];
    }
}
