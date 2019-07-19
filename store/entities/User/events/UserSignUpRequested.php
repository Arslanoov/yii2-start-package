<?php

namespace store\entities\User\events;

use store\entities\User\User;

class UserSignUpRequested
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
