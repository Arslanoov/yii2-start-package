<?php

namespace store\forms\manage\User;

use store\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\UploadedFile;

class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules(): array
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'email' => 'E-mail',
            'password' => 'Пароль'
        ];
    }
}