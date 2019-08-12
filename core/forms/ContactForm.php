<?php

namespace core\forms;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $username;
    public $email;
    public $message;

    public function rules(): array
    {
        return [
            [['username', 'email', 'message'], 'required'],
            ['email', 'email'],
            ['username', 'string', 'min' => 4, 'max' => 32],
            ['message', 'trim'],
            ['message', 'string', 'max' => 1000]
        ];
    }
}
