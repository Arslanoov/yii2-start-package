<?php

namespace store\forms\auth;

use yii\base\Model;
use store\entities\User\User;

class PasswordResetForm extends Model
{
    public $email;

    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::class,
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }
}
