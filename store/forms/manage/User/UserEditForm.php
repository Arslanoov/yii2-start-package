<?php

namespace store\forms\manage\User;

use Yii;
use store\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class UserEditForm extends Model
{
    public $username;
    public $email;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'username' => 'Имя',
            'email' => 'E-mail',
            'aboutMe' => 'Обо мне'
        ];
    }
}