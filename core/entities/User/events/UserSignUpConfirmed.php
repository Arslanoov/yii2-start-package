<?php

namespace core\entities\User\events;

use core\entities\User\User;

class UserSignUpConfirmed
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}